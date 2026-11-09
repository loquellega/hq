<?php
/*
 * hq v1.7.0 (2026-11-09) · entrada
 * Si hay sesión, muestra la base. Si no, pide la contraseña (o la crea la primera vez).
 * Alchemy of Meaning (Alquimia del Significado) · © 2026 Héctor Cruz Juárez · GPL-3.0-or-later
 */
require __DIR__ . '/lib.php';
sin_cache();

if (isset($_GET['salir'])) { cerrar_sesion(); header('Location: ./'); exit; }

$error = '';
// si llega desde el botón «+ hq» sin sesión, conservar el sitio para después de entrar
$vuelta = './' . (isset($_GET['add']) ? '?' . http_build_query(['add' => (string)$_GET['add'], 't' => (string)($_GET['t'] ?? '')]) : '');
$primera = !hay_contrasena();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $clave = (string)($_POST['password'] ?? '');
  if ($primera) {
    $otra = (string)($_POST['password2'] ?? '');
    if ((function_exists('mb_strlen') ? mb_strlen($clave) : strlen($clave)) < 8) $error = 'Usa al menos 8 caracteres.';
    elseif ($clave !== $otra) $error = 'Las dos contraseñas no coinciden.';
    elseif (!fijar_contrasena($clave)) $error = 'No pude escribir en la carpeta datos/. Revisa sus permisos.';
    else { abrir_sesion(); header('Location: ' . $vuelta); exit; }
  } else {
    if (bloqueado()) $error = 'Demasiados intentos. Espera 15 minutos.';
    else {
      $acc = leer_datos('acceso');
      if (password_verify($clave, $acc['hash'])) {
        limpiar_fallos(); abrir_sesion(); header('Location: ' . $vuelta); exit;
      }
      anotar_fallo(); sleep(1);
      $error = 'Contraseña incorrecta.';
    }
  }
}

if (!$primera && sesion_valida()) { require __DIR__ . '/app.php'; exit; }
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="robots" content="noindex, nofollow">
<meta name="theme-color" content="#0a0a0a">
<title>hq</title>
<link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'><rect width='64' height='64' rx='14' fill='%230a0a0a'/><circle cx='32' cy='32' r='15' fill='none' stroke='%2376c411' stroke-width='3'/><circle cx='32' cy='32' r='3.5' fill='%23ecebe7'/></svg>">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;400;500;600&display=swap" rel="stylesheet">
<style>
  /* Alchemy of Meaning · UI v1 */
  :root{--a-bg:#0a0a0a;--a-panel:#101010;--a-bar:#1c1c1c;--a-line:#2e2e2e;--a-line-ctl:#3a3a3a;--a-ink:#d2d2d2;--a-ink-strong:#fff;--a-ink-dim:#8f8f8f;
        --a-accent:#76c411;--a-radius:3px;--a-font:Inter,Arial,Helvetica,sans-serif}
  *{box-sizing:border-box;margin:0}
  html,body{height:100%;background:var(--a-bg);color:var(--a-ink);font:400 12px/1.5 var(--a-font);-webkit-font-smoothing:antialiased}
  body{display:grid;place-items:center;padding:16px}
  form{width:min(340px,100%);border:1px solid var(--a-line);border-radius:var(--a-radius);background:var(--a-panel);overflow:hidden}
  .k{height:24px;padding:0 10px;background:var(--a-bar);border-bottom:1px solid var(--a-line);font:500 11px/24px var(--a-font);letter-spacing:.02em;color:var(--a-ink);display:flex;align-items:center;gap:8px}
  .k i{width:6px;height:6px;border-radius:50%;background:var(--a-accent)}
  .in{padding:18px 16px 16px}
  h1{font-weight:200;font-size:38px;letter-spacing:-.02em;color:var(--a-ink-strong);margin:0 0 6px}
  p{color:var(--a-ink-dim);font-size:12px;line-height:1.5;margin-bottom:14px}
  label{display:block;font:400 11px var(--a-font);color:var(--a-ink-dim);margin:12px 0 6px}
  input{width:100%;background:#0d0d0d;border:1px solid var(--a-line-ctl);border-radius:var(--a-radius);padding:9px 10px;color:var(--a-ink-strong);font:400 16px var(--a-font);outline:0}
  input:focus-visible,button:focus-visible{outline:2px solid var(--a-accent);outline-offset:2px}
  button{width:100%;min-height:32px;margin-top:18px;border:1px solid var(--a-accent);border-radius:var(--a-radius);background:var(--a-accent);color:#0a0a0a;font:600 12px var(--a-font);cursor:pointer}
  button:hover{filter:brightness(1.08)}
  .err{color:#e9a092;font-size:12px;margin-top:12px}
  .u{position:absolute;left:-9999px}
  .v{font:400 11px var(--a-font);color:var(--a-ink-dim);text-align:center;margin-top:14px}
</style>
</head>
<body>
<form method="post" autocomplete="on">
  <div class="k"><i></i>hq · <?= $primera ? 'primera vez' : 'privada' ?></div>
  <div class="in">
  <h1><?= $primera ? 'Crea tu clave' : 'Hola' ?></h1>
  <p><?= $primera
      ? 'Elige la contraseña de tu hq. Este navegador quedará recordado por 400 días; en otros dispositivos la escribes una sola vez.'
      : 'Escribe tu contraseña. Este navegador quedará recordado por 400 días.' ?></p>
  <!-- usuario oculto: ayuda a que Brave y los gestores de contraseñas la guarden -->
  <input class="u" type="text" name="username" value="hq" autocomplete="username" tabindex="-1" aria-hidden="true">
  <label for="p">Contraseña</label>
  <input id="p" type="password" name="password" required autofocus
         autocomplete="<?= $primera ? 'new-password' : 'current-password' ?>">
  <?php if ($primera): ?>
  <label for="p2">Repítela</label>
  <input id="p2" type="password" name="password2" required autocomplete="new-password">
  <?php endif; ?>
  <button type="submit"><?= $primera ? 'Guardar y entrar' : 'Entrar' ?></button>
  <div class="v">hq v<?= HQ_VERSION ?> · Alchemy of Meaning</div>
  <?php if ($error): ?><div class="err"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  </div>
</form>
</body>
</html>
