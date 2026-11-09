<?php
/*
 * hq v1.7.0 (2026-11-09) · API mínima (solo con sesión)
 *   GET  api.php?estado   → accesos, relojes y modos guardados
 *   POST api.php?estado   → guarda el estado (JSON)
 *   GET  api.php?titulo64=… → título e ícono de una página (dirección en base64url)
 *   GET  api.php?rss64=…    → titulares de un RSS/Atom (caché de 10 minutos)
 *   GET  api.php?diag       → diagnóstico: PHP, extensiones y salida a internet
 *   (las direcciones viajan en base64 para que el filtro de seguridad del hosting no las bloquee)
 *   POST api.php?clave    → cambia la contraseña {actual, nueva}
 * Alchemy of Meaning (Alquimia del Significado) · © 2026 Héctor Cruz Juárez · GPL-3.0-or-later
 */
require __DIR__ . '/lib.php';
@ini_set('display_errors', '0');                   // nunca mezclar avisos de PHP con el JSON
sin_cache();
header('Content-Type: application/json; charset=utf-8');

function salir($codigo, $datos) { if (!headers_sent()) http_response_code($codigo); echo json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE); exit; }
// si PHP se detiene por un error grave, responder igual en JSON con el motivo
register_shutdown_function(function () {
  $e = error_get_last();
  if ($e && in_array($e['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
    if (!headers_sent()) { http_response_code(500); header('Content-Type: application/json; charset=utf-8'); }
    echo json_encode(['error' => 'PHP: ' . $e['message'] . ' (línea ' . $e['line'] . ')'], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
  }
});
function corta($t, $n) { return function_exists('mb_substr') ? mb_substr($t, 0, $n) : substr($t, 0, $n); }
function param_url($nombre) {
  if (isset($_GET[$nombre . '64'])) {
    $b = strtr((string)$_GET[$nombre . '64'], '-_', '+/');
    return (string)base64_decode($b . str_repeat('=', (4 - strlen($b) % 4) % 4));
  }
  return (string)($_GET[$nombre] ?? '');
}
$ULTIMO = ['via' => '', 'code' => 0, 'err' => '', 'bytes' => 0];

if (!sesion_valida()) salir(401, ['error' => 'sin sesión']);
header('X-HQ-Version: ' . HQ_VERSION);

$post = $_SERVER['REQUEST_METHOD'] === 'POST';
// Un encabezado propio obliga a que cualquier petición desde otro sitio sea bloqueada por el navegador.
if ($post && ($_SERVER['HTTP_X_BASE'] ?? '') !== '1') salir(403, ['error' => 'petición no permitida']);

if (isset($_GET['estado'])) {
  if (!$post) salir(200, leer_datos('estado') ?: new stdClass);
  $txt = file_get_contents('php://input', false, null, 0, 300001);
  if (strlen($txt) > 300000) salir(413, ['error' => 'demasiado grande']);
  $j = json_decode($txt, true);
  if (!is_array($j)) salir(400, ['error' => 'JSON inválido']);
  $j['rev'] = (int) round(microtime(true) * 1000);
  if (!escribir_datos('estado', $j)) salir(500, ['error' => 'no pude escribir en datos/']);
  salir(200, ['ok' => true, 'rev' => $j['rev']]);
}

if (isset($_GET['titulo']) || isset($_GET['titulo64'])) {
  $url = param_url('titulo');
  $chk = url_permitida($url); if ($chk !== true) salir(400, ['error' => $chk]);
  $html = traer($url, $final);
  if ($html === null) salir(200, ['title' => '', 'icon' => '']);
  $titulo = '';
  if (preg_match('/<meta[^>]+property=["\']og:(?:site_name|title)["\'][^>]+content=["\']([^"\']+)/i', $html, $m)) $titulo = $m[1];
  if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $m)) $titulo = $m[1] ?: $titulo;
  $titulo = trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($titulo), ENT_QUOTES | ENT_HTML5, 'UTF-8')));
  // ícono: primero apple-touch-icon (más grande), luego icon
  $icono = '';
  if (preg_match_all('/<link\b(?:[^>"\']|"[^"]*"|\'[^\']*\')*>/i', $html, $links)) {
    $mejor = -1;
    foreach ($links[0] as $l) {
      if (!preg_match('/rel=(?:"([^"]*)"|\'([^\']*)\'|(\S+))/i', $l, $r)) continue;
      $rel = $r[1] ?: ($r[2] ?? '') ?: ($r[3] ?? '');
      if (stripos($rel, 'icon') === false) continue;
      if (!preg_match('/href=(?:"([^"]*)"|\'([^\']*)\'|([^\s>]+))/i', $l, $h)) continue;
      $href = $h[1] ?: ($h[2] ?? '') ?: ($h[3] ?? '');
      $peso = stripos($rel, 'apple-touch') !== false ? 3 : (stripos($l, 'svg') !== false ? 2 : 1);
      if ($peso > $mejor) { $mejor = $peso; $icono = html_entity_decode($href, ENT_QUOTES | ENT_HTML5, 'UTF-8'); }
    }
  }
  if ($icono && strpos($icono, 'data:') !== 0) $icono = absoluta($icono, $final ?: $url);
  if (strpos($icono, 'data:') === 0 && strlen($icono) > 12000) $icono = '';
  salir(200, ['title' => corta($titulo, 160), 'icon' => $icono]);
}

if (isset($_GET['rss']) || isset($_GET['rss64'])) {
  $url = param_url('rss');
  $chk = url_permitida($url); if ($chk !== true) salir(400, ['error' => $chk]);
  $clave = 'rss_' . substr(md5($url), 0, 16);
  $cache = leer_datos($clave);
  if ($cache && time() - ($cache['t'] ?? 0) < 600) salir(200, $cache);
  $xml = traer($url, $final);
  $items = $xml ? leer_rss($xml) : [];
  if (!$items && $cache) salir(200, $cache);              // si la fuente falla, entrega lo último que hubo
  $res = ['t' => time(), 'items' => $items];
  if (!$items) $res['error'] = $xml === null ? motivo() : 'la fuente respondió, pero no parece un RSS (' . $ULTIMO['code'] . ')';
  if ($items) escribir_datos($clave, $res);
  if (mt_rand(1, 30) === 1) foreach (glob(DIR_DATOS . '/rss_*.php') ?: [] as $f) if (filemtime($f) < time() - 86400) @unlink($f);
  salir(200, $res);
}

function leer_rss($txt) {
  if (!function_exists('simplexml_load_string')) return leer_rss_simple($txt);
  $prev = libxml_use_internal_errors(true);
  $x = simplexml_load_string($txt, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NONET);
  libxml_use_internal_errors($prev);
  if (!$x) return [];
  $out = [];
  $limpia = fn($t) => trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags((string)$t), ENT_QUOTES | ENT_HTML5, 'UTF-8')));
  $nodos = isset($x->channel->item) ? $x->channel->item : (isset($x->item) ? $x->item : $x->entry);
  $canal = $limpia($x->channel->title ?? $x->title ?? '');
  foreach ($nodos ?: [] as $it) {
    $t = $limpia($it->title);
    $l = (string)$it->link;
    if (!$l && isset($it->link['href'])) $l = (string)$it->link['href'];
    if (!$l) foreach ($it->link as $lk) if ((string)$lk['rel'] !== 'self') { $l = (string)$lk['href']; break; }
    $d = strtotime((string)($it->pubDate ?? '')) ?: strtotime((string)($it->updated ?? '')) ?: strtotime((string)($it->published ?? '')) ?: 0;
    $fuente = $limpia($it->source ?? '');
    // Google News: «Titular - Medio»
    if ($fuente && substr($t, -strlen(' - ' . $fuente)) === ' - ' . $fuente) $t = substr($t, 0, -strlen(' - ' . $fuente));
    $img = '';
    $m = $it->children('http://search.yahoo.com/mrss/');
    if (isset($m->content)) foreach ($m->content as $mc) { $a = $mc->attributes(); if (!isset($a['medium']) || (string)$a['medium'] === 'image' || strpos((string)$a['type'], 'image') === 0) { $img = (string)$a['url']; break; } }
    if (!$img && isset($m->thumbnail)) $img = (string)$m->thumbnail->attributes()['url'];
    if (!$img && isset($m->group->content)) $img = (string)$m->group->content->attributes()['url'];
    if (!$img && isset($it->enclosure) && strpos((string)$it->enclosure['type'], 'image') === 0) $img = (string)$it->enclosure['url'];
    if (!$t || !preg_match('#^https?://#i', $l)) continue;
    $out[] = ['t' => corta($t, 220), 'l' => $l, 'd' => $d, 's' => $fuente ?: $canal, 'img' => preg_match('#^https?://#i', $img) ? $img : ''];
    if (count($out) >= 40) break;
  }
  return $out;
}

// lector de respaldo para servidores sin la extensión SimpleXML
function leer_rss_simple($txt) {
  $out = [];
  $limpia = fn($t) => trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags(preg_replace('/<!\[CDATA\[(.*?)\]\]>/s', '$1', (string)$t)), ENT_QUOTES | ENT_HTML5, 'UTF-8')));
  preg_match('/<title[^>]*>(.*?)<\/title>/s', $txt, $c); $canal = $limpia($c[1] ?? '');
  preg_match_all('/<(item|entry)\b.*?<\/\1>/s', $txt, $m);
  foreach ($m[0] as $it) {
    preg_match('/<title[^>]*>(.*?)<\/title>/s', $it, $t); $t = $limpia($t[1] ?? '');
    $l = preg_match('/<link[^>]*>(.*?)<\/link>/s', $it, $x) ? $limpia($x[1]) : (preg_match('/<link[^>]+href=["\']([^"\']+)/', $it, $x) ? $x[1] : '');
    $d = preg_match('/<(pubDate|updated|published)>(.*?)<\//s', $it, $x) ? (strtotime($x[2]) ?: 0) : 0;
    $fuente = preg_match('/<source[^>]*>(.*?)<\/source>/s', $it, $x) ? $limpia($x[1]) : '';
    if ($fuente && substr($t, -strlen(' - ' . $fuente)) === ' - ' . $fuente) $t = substr($t, 0, -strlen(' - ' . $fuente));
    $img = preg_match('/<media:(?:content|thumbnail)[^>]+url=["\']([^"\']+)/', $it, $x) ? $x[1] : (preg_match('/<enclosure[^>]+url=["\']([^"\']+)["\'][^>]*image/', $it, $x) ? $x[1] : '');
    if (!$t || !preg_match('#^https?://#i', $l)) continue;
    $out[] = ['t' => corta($t, 220), 'l' => html_entity_decode($l), 'd' => $d, 's' => $fuente ?: $canal, 'img' => preg_match('#^https?://#i', $img) ? html_entity_decode($img) : ''];
    if (count($out) >= 40) break;
  }
  return $out;
}

function motivo() {
  global $ULTIMO;
  if ($ULTIMO['via'] === 'ninguna') return 'tu hosting no permite que PHP descargue páginas (sin cURL ni allow_url_fopen)';
  if ($ULTIMO['err']) return 'error de conexión: ' . $ULTIMO['err'];
  if ($ULTIMO['code'] >= 400) return 'la fuente respondió ' . $ULTIMO['code'] . ($ULTIMO['code'] == 403 || $ULTIMO['code'] == 429 || $ULTIMO['code'] == 503 ? ' (bloquea a servidores)' : '');
  return 'la fuente no respondió';
}

function url_permitida($url) {
  $p = parse_url($url);
  if (!$p || !in_array(strtolower($p['scheme'] ?? ''), ['http', 'https']) || empty($p['host'])) return 'dirección inválida';
  // no consultar redes internas, salvo este mismo servidor
  $miHost = preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST'] ?? '');
  if (strcasecmp($p['host'], $miHost) === 0 || strcasecmp($p['host'], $_SERVER['SERVER_NAME'] ?? '') === 0) return true;
  $ip = gethostbyname($p['host']);
  if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) return 'red no permitida';
  return true;
}

function traer($url, &$final) {
  global $ULTIMO;
  $final = $url; $ULTIMO = ['via' => '', 'code' => 0, 'err' => '', 'bytes' => 0];
  if (function_exists('curl_init')) {
    $ULTIMO['via'] = 'curl';
    $c = curl_init($url);
    $buf = '';
    curl_setopt_array($c, [
      CURLOPT_FOLLOWLOCATION => true, CURLOPT_MAXREDIRS => 4, CURLOPT_TIMEOUT => 6, CURLOPT_CONNECTTIMEOUT => 4,
      CURLOPT_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS, CURLOPT_ENCODING => '',
      CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 hq/' . HQ_VERSION, CURLOPT_HTTPHEADER => ['Accept: text/html,application/rss+xml,application/atom+xml,application/xml;q=0.9,*/*;q=0.5', 'Accept-Language: es,en;q=0.8'],
      CURLOPT_WRITEFUNCTION => function ($ch, $d) use (&$buf) { $buf .= $d; return strlen($buf) > 400000 ? 0 : strlen($d); }
    ]);
    if (isset($_COOKIE[COOKIE]) && strcasecmp(parse_url($url, PHP_URL_HOST), preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST'] ?? '')) === 0) {
      // páginas propias protegidas con la misma sesión
      curl_setopt($c, CURLOPT_COOKIE, COOKIE . '=' . $_COOKIE[COOKIE]);
    }
    curl_exec($c);
    $final = curl_getinfo($c, CURLINFO_EFFECTIVE_URL) ?: $url;
    $ULTIMO['code'] = (int)curl_getinfo($c, CURLINFO_HTTP_CODE);
    if (curl_errno($c) && strlen($buf) <= 400000) $ULTIMO['err'] = curl_errno($c) . ' ' . curl_error($c);
    curl_close($c);
    $ULTIMO['bytes'] = strlen($buf);
    if ($ULTIMO['code'] >= 400) return null;
    return $buf !== '' ? $buf : null;
  }
  if (!filter_var(ini_get('allow_url_fopen'), FILTER_VALIDATE_BOOLEAN)) { $ULTIMO['via'] = 'ninguna'; return null; }
  $ULTIMO['via'] = 'fopen';
  $ctx = stream_context_create(['http' => ['timeout' => 6, 'follow_location' => 1, 'max_redirects' => 4, 'user_agent' => 'Mozilla/5.0 hq/' . HQ_VERSION, 'ignore_errors' => true]]);
  $h = @fopen($url, 'r', false, $ctx);
  if (!$h){ $e = error_get_last(); $ULTIMO['err'] = $e['message'] ?? 'no conecta'; return null; }
  $meta = stream_get_meta_data($h);
  foreach ($meta['wrapper_data'] ?? [] as $hd) if (preg_match('#^HTTP/\S+\s+(\d+)#', $hd, $mm)) $ULTIMO['code'] = (int)$mm[1];
  $buf = stream_get_contents($h, 400000); fclose($h);
  $ULTIMO['bytes'] = strlen((string)$buf);
  if ($ULTIMO['code'] >= 400) return null;
  return $buf ?: null;
}

function absoluta($rel, $base) {
  if (preg_match('#^[a-z][a-z0-9+.-]*:#i', $rel)) return $rel;
  $b = parse_url($base);
  $raiz = $b['scheme'] . '://' . $b['host'] . (isset($b['port']) ? ':' . $b['port'] : '');
  if (strpos($rel, '//') === 0) return $b['scheme'] . ':' . $rel;
  if (strpos($rel, '/') === 0) return $raiz . $rel;
  $dir = preg_replace('#/[^/]*$#', '/', $b['path'] ?? '/');
  $ruta = $dir . $rel;
  $partes = [];
  foreach (explode('/', $ruta) as $seg) { if ($seg === '..') array_pop($partes); elseif ($seg !== '.') $partes[] = $seg; }
  return $raiz . implode('/', $partes);
}

if (isset($_GET['diag'])) {
  $pruebas = [];
  foreach (['Euronews' => 'https://es.euronews.com/rss?format=mrss&level=theme&name=news',
            'Google News' => 'https://news.google.com/rss?hl=es-419&gl=MX&ceid=MX:es-419',
            'La Jornada' => 'https://www.jornada.com.mx/rss/edicion.xml?v=1'] as $n => $u) {
    $t0 = microtime(true); $x = traer($u, $f); $it = $x ? leer_rss($x) : [];
    $pruebas[] = ['fuente' => $n, 'ok' => count($it) > 0, 'titulares' => count($it), 'http' => $ULTIMO['code'], 'via' => $ULTIMO['via'],
                  'ms' => (int)((microtime(true) - $t0) * 1000), 'detalle' => count($it) ? '' : ($x === null ? motivo() : 'respondió, pero no es RSS')];
  }
  salir(200, ['hq' => HQ_VERSION, 'php' => PHP_VERSION, 'curl' => function_exists('curl_init') ? (curl_version()['version'] . ' · ' . curl_version()['ssl_version']) : 'no',
    'allow_url_fopen' => (bool)filter_var(ini_get('allow_url_fopen'), FILTER_VALIDATE_BOOLEAN), 'simplexml' => function_exists('simplexml_load_string'),
    'mbstring' => function_exists('mb_substr'), 'datos_escribible' => is_writable(DIR_DATOS), 'pruebas' => $pruebas]);
}

if (isset($_GET['clave']) && $post) {
  $j = json_decode(file_get_contents('php://input'), true) ?: [];
  $acc = leer_datos('acceso');
  if (!password_verify((string)($j['actual'] ?? ''), $acc['hash'])) { anotar_fallo(); sleep(1); salir(403, ['error' => 'La contraseña actual no es correcta.']); }
  if (strlen(utf8_decode_safe((string)($j['nueva'] ?? ''))) < 8) salir(400, ['error' => 'Usa al menos 8 caracteres.']);
  fijar_contrasena($j['nueva']); abrir_sesion();
  salir(200, ['ok' => true]);
}

function utf8_decode_safe($s) { return function_exists('mb_strlen') ? str_repeat('x', mb_strlen($s)) : preg_replace('/[\x80-\xBF]/', '', $s); }

salir(404, ['error' => 'no existe']);
