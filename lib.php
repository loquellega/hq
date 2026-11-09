<?php
/*
 * hq v1.7.0 (2026-11-09) · núcleo de acceso y datos
 * Parte de la colección Alchemy of Meaning (Alquimia del Significado).
 * © 2026 Héctor Cruz Juárez · GPL-3.0-or-later
 *
 * Los datos viven en datos/*.php: cada archivo empieza con un exit de PHP,
 * así que aunque el servidor no respete .htaccess, nadie puede leerlos por la web.
 */
define('HQ_VERSION', '1.7.0');   // versión de hq: sube junto con los demás .php
define('BASE', 1);
define('DIR_DATOS', __DIR__ . '/datos');
define('COOKIE', 'hq_sesion');
define('DURACION', 400 * 86400);   // 400 días: el máximo que aceptan los navegadores
define('GUARDA', "<?php exit; ?>\n");

function leer_datos($nombre) {
  $f = DIR_DATOS . "/$nombre.php";
  if (!is_file($f)) return null;
  $txt = file_get_contents($f);
  if (strpos($txt, GUARDA) === 0) $txt = substr($txt, strlen(GUARDA));
  $j = json_decode($txt, true);
  return is_array($j) ? $j : null;
}

function escribir_datos($nombre, $datos) {
  if (!is_dir(DIR_DATOS)) mkdir(DIR_DATOS, 0700, true);
  $f = DIR_DATOS . "/$nombre.php";
  $tmp = $f . '.' . bin2hex(random_bytes(4)) . '.tmp';
  $ok = file_put_contents($tmp, GUARDA . json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), LOCK_EX);
  if ($ok === false) return false;
  return rename($tmp, $f);
}

function es_https() {
  return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
      || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
      || (($_SERVER['SERVER_PORT'] ?? '') == 443);
}

function ruta_cookie() {
  $d = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
  return $d === '' ? '/' : $d . '/';
}

function poner_cookie($valor, $expira) {
  setcookie(COOKIE, $valor, [
    'expires' => $expira, 'path' => ruta_cookie(),
    'secure' => es_https(), 'httponly' => true, 'samesite' => 'Lax'
  ]);
}

function hay_contrasena() { return (bool) leer_datos('acceso'); }

function sesion_valida() {
  $acc = leer_datos('acceso');
  if (!$acc || empty($_COOKIE[COOKIE])) return false;
  $partes = explode('.', $_COOKIE[COOKIE]);
  if (count($partes) !== 3) return false;
  [$exp, $nonce, $firma] = $partes;
  if (!ctype_digit($exp) || (int)$exp < time()) return false;
  $esperada = hash_hmac('sha256', "$exp.$nonce", $acc['secreto']);
  return hash_equals($esperada, $firma);
}

function abrir_sesion() {
  $acc = leer_datos('acceso');
  $exp = time() + DURACION;
  $nonce = bin2hex(random_bytes(8));
  $firma = hash_hmac('sha256', "$exp.$nonce", $acc['secreto']);
  poner_cookie("$exp.$nonce.$firma", $exp);
}

function cerrar_sesion() { poner_cookie('', time() - 3600); }

/* Freno a intentos: 8 fallos en 15 minutos bloquean por 15 minutos (por IP). */
function ip() { return substr(preg_replace('/[^0-9a-fA-F:.]/', '', $_SERVER['REMOTE_ADDR'] ?? '0'), 0, 45); }
function bloqueado() {
  $i = leer_datos('intentos') ?: [];
  $ahora = time(); $mios = array_filter($i[ip()] ?? [], fn($t) => $t > $ahora - 900);
  return count($mios) >= 8;
}
function anotar_fallo() {
  $i = leer_datos('intentos') ?: [];
  $ahora = time();
  foreach ($i as $k => $ts) { $i[$k] = array_values(array_filter($ts, fn($t) => $t > $ahora - 900)); if (!$i[$k]) unset($i[$k]); }
  $i[ip()][] = $ahora;
  escribir_datos('intentos', $i);
}
function limpiar_fallos() {
  $i = leer_datos('intentos') ?: []; unset($i[ip()]); escribir_datos('intentos', $i);
}

/* Cambiar la contraseña genera un secreto nuevo: cierra todas las sesiones abiertas. */
function fijar_contrasena($clave) {
  return escribir_datos('acceso', [
    'hash' => password_hash($clave, PASSWORD_DEFAULT),
    'secreto' => bin2hex(random_bytes(32)),
    'creado' => date('c')
  ]);
}

function sin_cache() {
  header('Cache-Control: no-store, private');
  header('X-Robots-Tag: noindex, nofollow');
  header('X-Content-Type-Options: nosniff');
  header('Referrer-Policy: same-origin');
}
