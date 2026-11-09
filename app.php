<?php if (!defined('BASE')) { http_response_code(404); exit; } ?>
<!doctype html>
<!--
  hq v1.7.0 (2026-11-09) · página de inicio personal
  Parte de la colección Alchemy of Meaning (Alquimia del Significado).
  © 2026 Héctor Cruz Juárez · https://hectorcruz.com.mx · GPL-3.0-or-later
  Datos de clima, aire y mar: Open-Meteo (CC BY 4.0) · calidad del aire: Copernicus CAMS.
  Imágenes: NASA (APOD e Image Library), Art Institute of Chicago (dominio público), Cleveland Museum of Art (CC0).
  Radio: directorio abierto Radio Browser (radio-browser.info); cada estación es de su emisora.
  Sonido: síntesis procedural propia en Web Audio (modelos físicos de cuerda, tubos, bambú y cuencos), con modos inspirados en Cáustika y Endeles.
-->
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="robots" content="noindex, nofollow">
<meta name="theme-color" content="#0a0a0a">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="hq">
<title>hq</title>
<link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'><rect width='64' height='64' rx='14' fill='%230a0a0a'/><circle cx='32' cy='32' r='15' fill='none' stroke='%2376c411' stroke-width='3'/><circle cx='32' cy='32' r='3.5' fill='%23ecebe7'/></svg>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600&display=swap" rel="stylesheet">
<style>
:root{
  /* Alchemy of Meaning · UI v1 — tokens de la colección */
  --a-bg:#0a0a0a; --a-panel:#101010; --a-bar:#1c1c1c; --a-line:#2e2e2e; --a-line-ctl:#3a3a3a; --a-hover:#1f1f1f;
  --a-ink:#d2d2d2; --a-ink-strong:#fff; --a-ink-dim:#8f8f8f;
  --a-accent:#76c411; --a-accent-ink:#a5e04a; --a-accent-bg:#1b2512;
  --a-radius:3px; --a-radius-sm:2px; --a-gap:8px; --a-pad:10px; --a-bar-h:24px; --a-ctl-h:32px; --a-t:120ms;
  --a-font:Inter,Arial,Helvetica,sans-serif; --a-size:12px; --a-size-sm:11px;
  /* nombres internos de hq, ahora leídos de los tokens */
  --bg:var(--a-bg); --panel:rgba(16,16,16,.82); --panel-h:rgba(31,31,31,.9);
  --line:var(--a-line); --text:var(--a-ink); --dim:#ababab; --faint:var(--a-ink-dim);
  --accent:var(--a-accent); --accent-dim:var(--a-accent-bg);
  --ok:#8fc79a; --mid:#e0c66a; --warn:#e39a5b; --bad:#e06c6c; --vbad:#b98ad6; --sea:#8fc0d4;
  --r:var(--a-radius); --mono:var(--a-font); --sans:var(--a-font);
}
:root[data-contrast="alto"]{
  --a-bg:#000;--a-panel:#000;--a-bar:#fff;--a-line:#fff;--a-line-ctl:#fff;--a-hover:#262626;
  --a-ink:#fff;--a-ink-dim:#a8a8a8;--panel:rgba(0,0,0,.92);--dim:#d0d0d0;
}
*{box-sizing:border-box;margin:0;padding:0}
html,body{background:var(--bg);color:var(--text);font-family:var(--sans);min-height:100%;-webkit-font-smoothing:antialiased}
body{overflow-x:hidden}
#sky{position:fixed;inset:0;z-index:0;pointer-events:none;width:100%;height:100%}
.wrap{position:relative;z-index:1;max-width:1180px;margin:0 auto;padding:calc(16px + env(safe-area-inset-top)) 16px 48px}
a{color:inherit;text-decoration:none}
button{font:inherit;color:inherit;background:none;border:0;cursor:pointer}
[hidden]{display:none!important}

/* barra superior */
.top{display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;font-family:var(--mono);font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:var(--faint);padding-bottom:12px;border-bottom:1px solid var(--line)}
.live{display:flex;align-items:center;gap:8px}
.dot{width:6px;height:6px;border-radius:50%;background:var(--accent);box-shadow:0 0 10px var(--accent);animation:pulse 2.4s ease-in-out infinite}
.dot.local{background:var(--faint);box-shadow:none}
@keyframes pulse{50%{opacity:.35}}
.modes,.tools{display:flex;gap:6px;flex-wrap:wrap;align-items:center}
.chip{border:1px solid var(--line);border-radius:999px;padding:6px 11px;font-family:var(--mono);font-size:10.5px;letter-spacing:.08em;text-transform:uppercase;color:var(--dim);background:rgba(10,11,13,.35);transition:.2s;white-space:nowrap}
.chip:hover,.chip.on{border-color:var(--accent);color:var(--accent);background:var(--accent-dim)}
.chip .x{margin-left:6px;opacity:.6}
.chip .x:hover{opacity:1;color:var(--bad)}

/* secciones */
#secs{margin-top:18px}
#secs .col{display:flex;flex-direction:column;gap:18px;min-width:0}
.sec{position:relative;transition:opacity .25s;container-type:inline-size}
.sh{display:flex;align-items:center;gap:8px;height:22px;margin-bottom:8px;font-family:var(--mono);font-size:10.5px;letter-spacing:.16em;text-transform:uppercase;color:var(--faint)}
.sh::after{content:"";flex:1;height:1px;background:var(--line);order:2}
.sh .ttl{order:1}
.sh .act{order:3;display:flex;gap:6px}
.grip{order:0;width:22px;height:22px;display:grid;place-items:center;border-radius:6px;color:var(--faint);cursor:grab;opacity:0;transition:opacity .2s,background .2s;touch-action:none;font-size:13px;letter-spacing:0;margin-left:-4px}
.sec:hover .grip,.editing .grip{opacity:1}
.grip:hover{background:var(--accent-dim);color:var(--accent)}
.sh .ttl{white-space:nowrap;overflow:hidden;text-overflow:ellipsis;min-width:0}
.sbtn{white-space:nowrap;font-family:var(--mono);font-size:10px;letter-spacing:.1em;text-transform:uppercase;color:var(--faint);border:1px solid var(--line);border-radius:999px;padding:2px 9px;display:none}
.editing .sbtn{display:inline-block}
.sbtn:hover{color:var(--accent);border-color:var(--accent)}
.sec.off{display:none!important}
.editing .sec.off{display:block!important;opacity:.32}
.sec.dragging{z-index:5;opacity:.95}
.sec.dragging>.body{outline:1px solid var(--accent);outline-offset:6px;border-radius:var(--r)}
body.dragging,body.dragging *{cursor:grabbing!important;user-select:none;-webkit-user-select:none}
@media (hover:none){.grip{opacity:.0;pointer-events:none}.editing .grip{opacity:1;pointer-events:auto}}

.card{background:var(--panel);border:1px solid var(--line);border-radius:var(--r);backdrop-filter:blur(10px) saturate(1.1);-webkit-backdrop-filter:blur(10px) saturate(1.1)}
.lbl{font-family:var(--mono);font-size:10.5px;letter-spacing:.16em;text-transform:uppercase;color:var(--faint);margin-bottom:10px}
.big{font-size:24px;font-weight:300;line-height:1.1}
.sub{font-family:var(--mono);font-size:11.5px;color:var(--dim);margin-top:6px;line-height:1.6}

/* reloj */
.clock{padding:24px 26px 22px;display:flex;flex-direction:column;justify-content:space-between;gap:18px}
.greet{font-size:15px;color:var(--dim);font-weight:300}
.time{font-weight:200;font-size:clamp(72px,12vw,148px);line-height:.92;letter-spacing:-.04em;font-variant-numeric:tabular-nums;margin-top:10px}
.time small{font-size:.24em;font-weight:300;color:var(--faint);letter-spacing:0;margin-left:.3em;vertical-align:top;position:relative;top:.4em}
.date{font-size:18px;font-weight:300;margin-top:6px}
.meta{display:flex;flex-wrap:wrap;gap:8px 16px;margin-top:22px;font-family:var(--mono);font-size:12px;color:var(--dim);align-items:center}
.meta b{font-weight:400;color:var(--text)}
.pill{border:1px solid var(--line);border-radius:999px;padding:3px 10px;font-family:var(--mono);font-size:11px;color:var(--dim)}
.pill:hover{border-color:var(--accent);color:var(--accent)}
.mood{margin-top:12px;font-family:var(--mono);font-size:11px;color:var(--faint);letter-spacing:.04em}
.photo{position:relative;overflow:hidden;aspect-ratio:16/10;max-height:460px;width:100%;cursor:pointer}
.photo{background:#060708}
.photo img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;filter:saturate(.55) contrast(1.04) brightness(.82);transition:opacity 1s,filter .6s,transform 8s ease-out;opacity:0}
.photo img.fit{object-fit:contain}
.photo img.on{opacity:1}
.photo:hover img{filter:none;transform:scale(1.03)}
.photo:hover img.fit{transform:none}
.photo .cap{position:absolute;left:0;right:0;bottom:0;padding:48px 18px 15px;background:linear-gradient(transparent,rgba(0,0,0,.8));font-size:13.5px;line-height:1.35;color:var(--text);font-weight:300}
.photo .cap small{display:block;font-family:var(--mono);font-size:10.5px;letter-spacing:.06em;color:var(--dim);margin-top:4px}
.photo .cap a{text-decoration:underline;text-underline-offset:3px;color:var(--dim)}
.photo .cap a:hover{color:var(--accent)}
.photo .src{position:absolute;top:12px;right:12px;z-index:2;font-family:var(--mono);font-size:10px;letter-spacing:.1em;text-transform:uppercase;color:var(--text);border:1px solid rgba(255,255,255,.18);background:rgba(0,0,0,.45);border-radius:999px;padding:5px 10px;backdrop-filter:blur(6px)}
.photo .src:hover{border-color:var(--accent);color:var(--accent)}
.photo .ld{position:absolute;top:16px;left:16px;width:6px;height:6px;border-radius:50%;background:var(--accent);opacity:0;transition:opacity .3s}
.photo.loading .ld{opacity:1;animation:pulse 1s infinite}

/* frase */
.phrase{padding:20px 24px;display:flex;align-items:center;gap:18px;cursor:pointer}
.phrase .mark{font-family:var(--mono);font-size:11px;color:var(--accent);letter-spacing:.14em;white-space:nowrap}
.phrase p{font-size:clamp(18px,2.2vw,24px);font-weight:300;line-height:1.35}

/* relojes del mundo */
.zones{display:grid;grid-template-columns:repeat(auto-fill,minmax(190px,1fr));gap:10px}
.zone{padding:14px 16px;position:relative}
.zone .zn{font-size:13px;color:var(--dim);display:flex;justify-content:space-between;gap:8px;align-items:baseline}
.zone .zn span{white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.zone .zn em{font-style:normal;font-family:var(--mono);font-size:10.5px;color:var(--faint)}
.zone .zt{font-size:34px;font-weight:200;letter-spacing:-.02em;font-variant-numeric:tabular-nums;margin-top:4px}
.zone .zs{font-family:var(--mono);font-size:10.5px;color:var(--faint);margin-top:4px;display:flex;align-items:center;gap:6px}
.zone .zs i{width:6px;height:6px;border-radius:50%;background:var(--faint)}
.zone.here{border-color:rgba(217,183,121,.35)}
.zone .del{position:absolute;top:6px;right:8px;display:none;color:var(--faint);font-size:13px}
.editing .zone .del{display:block}
.zone .del:hover{color:var(--bad)}
.zone.add{display:none;place-items:center;border-style:dashed;color:var(--faint);font-family:var(--mono);font-size:12px;min-height:96px}
.zone.add:hover{color:var(--accent);border-color:var(--accent)}
.editing .zone.add,.zones:not(:has(.zone:not(.add))) .zone.add{display:grid}

/* cielo y ambiente */
.row3{display:grid;grid-template-columns:2fr 1fr 1fr;gap:18px}
.row4{display:grid;grid-template-columns:repeat(4,1fr);gap:18px}
.row3>.card,.row4>.card{padding:16px 18px}
.sunrow{display:flex;justify-content:space-between;font-family:var(--mono);font-size:12px;color:var(--dim)}
.sunrow b{display:block;color:var(--text);font-size:20px;font-weight:300;font-family:var(--sans);margin-top:2px}
.arc{height:56px;margin:12px 0 8px}
.arc svg{width:100%;height:100%;overflow:visible}
.bar{height:3px;background:var(--line);border-radius:3px;margin-top:12px;overflow:hidden}
.bar i{display:block;height:100%;background:var(--accent);transition:width .6s}
.scale{display:flex;gap:3px;margin-top:12px}
.scale i{flex:1;height:3px;border-radius:2px;background:var(--line)}
.lvl{display:inline-flex;align-items:center;gap:8px}
.lvl::before{content:"";width:8px;height:8px;border-radius:50%;background:var(--c,var(--faint));box-shadow:0 0 12px var(--c,transparent)}
.spark{width:100%;height:46px;margin-top:8px;overflow:visible}
.arrow{display:inline-block;transition:transform .6s}

/* búsqueda */
.search{display:flex;align-items:center;gap:12px;padding:6px 6px 6px 18px}
.search input{flex:1;background:none;border:0;outline:0;color:var(--text);font:300 17px var(--sans);padding:10px 0;min-width:0}
.search input::placeholder{color:var(--faint)}
.search kbd{font-family:var(--mono);font-size:11px;color:var(--faint);border:1px solid var(--line);border-radius:6px;padding:3px 7px}

/* accesos */
.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:10px;min-height:20px}
.tile{position:relative;min-width:0;display:flex;align-items:center;gap:12px;padding:13px;border-radius:12px;background:var(--panel);border:1px solid var(--line);transition:background .2s,border-color .2s,transform .2s;min-height:62px;backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px)}
.tile:hover{background:var(--panel-h);border-color:rgba(255,255,255,.18);transform:translateY(-1px)}
.ico{width:34px;height:34px;flex:none;border-radius:9px;background:rgba(255,255,255,.07);display:grid;place-items:center;overflow:hidden;font-weight:500;font-size:15px;color:#141210}
.ico img{width:20px;height:20px}
.ico.mono{background:var(--h)}
.ico .lt{font-weight:500}
.ico.emo{font-size:19px;background:rgba(255,255,255,.07)}
.ico.lg{width:44px;height:44px;border-radius:11px}
.ico.lg img{width:26px;height:26px}
.tile .t{min-width:0}
.tile .n{font-size:14px;font-weight:400;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.tile .h{font-family:var(--mono);font-size:10.5px;color:var(--faint);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-top:2px}
.tile.add{border-style:dashed;justify-content:center;color:var(--faint);font-family:var(--mono);font-size:12px;display:none}
.editing .tile.add,.grid:not(:has(.tile[data-i])) .tile.add{display:flex}
.tile.add:hover{color:var(--accent);border-color:var(--accent)}
.editing .tile[data-i]{touch-action:none;cursor:grab;border-style:dashed}
.tile.lift{z-index:6;border-color:var(--accent)!important;box-shadow:0 10px 30px rgba(0,0,0,.5);opacity:.9}

footer{margin-top:36px;font-family:var(--mono);font-size:10.5px;color:var(--faint);letter-spacing:.06em;display:flex;justify-content:space-between;flex-wrap:wrap;gap:8px;line-height:1.6}
footer a{text-decoration:underline;text-underline-offset:3px}

/* diálogos */
dialog{margin:auto;background:#121316;color:var(--text);border:1px solid var(--line);border-radius:16px;padding:22px;width:min(440px,calc(100vw - 32px));max-height:calc(100vh - 40px)}
dialog::backdrop{background:rgba(0,0,0,.6);backdrop-filter:blur(3px)}
dialog h4{font-weight:300;font-size:20px;margin-bottom:12px}
dialog label{display:block;font-family:var(--mono);font-size:10.5px;letter-spacing:.14em;text-transform:uppercase;color:var(--faint);margin:12px 0 6px}
dialog input{width:100%;background:rgba(255,255,255,.04);border:1px solid var(--line);border-radius:10px;padding:11px 12px;color:var(--text);font:300 15px var(--sans);outline:0}
dialog input:focus{border-color:var(--accent)}
dialog p{color:var(--dim);font-size:13.5px;line-height:1.5}
.row{display:flex;gap:8px;justify-content:flex-end;margin-top:20px;flex-wrap:wrap}
.btn{padding:9px 16px;border-radius:10px;border:1px solid var(--line);font-size:14px}
.btn:hover{border-color:rgba(255,255,255,.25)}
.btn.pri{background:var(--accent);color:#141210;border-color:var(--accent);font-weight:500}
.btn.del{margin-right:auto;color:#e9a092;border-color:rgba(227,139,125,.35)}
.chips{display:flex;flex-wrap:wrap;gap:6px;margin-top:8px}
.chips button{border:1px solid var(--line);border-radius:999px;padding:5px 10px;font-size:13px;color:var(--dim);line-height:1}
.chips button:hover{border-color:rgba(255,255,255,.25);color:var(--text)}
.chips button.on{border-color:var(--accent);color:var(--accent);background:var(--accent-dim)}
.chips button img{width:14px;height:14px;vertical-align:-2px}
.chips .emo{padding:4px 8px;font-size:16px}
.hint{font-family:var(--mono);font-size:10.5px;color:var(--faint);margin-top:6px;min-height:14px}
body.dropping .sec.grp>.body{outline:1px dashed rgba(217,183,121,.45);outline-offset:6px;border-radius:12px}
.sec.grp.over>.body{outline:1px solid var(--accent)!important;background:var(--accent-dim);border-radius:12px}
#dropNew{position:fixed;left:50%;bottom:calc(22px + env(safe-area-inset-bottom));transform:translateX(-50%);z-index:30;display:none;padding:16px 26px;border:1px dashed var(--accent);border-radius:14px;background:rgba(18,19,22,.92);color:var(--accent);font-family:var(--mono);font-size:11.5px;letter-spacing:.08em;text-transform:uppercase}
body.dropping #dropNew{display:block}
#dropNew.over{background:var(--accent);color:#141210}
.bm{display:inline-block;margin:10px 0 4px;padding:9px 16px;border-radius:10px;background:var(--accent);color:#141210;font-weight:500;cursor:grab}
.prev{display:flex;align-items:center;gap:14px;padding:12px;border:1px solid var(--line);border-radius:12px;margin-top:4px}
.prev .pn{font-size:15px}
.prev .ph{font-family:var(--mono);font-size:11px;color:var(--faint);margin-top:2px}
.sugs{display:flex;flex-direction:column;gap:4px;margin-top:6px}
.sugs button{text-align:left;padding:8px 10px;border-radius:8px;border:1px solid var(--line);font-size:13.5px}
.sugs button:hover{border-color:var(--accent);color:var(--accent)}
.sugs button em{font-style:normal;font-family:var(--mono);font-size:11px;color:var(--faint);margin-left:6px}
.menu{display:flex;flex-direction:column;gap:8px}
.menu .btn{text-align:left}
.msg{font-size:13px;margin-top:10px;color:var(--dim)}
.toast{position:fixed;left:50%;bottom:calc(20px + env(safe-area-inset-bottom));transform:translateX(-50%) translateY(20px);background:#16171a;border:1px solid var(--line);border-radius:999px;padding:9px 16px;font-family:var(--mono);font-size:11.5px;color:var(--dim);opacity:0;transition:.3s;z-index:20;pointer-events:none}
.toast.on{opacity:1;transform:translateX(-50%)}

/* noticias */
.news{display:flex;flex-direction:column;padding:12px 14px 6px}
.ntabs{display:flex;gap:4px;flex-wrap:wrap;margin-bottom:6px;align-items:center}
.ntabs button{font-family:var(--mono);font-size:10.5px;letter-spacing:.08em;text-transform:uppercase;color:var(--faint);padding:5px 9px;border-radius:999px;border:1px solid transparent}
.ntabs button:hover{color:var(--text)}
.ntabs button.on{color:var(--accent);border-color:rgba(217,183,121,.4);background:var(--accent-dim)}
.ntabs .x{margin-left:6px;display:none}
.editing .ntabs .x{display:inline}
.ntabs .x:hover{color:var(--bad)}
.ntabs .nup{margin-left:auto;font-family:var(--mono);font-size:10px;color:var(--faint);letter-spacing:.04em}
.nlist{list-style:none;max-height:440px;overflow-y:auto;scrollbar-width:thin;scrollbar-color:rgba(255,255,255,.12) transparent;margin:0 -4px;padding:0 4px}
.nlist li+li{border-top:1px solid var(--line)}
.nlist a{display:flex;gap:12px;padding:9px 2px;align-items:flex-start}
.nlist a:hover .nt{color:var(--accent)}
.nlist .tx{min-width:0;flex:1}
.nlist .nt{font-size:14px;line-height:1.32;font-weight:300;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;transition:color .2s}
.nlist .nm{display:block;font-family:var(--mono);font-size:10px;color:var(--faint);margin-top:4px;letter-spacing:.04em}
.nlist img{width:78px;height:52px;object-fit:cover;border-radius:6px;flex:none;filter:saturate(.6) brightness(.85)}
.nempty{font-family:var(--mono);font-size:11px;color:var(--faint);padding:12px 2px;line-height:1.6}

/* sonido */
.snd{padding:12px 14px 12px}
.srow{display:flex;align-items:center;gap:12px}
.splay{width:40px;height:40px;flex:none;border-radius:50%;border:1px solid rgba(217,183,121,.5);color:var(--accent);font-size:13px;display:grid;place-items:center;transition:.2s}
.splay:hover{background:var(--accent-dim)}
.splay.on{background:var(--accent);color:#141210;box-shadow:0 0 18px rgba(217,183,121,.35)}
.smeta{min-width:0;flex:1}
.sname{font-size:14px;font-weight:400;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.smood{font-family:var(--mono);font-size:10.5px;color:var(--faint);margin-top:3px;line-height:1.45;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.sdice{width:34px;height:34px;flex:none;border-radius:9px;border:1px solid var(--line);font-size:22px;line-height:1;color:var(--dim);display:grid;place-items:center;transition:color .2s,border-color .2s}
.sdice:hover{color:var(--accent);border-color:rgba(217,183,121,.5)}
.sdice.on{color:var(--accent);border-color:rgba(217,183,121,.5);background:var(--accent-dim)}
.sdice.roll{animation:dado .5s cubic-bezier(.3,1.4,.5,1)}
@keyframes dado{0%{transform:rotate(0) scale(1)}40%{transform:rotate(200deg) scale(1.25)}100%{transform:rotate(360deg) scale(1)}}
.scielo{font-family:var(--mono);font-size:10px;letter-spacing:.08em;text-transform:uppercase;color:var(--faint);border:1px solid var(--line);border-radius:999px;padding:4px 8px}
.scielo:hover{color:var(--accent);border-color:var(--accent)}
.sleft{font-family:var(--mono);font-size:12px;color:var(--accent);font-variant-numeric:tabular-nums}
.sviz{display:block;width:100%;height:34px;margin:8px 0 6px}
.sctl{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.seg{display:flex;border:1px solid var(--line);border-radius:999px;overflow:hidden}
.seg button{font-family:var(--mono);font-size:10px;letter-spacing:.06em;text-transform:uppercase;color:var(--faint);padding:5px 9px}
.seg button+button{border-left:1px solid var(--line)}
.seg button:hover{color:var(--text)}
.seg button.on{color:#141210;background:var(--accent)}
.srng{display:flex;align-items:center;gap:6px;flex:1;min-width:110px;font-family:var(--mono);font-size:9.5px;letter-spacing:.08em;text-transform:uppercase;color:var(--faint)}
.srng input{flex:1;min-width:50px;accent-color:var(--accent);height:16px}
.spres{display:flex;flex-wrap:wrap;gap:5px;margin-top:9px}
.sradio{margin-top:2px}
.rq{width:100%;margin-top:9px;background:rgba(255,255,255,.04);border:1px solid var(--line);border-radius:9px;padding:8px 10px;color:var(--text);font:300 13px var(--sans);outline:0}
.rq:focus{border-color:var(--accent)}
.rres{display:flex;flex-direction:column;margin-top:4px;max-height:210px;overflow-y:auto;scrollbar-width:thin}
.rrow{display:flex;align-items:center;gap:8px;padding:6px 2px;border-top:1px solid var(--line)}
.rrow:first-child{border-top:0}
.rrow .rn{flex:1;min-width:0;font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;cursor:pointer}
.rrow .rn:hover{color:var(--accent)}
.rrow em{font-style:normal;font-family:var(--mono);font-size:9.5px;color:var(--faint);white-space:nowrap}
.rrow button{font-family:var(--mono);font-size:11px;color:var(--faint);border:1px solid var(--line);border-radius:999px;width:24px;height:24px;flex:none}
.rrow button:hover{color:var(--accent);border-color:var(--accent)}
.rmsg{font-family:var(--mono);font-size:10.5px;color:var(--faint);padding:6px 2px}
.spres button{font-family:var(--mono);font-size:10px;letter-spacing:.04em;color:var(--dim);border:1px solid var(--line);border-radius:999px;padding:4px 9px;display:inline-flex;align-items:center;gap:5px}
.spres button:hover{color:var(--text);border-color:rgba(255,255,255,.22)}
.spres button.on{color:var(--accent);border-color:rgba(217,183,121,.5);background:var(--accent-dim)}
.spres button[data-psave]{color:var(--faint);border-style:dashed}
.spres .x{opacity:0;width:0;overflow:hidden;transition:.15s}
.spres button:hover .x,.editing .spres .x{opacity:.7;width:auto}
.spres .x:hover{opacity:1;color:var(--bad)}
#sndChip.on{animation:pulse 2.4s ease-in-out infinite}

/* tráfico */
.traffic{position:relative;overflow:hidden;min-height:58px;display:flex;align-items:center;justify-content:space-between;gap:10px;padding:12px 16px}
.traffic{flex-wrap:wrap}
.traffic .pill{white-space:nowrap}
.traffic .tl{font-family:var(--mono);font-size:11px;color:var(--dim);letter-spacing:.04em;flex:1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;min-width:0}
.traffic label{white-space:nowrap}
.traffic label{font-family:var(--mono);font-size:10.5px;color:var(--faint);display:flex;gap:6px;align-items:center;cursor:pointer}
.traffic iframe{position:absolute;inset:0;width:100%;height:100%;border:0;filter:invert(.9) hue-rotate(180deg) saturate(.9) brightness(.95)}
.sec[data-id=trafico].tlive .traffic{min-height:320px;padding:0}
.sec[data-id=trafico].tlive .traffic>:not(iframe):not(.tclose){display:none}
.traffic .tclose{display:none;position:absolute;top:10px;right:10px;z-index:2;font-family:var(--mono);font-size:10px;letter-spacing:.1em;text-transform:uppercase;color:var(--text);border:1px solid rgba(255,255,255,.18);background:rgba(0,0,0,.6);border-radius:999px;padding:5px 10px}
.sec[data-id=trafico].tlive .tclose{display:block}

/* adaptación por ancho de cada bloque (consultas de contenedor) */
@container (max-width:720px){
  .row3,.row4{grid-template-columns:1fr 1fr}
  .row3 .sunc{grid-column:1/-1}
}
@container (max-width:430px){
  .row3,.row4{gap:10px}
  .row3>.card,.row4>.card{padding:13px 14px}
  .zones{grid-template-columns:repeat(2,1fr)}
  .zone .zt{font-size:28px}
  .phrase{flex-direction:column;align-items:flex-start;gap:8px}
  .search kbd{display:none}
}
@media (max-width:520px){
  .grid{grid-template-columns:repeat(2,minmax(0,1fr))}
  .photo{aspect-ratio:4/3}
}

/* ============ TABLERO: escritorio horizontal, todo a la vista ============ */
body.dash{overflow:hidden;height:100vh}
.dash .wrap{max-width:none;margin:0;padding:14px 20px 8px;display:flex;flex-direction:column}
.dash .top{padding-bottom:10px}
.dash #secs{flex:1;min-height:0;display:grid;gap:18px;margin-top:14px}
.dash #secs .col{gap:12px;min-height:0;overflow-y:auto;overflow-x:hidden;scrollbar-width:none;padding-bottom:2px}
.dash #secs .col::-webkit-scrollbar{display:none}
.dash .sh{margin-bottom:6px;height:20px}
.dash .clock{padding:18px 22px 16px;gap:10px}
.dash .time{font-size:clamp(54px,27cqi,190px)}
.dash .meta{margin-top:10px}
.dash .row3,.dash .row4{gap:10px}
.dash .row3>.card,.dash .row4>.card{padding:12px 14px}
.dash .zones{grid-template-columns:repeat(auto-fill,minmax(128px,1fr));gap:8px}
.dash .zone{padding:10px 12px}
.dash .zone .zt{font-size:26px}
.dash .zone.add{min-height:70px}
.dash .phrase{padding:14px 18px}
.dash .phrase p{font-size:clamp(16px,5.2cqi,24px)}
.dash .grid{grid-template-columns:repeat(auto-fill,minmax(136px,1fr));gap:8px}
.dash .tile{padding:9px 11px;min-height:50px;gap:10px}
.dash .ico{width:30px;height:30px;border-radius:8px}
.dash .ico img{width:18px;height:18px}
.dash .sec[data-id=ventana],.dash .sec[data-id=noticias],.dash .sec[data-id=trafico].tlive{flex:1 1 0;min-height:190px;display:flex;flex-direction:column}
.dash .sec[data-id=ventana]>.body,.dash .sec[data-id=noticias]>.body,.dash .sec[data-id=trafico].tlive>.body{flex:1;min-height:0}
.dash .photo{aspect-ratio:auto;max-height:none;height:100%}
.dash .nlist{max-height:none;flex:1;min-height:0}
.dash .sec[data-id=trafico].tlive .traffic{min-height:0;height:100%}
.dash footer{margin-top:8px}
.col.newcol{border:1px dashed rgba(217,183,121,.35);border-radius:var(--r);display:flex!important;align-items:center;justify-content:center;text-align:center;color:var(--faint);font-family:var(--mono);font-size:11px;letter-spacing:.08em;text-transform:uppercase;padding:20px;min-height:140px}
.scl{display:flex;align-items:center;gap:8px;padding:9px 12px;border:1px solid var(--line);border-radius:10px;font-size:14px}
.scl span:first-child{flex:1}
.scl button{width:30px;height:30px;border:1px solid var(--line);border-radius:8px}
.scl button:hover{border-color:var(--accent);color:var(--accent)}
.scl b{font-family:var(--mono);font-weight:400;font-size:12px;min-width:44px;text-align:center}

/* ============ Alchemy of Meaning · UI v1 — capa de concordancia ============
   Ventanas con barra de título, líneas de 1 px, radio de 3 px, Inter 12 px con cifras tabulares.
   El verde es solo para lo vivo o principal: sonar, activo, selección, progreso. */
html,body{font-size:var(--a-size);font-variant-numeric:tabular-nums}
:focus-visible{outline:2px solid var(--a-accent);outline-offset:2px}
:root[data-contrast="alto"] .sh,:root[data-contrast="alto"] dialog h4{color:#000}

/* barra superior */
.top{font:400 var(--a-size-sm)/1.4 var(--a-font);letter-spacing:.01em;text-transform:none;color:var(--a-ink-dim);border-bottom-color:var(--a-line)}
.dot{background:var(--a-accent);box-shadow:none}
.dot.local{background:var(--a-ink-dim)}
.chip{height:26px;display:inline-flex;align-items:center;padding:0 10px;border:1px solid var(--a-line-ctl);border-radius:var(--a-radius);
  font:400 var(--a-size-sm)/1 var(--a-font);letter-spacing:.01em;text-transform:none;color:var(--a-ink);background:rgba(10,10,10,.6);
  transition:background var(--a-t),border-color var(--a-t),color var(--a-t)}
.chip:hover{background:var(--a-hover);border-color:#555;color:var(--a-ink-strong)}
.chip.on{background:var(--a-accent-bg);border-color:var(--a-accent);color:var(--a-accent-ink)}
#modes .chip.on{background:var(--a-ink);border-color:var(--a-ink);color:#0a0a0a;font-weight:500}   /* segmentado: tinta clara */
#sndChip.on{animation:none}
.brand{letter-spacing:.02em}

/* ventanas: cada bloque es una ventana con barra de título */
.sec{border:1px solid var(--a-line);border-radius:var(--a-radius);background:var(--panel);
  backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px)}
.sec>.sh,.dash .sec>.sh{height:var(--a-bar-h);margin:0;padding:0 6px 0 4px;background:var(--a-bar);border-bottom:1px solid var(--a-line);
  font:500 var(--a-size-sm)/1 var(--a-font);letter-spacing:.02em;text-transform:none;color:var(--a-ink);border-radius:var(--a-radius) var(--a-radius) 0 0}
.sh::after{display:none}
.sh .act{margin-left:auto}
.grip{width:18px;height:18px;border-radius:var(--a-radius-sm);color:var(--a-ink-dim);opacity:.55;transition:color var(--a-t),background var(--a-t);margin-left:0}
.sec:hover .grip,.editing .grip{opacity:1}
.grip:hover{background:var(--a-hover);color:var(--a-ink-strong)}
.sbtn{height:18px;padding:0 7px;border:1px solid var(--a-line-ctl);border-radius:var(--a-radius-sm);font:400 10.5px/16px var(--a-font);letter-spacing:0;text-transform:none;color:var(--a-ink-dim)}
.sbtn:hover{background:var(--a-hover);border-color:#555;color:var(--a-ink-strong)}
.sec>.body.card{border:0;border-radius:0 0 var(--a-radius) var(--a-radius);background:transparent;backdrop-filter:none;-webkit-backdrop-filter:none}
.sec>.body:not(.card){padding:var(--a-pad)}
.sec .body .card{border:1px solid var(--a-line);border-radius:var(--a-radius-sm);background:rgba(255,255,255,.015);backdrop-filter:none;-webkit-backdrop-filter:none}
.sec.dragging{outline:1px solid var(--a-accent);outline-offset:2px}
.sec.dragging>.body{outline:0}
.col.newcol{border:1px dashed var(--a-line-ctl);border-radius:var(--a-radius);font:400 var(--a-size-sm)/1.4 var(--a-font);letter-spacing:.01em;text-transform:none;color:var(--a-ink-dim)}
body.dropping .sec.grp{outline:1px dashed var(--a-line-ctl);outline-offset:2px}
body.dropping .sec.grp>.body{outline:0}
.sec.grp.over{outline:1px solid var(--a-accent)!important}
.sec.grp.over>.body{outline:0!important;background:var(--a-accent-bg)}

/* rótulos y lecturas: etiqueta tenue, valor claro */
.lbl{font:500 10.5px/1 var(--a-font);letter-spacing:.08em;color:var(--a-ink-dim)}
.sub,.meta,.mood,.sunrow,.zone .zn em,.zone .zs,.tile .h,.hint,.nlist .nm,.nempty,.smood,.rrow em,.rmsg,.traffic .tl,.traffic label,
.photo .cap small,.prev .ph,.sugs button em,.scl b,.search kbd,.sleft,footer{font-family:var(--a-font);letter-spacing:.01em}
.meta b,.sunrow b,.big,.zone .zt,.time{color:var(--a-ink-strong)}
.time{font-weight:200}
.time small,.mood,.greet{color:var(--a-ink-dim)}
.lvl::before{box-shadow:none}
.zone.here{border-color:#555}
.zone.add,.tile.add{border-style:dashed;border-color:var(--a-line-ctl)}
.zone.add:hover,.tile.add:hover{color:var(--a-ink-strong);border-color:#555;background:var(--a-hover)}
.pill{height:24px;padding:0 9px;border:1px solid var(--a-line-ctl);border-radius:var(--a-radius);font:400 var(--a-size-sm)/22px var(--a-font);color:var(--a-ink)}
.pill:hover{background:var(--a-hover);border-color:#555;color:var(--a-ink-strong)}
.phrase .mark{font:500 10.5px/1 var(--a-font);letter-spacing:.08em;color:var(--a-ink-dim)}
.search kbd{border-color:var(--a-line-ctl);border-radius:var(--a-radius-sm)}

/* accesos */
.tile{border-radius:var(--a-radius-sm);background:rgba(255,255,255,.015);border:1px solid var(--a-line);
  transition:background var(--a-t),border-color var(--a-t);backdrop-filter:none;-webkit-backdrop-filter:none}
.tile:hover{background:var(--a-hover);border-color:#555;transform:none}
.tile .n{color:var(--a-ink-strong);font-size:13px}
.ico{border-radius:var(--a-radius)}
.ico.lg{border-radius:var(--a-radius)}
.tile.lift{box-shadow:none;border-color:var(--a-accent)!important}

/* ventana de imágenes (contenido: sin acento) */
.photo .src,.traffic .tclose{height:22px;padding:0 8px;border:1px solid var(--a-line-ctl);border-radius:var(--a-radius);background:rgba(10,10,10,.75);
  font:400 var(--a-size-sm)/20px var(--a-font);letter-spacing:.01em;text-transform:none;color:var(--a-ink)}
.photo .src:hover,.traffic .tclose:hover{background:var(--a-hover);border-color:#555;color:var(--a-ink-strong)}
.photo .cap a:hover{color:var(--a-ink-strong)}

/* noticias: las pestañas son un segmentado (tinta clara), la lista con scroll discreto */
.ntabs button{height:22px;padding:0 8px;border-radius:var(--a-radius);border:1px solid transparent;font:400 var(--a-size-sm)/20px var(--a-font);letter-spacing:.01em;text-transform:none;color:var(--a-ink-dim)}
.ntabs button:hover{color:var(--a-ink-strong);background:var(--a-hover)}
.ntabs button.on{background:var(--a-ink);border-color:var(--a-ink);color:#0a0a0a;font-weight:500}
.nlist,.rres,dialog{scrollbar-width:thin;scrollbar-color:var(--a-line-ctl) transparent}
.nlist a:hover .nt{color:var(--a-ink-strong);text-decoration:underline;text-underline-offset:3px;text-decoration-color:#555}
.nlist .nt{color:var(--a-ink)}
.nlist img{border-radius:var(--a-radius-sm)}

/* sonido: sonar/detener es lo vivo (verde); modos y sesión son segmentados (tinta clara) */
.splay{width:36px;height:36px;border-radius:var(--a-radius);border:1px solid var(--a-accent);color:var(--a-accent-ink);background:transparent;transition:background var(--a-t),color var(--a-t)}
.splay:hover{background:var(--a-accent-bg)}
.splay.on{background:var(--a-accent);border-color:var(--a-accent);color:#0a0a0a;box-shadow:none}
.sname{color:var(--a-ink-strong);font-size:13px}
.sdice{width:32px;height:32px;border-radius:var(--a-radius);border:1px solid var(--a-line-ctl);color:var(--a-ink);transition:color var(--a-t),border-color var(--a-t),background var(--a-t)}
.sdice:hover{background:var(--a-hover);border-color:#555;color:var(--a-ink-strong)}
.sdice.on{background:var(--a-accent-bg);border-color:var(--a-accent);color:var(--a-accent-ink)}
.scielo{height:24px;padding:0 8px;border:1px solid var(--a-line-ctl);border-radius:var(--a-radius);font:400 var(--a-size-sm)/22px var(--a-font);letter-spacing:.01em;text-transform:none;color:var(--a-ink)}
.scielo:hover{background:var(--a-hover);border-color:#555;color:var(--a-ink-strong)}
.sleft{color:var(--a-accent-ink)}
.seg{border:1px solid var(--a-line-ctl);border-radius:var(--a-radius)}
.seg button{height:24px;padding:0 9px;font:400 var(--a-size-sm)/24px var(--a-font);letter-spacing:.01em;text-transform:none;color:var(--a-ink);transition:background var(--a-t),color var(--a-t)}
.seg button+button{border-left:1px solid var(--a-line-ctl)}
.seg button:hover{background:var(--a-hover);color:var(--a-ink-strong)}
.seg button.on{background:var(--a-ink);color:#0a0a0a;font-weight:500}
.srng{font:400 var(--a-size-sm)/1 var(--a-font);letter-spacing:.01em;text-transform:none;color:var(--a-ink-dim)}
.a-range{-webkit-appearance:none;appearance:none;width:100%;height:18px;margin:0;background:transparent}
.a-range::-webkit-slider-runnable-track{height:2px;border-radius:1px;background:linear-gradient(to right,var(--a-accent) var(--fill,0%),var(--a-line) var(--fill,0%))}
.a-range::-webkit-slider-thumb{-webkit-appearance:none;width:6px;height:14px;margin-top:-6px;border:0;border-radius:1px;background:var(--a-ink-strong)}
.a-range::-moz-range-track{height:2px;background:var(--a-line)}
.a-range::-moz-range-progress{height:2px;background:var(--a-accent)}
.a-range::-moz-range-thumb{width:6px;height:14px;border:0;border-radius:1px;background:var(--a-ink-strong)}
.spres button{height:24px;padding:0 9px;border:1px solid var(--a-line-ctl);border-radius:var(--a-radius);font:400 var(--a-size-sm)/22px var(--a-font);letter-spacing:.01em;color:var(--a-ink)}
.spres button:hover{background:var(--a-hover);border-color:#555;color:var(--a-ink-strong)}
.spres button.on{background:var(--a-accent-bg);border-color:var(--a-accent);color:var(--a-accent-ink)}
.spres button[data-psave]{color:var(--a-ink-dim)}
.rq{border-radius:var(--a-radius);border-color:var(--a-line-ctl);background:#0d0d0d;font:400 var(--a-size)/1.4 var(--a-font)}
.rrow .rn{color:var(--a-ink)}
.rrow .rn:hover{color:var(--a-ink-strong)}
.rrow button{border-radius:var(--a-radius-sm);border-color:var(--a-line-ctl);font-family:var(--a-font);color:var(--a-ink)}
.rrow button:hover{background:var(--a-hover);border-color:#555;color:var(--a-ink-strong)}

/* diálogos: ventana con barra de título */
dialog{background:var(--a-panel);border:1px solid var(--a-line);border-radius:var(--a-radius);padding:0 16px 16px;font-size:var(--a-size)}
dialog::backdrop{background:rgba(0,0,0,.7);backdrop-filter:none}
dialog h4{height:var(--a-bar-h);margin:0 -16px 14px;padding:0 var(--a-pad);background:var(--a-bar);border-bottom:1px solid var(--a-line);
  font:500 var(--a-size-sm)/var(--a-bar-h) var(--a-font);letter-spacing:.02em;color:var(--a-ink)}
dialog label{font:400 var(--a-size-sm)/1 var(--a-font);letter-spacing:.01em;text-transform:none;color:var(--a-ink-dim)}
dialog input{border-radius:var(--a-radius);border-color:var(--a-line-ctl);background:#0d0d0d;font:400 13px var(--a-font);padding:9px 10px}
dialog p{font-size:var(--a-size);color:var(--dim)}
.btn{min-height:var(--a-ctl-h);padding:0 12px;border:1px solid var(--a-line-ctl);border-radius:var(--a-radius);font:400 var(--a-size)/1 var(--a-font);color:var(--a-ink);
  transition:background var(--a-t),border-color var(--a-t),color var(--a-t);display:inline-flex;align-items:center}
.btn:hover{background:var(--a-hover);border-color:#555;color:var(--a-ink-strong)}
.btn.pri{background:var(--a-accent);border-color:var(--a-accent);color:#0a0a0a;font-weight:600}
.btn.pri:hover{filter:brightness(1.08);background:var(--a-accent)}
.chips button{border-radius:var(--a-radius);border-color:var(--a-line-ctl);font-size:var(--a-size)}
.chips button:hover{background:var(--a-hover);border-color:#555}
.chips button.on{background:var(--a-accent-bg);border-color:var(--a-accent);color:var(--a-accent-ink)}
.prev,.scl,.sugs button{border-radius:var(--a-radius);border-color:var(--a-line)}
.sugs button:hover{background:var(--a-hover);border-color:#555;color:var(--a-ink-strong)}
.scl button{border-radius:var(--a-radius);border-color:var(--a-line-ctl)}
.scl button:hover{background:var(--a-hover);border-color:#555;color:var(--a-ink-strong)}
.bm{border-radius:var(--a-radius);background:var(--a-accent);color:#0a0a0a;font-weight:600}
#dropNew{border-radius:var(--a-radius);background:var(--a-panel);border-color:var(--a-accent);color:var(--a-accent-ink);font:500 var(--a-size-sm)/1 var(--a-font);letter-spacing:.02em;text-transform:none}
#dropNew.over{background:var(--a-accent);color:#0a0a0a}
.toast{border-radius:var(--a-radius);background:var(--a-bar);border-color:var(--a-line);font:400 var(--a-size-sm)/1.4 var(--a-font);letter-spacing:.01em;color:var(--a-ink)}

/* pie y créditos */
footer{font-size:var(--a-size-sm);color:var(--a-ink-dim)}
footer a:hover{color:var(--a-ink-strong)}

/* «h» oculta la interfaz; ▪ la recupera */
body.sinui .wrap{visibility:hidden}
#uiBack{position:fixed;right:calc(14px + env(safe-area-inset-right));bottom:calc(14px + env(safe-area-inset-bottom));z-index:40;display:none;
  width:26px;height:26px;border:1px solid var(--a-line-ctl);border-radius:var(--a-radius);background:rgba(10,10,10,.7);color:var(--a-ink-dim);font-size:12px}
/* lecturas en columnas angostas */
.zone .zn em{white-space:nowrap;flex:none}
@container (max-width:470px){ .row4 .big,.row3 .big{font-size:19px} }
#uiBack:hover{color:var(--a-ink-strong);border-color:#555}
body.sinui #uiBack{display:block}
@media (prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important}}
</style>
</head>
<body>
<canvas id="sky"></canvas>

<div class="wrap">
  <div class="top">
    <div class="live"><span class="dot" id="syncDot"></span><span id="status">hq</span></div>
    <div class="modes" id="modes"></div>
    <div class="tools">
      <button class="chip" id="addBtn" title="Agregar sitio (N)">+ sitio</button>
      <button class="chip" id="sndChip" title="Música procedural (M)">♪ sonido</button>
      <button class="chip" id="modBtn" title="Nuevo módulo de enlaces">+ módulo</button>
      <button class="chip" id="editBtn" title="Mover, ocultar y editar (E)">editar</button>
      <button class="chip" id="menuBtn" title="Más opciones">···</button>
    </div>
  </div>

  <div id="secs">
    <section class="sec" data-id="reloj" data-name="Reloj">
        <div class="body card clock">
          <div>
            <div class="greet" id="greet">Hola</div>
            <div class="time" id="time">--:--<small id="sec">00</small></div>
            <div class="date" id="date"></div>
          </div>
          <div>
            <div class="meta">
              <span>📍 <b id="place">Ciudad de México</b></span>
              <span id="weather">—</span>
              <button class="pill" id="locBtn" title="Usar mi ubicación actual">usar mi ubicación</button>
            </div>
            <div class="mood" id="mood"></div>
          </div>
        </div>
    </section>

    <section class="sec" data-id="ventana" data-name="Ventana">
      <div class="body card photo" id="photo" title="Clic para otra imagen">
        <img id="imgA" alt=""><img id="imgB" alt="">
        <span class="ld"></span>
        <button class="src" id="srcBtn" title="Cambiar fuente">al azar</button>
        <div class="cap" id="cap"></div>
      </div>
    </section>

    <section class="sec" data-id="noticias" data-name="Noticias">
      <div class="body card news">
        <div class="ntabs" id="ntabs"></div>
        <ol class="nlist" id="nlist"></ol>
      </div>
    </section>

    <section class="sec" data-id="sonido" data-name="Sonido">
      <div class="body card snd">
        <div class="srow">
          <button class="splay" id="sPlay" title="Tocar (M)">▶</button>
          <div class="smeta"><div class="sname" id="sName">Enfoque</div><div class="smood" id="sMood"></div></div>
          <span class="sleft" id="sLeft"></span>
          <button class="scielo" id="sCielo" title="Volver a la armonía que dicta el cielo" hidden>cielo</button>
          <button class="sdice" id="sDice" title="Tirar los dados: otra armonía con el mismo clima (D)">⚄</button>
        </div>
        <canvas class="sviz" id="sViz"></canvas>
        <div class="sctl">
          <div class="seg" id="sModes"><button data-m="enfoque">Enfoque</button><button data-m="caustika">Cáustika</button><button data-m="endeles">Endeles</button><button data-m="radio">Radio</button></div>
          <div class="seg" id="sTimer" title="Sesión"><button data-t="0">∞</button><button data-t="25">25′</button><button data-t="50">50′</button></div>
          <label class="srng" title="Volumen">vol<input type="range" class="a-range" id="sVol" min="0" max="1" step=".01" aria-label="Volumen"></label>
          <label class="srng" id="sEspL" title="Reverberación: qué tan grande se siente el espacio">espacio<input type="range" class="a-range" id="sEsp" min="0" max="1" step=".01" aria-label="Espacio"></label>
        </div>
        <div class="sradio" id="sRadio" hidden>
          <div class="spres" id="rList"></div>
          <input class="rq" id="rQ" placeholder="buscar estación: NPR, jazz, Radio UNAM, ambient…" autocomplete="off" aria-label="Buscar estación">
          <div class="rres" id="rRes"></div>
        </div>
        <div class="spres" id="sPres"></div>
      </div>
    </section>

    <section class="sec" data-id="trafico" data-name="Tráfico">
      <div class="body card traffic" id="traffic">
        <button class="pill" id="tLoad">ver tráfico en vivo</button>
        <span class="tl" id="tPlace">Waze · —</span>
        <label><input type="checkbox" id="tAuto"> abrir siempre</label>
        <button class="src tclose" id="tClose" title="Cerrar mapa">cerrar</button>
      </div>
    </section>

    <section class="sec" data-id="frase" data-name="Frase">
      <div class="body card phrase" id="phrase" title="Clic para otra frase">
        <span class="mark">cue · hoy</span>
        <p id="phraseTxt"></p>
      </div>
    </section>

    <section class="sec" data-id="relojes" data-name="Relojes">
      <div class="body zones" id="zones"></div>
    </section>

    <section class="sec" data-id="cielo" data-name="Cielo">
      <div class="body row3">
        <div class="card sunc">
          <div class="lbl">Sol</div>
          <div class="sunrow">
            <div>Amanece<b id="rise">--:--</b></div>
            <div style="text-align:center">Luz de día<b id="dayLen">--</b></div>
            <div style="text-align:right">Anochece<b id="set">--:--</b></div>
          </div>
          <div class="arc">
            <svg viewBox="0 0 300 56" preserveAspectRatio="none">
              <path d="M4 54 Q150 -46 296 54" fill="none" stroke="rgba(255,255,255,.14)" stroke-width="1" stroke-dasharray="3 4" vector-effect="non-scaling-stroke"/>
              <line x1="0" y1="54" x2="300" y2="54" stroke="rgba(255,255,255,.14)" vector-effect="non-scaling-stroke"/>
              <circle id="sunDot" r="5" cx="4" cy="54" fill="#ffffff"/>
            </svg>
          </div>
          <div class="sub" id="sunNote"></div>
        </div>
        <div class="card">
          <div class="lbl">Luna</div>
          <div class="big" id="moonIco">○</div>
          <div class="sub" id="moonTxt"></div>
        </div>
        <div class="card">
          <div class="lbl">Año</div>
          <div class="big" id="yearPct">--%</div>
          <div class="sub" id="yearTxt"></div>
          <div class="bar"><i id="yearBar" style="width:0"></i></div>
        </div>
      </div>
    </section>

    <section class="sec" data-id="ambiente" data-name="Ambiente">
      <div class="body row4">
        <div class="card">
          <div class="lbl">Aire</div>
          <div class="big"><span class="lvl" id="aqiLvl">—</span></div>
          <div class="sub" id="aqiTxt">calidad del aire</div>
          <div class="scale" id="aqiScale"></div>
        </div>
        <div class="card">
          <div class="lbl">Índice UV</div>
          <div class="big"><span class="lvl" id="uvLvl">—</span></div>
          <div class="sub" id="uvTxt"></div>
          <div class="scale" id="uvScale"></div>
        </div>
        <div class="card">
          <div class="lbl">Viento · humedad</div>
          <div class="big"><span class="arrow" id="windArr">↓</span> <span id="windTxt">—</span></div>
          <div class="sub" id="humTxt"></div>
        </div>
        <div class="card" id="seaCard">
          <div class="lbl" id="seaLbl">Próximas horas</div>
          <div class="big" id="seaBig">—</div>
          <div class="sub" id="seaTxt"></div>
          <svg class="spark" id="spark" viewBox="0 0 200 46" preserveAspectRatio="none"></svg>
        </div>
      </div>
    </section>

    <section class="sec" data-id="buscar" data-name="Buscar">
      <form class="body card search" id="search" action="https://search.brave.com/search" method="get">
        <span style="color:var(--faint)">⌕</span>
        <input id="q" name="q" placeholder="Buscar en Brave o escribir una dirección…" autocomplete="off">
        <kbd>/</kbd>
      </form>
    </section>
  </div>

  <footer>
    <span><span id="foot">—</span> · <span id="ver">hq v<?= HQ_VERSION ?></span> · Colección Alchemy of Meaning · Diseño, investigación y desarrollo: <a href="https://hectorcruz.com.mx" target="_blank" rel="noopener">Héctor Cruz Juárez</a></span>
    <span>Atajos: <b>/</b> buscar · <b>N</b> sitio · <b>E</b> editar · <b>M</b> música · <b>D</b> dados · <b>H</b> ocultar · <b>1–9</b> modos · Datos: <a href="https://open-meteo.com" target="_blank" rel="noopener">Open-Meteo</a> · CAMS · NASA · Art Institute of Chicago · Cleveland Museum of Art · Euronews · Google News · Waze</span>
  </footer>
</div>

<!-- sitio -->
<dialog id="dlg">
  <form method="dialog" id="dlgForm">
    <h4 id="dlgTitle">Agregar sitio</h4>
    <div class="prev"><span class="ico big" id="pIco"></span><div><div class="pn" id="pName">Nuevo sitio</div><div class="ph" id="pHost">—</div></div></div>
    <label for="fUrl">Dirección</label>
    <input id="fUrl" required placeholder="https://… o ../fonoestratonika/" inputmode="url" autocomplete="off">
    <label for="fName">Nombre</label>
    <input id="fName" required placeholder="Fonoestratonika" autocomplete="off">
    <label for="fGroup">Módulo</label>
    <input id="fGroup" list="groupList" placeholder="Artefactos" autocomplete="off">
    <datalist id="groupList"></datalist>
    <div class="chips" id="gChips"></div>
    <label for="fIcon">Ícono</label>
    <div class="chips" id="iChips"></div>
    <input type="hidden" id="fIcon">
    <input id="fIconTxt" placeholder="otro: un emoji o la URL de una imagen" autocomplete="off" style="margin-top:8px">
    <div class="hint" id="fHint"></div>
    <div class="row">
      <button type="button" class="btn del" id="fDel" hidden>Eliminar</button>
      <button type="button" class="btn" data-close>Cancelar</button>
      <button type="submit" class="btn pri">Guardar</button>
    </div>
  </form>
</dialog>

<!-- reloj -->
<dialog id="zdlg">
  <form method="dialog" id="zForm">
    <h4>Agregar reloj</h4>
    <label for="zCity">Ciudad</label>
    <input id="zCity" placeholder="Lisboa, Bogotá, Tokio…" autocomplete="off">
    <div class="sugs" id="zSugs"></div>
    <label for="zTz">Zona horaria</label>
    <input id="zTz" list="tzList" required placeholder="Europe/Lisbon" autocomplete="off">
    <datalist id="tzList"></datalist>
    <div class="row">
      <button type="button" class="btn" data-close>Cancelar</button>
      <button type="submit" class="btn pri">Agregar</button>
    </div>
  </form>
</dialog>

<!-- menú -->
<dialog id="mdlg">
  <h4>hq <span style="font:400 11px var(--mono);color:var(--faint);letter-spacing:.1em">v<?= HQ_VERSION ?></span></h4>
  <div class="menu">
    <div class="scl"><span>Tamaño en esta pantalla</span><button id="zMinus" title="Más chico">−</button><b id="zVal">100%</b><button id="zPlus" title="Más grande">+</button></div>
    <button class="btn" id="mSaveMode">Guardar el orden actual como modo nuevo…</button>
    <button class="btn" id="mExport">Exportar respaldo (.json)</button>
    <button class="btn" id="mImport">Importar respaldo…</button>
    <button class="btn" id="mBm">Botón «+ hq» para la barra de favoritos…</button>
    <button class="btn" id="mDiag" onclick="runDiag()">Diagnóstico de noticias y conexión…</button>
    <button class="btn" id="mContr" aria-pressed="false">Contraste alto en esta pantalla</button>
    <button class="btn" id="mPass">Cambiar contraseña…</button>
    <a class="btn" href="./?salir">Cerrar sesión en este navegador</a>
  </div>
  <div class="row"><button class="btn" data-close>Cerrar</button></div>
</dialog>

<dialog id="fdlg">
  <form method="dialog" id="fForm">
    <h4>Agregar fuente de noticias</h4>
    <p>Cualquier dirección RSS o Atom: un periódico, un blog, un canal de YouTube…</p>
    <label for="nfName">Nombre de la pestaña</label>
    <input id="nfName" required placeholder="Cultura" autocomplete="off">
    <label for="nfUrl">Dirección del RSS</label>
    <input id="nfUrl" required placeholder="https://…/rss" inputmode="url" autocomplete="off">
    <div class="sugs" id="nfSugs"></div>
    <div class="row">
      <button type="button" class="btn" data-close>Cancelar</button>
      <button type="submit" class="btn pri">Agregar</button>
    </div>
  </form>
</dialog>

<dialog id="ddlg">
  <h4>Diagnóstico</h4>
  <div id="diagOut"></div>
  <div class="row"><button class="btn" data-close>Cerrar</button></div>
</dialog>

<dialog id="bdlg">
  <h4>Botón «+ hq»</h4>
  <p>Arrastra este botón a tu barra de favoritos de Brave (si no la ves: ⌘⇧B o Ctrl+Shift+B).
     Cuando estés en cualquier sitio, un clic en él lo trae a hq para guardarlo.</p>
  <a class="bm" id="bmLink" href="#">+ hq</a>
  <p style="margin-top:12px">Otras formas rápidas: arrastra el ícono que está a la izquierda de la dirección (o cualquier enlace) y suéltalo sobre un módulo de hq, o copia una dirección y pégala en hq con ⌘V.</p>
  <div class="row"><button class="btn" data-close>Listo</button></div>
</dialog>

<dialog id="pdlg">
  <form id="pForm">
    <h4>Cambiar contraseña</h4>
    <p>Al cambiarla se cierran las sesiones de tus otros dispositivos.</p>
    <input type="text" name="username" value="hq" autocomplete="username" hidden>
    <label for="pOld">Actual</label><input id="pOld" type="password" required autocomplete="current-password">
    <label for="pNew">Nueva (8 o más caracteres)</label><input id="pNew" type="password" required minlength="8" autocomplete="new-password">
    <div class="msg" id="pMsg"></div>
    <div class="row">
      <button type="button" class="btn" data-close>Cancelar</button>
      <button type="submit" class="btn pri">Cambiar</button>
    </div>
  </form>
</dialog>
<input type="file" id="impFile" accept="application/json" hidden>
<div class="toast" id="toast"></div>
<button id="uiBack" title="Mostrar la interfaz (h)" aria-label="Mostrar la interfaz">▪</button>
<div id="dropNew">soltar aquí · módulo nuevo</div>

<script>
const HQ_VERSION = '<?= HQ_VERSION ?>';
/* ============================================================
   CONFIGURACIÓN BASE (solo se usa la primera vez; después manda
   lo que guardes desde la página, sincronizado en tu servidor).
   ============================================================ */
const CONFIG = {
  name: 'Héctor',
  defaultPlace: { name: 'Ciudad de México', lat: 19.4326, lon: -99.1332 },
  zones: [
    { label: 'Ciudad de México', tz: 'America/Mexico_City' },
    { label: 'Buenos Aires',     tz: 'America/Argentina/Buenos_Aires' },
    { label: 'Europa Central',   tz: 'Europe/Berlin' }
  ],
  groups: ['Trabajo', 'Artefactos', 'Sitios'],
  links: [
    { name: 'Gmail',      url: 'https://mail.google.com',     group: 'Trabajo' },
    { name: 'Drive',      url: 'https://drive.google.com',    group: 'Trabajo' },
    { name: 'Calendario', url: 'https://calendar.google.com', group: 'Trabajo' },
    { name: 'Claude',     url: 'https://claude.ai',           group: 'Trabajo' },
    { name: 'Artefactos de Claude', url: 'https://claude.ai/code/artifacts', group: 'Artefactos' },
    { name: 'hectorcruz.com.mx',    url: 'https://hectorcruz.com.mx',        group: 'Sitios' },
    { name: 'Resonancias',          url: 'https://espacioresonancias.com',   group: 'Sitios' },
    { name: 'Otros Territorios',    url: 'https://otrosterritorios.com',     group: 'Sitios' }
  ],
  // Modos. «order» es el tablero de escritorio: «|» separa columnas.
  // «orderMobile» es el orden en una sola columna (teléfono o ventana angosta).
  modes: [
    { id: 'completo', name: 'Completo',
      order: ['reloj','relojes','cielo','|','buscar','ventana','frase','|','sonido','ambiente','noticias','trafico','|','g:Trabajo','g:Artefactos','g:Sitios'],
      orderMobile: ['reloj','buscar','g:Trabajo','g:Artefactos','sonido','relojes','noticias','ventana','frase','cielo','ambiente','g:Sitios','trafico'], hidden: [] },
    { id: 'enfoque', name: 'Enfoque',
      order: ['reloj','relojes','sonido','|','buscar','g:Trabajo','g:Artefactos','|','g:Sitios','noticias','|','frase','cielo','ambiente','ventana','trafico'],
      orderMobile: ['sonido','g:Trabajo','g:Artefactos','buscar','reloj','relojes','g:Sitios','noticias','frase','cielo','ambiente','ventana','trafico'], hidden: ['frase','cielo','ambiente','ventana','trafico'] },
    { id: 'calma', name: 'Calma',
      order: ['reloj','cielo','frase','|','ventana','sonido','|','g:Artefactos','g:Trabajo','|','relojes','buscar','g:Sitios','ambiente','noticias','trafico'],
      orderMobile: ['reloj','frase','sonido','ventana','g:Artefactos','g:Trabajo','cielo','relojes','buscar','g:Sitios','ambiente','noticias','trafico'], hidden: ['relojes','buscar','g:Sitios','ambiente','noticias','trafico'] }
  ],
  // Noticias. «local» se arma con la ciudad donde estés.
  feeds: [
    { id: 'mundo',  name: 'Mundo',  url: 'https://es.euronews.com/rss?format=mrss&level=theme&name=news' },
    { id: 'mexico', name: 'México', url: 'https://news.google.com/rss?hl=es-419&gl=MX&ceid=MX:es-419', alt: 'https://www.jornada.com.mx/rss/edicion.xml?v=1' },
    { id: 'local',  name: 'Local' }
  ],
  // Radio: «url» directa (verificada en la página de la estación) o «q» para buscarla en Radio Browser la primera vez
  radios: [
    { name: 'NPR · 24 horas', q: 'NPR 24 Hour Program Stream' },
    { name: 'WNYC · Nueva York', q: 'WNYC 93.9 FM' },
    { name: 'KQED · San Francisco', q: 'KQED' },
    { name: 'WBEZ · Chicago', q: 'WBEZ' },
    { name: 'KNKX · Seattle', url: 'https://knkx-live-a.edge.audiocdn.com/6284_128k' },
    { name: 'Jazz24', url: 'https://knkx-live-a.edge.audiocdn.com/6285_128k' },
    { name: 'Radio UNAM', q: 'Radio UNAM' },
    { name: 'Opus 94.5', q: 'Opus 94.5' },
    { name: 'SomaFM · Drone Zone', q: 'SomaFM Drone Zone' }
  ],
  feedSugs: [
    { name: 'Euronews (inglés)', url: 'https://www.euronews.com/rss?format=mrss&level=theme&name=news' },
    { name: 'Euronews Cultura', url: 'https://es.euronews.com/rss?format=mrss&level=vertical&name=culture' },
    { name: 'La Jornada', url: 'https://www.jornada.com.mx/rss/edicion.xml?v=1' },
    { name: 'Google News · Argentina', url: 'https://news.google.com/rss?hl=es-419&gl=AR&ceid=AR:es-419' },
    { name: 'Google News · España', url: 'https://news.google.com/rss?hl=es&gl=ES&ceid=ES:es' }
  ],
  // Ventana: imágenes de NASA y de museos con colecciones libres.
  // Clave gratuita de NASA (opcional, sube el límite): https://api.nasa.gov  — sin ella se usa DEMO_KEY.
  nasaKey: 'DEMO_KEY',
  phrases: [
    'La luz no ilumina el escenario: lo inventa.',
    'Primero el gesto, después la técnica.',
    'Lo que no se ensaya, se descubre.',
    'Hoy toca construir algo que todavía no tiene nombre.',
    'Cada proyección es una pregunta lanzada al espacio.',
    'Menos capas, más sentido.',
    'El error en vivo también es parte de la obra.',
    'Escucha el espacio antes de llenarlo.',
    'La sombra es la mitad del dibujo.',
    'Termina una cosa hoy. Solo una, pero entera.',
    'La técnica sirve al cuerpo, nunca al revés.',
    'Lo simple bien hecho sobrevive a cualquier gira.',
    'Hacer visible lo que el texto calla.',
    'Un buen respaldo vale más que un buen render.',
    'El público completa la imagen; deja espacio para él.',
    'Curiosidad primero, presupuesto después.'
  ]
};

/* ---------- utilidades ---------- */
const $ = id => document.getElementById(id);
// deslizador Alchemy: la parte recorrida se pinta en acento
const fillR = el => { if (el) el.style.setProperty('--fill', ((el.value - (el.min || 0)) / ((el.max || 1) - (el.min || 0)) * 100) + '%'); };
document.addEventListener('input', e => { if (e.target.classList?.contains('a-range')) fillR(e.target); });
const pad = n => String(n).padStart(2, '0');
const clamp = (v, a, b) => Math.max(a, Math.min(b, v));
const lerp = (a, b, t) => a + (b - a) * t;
const fmtT = (d, tz) => d ? d.toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit', hour12: false, ...(tz ? { timeZone: tz } : {}) }) : '--:--';
const dayOfYear = d => Math.floor((d - new Date(d.getFullYear(), 0, 0)) / 864e5);
const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[c]));
const local = {
  get(k, d){ try{ const v = localStorage.getItem(k); return v ? JSON.parse(v) : d; }catch(e){ return d; } },
  set(k, v){ try{ localStorage.setItem(k, JSON.stringify(v)); }catch(e){} }
};
const LOCAL_TZ = (() => { try{ return Intl.DateTimeFormat().resolvedOptions().timeZone; }catch(e){ return 'America/Mexico_City'; } })();
let toastT;
function toast(t){ const el = $('toast'); el.textContent = t; el.classList.add('on'); clearTimeout(toastT); toastT = setTimeout(() => el.classList.remove('on'), 2200); }
document.querySelectorAll('[data-close]').forEach(b => b.onclick = () => b.closest('dialog').close());

/* ============================================================
   ESTADO SINCRONIZADO (servidor + copia local)
   ============================================================ */
const KEY = 'hq.estado.v1';
let S = null, serverOK = false, saveT = null, pending = false;

function defaults(){
  const old = local.get('base.links.v1', null);   // migra la versión anterior
  return { v: 2, layoutV: 2, links: old || CONFIG.links.slice(), groups: CONFIG.groups.slice(), zones: CONFIG.zones.slice(),
           modes: JSON.parse(JSON.stringify(CONFIG.modes)), active: 'completo', feeds: [], newsTab: 'mundo', sonidos: [], radios: CONFIG.radios.map(r => ({ ...r })), rev: 0 };
}
// columnas del tablero
const DASH_COL = { reloj: 0, relojes: 0, cielo: 0, sonido: 2, ambiente: 2, buscar: 1, ventana: 1, frase: 1, noticias: 2, trafico: 2 };
const splitCols = order => { const out = [[]]; (order || []).forEach(id => id === '|' ? out.push([]) : out[out.length - 1].push(id)); return out.filter(c => c.length); };
const joinCols = cols => cols.filter(c => c.length).flatMap((c, i) => i ? ['|', ...c] : c);
function autoCols(flat){ const b = [[], [], [], []]; flat.forEach(id => { if (id !== '|') b[DASH_COL[id] ?? 3].push(id); }); return b.filter(c => c.length); }
function normalize(s){
  if (!s || !Array.isArray(s.links)) return null;
  s.zones = Array.isArray(s.zones) ? s.zones : CONFIG.zones.slice();
  s.modes = Array.isArray(s.modes) && s.modes.length ? s.modes : JSON.parse(JSON.stringify(CONFIG.modes));
  s.groups = Array.isArray(s.groups) ? s.groups : [...new Set(s.links.map(l => l.group || 'Otros'))];
  s.feeds = Array.isArray(s.feeds) ? s.feeds : [];
  s.sonidos = Array.isArray(s.sonidos) ? s.sonidos : [];
  s.radios = Array.isArray(s.radios) ? s.radios : CONFIG.radios.map(r => ({ ...r }));
  if ((s.layoutV || 0) < 2){          // migra modos de una columna al tablero por columnas
    s.modes.forEach(m => {
      if ((m.order || []).includes('|')) return;
      const flat = (m.order || []).slice();
      const add = (id, after) => { if (!flat.includes(id)){ const k = flat.indexOf(after); k >= 0 ? flat.splice(k + 1, 0, id) : flat.push(id); } };
      add('ventana', 'reloj'); add('noticias', 'ventana'); add('trafico', 'noticias');
      m.orderMobile = flat.slice();
      m.order = joinCols(autoCols(flat));
      m.hidden = m.hidden || [];
      if (m.id === 'enfoque') m.hidden = [...new Set([...m.hidden, 'ventana', 'trafico'])];
      if (m.id === 'calma') m.hidden = [...new Set([...m.hidden, 'noticias', 'trafico'])];
    });
    s.layoutV = 2;
  }
  if (!s.modes.find(m => m.id === s.active)) s.active = s.modes[0].id;
  return s;
}
async function api(path, opts = {}){
  const r = await fetch('api.php?' + path, { credentials: 'same-origin', cache: 'no-store', ...opts,
    headers: { 'X-Base': '1', 'Content-Type': 'application/json', ...(opts.headers || {}) } });
  if (r.status === 401){ location.reload(); throw new Error('sesión'); }
  return r;
}
async function loadState(){
  let srv = null;
  try{ const r = await api('estado'); if (r.ok){ srv = normalize(await r.json()); serverOK = true; } }catch(e){}
  const loc = normalize(local.get(KEY, null));
  S = srv || loc || defaults();
  if (srv && loc && (loc.rev || 0) > (srv.rev || 0) && local.get('hq.pending', false)) S = loc;  // había cambios sin subir
  if (serverOK && (!srv || S === loc)) pushNow();
  setSync();
}
function save(){
  S.rev = Date.now(); local.set(KEY, S); local.set('hq.pending', true); pending = true; setSync();
  clearTimeout(saveT); saveT = setTimeout(pushNow, 600);
}
async function pushNow(){
  try{
    const r = await api('estado', { method: 'POST', body: JSON.stringify(S) });
    if (!r.ok) throw 0;
    const j = await r.json(); S.rev = j.rev; local.set(KEY, S); local.set('hq.pending', false);
    pending = false; serverOK = true;
  }catch(e){ serverOK = false; }
  setSync();
}
function setSync(){
  const ok = serverOK && !pending;
  $('syncDot').classList.toggle('local', !ok);
  $('status').textContent = ok ? 'hq · sincronizada' : pending && serverOK ? 'hq · guardando…' : 'hq · solo en este dispositivo';
}
async function refreshFromServer(){
  if (editing || document.querySelector('dialog[open]') || pending) return;
  try{
    const r = await api('estado'); if (!r.ok) return;
    const srv = normalize(await r.json()); serverOK = true; setSync();
    if (srv && (srv.rev || 0) > (S.rev || 0)){ S = srv; local.set(KEY, S); renderAll(); }
  }catch(e){}
}
addEventListener('online', () => pending && pushNow());

/* ============================================================
   RELOJ PRINCIPAL Y RELOJES DEL MUNDO
   ============================================================ */
function tickClock(){
  const now = new Date(), h = now.getHours();
  $('time').firstChild.nodeValue = pad(h) + ':' + pad(now.getMinutes());
  $('sec').textContent = pad(now.getSeconds());
  $('date').textContent = now.toLocaleDateString('es-MX', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }).replace(/^\w/, c => c.toUpperCase());
  $('greet').textContent = (h < 6 ? 'Buena madrugada' : h < 12 ? 'Buenos días' : h < 19 ? 'Buenas tardes' : 'Buenas noches') + ', ' + CONFIG.name;
  if (now.getSeconds() === 0 || !tickClock.done){ renderZones(); updateSky(); tickClock.done = true; }
}
function tzOffsetMin(tz, d = new Date()){
  try{
    const p = new Intl.DateTimeFormat('en-US', { timeZone: tz, hourCycle: 'h23', year: 'numeric', month: 'numeric', day: 'numeric', hour: 'numeric', minute: 'numeric' }).formatToParts(d);
    const g = t => +p.find(x => x.type === t).value;
    return Math.round((Date.UTC(g('year'), g('month') - 1, g('day'), g('hour'), g('minute')) - Math.floor(d / 6e4) * 6e4) / 6e4);
  }catch(e){ return 0; }
}
function zoneHour(tz, d){ try{ return +new Intl.DateTimeFormat('en-US', { timeZone: tz, hour: 'numeric', hourCycle: 'h23' }).format(d); }catch(e){ return 0; } }
function zoneDay(tz, d){ try{ return new Intl.DateTimeFormat('es-MX', { timeZone: tz, weekday: 'short' }).format(d); }catch(e){ return ''; } }
function renderZones(){
  if (!S) return;
  const now = new Date(), myOff = tzOffsetMin(LOCAL_TZ, now), myDay = zoneDay(LOCAL_TZ, now);
  let list = S.zones.map((z, i) => ({ ...z, i }));
  if (!list.some(z => z.tz === LOCAL_TZ)) list.unshift({ label: (place.name || 'Aquí').split(',')[0], tz: LOCAL_TZ, i: -1 });
  list.sort((a, b) => (b.tz === LOCAL_TZ) - (a.tz === LOCAL_TZ));
  $('zones').innerHTML = list.map(z => {
    const off = tzOffsetMin(z.tz, now) - myOff, here = z.tz === LOCAL_TZ;
    const diff = here ? 'aquí' : (off === 0 ? 'misma hora' : (off > 0 ? '+' : '−') + (Math.abs(off) / 60).toString().replace('.5', '½').replace(/\.\d+/, '') + ' h');
    const hr = zoneHour(z.tz, now), wd = zoneDay(z.tz, now);
    const dow = new Date(now.toLocaleString('en-US', { timeZone: z.tz })).getDay(), lab = dow > 0 && dow < 6;
    const [txt, col] = hr >= 9 && hr < 19 && lab ? ['en horario', 'var(--ok)'] : hr >= 7 && hr < 22 ? ['fuera de horario', 'var(--mid)'] : ['descansando', 'var(--faint)'];
    return `<div class="card zone${here ? ' here' : ''}">
      ${z.i >= 0 ? `<button class="del" data-zdel="${z.i}" title="Quitar">×</button>` : ''}
      <div class="zn"><span>${esc(z.label)}</span><em>${diff}</em></div>
      <div class="zt">${fmtT(now, z.tz)}</div>
      <div class="zs"><i style="background:${col}"></i>${txt}${wd !== myDay ? ' · ' + esc(wd) : ''}</div></div>`;
  }).join('') + `<button class="card zone add" id="zAdd">+ reloj</button>`;
}
$('zones').addEventListener('click', e => {
  const d = e.target.closest('[data-zdel]');
  if (d){ S.zones.splice(+d.dataset.zdel, 1); save(); renderZones(); return; }
  if (e.target.closest('#zAdd')) openZone();
});
let tzNames = [];
function openZone(){
  if (!tzNames.length){ try{ tzNames = Intl.supportedValuesOf('timeZone'); }catch(e){} $('tzList').innerHTML = tzNames.map(t => `<option value="${t}">`).join(''); }
  $('zCity').value = ''; $('zTz').value = ''; $('zSugs').innerHTML = '';
  $('zdlg').showModal(); setTimeout(() => $('zCity').focus(), 50);
}
let geoT;
$('zCity').addEventListener('input', () => {
  clearTimeout(geoT);
  const q = $('zCity').value.trim(); if (q.length < 2){ $('zSugs').innerHTML = ''; return; }
  geoT = setTimeout(async () => {
    try{
      const j = await (await fetch(`https://geocoding-api.open-meteo.com/v1/search?name=${encodeURIComponent(q)}&count=5&language=es`)).json();
      $('zSugs').innerHTML = (j.results || []).map(r => `<button type="button" data-tz="${esc(r.timezone)}" data-n="${esc(r.name)}">${esc(r.name)}<em>${esc([r.admin1, r.country].filter(Boolean).join(', '))} · ${esc(r.timezone)}</em></button>`).join('');
    }catch(e){}
  }, 300);
});
$('zSugs').addEventListener('click', e => { const b = e.target.closest('[data-tz]'); if (!b) return; $('zCity').value = b.dataset.n; $('zTz').value = b.dataset.tz; });
$('zForm').addEventListener('submit', e => {
  const tz = $('zTz').value.trim();
  try{ new Intl.DateTimeFormat('es', { timeZone: tz }); }catch(err){ e.preventDefault(); toast('Esa zona horaria no existe'); return; }
  S.zones.push({ label: $('zCity').value.trim() || tz.split('/').pop().replace(/_/g, ' '), tz });
  save(); renderZones();
});

/* ============================================================
   SOL, LUNA, AÑO (cálculo astronómico local, sin API)
   ============================================================ */
const RAD = Math.PI / 180, J1970 = 2440588, J2000 = 2451545, E = RAD * 23.4397, J0 = 0.0009;
const toDays = d => d.valueOf() / 864e5 - 0.5 + J1970 - J2000;
const fromJ = j => new Date((j + 0.5 - J1970) * 864e5);
function sunTimes(date, lat, lon){
  const lw = RAD * -lon, phi = RAD * lat, d = toDays(date);
  const n = Math.round(d - J0 - lw / (2 * Math.PI));
  const ds = J0 + lw / (2 * Math.PI) + n;
  const M = RAD * (357.5291 + 0.98560028 * ds);
  const C = RAD * (1.9148 * Math.sin(M) + 0.02 * Math.sin(2 * M) + 0.0003 * Math.sin(3 * M));
  const L = M + C + RAD * 102.9372 + Math.PI;
  const dec = Math.asin(Math.sin(E) * Math.sin(L));
  const transit = a => J2000 + a + 0.0053 * Math.sin(M) - 0.0069 * Math.sin(2 * L);
  const noon = transit(ds);
  const at = h => {
    const c = (Math.sin(h) - Math.sin(phi) * Math.sin(dec)) / (Math.cos(phi) * Math.cos(dec));
    if (c < -1 || c > 1) return null;
    const w = Math.acos(c), set = transit(J0 + (w + lw) / (2 * Math.PI) + n);
    return [fromJ(noon - (set - noon)), fromJ(set)];
  };
  const sun = at(-0.833 * RAD), golden = at(6 * RAD), civil = at(-6 * RAD);
  return { rise: sun && sun[0], set: sun && sun[1], noon: fromJ(noon), goldenEve: golden && golden[1], goldenMorn: golden && golden[0], dawn: civil && civil[0], dusk: civil && civil[1] };
}
function sunAltitude(date, lat, lon){
  const lw = RAD * -lon, phi = RAD * lat, d = toDays(date);
  const M = RAD * (357.5291 + 0.98560028 * d);
  const C = RAD * (1.9148 * Math.sin(M) + 0.02 * Math.sin(2 * M) + 0.0003 * Math.sin(3 * M));
  const L = M + C + RAD * 102.9372 + Math.PI;
  const dec = Math.asin(Math.sin(E) * Math.sin(L)), ra = Math.atan2(Math.sin(L) * Math.cos(E), Math.cos(L));
  const H = RAD * (280.16 + 360.9856235 * d) - lw - ra;
  return Math.asin(Math.sin(phi) * Math.sin(dec) + Math.cos(phi) * Math.cos(dec) * Math.cos(H)) / RAD;
}
function moon(now){
  const syn = 29.530588853, ref = Date.UTC(2000, 0, 6, 18, 14);
  const age = ((((now - ref) / 864e5) % syn) + syn) % syn, f = age / syn;
  const ph = [['🌑','Luna nueva'],['🌒','Creciente'],['🌓','Cuarto creciente'],['🌔','Gibosa creciente'],['🌕','Luna llena'],['🌖','Gibosa menguante'],['🌗','Cuarto menguante'],['🌘','Menguante']];
  const i = Math.round(f * 8) % 8;
  return { ico: ph[i][0], name: ph[i][1], illum: Math.round((1 - Math.cos(2 * Math.PI * f)) / 2 * 100), toFull: Math.round(((0.5 - f + 1) % 1) * syn), toNew: Math.round((1 - f) * syn) };
}

let place = local.get('hq.place', CONFIG.defaultPlace);
function updateSky(){
  const now = new Date(), s = sunTimes(now, place.lat, place.lon);
  $('rise').textContent = fmtT(s.rise); $('set').textContent = fmtT(s.set);
  amb.alt = sunAltitude(now, place.lat, place.lon);
  if (s.rise && s.set){
    const len = (s.set - s.rise) / 6e4;
    $('dayLen').textContent = Math.floor(len / 60) + ' h ' + pad(Math.round(len % 60));
    const p = (now - s.rise) / (s.set - s.rise), dot = $('sunDot');
    amb.dayP = p;
    let note;
    if (p >= 0 && p <= 1){
      dot.setAttribute('cx', 4 + p * 292); dot.setAttribute('cy', 54 - 200 * p * (1 - p)); dot.setAttribute('fill', '#ffffff');
      const left = Math.round((s.set - now) / 6e4);
      note = `Quedan ${Math.floor(left / 60)} h ${pad(left % 60)} min de luz` + (s.goldenEve ? ` · hora dorada desde ${fmtT(s.goldenEve)}` : '');
    } else {
      dot.setAttribute('cx', p < 0 ? 4 : 296); dot.setAttribute('cy', 54); dot.setAttribute('fill', 'rgba(236,235,231,.35)');
      const next = p < 0 ? s.rise : sunTimes(new Date(now.getTime() + 864e5), place.lat, place.lon).rise;
      note = `Noche · próximo amanecer ${fmtT(next)}` + (s.dusk && p > 1 && now < s.dusk ? ` · crepúsculo hasta ${fmtT(s.dusk)}` : '');
    }
    $('sunNote').textContent = note + ` · mediodía solar ${fmtT(s.noon)}`;
  }
  const m = moon(now); amb.moon = m.illum / 100;
  $('moonIco').textContent = m.ico + ' ' + m.illum + '%';
  const dias = n => n === 1 ? '1 día' : n + ' días';
  $('moonTxt').innerHTML = `${m.name}<br>${m.name === 'Luna nueva' ? 'llena en ' + dias(m.toFull) : m.name === 'Luna llena' || m.toFull === 0 ? 'nueva en ' + dias(m.toNew) : m.toFull < m.toNew ? 'llena en ' + dias(m.toFull) : 'nueva en ' + dias(m.toNew)}`;
  const y = now.getFullYear(), dy = dayOfYear(now), tot = (y % 4 === 0 && y % 100 !== 0) || y % 400 === 0 ? 366 : 365;
  const pct = Math.round(dy / tot * 100);
  const d = new Date(Date.UTC(y, now.getMonth(), now.getDate())), dn = d.getUTCDay() || 7; d.setUTCDate(d.getUTCDate() + 4 - dn);
  const wk = Math.ceil(((d - Date.UTC(d.getUTCFullYear(), 0, 1)) / 864e5 + 1) / 7);
  $('yearPct').textContent = pct + '%'; $('yearTxt').innerHTML = `Día ${dy} de ${tot}<br>Semana ${wk}`; $('yearBar').style.width = pct + '%';
}

/* ============================================================
   CLIMA, AIRE Y MAR (Open-Meteo, sin clave)
   ============================================================ */
const WMO = { 0:'Despejado',1:'Casi despejado',2:'Parcialmente nublado',3:'Nublado',45:'Niebla',48:'Niebla con escarcha',
  51:'Llovizna ligera',53:'Llovizna',55:'Llovizna densa',56:'Llovizna helada',57:'Llovizna helada',61:'Lluvia ligera',63:'Lluvia',65:'Lluvia fuerte',
  66:'Lluvia helada',67:'Lluvia helada',71:'Nieve ligera',73:'Nieve',75:'Nieve fuerte',77:'Granos de nieve',80:'Chubascos',81:'Chubascos',82:'Chubascos fuertes',
  85:'Nevadas',86:'Nevadas fuertes',95:'Tormenta',96:'Tormenta con granizo',99:'Tormenta con granizo' };
// estado del ambiente que lee el fondo animado
const amb = { alt: 10, dayP: .5, cloud: .2, rain: 0, snow: 0, fog: 0, storm: 0, wind: 6, windDir: 270, aqi: 30, uv: 3, moon: .5, tide: null };
const AQI = [[50,'Buena','var(--ok)'],[100,'Aceptable','var(--mid)'],[150,'Mala para sensibles','var(--warn)'],[200,'Mala','var(--bad)'],[300,'Muy mala','var(--vbad)'],[1e9,'Extremadamente mala','#a0605a']];
const UVS = [[3,'Bajo','var(--ok)'],[6,'Moderado','var(--mid)'],[8,'Alto','var(--warn)'],[11,'Muy alto','var(--bad)'],[1e9,'Extremo','var(--vbad)']];
const band = (v, t) => t.find(b => v < b[0]) || t[t.length - 1];
function scaleBar(el, v, t, max){
  el.innerHTML = t.map((b, i) => `<i style="background:${v >= (i ? t[i - 1][0] : 0) ? b[2] : 'var(--line)'};opacity:${v >= (i ? t[i - 1][0] : 0) ? .9 : 1}"></i>`).join('');
}
const dirName = d => ['N','NE','E','SE','S','SO','O','NO'][Math.round(((d % 360) + 360) % 360 / 45) % 8];
function sparkline(vals, vals2){
  const w = 200, h = 46, mn = Math.min(...vals), mx = Math.max(...vals), k = mx - mn || 1;
  const pts = vals.map((v, i) => `${(i / (vals.length - 1) * w).toFixed(1)},${(h - 6 - (v - mn) / k * (h - 14)).toFixed(1)}`).join(' ');
  const bars = (vals2 || []).map((p, i) => p > 0 ? `<rect x="${(i / vals2.length * w + 1).toFixed(1)}" y="${(h - p / 100 * 20).toFixed(1)}" width="${(w / vals2.length - 2).toFixed(1)}" height="${(p / 100 * 20).toFixed(1)}" fill="rgba(143,192,212,.35)"/>` : '').join('');
  return bars + `<polyline points="${pts}" fill="none" stroke="#d2d2d2" stroke-width="1.4" vector-effect="non-scaling-stroke" stroke-linejoin="round"/>`;
}
function haversine(a, b, c, d){ const R = 6371, x = (c - a) * RAD, y = (d - b) * RAD, q = Math.sin(x / 2) ** 2 + Math.cos(a * RAD) * Math.cos(c * RAD) * Math.sin(y / 2) ** 2; return 2 * R * Math.asin(Math.sqrt(q)); }

async function loadWeather(){
  const L = `latitude=${place.lat}&longitude=${place.lon}&timezone=auto`;
  const [w, a, m] = await Promise.allSettled([
    fetch(`https://api.open-meteo.com/v1/forecast?${L}&current=temperature_2m,apparent_temperature,relative_humidity_2m,weather_code,cloud_cover,wind_speed_10m,wind_direction_10m,precipitation&hourly=temperature_2m,precipitation_probability&daily=temperature_2m_max,temperature_2m_min,precipitation_probability_max&forecast_days=2`).then(r => r.json()),
    fetch(`https://air-quality-api.open-meteo.com/v1/air-quality?${L}&current=us_aqi,pm2_5,pm10,uv_index,dust`).then(r => r.json()),
    fetch(`https://marine-api.open-meteo.com/v1/marine?${L}&current=sea_level_height_msl,wave_height,sea_surface_temperature&hourly=sea_level_height_msl&forecast_days=2`).then(r => r.json())
  ]);
  const moods = [];
  if (w.status === 'fulfilled' && w.value.current){
    const c = w.value.current, d = w.value.daily, code = c.weather_code;
    $('weather').innerHTML = `<b>${Math.round(c.temperature_2m)}°</b> ${WMO[code] || ''} · ${Math.round(d.temperature_2m_min[0])}° / ${Math.round(d.temperature_2m_max[0])}°` + (d.precipitation_probability_max?.[0] != null ? ` · lluvia ${d.precipitation_probability_max[0]}%` : '');
    amb.cloud = (c.cloud_cover ?? 20) / 100; amb.temp = c.temperature_2m;
    amb.rain = [51,53,55,56,57].includes(code) ? .3 : [61,80,66].includes(code) ? .55 : [63,81].includes(code) ? .8 : [65,82,67].includes(code) ? 1 : (c.precipitation > 0 ? .4 : 0);
    amb.snow = [71,73,75,77,85,86].includes(code) ? ([75,86].includes(code) ? 1 : .6) : 0;
    amb.fog = [45,48].includes(code) ? 1 : 0;
    amb.storm = [95,96,99].includes(code) ? 1 : 0;
    if (amb.storm) amb.rain = Math.max(amb.rain, .9);
    amb.wind = c.wind_speed_10m ?? 6; amb.windDir = c.wind_direction_10m ?? 270;
    $('windArr').style.transform = `rotate(${amb.windDir}deg)`;
    $('windTxt').textContent = `${Math.round(amb.wind)} km/h ${dirName(amb.windDir)}`;
    $('humTxt').innerHTML = `Humedad ${c.relative_humidity_2m}%<br>Sensación ${Math.round(c.apparent_temperature)}° · nubes ${c.cloud_cover}%`;
    moods.push(amb.storm ? 'tormenta' : amb.snow ? 'nieve' : amb.rain ? 'lluvia' : amb.fog ? 'niebla' : amb.cloud > .7 ? 'cielo cubierto' : amb.cloud > .35 ? 'nubes sueltas' : 'cielo limpio');
    if (amb.wind > 30) moods.push('viento fuerte');
    // próximas 12 horas (se reemplaza por mareas si hay mar cerca)
    const h = w.value.hourly, nowIso = c.time.slice(0, 13);
    let i0 = h.time.findIndex(t => t.slice(0, 13) === nowIso); if (i0 < 0) i0 = 0;
    W12 = { temp: h.temperature_2m.slice(i0, i0 + 13), rain: h.precipitation_probability.slice(i0, i0 + 13) };
  } else $('weather').textContent = 'clima no disponible';

  if (a.status === 'fulfilled' && a.value.current){
    const c = a.value.current, aqi = c.us_aqi ?? 0, uv = c.uv_index ?? 0;
    amb.aqi = aqi; amb.uv = uv; amb.dust = c.dust || 0;
    const [_, an, ac] = band(aqi, AQI);
    $('aqiLvl').style.setProperty('--c', ac); $('aqiLvl').textContent = `${Math.round(aqi)} · ${an}`;
    $('aqiTxt').innerHTML = `PM2.5 ${Math.round(c.pm2_5 ?? 0)} · PM10 ${Math.round(c.pm10 ?? 0)} µg/m³` + (aqi > 100 ? '<br>mejor ventilar menos y evitar ejercicio fuera' : '');
    scaleBar($('aqiScale'), aqi, AQI);
    const [__, un, uc] = band(uv, UVS);
    $('uvLvl').style.setProperty('--c', uc); $('uvLvl').textContent = `${uv.toFixed(1)} · ${un}`;
    $('uvTxt').textContent = uv >= 6 ? 'bloqueador y sombra al mediodía' : uv >= 3 ? 'protección si sales un rato largo' : 'sin cuidado especial';
    scaleBar($('uvScale'), uv, UVS);
    moods.push('aire ' + an.toLowerCase());
  }

  amb.tide = null;
  if (m.status === 'fulfilled' && m.value.hourly && m.value.current && m.value.current.sea_level_height_msl != null
      && haversine(place.lat, place.lon, m.value.latitude, m.value.longitude) < 30){
    const H = m.value.hourly, v = H.sea_level_height_msl, cur = m.value.current;
    const nowIso = cur.time.slice(0, 13); let i0 = H.time.findIndex(t => t.slice(0, 13) === nowIso); if (i0 < 0) i0 = 0;
    const next = v.slice(i0, i0 + 25).filter(x => x != null), mn = Math.min(...next), mx = Math.max(...next);
    const rising = (v[i0 + 1] ?? cur.sea_level_height_msl) > cur.sea_level_height_msl;
    let hi = null, lo = null;
    for (let i = i0 + 1; i < v.length - 1 && (!hi || !lo); i++){
      if (!hi && v[i] >= v[i - 1] && v[i] > v[i + 1]) hi = H.time[i];
      if (!lo && v[i] <= v[i - 1] && v[i] < v[i + 1]) lo = H.time[i];
    }
    amb.tide = { level: clamp((cur.sea_level_height_msl - mn) / ((mx - mn) || 1), 0, 1), rising };
    $('seaLbl').textContent = 'Mar';
    $('seaBig').innerHTML = `<span style="color:var(--sea)">Marea ${rising ? 'subiendo ↑' : 'bajando ↓'}</span>`;
    $('seaTxt').innerHTML = [hi && 'pleamar ' + hi.slice(11, 16), lo && 'bajamar ' + lo.slice(11, 16)].filter(Boolean).join(' · ')
      + `<br>olas ${(cur.wave_height ?? 0).toFixed(1)} m` + (cur.sea_surface_temperature != null ? ` · agua ${Math.round(cur.sea_surface_temperature)}°` : '');
    $('spark').innerHTML = sparkline(next.slice(0, 13).length > 1 ? next.slice(0, 13) : [0, 0]).replace('#d2d2d2', '#8fc0d4');
    moods.push('marea ' + (rising ? 'subiendo' : 'bajando'));
  } else if (W12.temp.length > 1){
    $('seaLbl').textContent = 'Próximas 12 horas';
    const maxR = Math.max(...W12.rain);
    $('seaBig').textContent = `${Math.round(Math.min(...W12.temp))}° – ${Math.round(Math.max(...W12.temp))}°`;
    $('seaTxt').textContent = maxR >= 30 ? `lluvia probable (${maxR}%)` : 'sin lluvia a la vista';
    $('spark').innerHTML = sparkline(W12.temp, W12.rain);
  }
  if (window.SND) SND.refresh();
  $('mood').textContent = moods.length ? 'el fondo hoy: ' + moods.join(' · ') : '';
}
let W12 = { temp: [], rain: [] };

/* ---------- ubicación ---------- */
function setPlaceName(){ $('place').textContent = place.name; }
async function reverseName(lat, lon){
  try{
    const j = await (await fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${lat}&longitude=${lon}&localityLanguage=es`)).json();
    return [j.city || j.locality, j.principalSubdivision].filter(Boolean).join(', ') || 'Mi ubicación';
  }catch(e){ return 'Mi ubicación'; }
}
function locate(){
  if (!navigator.geolocation) return;
  $('locBtn').textContent = 'buscando…';
  navigator.geolocation.getCurrentPosition(async pos => {
    const lat = +pos.coords.latitude.toFixed(3), lon = +pos.coords.longitude.toFixed(3);
    place = { lat, lon, name: await reverseName(lat, lon) };
    local.set('hq.place', place);
    setPlaceName(); updateSky(); renderZones(); loadWeather();
    trafficLabel(); if ($('traffic').querySelector('iframe')) loadTraffic();
    renderNewsTabs(); if (S.newsTab === 'local') loadNews(true);
    $('locBtn').textContent = 'actualizar';
  }, () => { $('locBtn').textContent = 'ubicación no permitida'; }, { timeout: 10000, maximumAge: 36e5 });
}
$('locBtn').onclick = locate;

/* ---------- frase y foto ---------- */
let phraseIdx = dayOfYear(new Date()) % CONFIG.phrases.length;
function showPhrase(){ $('phraseTxt').textContent = CONFIG.phrases[phraseIdx]; }
$('phrase').onclick = () => { phraseIdx = (phraseIdx + 1) % CONFIG.phrases.length; showPhrase(); };
/* ============================================================
   VENTANA: una imagen viva de NASA o de museos de dominio público.
   La pintura se elige según el clima y la hora: lluvia → tormentas,
   niebla → bruma, noche → nocturnos, nieve → invierno…
   ============================================================ */
const FUENTES = [
  { id: 'azar',     name: 'al azar' },
  { id: 'apod',     name: 'NASA · foto del día' },
  { id: 'espacio',  name: 'NASA · espacio' },
  { id: 'pintura',  name: 'pintura · Chicago' },
  { id: 'cleveland',name: 'pintura · Cleveland' }
];
const pick = a => a[Math.floor(Math.random() * a.length)];
const rnd = (a, b) => a + Math.floor(Math.random() * (b - a + 1));
function temaDelCielo(){
  const noche = amb.alt < -6, crep = amb.alt >= -6 && amb.alt < 8;
  if (amb.storm > .5) return pick(['storm', 'lightning', 'shipwreck', 'tempest']);
  if (amb.rain > .2)  return pick(['rain', 'storm', 'wet street', 'river']);
  if (amb.snow > .2)  return pick(['snow', 'winter', 'ice']);
  if (amb.fog > .2)   return pick(['fog', 'mist', 'haze']);
  if (noche)          return pick(['night', 'moonlight', 'moon', 'nocturne', 'stars']);
  if (crep)           return pick(['sunset', 'dusk', 'twilight', 'evening', 'dawn']);
  if (amb.cloud > .6) return pick(['clouds', 'overcast', 'sky', 'grey sea']);
  return pick(['landscape', 'sea', 'sky', 'garden', 'mountains', 'light']);
}
const ESPACIO = ['nebula', 'galaxy', 'aurora', 'earth from space', 'hubble', 'webb telescope', 'saturn', 'jupiter', 'milky way', 'moon surface', 'mars surface', 'star cluster'];
const strip = h => String(h || '').replace(/<[^>]+>/g, '').replace(/\s+/g, ' ').trim();
const cut = (t, n) => t.length > n ? t.slice(0, n - 1).trimEnd() + '…' : t;

async function fApod(){
  const hoy = new Date().toISOString().slice(0, 10), c = local.get('hq.apod', null);
  let j = c && c.date === hoy ? c : null;
  if (!j){
    j = await (await fetch(`https://api.nasa.gov/planetary/apod?api_key=${CONFIG.nasaKey || 'DEMO_KEY'}&thumbs=true`)).json();
    if (j.error || j.code) throw 0;
    j.date = hoy; local.set('hq.apod', j);
  }
  const src = j.media_type === 'image' ? (j.url || j.hdurl) : j.thumbnail_url;
  if (!src) throw 0;
  return { src, title: j.title, by: 'NASA · Astronomy Picture of the Day' + (j.copyright ? ' · © ' + strip(j.copyright) : ''), link: `https://apod.nasa.gov/apod/ap${hoy.slice(2).replace(/-/g, '')}.html`, fit: false };
}
async function fEspacio(){
  const q = pick(ESPACIO);
  const j = await (await fetch(`https://images-api.nasa.gov/search?q=${encodeURIComponent(q)}&media_type=image&page_size=60&page=${rnd(1, 3)}`)).json();
  const items = (j.collection?.items || []).filter(i => i.links?.[0]?.href);
  if (!items.length) throw 0;
  const it = pick(items), d = it.data[0];
  const thumb = it.links[0].href;
  return { src: thumb.replace('~thumb.', '~medium.'), alt: thumb, title: cut(d.title || q, 90), by: 'NASA Image Library' + (d.center ? ' · ' + d.center : '') + (d.date_created ? ' · ' + d.date_created.slice(0, 4) : ''), link: `https://images.nasa.gov/details/${encodeURIComponent(d.nasa_id)}` };
}
async function fChicago(){
  const q = temaDelCielo();
  const url = `https://api.artic.edu/api/v1/artworks/search?q=${encodeURIComponent(q)}&query[term][is_public_domain]=true&fields=id,title,artist_title,artist_display,date_display,image_id,classification_title&limit=40&page=${rnd(1, 3)}`;
  let r; try{ r = await fetch(url, { headers: { 'AIC-User-Agent': 'hq-inicio-personal' } }); }catch(e){ r = await fetch(url); }
  const j = await r.json();
  const items = (j.data || []).filter(i => i.image_id && /paint|print|drawing|photo/i.test(i.classification_title || 'painting'));
  if (!items.length) throw 0;
  const it = pick(items), base = j.config?.iiif_url || 'https://www.artic.edu/iiif/2';
  return { src: `${base}/${it.image_id}/full/843,/0/default.jpg`, title: cut(it.title, 90), by: [it.artist_title, it.date_display].filter(Boolean).join(' · ') + ' · Art Institute of Chicago', link: `https://www.artic.edu/artworks/${it.id}`, tema: q };
}
async function fCleveland(){
  const q = temaDelCielo();
  const j = await (await fetch(`https://openaccess-api.clevelandart.org/api/artworks/?q=${encodeURIComponent(q)}&cc0&has_image=1&type=Painting&limit=30&skip=${rnd(0, 30)}`)).json();
  let items = (j.data || []).filter(i => i.images?.web?.url);
  if (!items.length) throw 0;
  const it = pick(items), who = (it.creators?.[0]?.description || '').replace(/\s*\(.*$/, '');
  return { src: it.images.web.url, title: cut(it.title, 90), by: [who, it.creation_date].filter(Boolean).join(' · ') + ' · Cleveland Museum of Art', link: it.url, tema: q };
}
const FX = { apod: fApod, espacio: fEspacio, pintura: fChicago, cleveland: fCleveland };

let flip = false, vBusy = false;
function fuente(){ return (S && S.ventana) || 'azar'; }
function pintarFuente(){ $('srcBtn').textContent = FUENTES.find(f => f.id === fuente())?.name || 'al azar'; }
function mostrar(it){
  return new Promise((ok, no) => {
    const next = flip ? $('imgA') : $('imgB'), prev = flip ? $('imgB') : $('imgA');
    next.onload = () => {
      next.classList.toggle('fit', next.naturalHeight > next.naturalWidth * 1.25 && it.fit !== false);
      next.classList.add('on'); prev.classList.remove('on'); flip = !flip;
      $('cap').innerHTML = `${esc(it.title || '')}<small>${esc(it.by || '')}${it.link ? ` · <a href="${esc(it.link)}" target="_blank" rel="noopener" onclick="event.stopPropagation()">ver</a>` : ''}${it.tema ? ' · tema «' + esc(it.tema) + '»' : ''}</small>`;
      local.set('hq.ventana.ultima', it); ok();
    };
    next.onerror = () => { if (it.alt && next.src !== it.alt){ next.src = it.alt; return; } no(); };
    next.alt = it.title || ''; next.src = it.src;
  });
}
async function nuevaImagen(){
  if (vBusy) return; vBusy = true; $('photo').classList.add('loading');
  const f = fuente(), orden = f === 'azar' ? [pick(['pintura', 'cleveland', 'espacio', 'pintura', 'espacio']), 'pintura', 'espacio', 'cleveland'] : [f, 'espacio', 'pintura'];
  for (const id of [...new Set(orden)]){
    try{ await mostrar(await FX[id]()); break; }catch(e){}
  }
  $('photo').classList.remove('loading'); vBusy = false;
}
$('photo').onclick = e => { if (!e.target.closest('a,button')) nuevaImagen(); };
$('srcBtn').onclick = e => {
  e.stopPropagation();
  const i = FUENTES.findIndex(f => f.id === fuente());
  S.ventana = FUENTES[(i + 1) % FUENTES.length].id; save(); pintarFuente(); nuevaImagen();
};
function iniciarVentana(){
  pintarFuente();
  const u = local.get('hq.ventana.ultima', null);
  if (u) mostrar(u).catch(() => {});          // la última, al instante
  setTimeout(nuevaImagen, u ? 900 : 0);       // y luego una nueva
}

/* ============================================================
   NOTICIAS: Mundo (Euronews), México (Google News), Local (tu ciudad)
   Los RSS se piden a tu servidor, que los guarda 10 minutos.
   ============================================================ */
const newsCache = {};
function feedsAll(){ return [...CONFIG.feeds, ...(S.feeds || []).map((f, i) => ({ ...f, id: 'c' + i, custom: i }))]; }
const cityName = () => (place.name || 'Ciudad de México').split(',')[0].trim();
function feedUrl(f){
  if (f.id === 'local') return `https://news.google.com/rss/search?q=${encodeURIComponent('"' + cityName() + '" when:1d')}&hl=es-419&gl=MX&ceid=MX:es-419`;
  return f.url;
}
function renderNewsTabs(){
  const fs = feedsAll();
  if (!fs.find(f => f.id === S.newsTab)) S.newsTab = 'mundo';
  $('ntabs').innerHTML = fs.map(f => `<button data-tab="${esc(f.id)}" class="${f.id === S.newsTab ? 'on' : ''}" aria-pressed="${f.id === S.newsTab}">${esc(f.id === 'local' ? cityName() : f.name)}${f.custom != null ? `<span class="x" data-fdel="${f.custom}" title="Quitar fuente">×</span>` : ''}</button>`).join('')
    + `<button data-addfeed title="Agregar fuente RSS">+</button><span class="nup" id="nup"></span>`;
}
const rtf = new Intl.RelativeTimeFormat('es', { numeric: 'auto', style: 'short' });
function hace(ts){
  if (!ts) return '';
  const m = Math.round((ts * 1000 - Date.now()) / 6e4);
  if (m > -1) return 'ahora';
  if (m > -60) return rtf.format(m, 'minute');
  const h = Math.round(m / 60); if (h > -24) return rtf.format(h, 'hour');
  return rtf.format(Math.round(h / 24), 'day');
}
function drawNews(items, t){
  if (!items.length){ $('nlist').innerHTML = '<li class="nempty">Sin titulares por ahora.</li>'; return; }
  $('nlist').innerHTML = items.slice(0, 30).map((it, i) => `<li><a href="${esc(it.l)}" rel="noopener"><span class="tx"><span class="nt">${esc(it.t)}</span><span class="nm">${esc([it.s, hace(it.d)].filter(Boolean).join(' · '))}</span></span>${i < 8 && it.img ? `<img src="${esc(it.img)}" alt="" loading="lazy" referrerpolicy="no-referrer" onerror="this.remove()">` : ''}</a></li>`).join('');
  if ($('nup')) $('nup').textContent = 'act. ' + fmtT(new Date(t));
}
// las direcciones viajan en base64url: los filtros de seguridad de muchos hostings bloquean «https://» en la URL
const b64u = t => btoa(String.fromCharCode(...new TextEncoder().encode(t))).replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
async function apiJSON(path){
  const r = await api(path), txt = await r.text();
  try{ return JSON.parse(txt); }
  catch(e){ return { error: `el servidor respondió ${r.status}` + (r.status === 403 || r.status === 406 ? ' (filtro de seguridad del hosting)' : '') + (txt ? ': ' + txt.replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim().slice(0, 90) : '') }; }
}
function newsMsg(t){ $('nlist').innerHTML = `<li class="nempty">${t}</li>`; }
async function loadNews(force){
  const f = feedsAll().find(x => x.id === S.newsTab) || CONFIG.feeds[0], url = feedUrl(f), c = newsCache[url];
  if (c && !force && Date.now() - c.t < 8 * 6e4){ drawNews(c.items, c.t); return; }
  if (!c) newsMsg('cargando titulares…');
  try{
    let j = await apiJSON('rss64=' + b64u(url));
    if (j.error === 'preview'){ newsMsg('En la vista previa no hay noticias: llegan a través de tu servidor y aparecerán cuando subas hq.'); return; }
    if ((!j.items || !j.items.length) && f.alt){ const k = await apiJSON('rss64=' + b64u(f.alt)); if (k.items && k.items.length) j = k; }
    if (!j.items || !j.items.length){
      if (!c) newsMsg(`No pude cargar esta fuente.<br>${esc(j.error || 'sin titulares')}<br><button class="pill" style="margin-top:8px" onclick="runDiag()">diagnóstico</button>`);
      return;
    }
    newsCache[url] = { items: j.items, t: Date.now() };
    if ((feedsAll().find(x => x.id === S.newsTab) || {}).id === f.id) drawNews(newsCache[url].items, newsCache[url].t);
  }catch(e){ if (!c) newsMsg('No pude cargar esta fuente: sin conexión con tu servidor.'); }
}

/* ---------- diagnóstico: qué le falta al servidor para traer noticias ---------- */
async function runDiag(){
  $('mdlg').open && $('mdlg').close();
  $('diagOut').innerHTML = '<p>probando la salida a internet de tu servidor…</p>'; $('ddlg').showModal();
  const j = await apiJSON('diag');
  if (j.error){ $('diagOut').innerHTML = `<p>${esc(j.error)}</p>`; return; }
  const si = v => v ? '<b style="color:var(--ok)">sí</b>' : '<b style="color:var(--bad)">no</b>';
  $('diagOut').innerHTML = `<p style="font-family:var(--mono);font-size:12px;line-height:1.8">hq ${esc(j.hq)} · PHP ${esc(j.php)}<br>cURL: ${esc(j.curl)}<br>allow_url_fopen ${si(j.allow_url_fopen)} · SimpleXML ${si(j.simplexml)} · mbstring ${si(j.mbstring)} · datos/ escribible ${si(j.datos_escribible)}</p>`
    + `<div style="margin-top:12px;display:flex;flex-direction:column;gap:6px">` + j.pruebas.map(p => `<div class="scl"><span>${p.ok ? '✅' : '❌'} ${esc(p.fuente)}</span><b style="min-width:auto">${p.ok ? p.titulares + ' titulares · ' + p.ms + ' ms' : esc(p.detalle) + (p.http ? ' · HTTP ' + p.http : '')}</b></div>`).join('') + '</div>'
    + '<p style="margin-top:12px">Si algo sale en rojo, mándame una captura de esta ventana.</p>';
}
window.runDiag = runDiag;
$('ntabs').addEventListener('click', e => {
  const d = e.target.closest('[data-fdel]');
  if (d){ e.stopPropagation(); S.feeds.splice(+d.dataset.fdel, 1); save(); renderNewsTabs(); loadNews(); return; }
  const b = e.target.closest('[data-tab]'); if (!b) return;
  S.newsTab = b.dataset.tab; save(); renderNewsTabs(); loadNews();
});
function openFeedDlg(){
  $('nfName').value = ''; $('nfUrl').value = '';
  $('nfSugs').innerHTML = CONFIG.feedSugs.map(f => `<button type="button" data-u="${esc(f.url)}" data-n="${esc(f.name)}">${esc(f.name)}</button>`).join('');
  $('fdlg').showModal(); setTimeout(() => $('nfName').focus(), 50);
}
$('nfSugs').addEventListener('click', e => { const b = e.target.closest('[data-u]'); if (!b) return; $('nfName').value = b.dataset.n; $('nfUrl').value = b.dataset.u; });
$('fForm').addEventListener('submit', () => {
  S.feeds.push({ name: $('nfName').value.trim(), url: fixUrl($('nfUrl').value) });
  S.newsTab = 'c' + (S.feeds.length - 1); save(); renderNewsTabs(); loadNews(true);
});

/* ============================================================
   SONIDO: música procedural que toma su mood del cielo de hoy
   Tres modos:
     · Enfoque  — cama suave para concentrarse: bordón, acordes lentos,
                  pulsaciones tipo piano, ruido café; casi sin sorpresas.
     · Cáustika — atmósfera ritual: bordones graves, acordes, campanas FM,
                  percusión de tambor grave.
     · Endeles  — bosque: viento, lluvia, aves de día, oleaje si hay mar,
                  bordón lejano.
   Datos en vivo → música:
     altura del sol → modo (lidio de día, mixolidio al atardecer, eólico de noche)
     nubes y lluvia → dórico, filtro más oscuro, más reverberación
     temperatura   → tónica (más fresco, más grave)
     viento        → tempo y capa de viento
     lluvia/tormenta → capa de lluvia, truenos lejanos
     luna          → campanas nocturnas
     aire          → bruma: ligera desafinación
     marea         → oleaje que respira con la pleamar
   ============================================================ */
const SND = (() => {
  /* Cada modo es una mezcla de instrumentos (0 = no suena). «nivel» empareja el volumen entre modos. */
  const MODOS = {
    enfoque:  { name: 'Enfoque',  desc: 'concentración', nivel: 1.5, chordBeats: 16,
                mix: { drone: .5, pad: .7, air: .6, pluck: 1, harp: 0, bell: .1, chimes: .35, bamboo: .2, metal: .45, drum: 0, rain: .45, wind: .25, brown: .8, birds: 0, waves: .3, thunder: .25 } },
    caustika: { name: 'Cáustika', desc: 'atmósfera ritual', nivel: 1, chordBeats: 12,
                mix: { drone: 1, pad: .8, air: .8, pluck: 0, harp: .5, bell: .55, chimes: .6, bamboo: .45, metal: 1, drum: .9, rain: .7, wind: .45, brown: 0, birds: 0, waves: .8, thunder: 1 } },
    endeles:  { name: 'Endeles',  desc: 'bosque', nivel: 1.55, chordBeats: 20,
                mix: { drone: .45, pad: .45, air: 1, pluck: 0, harp: .3, bell: .15, chimes: 1, bamboo: 1, metal: .5, drum: 0, rain: 1, wind: 1, brown: .15, birds: 1, waves: 1, thunder: 1 } },
    radio:    { name: 'Radio', desc: 'en vivo', nivel: 1, chordBeats: 16, mix: {} }
  };
  const ESCALAS = { lidio: [0,2,4,6,7,9,11], jónico: [0,2,4,5,7,9,11], mixolidio: [0,2,4,5,7,9,10], dórico: [0,2,3,5,7,9,10], eólico: [0,2,3,5,7,8,10] };
  const NOTAS = ['Do','Do♯','Re','Mi♭','Mi','Fa','Fa♯','Sol','La♭','La','Si♭','Si'];
  const mtof = m => 440 * Math.pow(2, (m - 69) / 12);
  const R = Math.random, pick = a => a[Math.floor(R() * a.length)];

  let ctx = null, out, dry, verbIn, dlyIn, analyser, noiseBuf, softWave, cont = {}, playing = false, sched = null, endAt = 0;
  // buses fijos (capas continuas) y «lote» de voces programadas: al cambiar algo, el lote viejo se funde y entra uno nuevo
  let busDry, busVerb, busDly, lote = null, finProgramado = false;
  // en el teléfono el sonido sale por un reproductor de audio normal: así el sistema lo trata como música y sigue con la pantalla bloqueada
  const movil = matchMedia('(pointer: coarse)').matches || /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
  let sink = null, sinkOK = false;
  // segundos de música que se dejan programados cuando la página se oculta. En escritorio el navegador sigue corriendo
  // el código de una pestaña que suena, así que basta un margen corto; en el teléfono el código se congela al bloquear.
  // (v1.6.1 programaba 150 s de golpe: cientos de voces a la vez saturaban el motor y el sonido se rompía.)
  const VENTANA_OCULTA = movil ? 40 : 4;
  const ligero = () => movil && document.hidden;           // con el teléfono bloqueado: voces más simples
  let modo = local.get('hq.sonido.modo', 'enfoque'); if (!MODOS[modo]) modo = 'enfoque';
  let vol = clamp(+local.get('hq.sonido.vol', .6), 0, 1), minutos = +local.get('hq.sonido.min', 0) || 0;
  let espacio = clamp(+local.get('hq.sonido.espacio', .65), 0, 1);
  let M = null, chord = [], nextChord = 0, nextBeat = 0, beat = 0, paso = 0;
  let nextBell = 0, nextBird = 0, nextThunder = 0, nextChime = 0, nextBamboo = 0, nextMetal = 0;
  // dados: variación sobre lo que dicta el cielo (tónica, escala vecina, progresión, tempo). null = el cielo puro
  let dado = local.get('hq.sonido.dado', null);
  const CARAS = ['⚀','⚁','⚂','⚃','⚄','⚅'];
  const VECINAS = { lidio: ['jónico', 'mixolidio'], jónico: ['lidio', 'mixolidio'], mixolidio: ['jónico', 'dórico'], dórico: ['mixolidio', 'eólico'], eólico: ['dórico', 'dórico'] };
  const PROGS = {
    suave:  [[0, 3, 5, 4, 0, 2], [0, 5, 3, 4], [0, 2, 5, 3], [0, 4, 5, 3], [0, 3, 0, 4]],
    ritual: [[0, 5, 3, 6, 0, 4], [0, 3, 4, 0], [0, 6, 5, 6], [0, 2, 3, 5], [0, 5, 0, 3]]
  };
  const canal = 'BroadcastChannel' in window ? new BroadcastChannel('hq-sonido') : null;
  const yo = Math.random().toString(36).slice(2);

  function mood(){
    const alt = amb.alt, noche = alt < -6, crep = alt >= -6 && alt < 8, gris = amb.cloud > .65 || amb.rain > .2;
    let escala = noche ? 'eólico' : gris ? 'dórico' : crep ? 'mixolidio' : amb.cloud > .35 ? 'jónico' : 'lidio';
    const temp = Number.isFinite(amb.temp) ? amb.temp : 18;
    let raiz = 45 + Math.round(clamp((temp - 4) / 28, 0, 1) * 5);         // La1 (frío) … Re2 (calor)
    let tk = 1;
    if (dado){ escala = [escala, ...VECINAS[escala]][dado.e % 3]; raiz = clamp(raiz + dado.shift, 41, 57); tk = dado.tk; }
    return {
      escala, raiz, noche, temp,
      brillo: clamp((alt + 12) / 50, 0, 1) * (1 - amb.cloud * .45) * (1 - amb.fog * .4),
      tempo: Math.round((54 + clamp(amb.wind / 35, 0, 1) * 18) * tk),
      lluvia: clamp(amb.rain, 0, 1), viento: clamp(amb.wind / 45, 0, 1), tormenta: amb.storm > .5,
      luna: amb.moon, bruma: clamp((amb.aqi - 50) / 150, 0, 1), marea: amb.tide
    };
  }
  function moodTxt(){
    if (!M) M = mood();
    const capas = [];
    if (M.tormenta) capas.push('tormenta'); else if (M.lluvia > .1) capas.push('lluvia');
    if (M.viento > .35) capas.push('viento');
    if (M.marea) capas.push('marea ' + (M.marea.rising ? 'subiendo' : 'bajando'));
    if (M.noche) capas.push('noche' + (M.luna > .6 ? ' de luna' : ''));
    if (M.bruma > .2) capas.push('bruma');
    return `${NOTAS[M.raiz % 12]} ${M.escala} · ${M.tempo} bpm` + (capas.length ? ' · ' + capas.join(', ') : '') + (dado ? ' · dado ' + CARAS[dado.cara - 1] : '');
  }

  /* ---------- grafo ---------- */
  // reverberación natural: reflexiones tempranas + cola que se oscurece al apagarse (como una sala o un bosque grande)
  function impulso(seg){
    const sr = ctx.sampleRate, n = Math.floor(sr * seg), b = ctx.createBuffer(2, n, sr);
    for (let c = 0; c < 2; c++){
      const d = b.getChannelData(c); let lp = 0;
      for (let i = 0; i < n; i++){
        const t = i / sr, k = .62 - .5 * Math.min(1, t / seg);
        lp += k * ((R() * 2 - 1) - lp);
        d[i] = lp * Math.pow(10, -3 * t / seg) * Math.min(1, t / .004);
      }
      for (let r = 0; r < 12; r++){ const at = Math.floor(sr * (.007 + R() * .075)); d[at] += (R() * 2 - 1) * .6 * (1 - r / 14); }
    }
    return b;
  }
  function ruido(seg){
    const n = Math.floor(ctx.sampleRate * seg), b = ctx.createBuffer(2, n, ctx.sampleRate);
    for (let c = 0; c < 2; c++){ const d = b.getChannelData(c); let last = 0; for (let i = 0; i < n; i++){ const w = R() * 2 - 1; last = (last + .02 * w) / 1.02; d[i] = w * .35 + last * 3.2; } }
    return b;                                   // mezcla de blanco y café: sirve para lluvia, viento, mar y soplo
  }
  function capa(filtro, f, q, destino){
    const s = ctx.createBufferSource(); s.buffer = noiseBuf; s.loop = true;
    const fl = ctx.createBiquadFilter(); fl.type = filtro; fl.frequency.value = f; fl.Q.value = q || .7;
    const g = ctx.createGain(); g.gain.value = 0;
    s.connect(fl); fl.connect(g); g.connect(destino || busDry); g.connect(busVerb);
    s.start(ctx.currentTime, R() * 5);
    return { s, fl, g };
  }
  function aplicarEspacio(t){
    if (!ctx) return;
    busVerb.gain.setTargetAtTime(.2 + espacio * 1.15, t, .4);
    busDry.gain.setTargetAtTime(.9 - espacio * .4, t, .4);
  }
  function nuevoLote(){
    const t = T();
    if (lote){
      const v = lote;
      [v.d, v.v, v.y].forEach(g => { g.gain.cancelScheduledValues(t); g.gain.setValueAtTime(g.gain.value, t); g.gain.linearRampToValueAtTime(0, t + 1.2); });
      // las voces que ya estaban programadas se detienen de verdad (antes seguían vivas en silencio y ocupaban el motor)
      v.f.forEach(n => { try{ n.stop(t + 1.3); }catch(e){} });
      setTimeout(() => { [v.d, v.v, v.y].forEach(g => { try{ g.disconnect(); }catch(e){} }); v.f.forEach(n => { try{ n.disconnect(); }catch(e){} }); v.f.clear(); }, 1800);
    }
    const d = ctx.createGain(), v = ctx.createGain(), y = ctx.createGain();
    d.connect(busDry); v.connect(busVerb); y.connect(busDly);
    lote = { d, v, y, f: new Set() }; dry = d; verbIn = v; dlyIn = y;
  }
  // tras un cambio (modo, dados, sonoridad) se descarta lo ya programado y se vuelve a empezar desde ahora
  function reprogramar(){
    if (!playing) return;
    nuevoLote(); M = mood();
    // descarta la automatización que quedó programada a futuro en las capas continuas
    const t0 = T(), fijar = p => { p.cancelScheduledValues(t0); p.setValueAtTime(p.value, t0); };
    const d = cont.drone; [d.o1.frequency, d.o2.frequency, d.o3.frequency, d.o3.detune, d.df.frequency, d.g.gain].forEach(fijar);
    ['air1', 'air2', 'rain', 'wind', 'brown', 'waves'].forEach(k => { fijar(cont[k].g.gain); fijar(cont[k].fl.frequency); });
    aplicarCapas(T());
    const t = T(); nextBeat = t + .15; nextChord = nextBeat; paso = 0;
    nextBell = t + 3; nextChime = t + 1.5; nextBamboo = t + 5; nextMetal = t + 8; nextBird = t + 3; nextThunder = t + 6;
    tick();
  }
  function init(){
    // «playback»: búfer de salida más grande; para música ambiental la latencia no importa y evita cortes
    const AC = window.AudioContext || window.webkitAudioContext;
    try{ ctx = new AC({ latencyHint: 'playback' }); }catch(e){ ctx = new AC(); }
    out = ctx.createGain(); out.gain.value = 0;
    const comp = ctx.createDynamicsCompressor(); comp.threshold.value = -20; comp.ratio.value = 3; comp.attack.value = .05; comp.release.value = .4;
    analyser = ctx.createAnalyser(); analyser.fftSize = 1024;
    out.connect(comp); comp.connect(analyser); analyser.connect(ctx.destination);
    if (movil && ctx.createMediaStreamDestination){
      const msd = ctx.createMediaStreamDestination(); analyser.connect(msd);
      sink = document.createElement('audio'); sink.setAttribute('playsinline', ''); sink.srcObject = msd.stream;
    }
    busDry = ctx.createGain(); busDry.connect(out);
    const pre = ctx.createDelay(.2); pre.delayTime.value = .028;
    const verb = ctx.createConvolver(); verb.buffer = impulso(movil ? 5 : 8.5);        // el navegador normaliza la energía del impulso
    busVerb = ctx.createGain(); busVerb.connect(pre); pre.connect(verb); verb.connect(out);
    aplicarEspacio(0);
    const dly = ctx.createDelay(2); dly.delayTime.value = .48;
    const fb = ctx.createGain(); fb.gain.value = .34;
    const dlf = ctx.createBiquadFilter(); dlf.type = 'lowpass'; dlf.frequency.value = 2000;
    busDly = ctx.createGain(); busDly.gain.value = .8; busDly.connect(dly); dly.connect(dlf); dlf.connect(fb); fb.connect(dly); dlf.connect(busVerb); dlf.connect(busDry);
    nuevoLote();
    noiseBuf = ruido(6);
    // forma de onda suave (pocos armónicos, como flauta u órgano de madera) para bordón y pads
    softWave = ctx.createPeriodicWave(new Float32Array(7), new Float32Array([0, 1, .32, .14, .06, .03, .012]));
    // bordón: tónica grave + quinta + tónica suave, con filtro que respira
    const dg = ctx.createGain(); dg.gain.value = 0;
    const df = ctx.createBiquadFilter(); df.type = 'lowpass'; df.frequency.value = 380; df.Q.value = 1.2;
    const o1 = ctx.createOscillator(), o2 = ctx.createOscillator(), o3 = ctx.createOscillator();
    o1.type = 'sine'; o2.setPeriodicWave(softWave); o3.setPeriodicWave(softWave);
    const g2 = ctx.createGain(); g2.gain.value = .55; const g3 = ctx.createGain(); g3.gain.value = .3;
    o1.connect(df); o2.connect(g2); g2.connect(df); o3.connect(g3); g3.connect(df); df.connect(dg); dg.connect(busDry); dg.connect(busVerb);
    const lfo = ctx.createOscillator(), lg = ctx.createGain(); lfo.frequency.value = .045; lg.gain.value = 140; lfo.connect(lg); lg.connect(df.frequency);
    [o1, o2, o3, lfo].forEach(o => o.start());
    cont.drone = { o1, o2, o3, df, g: dg };
    // aire: soplo afinado a la tónica y la quinta, como viento entrando en un tubo
    cont.air1 = capa('bandpass', 220, 28); cont.air2 = capa('bandpass', 330, 32);
    const al = ctx.createOscillator(), alg = ctx.createGain(); al.frequency.value = .07; alg.gain.value = .5;
    const aBus = ctx.createGain(); aBus.gain.value = .5; al.connect(alg); alg.connect(aBus.gain); al.start();
    [cont.air1, cont.air2].forEach(a => { a.g.disconnect(); a.g.connect(aBus); });
    aBus.connect(busDry); aBus.connect(busVerb);
    cont.rain = capa('highpass', 1400, .4);
    cont.wind = capa('bandpass', 500, 1.4);
    cont.brown = capa('lowpass', 320, .5);
    cont.waves = capa('lowpass', 650, .6);
    const wl = ctx.createOscillator(), wlg = ctx.createGain(); wl.frequency.value = .11; wlg.gain.value = 260; wl.connect(wlg); wlg.connect(cont.wind.fl.frequency); wl.start();
  }

  /* ---------- voces ---------- */
  // cada fuente queda registrada en su lote para poder detenerla si cambia la música
  const vivo = n => { const f = lote.f; f.add(n); n.addEventListener('ended', () => f.delete(n)); return n; };
  const T = () => ctx.currentTime;
  const mix = k => MODOS[modo].mix[k] || 0;
  function env(g, t, a, peak, d){ g.gain.setValueAtTime(0, t); g.gain.linearRampToValueAtTime(peak, t + a); g.gain.setTargetAtTime(0, t + a, d / 4); }
  function panner(ancho){ const p = ctx.createStereoPanner ? ctx.createStereoPanner() : ctx.createGain(); if (p.pan) p.pan.value = (R() * 2 - 1) * (ancho || .6); return p; }
  function soplo(f, q, t, dur, amp, dest){         // ruido afinado: el «aire» de una flauta o un tubo
    const s = ctx.createBufferSource(), bp = ctx.createBiquadFilter(), g = ctx.createGain();
    s.buffer = noiseBuf; bp.type = 'bandpass'; bp.frequency.value = f; bp.Q.value = q;
    s.connect(bp); bp.connect(g); g.connect(dest);
    g.gain.setValueAtTime(0, t); g.gain.linearRampToValueAtTime(amp, t + dur * .4); g.gain.setValueAtTime(amp, t + dur * .7); g.gain.linearRampToValueAtTime(0, t + dur + 4);
    vivo(s).start(t, R() * 5); s.stop(t + dur + 5);
  }
  // pad de aire: onda suave con vibrato lento + soplo afinado en la nota y su octava
  function padVoz(notas, t, dur){
    const amp = .05 * mix('pad'); if (!amp) return;
    notas.forEach((n, i) => {
      const f = mtof(n), g = ctx.createGain(), lp = ctx.createBiquadFilter(), p = panner(.7);
      lp.type = 'lowpass'; lp.frequency.value = 500 + M.brillo * 1700; lp.Q.value = .4;
      const vib = ctx.createOscillator(), vg = ctx.createGain(); vib.frequency.value = 4.2 + R() * 1.2; vg.gain.value = f * .0022;
      vib.connect(vg);
      [0, 4 + M.bruma * 10].forEach(c => { const o = ctx.createOscillator(); o.setPeriodicWave(softWave); o.frequency.value = f; o.detune.value = c + (R() - .5) * 3; vg.connect(o.frequency); o.connect(lp); vivo(o).start(t); o.stop(t + dur + 6); });
      vivo(vib).start(t); vib.stop(t + dur + 6);
      lp.connect(g); g.connect(p); p.connect(dry); p.connect(verbIn);
      g.gain.setValueAtTime(0, t); g.gain.linearRampToValueAtTime(amp * (i ? .7 : 1), t + dur * .4); g.gain.setValueAtTime(amp * (i ? .7 : 1), t + dur * .7); g.gain.linearRampToValueAtTime(0, t + dur + 5);
      if (!ligero()){ soplo(f, 38, t, dur, amp * 3.2 * mix('air'), p); soplo(f * 2, 46, t + .3, dur, amp * 1.6 * mix('air'), p); }
    });
  }
  // cuerda pulsada por modelo físico (Karplus-Strong): suena a kalimba o arpa, no a sintetizador
  const ksCache = new Map();
  function ksBuf(n, tipo){
    const key = n + tipo; if (ksCache.has(key)) return ksCache.get(key);
    if (ksCache.size > 60) ksCache.clear();
    const sr = ctx.sampleRate, f = mtof(n), N = Math.max(2, Math.floor(sr / f - .5));
    const arpa = tipo === 'arpa', len = Math.floor(sr * (arpa ? 3.4 : 2.3)), b = ctx.createBuffer(1, len, sr), y = b.getChannelData(0);
    const decae = arpa ? .9982 : .996, suave = arpa ? .45 : .8;
    let s = 0, media = 0;
    for (let i = 0; i < N; i++){ s += suave * ((R() * 2 - 1) - s); y[i] = s; media += s; }
    media /= N; for (let i = 0; i < N; i++) y[i] = (y[i] - media) * 1.6;
    for (let i = N; i < len; i++) y[i] = decae * .5 * (y[i - N] + (i - N - 1 >= 0 ? y[i - N - 1] : 0));
    const r = { buf: b, rate: f / (sr / (N + .5)) };
    ksCache.set(key, r); return r;
  }
  function cuerda(n, t, amp, tipo){
    const { buf, rate } = ksBuf(n, tipo), s = ctx.createBufferSource(), lp = ctx.createBiquadFilter(), g = ctx.createGain(), p = panner();
    s.buffer = buf; s.playbackRate.value = rate;
    lp.type = 'lowpass'; lp.frequency.value = 1400 + M.brillo * 3200;
    s.connect(lp); lp.connect(g); g.connect(p); p.connect(dry); p.connect(verbIn); if (tipo !== 'arpa') p.connect(dlyIn);
    g.gain.value = amp;
    if (tipo !== 'arpa'){                            // cuerpo redondo de la kalimba
      const o = ctx.createOscillator(), og = ctx.createGain(); o.frequency.value = mtof(n); o.connect(og); og.connect(p);
      env(og, t, .004, amp * .35, 1.1); vivo(o).start(t); o.stop(t + 2);
    }
    vivo(s).start(t);
  }
  // campana FM (la de Cáustika), más dulce
  function bell(n, t, amp){
    const c = ctx.createOscillator(), m = ctx.createOscillator(), mg = ctx.createGain(), g = ctx.createGain(), p = panner();
    const f = mtof(n), ratio = R() < .5 ? 3.5 : 2.76;
    c.frequency.value = f; m.frequency.value = f * ratio;
    mg.gain.setValueAtTime(f * 1.4, t); mg.gain.exponentialRampToValueAtTime(f * .03, t + 2.5);
    m.connect(mg); mg.connect(c.frequency); c.connect(g); g.connect(p); p.connect(dry); p.connect(verbIn); p.connect(dlyIn);
    env(g, t, .004, amp, 6); vivo(c).start(t); vivo(m).start(t); c.stop(t + 8); m.stop(t + 8);
  }
  // campanas de viento: tubos metálicos con parciales inarmónicos reales (1 · 2.76 · 5.40 · 8.93)
  const TUBO = [[1, 1, 6], [2.756, .5, 2.8], [5.404, .26, 1.5], [8.933, .12, .8]];
  function tubo(n, t, amp){
    const f = mtof(n), p = panner(.9), bus = ctx.createGain(); bus.gain.value = 1;
    bus.connect(p); p.connect(dry); p.connect(verbIn);
    TUBO.forEach(([r, a, d]) => { if (f * r > 14000) return; const o = ctx.createOscillator(), g = ctx.createGain(); o.frequency.value = f * r * (1 + (R() - .5) * .002); o.connect(g); g.connect(bus); env(g, t, .0015, amp * a, d); vivo(o).start(t); o.stop(t + d * 1.6); });
  }
  function racimoCampanas(t){
    const esc = ESCALAS[M.escala], penta = [0, 1, 2, 4, 5].map(i => esc[i]), n = 2 + Math.floor(R() * (3 + M.viento * 5));
    let tt = t; for (let i = 0; i < n; i++){ tubo(M.raiz + 36 + pick(penta) + (R() < .3 ? 12 : 0), tt, .03 * mix('chimes') * (.5 + R() * .5)); tt += .07 + R() * .4; }
  }
  // bambú: golpe de madera hueca (tono que cae un poco + parcial + chasquido)
  function golpeBambu(f, t, amp){
    const p = panner(.9), bus = ctx.createGain(); bus.connect(p); p.connect(dry); p.connect(verbIn);
    const o = ctx.createOscillator(), g = ctx.createGain(); o.frequency.setValueAtTime(f * 1.03, t); o.frequency.exponentialRampToValueAtTime(f, t + .03);
    o.connect(g); g.connect(bus); env(g, t, .001, amp, .2); vivo(o).start(t); o.stop(t + .5);
    const o2 = ctx.createOscillator(), g2 = ctx.createGain(); o2.frequency.value = f * 2.32; o2.connect(g2); g2.connect(bus); env(g2, t, .001, amp * .35, .07); vivo(o2).start(t); o2.stop(t + .3);
    const s = ctx.createBufferSource(), bp = ctx.createBiquadFilter(), sg = ctx.createGain();
    s.buffer = noiseBuf; bp.type = 'bandpass'; bp.frequency.value = f * 3; bp.Q.value = 5; s.connect(bp); bp.connect(sg); sg.connect(bus);
    env(sg, t, .0008, amp * .5, .02); vivo(s).start(t, R() * 5); s.stop(t + .1);
  }
  function racimoBambu(t){
    const esc = ESCALAS[M.escala], penta = [0, 1, 2, 4, 5].map(i => esc[i]), tonos = [0, 1, 2].map(() => mtof(M.raiz + 31 + pick(penta)));
    const n = 3 + Math.floor(R() * (3 + M.viento * 6));
    let tt = t; for (let i = 0; i < n; i++){ golpeBambu(pick(tonos), tt, .09 * mix('bamboo') * (.4 + R() * .6)); tt += .05 + R() * .2; }
  }
  // metales a lo lejos: cuenco tibetano (parciales con batido) o gong grave que crece despacio
  function metalLejano(t){
    const gong = modo === 'caustika' ? R() < .45 : R() < .15;
    const lp = ctx.createBiquadFilter(); lp.type = 'lowpass'; lp.frequency.value = gong ? 1100 : 2000;
    const bus = ctx.createGain(), p = panner(.8); lp.connect(bus); bus.connect(p);
    const lejos = ctx.createGain(); lejos.gain.value = .22; p.connect(lejos); lejos.connect(dry); p.connect(verbIn);
    const base = gong ? mtof(M.raiz - 12 + pick([0, 7])) : mtof(M.raiz + 12 + pick([0, 7, 12]));
    const parc = gong ? [[1, 1, 12], [1.52, .6, 10], [2.03, .45, 8], [2.74, .3, 6], [3.36, .2, 5], [4.17, .12, 4]]
                      : [[1, 1, 16], [2.71, .5, 9], [5.15, .22, 5], [8.3, .1, 3]];
    const amp = (gong ? .09 : .06) * mix('metal'), ataque = gong ? 1.6 : .12;
    (ligero() ? parc.slice(0, 2) : parc).forEach(([r, a, d]) => {
      const batido = .6 + R() * 1.6;
      (ligero() ? [0] : [0, batido]).forEach(df => { const o = ctx.createOscillator(), g = ctx.createGain(); o.frequency.value = base * r + df; o.connect(g); g.connect(lp);
        g.gain.setValueAtTime(0, t); g.gain.linearRampToValueAtTime(amp * a * .5, t + ataque); g.gain.setTargetAtTime(0, t + ataque, d / 3.5); vivo(o).start(t); o.stop(t + ataque + d * 1.4); });
    });
  }
  function drum(t, amp){
    const o = ctx.createOscillator(), g = ctx.createGain();
    o.frequency.setValueAtTime(88, t); o.frequency.exponentialRampToValueAtTime(40, t + .5);
    o.connect(g); g.connect(dry); g.connect(verbIn); env(g, t, .006, amp, 1); vivo(o).start(t); o.stop(t + 1.8);
    const s = ctx.createBufferSource(), bf = ctx.createBiquadFilter(), sg = ctx.createGain();
    s.buffer = noiseBuf; bf.type = 'bandpass'; bf.frequency.value = 900; bf.Q.value = 1.2;
    s.connect(bf); bf.connect(sg); sg.connect(dry); sg.connect(verbIn); env(sg, t, .002, amp * .22, .12); vivo(s).start(t, R() * 4); s.stop(t + .4);
  }
  function bird(t){
    const o = ctx.createOscillator(), g = ctx.createGain(), p = panner(.9);
    const base = 2400 + R() * 2200, n = 2 + Math.floor(R() * 4);
    o.connect(g); g.connect(p); p.connect(dry); p.connect(verbIn); g.gain.value = 0;
    for (let i = 0; i < n; i++){
      const s = t + i * (.11 + R() * .08);
      o.frequency.setValueAtTime(base * (1 + R() * .15), s); o.frequency.exponentialRampToValueAtTime(base * (1.3 + R() * .5), s + .06);
      g.gain.setValueAtTime(0, s); g.gain.linearRampToValueAtTime(.03 * mix('birds'), s + .015); g.gain.linearRampToValueAtTime(0, s + .09);
    }
    vivo(o).start(t); o.stop(t + n * .2 + .2);
  }
  function thunder(t){
    const s = ctx.createBufferSource(), f = ctx.createBiquadFilter(), g = ctx.createGain();
    s.buffer = noiseBuf; f.type = 'lowpass'; f.frequency.value = 110;
    s.connect(f); f.connect(g); g.connect(dry); g.connect(verbIn);
    g.gain.setValueAtTime(0, t); g.gain.linearRampToValueAtTime(.7 * mix('thunder'), t + .25 + R() * .4); g.gain.setTargetAtTime(0, t + .8, 1.4);
    vivo(s).start(t, R() * 3); s.stop(t + 7);
  }

  /* ---------- armonía ---------- */
  function nuevoAcorde(){
    M = mood();
    const esc = ESCALAS[M.escala], lista = PROGS[modo === 'enfoque' ? 'suave' : 'ritual'];
    // con el cielo puro el acorde se elige al azar dentro de la progresión base; con dados, la progresión elegida se recorre en orden
    const g = dado ? lista[dado.prog % lista.length][paso++ % lista[dado.prog % lista.length].length] : lista[0][Math.floor(R() * lista[0].length)];
    chord = [0, 2, 4, 6].map(k => { const i = g + k; return M.raiz + 12 + esc[i % 7] + 12 * Math.floor(i / 7); });
  }
  function aplicarCapas(t){
    const d = cont.drone; if (!d) return;
    d.o1.frequency.setTargetAtTime(mtof(M.raiz - 12), t, 2.7);
    d.o2.frequency.setTargetAtTime(mtof(M.raiz - 5), t, 2.7);
    d.o3.frequency.setTargetAtTime(mtof(M.raiz), t, 2.7);
    d.o3.detune.setTargetAtTime(M.bruma * 14, t, 3);
    d.df.frequency.setTargetAtTime(240 + M.brillo * 520, t, 4);
    d.g.gain.setTargetAtTime(.22 * mix('drone'), t, 3);
    cont.air1.fl.frequency.setTargetAtTime(mtof(M.raiz + 12), t, 3);
    cont.air2.fl.frequency.setTargetAtTime(mtof(M.raiz + 19), t, 3);
    cont.air1.g.gain.setTargetAtTime((.16 + M.viento * .18) * mix('air'), t, 3);
    cont.air2.g.gain.setTargetAtTime((.1 + M.viento * .14) * mix('air'), t, 3);
    cont.rain.g.gain.setTargetAtTime((M.lluvia * .32 + (M.tormenta ? .08 : 0)) * mix('rain'), t, 3);
    cont.wind.g.gain.setTargetAtTime((M.viento * .22 + (modo === 'endeles' ? .05 : 0)) * mix('wind'), t, 3);
    cont.wind.fl.frequency.setTargetAtTime(380 + M.viento * 700, t, 4);
    cont.brown.g.gain.setTargetAtTime(.11 * mix('brown'), t, 3);
    cont.waves.g.gain.setTargetAtTime((M.marea ? .06 + M.marea.level * .1 : 0) * mix('waves'), t, 3);
    cont.dia = !M.noche && amb.alt > -2;
  }
  // los racimos de campanas y bambú se mueven con el viento real
  const cada = (lo, hi) => (lo + R() * (hi - lo)) / (.45 + M.viento * 1.1);

  /* ---------- planificador ----------
     Con la página a la vista mira 0.6 s adelante. Oculta (otra pestaña, teléfono bloqueado) deja programados
     VENTANA_OCULTA segundos en el reloj de audio, que sigue corriendo aunque el sistema pause el código. */
  function tick(){
    if (!playing) return;
    const now = T(), ahead = now + (document.hidden ? VENTANA_OCULTA : .6), spb = 60 / M.tempo;
    if (M.marea && cont.waves){ const w = Math.sin(now / 9 * 2 * Math.PI) * .5 + .5; cont.waves.fl.frequency.setTargetAtTime(380 + w * 700, now, .8); }
    let cambio = false;
    while (nextBeat < ahead){
      if (nextBeat >= nextChord){
        nuevoAcorde(); aplicarCapas(nextBeat);
        const dur = MODOS[modo].chordBeats * (60 / M.tempo);
        padVoz(chord, nextBeat, dur); nextChord = nextBeat + dur; cambio = true;
      }
      if (mix('pluck') && R() < .58){
        const pat = [0, 2, 1, 3, 2, 1], n = chord[pat[beat % pat.length]] + (R() < .3 ? 12 : 0);
        cuerda(n, nextBeat + (R() - .5) * .025, .2 * mix('pluck') * (.7 + R() * .3), 'kalimba');
      }
      if (mix('harp') && !ligero() && beat % 2 === 0 && R() < .22) cuerda(pick(chord) + pick([0, 12]), nextBeat, .22 * mix('harp'), 'arpa');
      if (mix('drum') && (beat % 8 === 0 || (beat % 8 === 5 && R() < .45) || R() < .05)) drum(nextBeat, .45 * mix('drum') * (beat % 8 === 0 ? 1 : .55));
      beat++; nextBeat += 60 / M.tempo;
    }
    const alDia = x => x < now ? now + .1 : x;            // nunca programar en el pasado
    nextBell = alDia(nextBell); nextChime = alDia(nextChime); nextBamboo = alDia(nextBamboo); nextMetal = alDia(nextMetal); nextBird = alDia(nextBird); nextThunder = alDia(nextThunder);
    while (mix('bell') && nextBell < ahead){
      const esc = ESCALAS[M.escala], penta = [0, 1, 2, 4, 5].map(i => esc[i]);
      bell(M.raiz + 24 + pick(penta) + (R() < .35 ? 12 : 0), nextBell, .07 * mix('bell') * (M.noche ? .8 + M.luna * .6 : 1));
      nextBell += modo === 'enfoque' ? 30 + R() * 30 : (M.noche ? 3 : 5) + R() * 7;
    }
    while (mix('chimes') && nextChime < ahead){ racimoCampanas(nextChime); nextChime += modo === 'endeles' ? cada(5, 13) : modo === 'caustika' ? cada(9, 20) : cada(20, 42); }
    while (mix('bamboo') && nextBamboo < ahead){ racimoBambu(nextBamboo); nextBamboo += modo === 'endeles' ? cada(7, 18) : modo === 'caustika' ? cada(14, 30) : cada(30, 60); }
    while (mix('metal') && nextMetal < ahead){ metalLejano(nextMetal); nextMetal += modo === 'caustika' ? 14 + R() * 20 : 30 + R() * 35; }
    while (mix('birds') && cont.dia && M.lluvia < .3 && nextBird < ahead){ bird(nextBird); nextBird += 3 + R() * 10; }
    while (M.tormenta && nextThunder < ahead){ thunder(nextThunder); nextThunder += 14 + R() * 30; }
    // fin de sesión con la pantalla bloqueada: se deja programado en el reloj de audio
    if (endAt && !finProgramado){
      const tEnd = now + (endAt - Date.now()) / 1000;
      if (tEnd < ahead && document.hidden){
        const esc = ESCALAS[M.escala];
        [0, 2, 4].forEach((k, i) => tubo(M.raiz + 36 + esc[k], tEnd + i * .6, .07));
        out.gain.setValueAtTime(nivel(), tEnd + 1.5); out.gain.linearRampToValueAtTime(0, tEnd + 5);
        finProgramado = true;
      }
    }
    if (endAt && Date.now() >= endAt) terminarSesion();
    if (cambio && !document.hidden) pintar(); else pintarTiempo();
  }
  document.addEventListener('visibilitychange', () => { if (playing) tick(); });

  /* ---------- transporte ---------- */
  async function play(){
    if (modo === 'radio') return radioPlay();
    if (!ctx) init();
    // el reproductor del teléfono se arranca dentro del mismo toque (iOS lo exige); cuando suena, se apaga la salida directa
    if (sink) sink.play().then(() => { if (!sinkOK){ try{ analyser.disconnect(ctx.destination); }catch(e){} sinkOK = true; } }).catch(() => {});
    if (ctx.state !== 'running') await ctx.resume();
    if (playing) return;
    playing = true; M = mood(); finProgramado = false;
    const t = T();
    nextBeat = t + .15; nextChord = nextBeat; beat = 0;
    nextBell = t + 4; nextBird = t + 4; nextThunder = t + 6; nextChime = t + 2.5; nextBamboo = t + 6; nextMetal = t + 9;
    out.gain.cancelScheduledValues(t); out.gain.setValueAtTime(out.gain.value, t); out.gain.linearRampToValueAtTime(nivel(), t + 3.5);
    clearInterval(sched); sched = setInterval(tick, 200); tick();
    endAt = minutos ? Date.now() + minutos * 6e4 : 0;
    canal?.postMessage({ t: 'play', de: yo });
    if ('mediaSession' in navigator){
      try{
        navigator.mediaSession.metadata = new MediaMetadata({ title: 'hq · ' + MODOS[modo].name, artist: 'Alchemy of Meaning (Alquimia del Significado)', album: moodTxt() });
        navigator.mediaSession.setActionHandler('play', play); navigator.mediaSession.setActionHandler('pause', () => stop());
        navigator.mediaSession.playbackState = 'playing';
      }catch(e){}
    }
    pintar(); dibujar();
  }
  function stop(){
    if (radioOn) radioStop();
    if (!playing){ endAt = 0; pintar(); return; }
    playing = false; clearInterval(sched); endAt = 0; finProgramado = false;
    const t = T(); out.gain.cancelScheduledValues(t); out.gain.setValueAtTime(out.gain.value, t); out.gain.linearRampToValueAtTime(0, t + 2.2);
    nuevoLote();                                            // descarta las notas que quedaban programadas
    try{ navigator.mediaSession.playbackState = 'paused'; }catch(e){}
    setTimeout(() => { if (!playing){ ctx.suspend(); sink?.pause(); } }, 2600);
    pintar();
  }
  function terminarSesion(){
    endAt = 0;
    if (radioOn){ toast(`Sesión de ${minutos} min terminada`); stop(); return; }
    if (finProgramado){ toast(`Sesión de ${minutos} min terminada`); stop(); return; }   // ya sonó con la pantalla bloqueada
    const t = T() + .1, esc = ESCALAS[M.escala];
    [0, 2, 4].forEach((k, i) => tubo(M.raiz + 36 + esc[k], t + i * .6, .07));
    toast(`Sesión de ${minutos} min terminada`);
    setTimeout(() => stop(), 4000);
  }
  function setModo(m){
    if (!MODOS[m]) return;
    const sonaba = playing || radioOn;
    if (m === 'radio' && playing) stop();                  // cambiar entre radio y música procedural: una se apaga, la otra entra
    if (m !== 'radio' && radioOn) radioStop();
    modo = m; local.set('hq.sonido.modo', m);
    if (sonaba && !playing && !radioOn){ pintar(); play(); return; }
    if (playing){ reprogramar(); out.gain.setTargetAtTime(nivel(), T(), 1.2);
      if ('mediaSession' in navigator && navigator.mediaSession.metadata) navigator.mediaSession.metadata.title = 'hq · ' + MODOS[m].name; }
    pintar();
  }
  function setVol(v){ vol = clamp(v, 0, 1); local.set('hq.sonido.vol', vol); if (playing) out.gain.setTargetAtTime(nivel(), T(), .3); au.volume = vol; }
  function setEspacio(v){ espacio = clamp(v, 0, 1); local.set('hq.sonido.espacio', espacio); if (ctx) aplicarEspacio(T()); }
  function nivel(){ return vol * .9 * MODOS[modo].nivel; }
  function setMin(m){ minutos = m; local.set('hq.sonido.min', m); endAt = playing && m ? Date.now() + m * 6e4 : 0; pintar(); }
  canal && (canal.onmessage = e => { if (e.data?.t === 'play' && e.data.de !== yo && (playing || radioOn)){ stop(); toast('El sonido sigue en otra pestaña'); } });

  /* ---------- radio en internet (directorio abierto Radio Browser) ---------- */
  const au = new Audio(); au.preload = 'none';
  let radioOn = false, radioEstado = '', radioTimer = null, buscarT = null, resultados = [];
  const RB = ['https://de1.api.radio-browser.info', 'https://de2.api.radio-browser.info', 'https://fi1.api.radio-browser.info'];
  async function rb(path, params){
    for (const h of [...RB].sort(() => R() - .5)){
      try{
        const r = await fetch(h + path + (params ? '?' + new URLSearchParams(params) : ''));
        if (r.ok) return { h, j: await r.json() };
      }catch(e){}
    }
    return null;
  }
  const sirve = st => /^https:/i.test(st.url_resolved || '') && !st.hls;     // la página es https: solo streams https y sin HLS
  async function buscar(q){
    const r = await rb('/json/stations/search', { name: q, hidebroken: 'true', order: 'votes', reverse: 'true', limit: '40' });
    return r ? { h: r.h, lista: r.j.filter(sirve) } : null;
  }
  function estaciones(){ return (S && S.radios) || CONFIG.radios; }
  function actual(){ const sel = local.get('hq.radio.sel', null), l = estaciones(); return l.find(x => x.name === sel) || l[0]; }
  async function resolver(st){
    if (st.url) return st.url;
    radioEstado = 'buscando la estación…'; pintar();
    const r = await buscar(st.q || st.name);
    const e = r && r.lista[0];
    if (!e) return null;
    st.url = e.url_resolved; save();                      // se guarda: la próxima vez entra directo
    return st.url;
  }
  async function radioPlay(st){
    st = st || actual(); if (!st){ toast('Agrega una estación'); return; }
    local.set('hq.radio.sel', st.name);
    if (playing) stop();
    radioOn = true; radioEstado = 'conectando…'; pintar();
    const url = await resolver(st);
    if (!url){ radioOn = false; radioEstado = 'no encontré esa estación'; pintar(); toast('No encontré «' + st.name + '» en el directorio'); return; }
    if (!radioOn) return;
    au.src = url; au.volume = vol;
    try{ await au.play(); }catch(e){ return falloRadio(st); }
    canal?.postMessage({ t: 'play', de: yo });
    endAt = minutos ? Date.now() + minutos * 6e4 : 0;
    clearInterval(radioTimer); radioTimer = setInterval(() => { if (endAt && Date.now() >= endAt) terminarSesion(); pintarTiempo(); }, 1000);
    if ('mediaSession' in navigator){
      try{ navigator.mediaSession.metadata = new MediaMetadata({ title: st.name, artist: 'hq · radio', album: 'en vivo' });
           navigator.mediaSession.setActionHandler('play', () => radioPlay()); navigator.mediaSession.setActionHandler('pause', () => stop()); }catch(e){}
    }
    radioViz();
  }
  function falloRadio(st){
    radioOn = false; radioEstado = 'la estación no responde';
    if (st && st.q && st.url){ delete st.url; save(); }     // si venía del directorio, se vuelve a buscar la próxima vez
    au.removeAttribute('src'); au.load(); pintar();
    toast('«' + (st ? st.name : 'La estación') + '» no responde; prueba otra');
  }
  function radioStop(){
    radioOn = false; radioEstado = ''; clearInterval(radioTimer);
    const v0 = au.volume, t0 = performance.now();
    (function bajar(){ const k = Math.min(1, (performance.now() - t0) / 600); au.volume = v0 * (1 - k); if (k < 1 && !radioOn) requestAnimationFrame(bajar); else if (!radioOn){ au.pause(); au.removeAttribute('src'); au.load(); au.volume = vol; } })();
    pintar();
  }
  au.addEventListener('playing', () => { if (radioOn){ radioEstado = 'en vivo'; pintar(); } });
  au.addEventListener('waiting', () => { if (radioOn){ radioEstado = 'cargando…'; pintar(); } });
  au.addEventListener('error', () => { if (radioOn) falloRadio(actual()); });
  function radioViz(){
    const cv = $('sViz'), g = cv.getContext('2d');
    const w = cv.width = cv.clientWidth * (devicePixelRatio || 1), h = cv.height = cv.clientHeight * (devicePixelRatio || 1);
    const n = 48, fase = [...Array(n)].map(() => R() * 6.28);
    (function frame(t){
      if (!radioOn){ g.clearRect(0, 0, w, h); return; }
      requestAnimationFrame(frame);
      if (document.hidden) return;
      g.clearRect(0, 0, w, h); g.fillStyle = 'rgba(118,196,17,.8)';      // verde: señal viva
      const vivo = radioEstado === 'en vivo' && !au.paused ? 1 : .15;
      for (let i = 0; i < n; i++){
        const a = (Math.sin(t / 380 + fase[i]) * .5 + .5) * (Math.sin(t / 900 + i * .3) * .3 + .7) * vivo;
        const bh = Math.max(1, a * h * .9), bw = w / n * .55;
        g.fillRect(i * w / n, (h - bh) / 2, bw, bh);
      }
    })(performance.now());
  }
  function pintarRadio(){
    const l = estaciones(), sel = actual();
    $('rList').innerHTML = l.map((st, i) => `<button data-st="${i}" class="${sel && st.name === sel.name ? 'on' : ''}">${esc(st.name)}<span class="x" data-stdel="${i}" title="Quitar">×</span></button>`).join('');
  }
  function pintarResultados(msg){
    if (msg){ $('rRes').innerHTML = `<div class="rmsg">${msg}</div>`; return; }
    $('rRes').innerHTML = resultados.slice(0, 14).map((e, i) => `<div class="rrow"><button data-rplay="${i}" title="Escuchar">▶</button><span class="rn" data-rplay="${i}" title="${esc(e.name)}">${esc(e.name.trim())}</span><em>${esc([e.countrycode, (e.codec || '').toLowerCase(), e.bitrate ? e.bitrate + 'k' : ''].filter(Boolean).join(' · '))}</em><button data-radd="${i}" title="Agregar a tus estaciones">+</button></div>`).join('')
      || '<div class="rmsg">Sin resultados con transmisión segura (https). Prueba otro nombre.</div>';
  }
  $('rQ').addEventListener('input', () => {
    clearTimeout(buscarT);
    const q = $('rQ').value.trim();
    if (q.length < 2){ resultados = []; $('rRes').innerHTML = ''; return; }
    buscarT = setTimeout(async () => {
      pintarResultados('buscando…');
      const r = await buscar(q);
      if (!r){ pintarResultados('No pude consultar el directorio de radios.'); return; }
      resultados = r.lista.map(e => ({ ...e, _h: r.h })); pintarResultados();
    }, 450);
  });
  $('rRes').addEventListener('click', e => {
    const a = e.target.closest('[data-radd]'), p = e.target.closest('[data-rplay]');
    const it = resultados[+(a || p)?.dataset[a ? 'radd' : 'rplay']]; if (!it) return;
    const st = { name: it.name.trim().slice(0, 48), url: it.url_resolved };
    fetch(it._h + '/json/url/' + it.stationuuid).catch(() => {});   // cuenta el clic en el directorio, como pide Radio Browser
    if (a){ if (!S.radios.some(x => x.url === st.url)){ S.radios.push(st); save(); } toast('«' + st.name + '» agregada'); pintarRadio(); return; }
    radioPlay(st);
  });
  $('rList').addEventListener('click', e => {
    const d = e.target.closest('[data-stdel]');
    if (d){ e.stopPropagation(); const st = S.radios[+d.dataset.stdel]; if (!confirm(`¿Quitar «${st.name}» de tus estaciones?`)) return; S.radios.splice(+d.dataset.stdel, 1); save(); pintarRadio(); return; }
    const b = e.target.closest('[data-st]'); if (!b) return;
    radioPlay(estaciones()[+b.dataset.st]);
  });

  /* ---------- sonoridades guardadas (se sincronizan con tus otros dispositivos) ---------- */
  const mismo = (a, b) => JSON.stringify(a || null) === JSON.stringify(b || null);
  function presetActivo(){
    if (modo === 'radio'){ const st = actual(); return (S?.sonidos || []).findIndex(p => p.modo === 'radio' && st && p.radio?.name === st.name); }
    return (S?.sonidos || []).findIndex(p => p.modo === modo && mismo(p.dado, dado) && Math.abs((p.espacio ?? .65) - espacio) < .02);
  }
  function pintarPresets(){
    if (!S) return;
    const act = presetActivo();
    $('sPres').innerHTML = (S.sonidos || []).map((p, i) => `<button data-p="${i}" class="${i === act ? 'on' : ''}" title="${esc(MODOS[p.modo]?.name || '')}${p.modo === 'radio' ? ' · ' + esc(p.radio?.name || '') : p.dado ? ' · dado ' + CARAS[p.dado.cara - 1] : ' · cielo'}">${esc(p.name)}<span class="x" data-pdel="${i}" title="Borrar">×</span></button>`).join('')
      + `<button data-psave title="Guardar el modo, los dados y el espacio actuales">+ guardar</button>`;
  }
  $('sPres').onclick = e => {
    const d = e.target.closest('[data-pdel]');
    if (d){ e.stopPropagation(); const p = S.sonidos[+d.dataset.pdel]; if (!confirm(`¿Borrar la sonoridad «${p.name}»?`)) return; S.sonidos.splice(+d.dataset.pdel, 1); save(); pintarPresets(); return; }
    if (e.target.closest('[data-psave]')){
      if (!M) M = mood();
      const st = actual();
      const sug = modo === 'radio' ? (st ? st.name : 'Radio') : MODOS[modo].name + ' · ' + NOTAS[M.raiz % 12] + ' ' + M.escala;
      const name = (prompt('Nombre de esta sonoridad (p. ej. «Escritura», «Ensayo», «Noche larga»)', sug) || '').trim(); if (!name) return;
      S.sonidos = S.sonidos || [];
      S.sonidos.push(modo === 'radio' ? { name: name.slice(0, 40), modo, radio: st ? { name: st.name, url: st.url, q: st.q } : null }
                                      : { name: name.slice(0, 40), modo, dado: dado ? { ...dado } : null, espacio: +espacio.toFixed(2) });
      save(); pintarPresets(); toast('Sonoridad «' + name + '» guardada'); return;
    }
    const b = e.target.closest('[data-p]'); if (!b) return;
    const p = S.sonidos[+b.dataset.p];
    if (p.modo === 'radio'){
      const st = p.radio || actual(); if (!st) return;
      if (!estaciones().some(x => x.name === st.name)){ S.radios.push({ ...st }); save(); }
      const real = estaciones().find(x => x.name === st.name);
      if (modo !== 'radio'){ if (playing) stop(); modo = 'radio'; local.set('hq.sonido.modo', 'radio'); }
      toast('Sonoridad «' + p.name + '»'); radioPlay(real); return;
    }
    dado = p.dado ? { ...p.dado } : null; setEspacio(p.espacio ?? .65); $('sEsp').value = espacio;
    setModo(p.modo); aplicarDado(true); toast('Sonoridad «' + p.name + '»');
    if (!playing) play();
  };

  /* ---------- interfaz ---------- */
  function pintarTiempo(){
    if (!endAt){ $('sLeft').textContent = ''; return; }
    const s = Math.max(0, Math.round((endAt - Date.now()) / 1000));
    $('sLeft').textContent = Math.floor(s / 60) + ':' + pad(s % 60);
  }
  function pintar(){
    const on = playing || radioOn, radio = modo === 'radio', st = radio ? actual() : null;
    $('sPlay').textContent = on ? '❚❚' : '▶'; $('sPlay').classList.toggle('on', on);
    $('sPlay').title = on ? 'Pausar (M)' : 'Tocar (M)';
    $('sndChip').classList.toggle('on', on); $('sndChip').textContent = on ? '♪ ' + MODOS[modo].name : '♪ sonido';
    $('sName').textContent = radio ? 'Radio · ' + (st ? st.name : 'elige una estación') : MODOS[modo].name + ' · ' + MODOS[modo].desc;
    $('sMood').textContent = radio ? (radioOn ? radioEstado : radioEstado || 'estaciones en vivo · busca cualquier radio del mundo') : on || M ? moodTxt() : 'el mood se toma del cielo, el clima y la luna de hoy';
    $('sRadio').hidden = !radio; $('sEspL').hidden = radio; $('sDice').hidden = radio;
    if (radio) pintarRadio();
    document.querySelectorAll('#sModes [data-m]').forEach(b => { const o = b.dataset.m === modo; b.classList.toggle('on', o); b.setAttribute('aria-pressed', o); });
    document.querySelectorAll('#sTimer [data-t]').forEach(b => { const o = +b.dataset.t === minutos; b.classList.toggle('on', o); b.setAttribute('aria-pressed', o); });
    $('sPlay').setAttribute('aria-pressed', on); $('sndChip').setAttribute('aria-pressed', on);
    $('sVol').value = vol; $('sEsp').value = espacio; fillR($('sVol')); fillR($('sEsp')); pintarTiempo();
    $('sDice').textContent = dado ? CARAS[dado.cara - 1] : '⚄'; $('sDice').classList.toggle('on', !!dado);
    $('sCielo').hidden = !dado || radio;
    pintarPresets(); setTimeout(() => { try{ if (dash) refit(); }catch(e){} }, 0);
  }
  function dibujar(){
    const cv = $('sViz'), g = cv.getContext('2d');
    const w = cv.width = cv.clientWidth * (devicePixelRatio || 1), h = cv.height = cv.clientHeight * (devicePixelRatio || 1);
    const data = new Uint8Array(analyser.fftSize);
    (function frame(){
      if (!playing && out.gain.value < .001){ g.clearRect(0, 0, w, h); return; }
      requestAnimationFrame(frame);
      if (document.hidden) return;
      analyser.getByteTimeDomainData(data);
      g.clearRect(0, 0, w, h); g.beginPath();
      for (let i = 0; i < data.length; i += 4){ const x = i / data.length * w, y = h / 2 + (data[i] - 128) / 128 * h * 1.6; i ? g.lineTo(x, y) : g.moveTo(x, y); }
      g.strokeStyle = 'rgba(118,196,17,.9)'; g.lineWidth = 1.2 * (devicePixelRatio || 1); g.stroke();
    })();
  }
  function aplicarDado(silencioso){
    local.set('hq.sonido.dado', dado); paso = 0;
    if (playing) reprogramar();                             // el nuevo acorde entra de inmediato; lo ya programado se funde
    else M = mood();
    if (!silencioso){ const b = $('sDice'); b.classList.remove('roll'); void b.offsetWidth; b.classList.add('roll'); }
    pintar();
  }
  function tirar(){
    const prev = dado ? dado.cara : 0;
    let cara; do { cara = 1 + Math.floor(R() * 6); } while (cara === prev);
    dado = { cara, shift: [0, 2, -2, 3, -3, 5, -5, 7, -7][Math.floor(R() * 9)], e: Math.floor(R() * 3), prog: 1 + Math.floor(R() * 4), tk: +(0.88 + R() * .24).toFixed(2) };
    aplicarDado(); toast('Nueva armonía · ' + moodTxt().split(' · ').slice(0, 2).join(' · '));
  }
  function cielo(){ dado = null; aplicarDado(); toast('De vuelta a la armonía del cielo'); }
  const toggle = () => (playing || radioOn) ? stop() : play();
  $('sDice').onclick = tirar;
  $('sCielo').onclick = cielo;
  $('sPlay').onclick = toggle;
  $('sndChip').onclick = toggle;
  $('sModes').onclick = e => { const b = e.target.closest('[data-m]'); if (b) setModo(b.dataset.m); };
  $('sTimer').onclick = e => { const b = e.target.closest('[data-t]'); if (b) setMin(+b.dataset.t); };
  $('sVol').oninput = e => setVol(+e.target.value);
  $('sEsp').oninput = e => { setEspacio(+e.target.value); pintarPresets(); };
  pintar();
  return { toggle, play, stop, setModo, tirar, cielo, presets: pintarPresets, refresh(){ if (playing){ M = mood(); aplicarCapas(T()); } pintar(); }, get playing(){ return playing || radioOn; } };
})();

/* ---------- tráfico en vivo (mapa de Waze, se carga solo cuando lo pides) ---------- */
function trafficLabel(){ $('tPlace').textContent = 'Waze · ' + cityName(); }
function loadTraffic(){
  const sec = document.querySelector('.sec[data-id=trafico]'); if (!sec) return;
  const src = `https://embed.waze.com/es/iframe?zoom=12&lat=${place.lat}&lon=${place.lon}`;
  let f = $('traffic').querySelector('iframe');
  if (!f){ f = document.createElement('iframe'); f.title = 'Tráfico en vivo · Waze'; f.referrerPolicy = 'no-referrer'; $('traffic').appendChild(f); }
  if (f.getAttribute('src') !== src) f.src = src;
  sec.classList.add("tlive"); fit();
}
function closeTraffic(){
  $('traffic').querySelector('iframe')?.remove();
  document.querySelector('.sec[data-id=trafico]')?.classList.remove("tlive"); fit();
}
$('tLoad').onclick = loadTraffic;
$('tClose').onclick = e => { e.stopPropagation(); closeTraffic(); };
$('tAuto').checked = !!local.get('hq.trafico.auto', false);
$('tAuto').onchange = () => { local.set('hq.trafico.auto', $('tAuto').checked); if ($('tAuto').checked) loadTraffic(); };

/* ---------- búsqueda ---------- */
$('search').addEventListener('submit', e => {
  const v = $('q').value.trim();
  if (/^(https?:\/\/)?[\w-]+(\.[\w-]+)+(:\d+)?(\/\S*)?$/i.test(v) && !/\s/.test(v)){
    e.preventDefault(); location.href = /^https?:\/\//.test(v) ? v : 'https://' + v;
  }
});

/* ============================================================
   SECCIONES, MODOS Y ACCESOS
   ============================================================ */
let editing = false, editIdx = -1;
const mode = () => S.modes.find(m => m.id === S.active) || S.modes[0];
const groupsOf = () => [...new Set([...(S.groups || []), ...S.links.map(l => l.group || 'Otros')])];
const cleanGroup = g => String(g || '').replace(/\|/g, '/').trim();
let dash = false;
const isDash = () => innerWidth >= 1000 && innerWidth / innerHeight >= 1.2;
const host = u => { try{ return new URL(u, location.href).hostname.replace(/^www\./, ''); }catch(e){ return u; } };
const hue = s => { let h = 0; for (const c of String(s)) h = (h * 31 + c.charCodeAt(0)) % 360; return h; };
const absUrl = u => { try{ return new URL(u, location.href).href; }catch(e){ return u; } };

function iconHTML(l, big){
  const ic0 = (l.icon || '').trim(), cls = 'ico' + (big ? ' lg' : '');
  if (ic0 === 'letra'){ const n = l.name || host(l.url || '') || '?'; return `<span class="${cls} mono" style="background:hsl(${hue(n)} 38% 68%)"><b class="lt">${esc(n.trim().charAt(0).toUpperCase())}</b></span>`; }
  const ic = ic0;
  if (ic && /^(https?:|\/|\.{1,2}\/|data:image)/.test(ic)) return `<span class="${cls}"><img src="${esc(ic)}" alt="" style="width:100%;height:100%;object-fit:cover"></span>`;
  if (ic) return `<span class="${cls} emo">${esc(ic)}</span>`;
  const h = host(l.url || ''), letter = esc((l.name || h || '?').trim().charAt(0).toUpperCase());
  const mono = `background:hsl(${hue(l.name || h)} 38% 68%)`;
  if (!h) return `<span class="${cls} mono" style="${mono}">${letter}</span>`;
  // mismo servidor: intenta el favicon de la carpeta del artefacto; fuera, el servicio de favicons
  const sameHost = h === location.hostname.replace(/^www\./, '');
  const first = sameHost ? new URL('favicon.ico', absUrl(l.url)).href : `https://www.google.com/s2/favicons?domain=${encodeURIComponent(h)}&sz=64`;
  const second = `https://icons.duckduckgo.com/ip3/${encodeURIComponent(h)}.ico`;
  // empieza con la letra de color; si el favicon carga, lo sustituye
  return `<span class="${cls} mono" style="${mono}"><b class="lt">${letter}</b><img src="${esc(first)}" data-alt="${esc(second)}" alt="" hidden onload="icoOk(this)" onerror="icoFail(this)"></span>`;
}
window.icoOk = img => {
  if (img.naturalWidth < 8) return icoFail(img);       // algunos servicios devuelven un pixel vacío
  const s = img.parentNode; s.classList.remove('mono'); s.removeAttribute('style'); img.hidden = false; s.querySelector('.lt')?.remove();
};
window.icoFail = img => {
  if (img.dataset.alt){ const a = img.dataset.alt; img.dataset.alt = ''; img.src = a; return; }
  img.remove();
};

function secHead(id, name){
  const off = mode().hidden.includes(id);
  return `<div class="sh"><span class="grip" title="Arrastra para mover">⠿</span><span class="ttl">${esc(name)}</span>
    <span class="act">${id.startsWith('g:') ? `<button class="sbtn" data-addg="${esc(id.slice(2))}" title="Agregar sitio">+</button><button class="sbtn" data-ren="${esc(id.slice(2))}" title="Renombrar módulo">✎</button><button class="sbtn" data-delg="${esc(id.slice(2))}" title="Quitar módulo">×</button>` : ''}
    ${id === 'noticias' ? '<button class="sbtn" data-addfeed title="Agregar fuente de noticias">+</button>' : ''}${id === 'relojes' ? '<button class="sbtn" data-addzone title="Agregar reloj">+</button>' : ''}
    <button class="sbtn" data-hide="${esc(id)}" title="${off ? 'Mostrar en este modo' : 'Ocultar en este modo'}">${off ? 'ver' : 'ocultar'}</button></span></div>`;
}
function renderGroups(){
  const cont = $('secs');
  cont.querySelectorAll('.sec.grp').forEach(n => n.remove());
  S.groups = groupsOf();
  groupsOf().forEach(g => {
    const sec = document.createElement('section');
    sec.className = 'sec grp'; sec.dataset.id = 'g:' + g; sec.dataset.name = g;
    const tiles = S.links.map((l, i) => ({ ...l, i })).filter(l => (l.group || 'Otros') === g).map(l => `
      <a class="tile" href="${esc(l.url)}" data-i="${l.i}" draggable="false">
        ${iconHTML(l)}
        <span class="t"><div class="n">${esc(l.name)}</div><div class="h">${esc(host(l.url))}</div></span>
      </a>`).join('');
    sec.innerHTML = `<div class="body grid" data-group="${esc(g)}">${tiles}<button class="tile add" data-addg="${esc(g)}">+ agregar</button></div>`;
    cont.appendChild(sec);
  });
  $('groupList').innerHTML = [...new Set([...CONFIG.groups, ...groupsOf()])].map(g => `<option value="${esc(g)}">`).join('');
}
function applyLayout(){
  const m = mode(), cont = $('secs');
  dash = isDash(); document.body.classList.toggle('dash', dash);
  const secs = [...cont.querySelectorAll('.sec')], byId = Object.fromEntries(secs.map(s => [s.dataset.id, s]));
  const all = secs.map(s => s.dataset.id);
  let cols;
  if (dash){
    cols = splitCols(m.order).map(c => c.filter(id => byId[id]));
    const seen = new Set(cols.flat()), extra = all.filter(id => !seen.has(id));
    if (extra.length){ if (!cols.length) cols.push([]); extra.forEach(id => { const c = cols[id.startsWith('g:') || !(id in DASH_COL) ? cols.length - 1 : Math.min(DASH_COL[id], cols.length - 1)]; id === 'sonido' ? c.unshift(id) : c.push(id); }); }
    cols = cols.filter(c => c.length);
  } else {
    const flat = (m.orderMobile && m.orderMobile.length ? m.orderMobile : splitCols(m.order).flat()).filter(id => byId[id]);
    if (!flat.includes('sonido') && byId.sonido) flat.splice(flat.indexOf('reloj') + 1, 0, 'sonido');
    cols = [[...flat, ...all.filter(id => !flat.includes(id))]];
  }
  secs.forEach(s => s.remove());
  cont.innerHTML = '';
  cols.forEach(c => { const d = document.createElement('div'); d.className = 'col'; c.forEach(id => d.appendChild(byId[id])); cont.appendChild(d); });
  if (dash && editing){ const d = document.createElement('div'); d.className = 'col newcol'; d.textContent = 'arrastra aquí un bloque · columna nueva'; cont.appendChild(d); }
  if (dash){
    // el reloj y la ventana piden un poco más de ancho
    cont.style.gridTemplateColumns = [...cont.children].map(c => c.classList.contains('newcol') ? 'minmax(160px,.55fr)'
      : c.querySelector('.sec[data-id=ventana]:not(.off)') ? '1.3fr' : c.querySelector('.sec[data-id=reloj]:not(.off)') ? '1.12fr' : '1fr').join(' ');
  } else cont.style.gridTemplateColumns = '';
  secs.forEach(s => {
    s.classList.toggle('off', m.hidden.includes(s.dataset.id));
    const old = s.querySelector(':scope > .sh'); if (old) old.remove();
    s.insertAdjacentHTML('afterbegin', secHead(s.dataset.id, s.dataset.name));
  });
  fit();
}

/* ---------- ajuste a la pantalla: todo a la vista, de 1080p a 4K ---------- */
let escala = clamp(+local.get('hq.escala', 1) || 1, .6, 1.8);
function fit(){
  const w = document.querySelector('.wrap');
  if (!dash){ w.style.zoom = ''; w.style.width = ''; w.style.height = ''; return; }
  const cols = [...$('secs').children].filter(c => !c.classList.contains('newcol'));
  const apply = z => { w.style.zoom = z; w.style.width = (innerWidth / z) + 'px'; w.style.height = (innerHeight / z) + 'px'; };
  const over = () => cols.some(c => c.scrollHeight > c.clientHeight + 2);
  let z = clamp(innerWidth / 1720, .78, 2.6) * escala;
  apply(z);
  for (let k = 0; k < 14 && over() && z > .56; k++){ z *= .94; apply(z); }   // si no cabe, encoge hasta que quepa
}
let fitT, fitRO = null;
const refit = () => { clearTimeout(fitT); fitT = setTimeout(fit, 90); };
if (window.ResizeObserver){
  fitRO = new ResizeObserver(() => { if (dash && !document.body.classList.contains('dragging')) refit(); });
  new MutationObserver(() => { fitRO.disconnect(); document.querySelectorAll('#secs .sec').forEach(n => fitRO.observe(n)); }).observe($('secs'), { childList: true });
}
document.fonts?.ready.then(refit);
addEventListener('resize', () => { clearTimeout(fitT); fitT = setTimeout(() => { if (isDash() !== dash) applyLayout(); else fit(); }, 120); });
function renderModes(){
  $('modes').innerHTML = S.modes.map((m, i) => `<button class="chip${m.id === S.active ? ' on' : ''}" data-mode="${esc(m.id)}" aria-pressed="${m.id === S.active}" title="Modo ${i + 1}">${esc(m.name)}${editing && S.modes.length > 1 ? `<span class="x" data-mdel="${esc(m.id)}" title="Borrar modo">×</span>` : ''}</button>`).join('');
}
function renderAll(){
  renderGroups(); applyLayout(); renderModes(); renderZones();
  $('foot').textContent = `${S.links.length} accesos · ${groupsOf().length} módulos · modo ${mode().name.toLowerCase()}`;
  renderNewsTabs();
  if (window.SND) SND.presets();
}
function currentCols(){ return [...$('secs').children].map(c => [...c.querySelectorAll(':scope > .sec')].map(s => s.dataset.id)).filter(c => c.length); }
function saveOrder(){
  const m = mode();
  if (dash) m.order = joinCols(currentCols()); else m.orderMobile = currentCols().flat();
  save();
}
function addToModes(id, asColumn){
  S.modes.forEach(m => {
    if (!m.order.includes(id)){
      if (asColumn && m.id === S.active && splitCols(m.order).length < 6) m.order.push('|', id); else m.order.push(id);
    }
    if (m.orderMobile && !m.orderMobile.includes(id)) m.orderMobile.push(id);
  });
}
function renameInModes(a, b){
  S.modes.forEach(m => ['order', 'orderMobile', 'hidden'].forEach(k => { if (m[k]) m[k] = m[k].map(x => x === a ? b : x); }));
}

$('modes').addEventListener('click', e => {
  const d = e.target.closest('[data-mdel]');
  if (d){ e.stopPropagation(); if (!confirm('¿Borrar este modo?')) return; S.modes = S.modes.filter(m => m.id !== d.dataset.mdel); if (S.active === d.dataset.mdel) S.active = S.modes[0].id; save(); renderAll(); return; }
  const b = e.target.closest('[data-mode]'); if (!b) return;
  S.active = b.dataset.mode; save(); applyLayout(); renderModes(); toast('Modo ' + mode().name);
});
$('modBtn').onclick = () => {
  const n = cleanGroup(prompt('Nombre del nuevo módulo de enlaces (p. ej. «Producción», «Docencia», «Herramientas»)') || '');
  if (!n) return;
  if (groupsOf().includes(n)){ toast('Ya existe un módulo con ese nombre'); return; }
  S.groups.push(n); addToModes('g:' + n, true);
  save(); renderAll(); toast('Módulo «' + n + '» creado · agrégale sitios');
};
$('secs').addEventListener('click', e => {
  const dg = e.target.closest('[data-delg]');
  if (dg){
    const g = dg.dataset.delg, n = S.links.filter(l => (l.group || 'Otros') === g).length;
    if (!confirm(n ? `¿Quitar el módulo «${g}» y sus ${n} accesos?` : `¿Quitar el módulo «${g}»?`)) return;
    S.links = S.links.filter(l => (l.group || 'Otros') !== g); S.groups = S.groups.filter(x => x !== g);
    S.modes.forEach(m => ['order', 'orderMobile', 'hidden'].forEach(k => { if (m[k]) m[k] = m[k].filter(x => x !== 'g:' + g); }));
    S.modes.forEach(m => { m.order = joinCols(splitCols(m.order)); });
    save(); renderAll(); toast('Módulo quitado'); return;
  }
  if (e.target.closest('[data-addfeed]')){ openFeedDlg(); return; }
  if (e.target.closest('[data-addzone]')){ openZone(); return; }
  const h = e.target.closest('[data-hide]');
  if (h){ const id = h.dataset.hide, m = mode(); m.hidden = m.hidden.includes(id) ? m.hidden.filter(x => x !== id) : [...m.hidden, id]; save(); applyLayout(); return; }
  const r = e.target.closest('[data-ren]');
  if (r){
    const oldN = r.dataset.ren, n = cleanGroup(prompt('Nuevo nombre para el módulo', oldN) || '');
    if (!n || n === oldN) return;
    S.links.forEach(l => { if ((l.group || 'Otros') === oldN) l.group = n; });
    S.groups = S.groups.map(x => x === oldN ? n : x);
    renameInModes('g:' + oldN, 'g:' + n);
    save(); renderAll(); return;
  }
  const a = e.target.closest('[data-addg]'); if (a){ e.preventDefault(); openDlg(-1, a.dataset.addg); return; }
  if (editing && e.target.closest('.tile[data-i]')) e.preventDefault();
});

/* ---------- arrastrar filas (mouse y touch) ---------- */
let scrollRAF = null, dragY = 0, dragX = 0;
$('secs').addEventListener('pointerdown', e => {
  const grip = e.target.closest('.grip'); if (!grip) return;
  e.preventDefault();
  const sec = grip.closest('.sec'), cont = $('secs');
  if (dash && !cont.querySelector('.newcol')){                // mientras arrastras aparece una columna nueva a la derecha
    const d = document.createElement('div'); d.className = 'col newcol'; d.textContent = 'suelta aquí · columna nueva';
    cont.appendChild(d); cont.style.gridTemplateColumns += ' minmax(150px,.5fr)';
  }
  sec.classList.add('dragging'); document.body.classList.add('dragging'); dragY = e.clientY; dragX = e.clientX;
  // coloca el bloque en la columna bajo el puntero, antes del primer bloque cuya mitad quede por debajo
  const reorder = () => {
    const cols = [...cont.children];
    let col = cols[0];
    if (dash){
      let best = Infinity;
      cols.forEach(c => { const r = c.getBoundingClientRect(), d = dragX < r.left ? r.left - dragX : dragX > r.right ? dragX - r.right : 0; if (d < best){ best = d; col = c; } });
    }
    const cands = dash ? [...col.querySelectorAll(':scope > .sec')] : cols.flatMap(c => [...c.querySelectorAll(':scope > .sec')]);
    const vis = cands.filter(s => s !== sec && s.offsetParent !== null);
    const target = vis.find(s => { const r = s.getBoundingClientRect(); return dragY < r.top + r.height / 2; });
    if (target){ if (sec.nextElementSibling !== target) target.parentNode.insertBefore(sec, target); }
    else {
      const last = vis[vis.length - 1], parent = dash ? col : (last ? last.parentNode : col);
      if (col.classList.contains('newcol') && sec.parentNode !== col){ col.textContent = ''; col.classList.remove('newcol'); col.appendChild(sec); }
      else if (!last || sec.parentNode !== parent || parent.lastElementChild !== sec){ if (last && !dash) last.after(sec); else parent.appendChild(sec); }
    }
  };
  const move = ev => { dragY = ev.clientY; dragX = ev.clientX; reorder(); };
  const scroll = () => { if (!dash){ if (dragY < 70) scrollBy(0, -14); else if (dragY > innerHeight - 70) scrollBy(0, 14); } reorder(); scrollRAF = requestAnimationFrame(scroll); };
  scrollRAF = requestAnimationFrame(scroll);
  const up = () => {
    removeEventListener('pointermove', move); removeEventListener('pointerup', up); removeEventListener('pointercancel', up);
    cancelAnimationFrame(scrollRAF); sec.classList.remove('dragging'); document.body.classList.remove('dragging');
    saveOrder(); applyLayout();
  };
  addEventListener('pointermove', move); addEventListener('pointerup', up); addEventListener('pointercancel', up);
});

/* ---------- arrastrar accesos (en modo editar) ---------- */
$('secs').addEventListener('pointerdown', e => {
  if (!editing) return;
  const tile = e.target.closest('.tile[data-i]'); if (!tile) return;
  e.preventDefault();
  const sx = e.clientX, sy = e.clientY; let moved = false;
  const move = ev => {
    dragY = ev.clientY;
    if (!moved && Math.hypot(ev.clientX - sx, ev.clientY - sy) < 6) return;
    if (!moved){ moved = true; tile.classList.add('lift'); document.body.classList.add('dragging'); scrollRAF = requestAnimationFrame(function sc(){ if (dragY < 70) scrollBy(0, -12); else if (dragY > innerHeight - 70) scrollBy(0, 12); scrollRAF = requestAnimationFrame(sc); }); }
    tile.style.pointerEvents = 'none';
    const el = document.elementFromPoint(ev.clientX, ev.clientY);
    tile.style.pointerEvents = '';
    if (!el) return;
    const t = el.closest('.tile[data-i]'), grid = el.closest('.grid[data-group]');
    if (t && t !== tile){
      const r = t.getBoundingClientRect(), dy = ev.clientY - (r.top + r.height / 2);
      const before = Math.abs(dy) > r.height / 4 ? dy < 0 : ev.clientX < r.left + r.width / 2;
      t.parentNode.insertBefore(tile, before ? t : t.nextSibling);
    } else if (grid && !t && tile.parentNode !== grid){
      grid.insertBefore(tile, grid.querySelector('.tile.add'));
    }
  };
  const up = () => {
    removeEventListener('pointermove', move); removeEventListener('pointerup', up); removeEventListener('pointercancel', up);
    cancelAnimationFrame(scrollRAF); tile.classList.remove('lift'); document.body.classList.remove('dragging');
    if (!moved){ openDlg(+tile.dataset.i); return; }
    const next = [];
    document.querySelectorAll('.grid[data-group]').forEach(g => g.querySelectorAll('.tile[data-i]').forEach(t => next.push({ ...S.links[+t.dataset.i], group: g.dataset.group })));
    S.links = next; save(); renderAll();
  };
  addEventListener('pointermove', move); addEventListener('pointerup', up); addEventListener('pointercancel', up);
});

/* ---------- diálogo de sitio con vista previa del ícono ---------- */
function fixUrl(v){ v = v.trim(); if (!v) return ''; if (/^[a-z][a-z0-9+.-]*:/i.test(v) || /^(\/|\.{1,2}\/)/.test(v)) return v; return 'https://' + v; }
function preview(){
  const l = { name: $('fName').value.trim(), url: fixUrl($('fUrl').value), icon: $('fIcon').value };
  $('pIco').outerHTML = iconHTML(l, true).replace('<span class="ico', '<span id="pIco" class="ico');
  $('pName').textContent = l.name || 'Nuevo sitio'; $('pHost').textContent = l.url ? host(l.url) : '—';
}
let prevT;
['fUrl', 'fName', 'fIconTxt'].forEach(id => $(id).addEventListener('input', () => { clearTimeout(prevT); prevT = setTimeout(preview, 250); }));
const EMOJIS = ['🌊','🔭','🎭','🎛️','💡','📐','🎨','🎧','📷','🌿','📁','✉️','📅','⭐'];
let siteIcon = '', nameTouched = false, fromBookmarklet = false;
function renderChips(){
  const g = $('fGroup').value.trim();
  $('gChips').innerHTML = [...new Set([...groupsOf(), ...CONFIG.groups])].map(x => `<button type="button" data-g="${esc(x)}" class="${x === g ? 'on' : ''}">${esc(x)}</button>`).join('')
    + `<button type="button" data-g="">+ módulo nuevo</button>`;
  const ic = $('fIcon').value.trim();
  $('iChips').innerHTML = `<button type="button" data-ic="" class="${!ic ? 'on' : ''}">automático</button>`
    + (siteIcon ? `<button type="button" data-ic="${esc(siteIcon)}" class="${ic === siteIcon ? 'on' : ''}"><img src="${esc(siteIcon)}" alt=""> del sitio</button>` : '')
    + `<button type="button" data-ic="letra" class="${ic === 'letra' ? 'on' : ''}">letra</button>`
    + EMOJIS.map(e => `<button type="button" class="emo${ic === e ? ' on' : ''}" data-ic="${e}">${e}</button>`).join('');
}
$('gChips').addEventListener('click', e => {
  const b = e.target.closest('[data-g]'); if (!b) return;
  $('fGroup').value = b.dataset.g; renderChips(); if (!b.dataset.g) $('fGroup').focus();
});
$('iChips').addEventListener('click', e => {
  const b = e.target.closest('[data-ic]'); if (!b) return;
  $('fIcon').value = b.dataset.ic; $('fIconTxt').value = ''; renderChips(); preview();
});
$('fGroup').addEventListener('input', renderChips);
$('fIconTxt').addEventListener('input', () => { $('fIcon').value = $('fIconTxt').value.trim(); renderChips(); });
$('fName').addEventListener('input', () => { nameTouched = true; });
function openDlg(i, group, pre){
  editIdx = i; siteIcon = ''; nameTouched = false; $('fHint').textContent = '';
  const l = i >= 0 ? S.links[i] : { name: '', url: '', group: group || groupsOf()[0] || 'Trabajo', icon: '', ...(pre || {}) };
  $('dlgTitle').textContent = i >= 0 ? 'Editar acceso' : 'Agregar sitio';
  $('fName').value = l.name; $('fUrl').value = l.url; $('fGroup').value = l.group || ''; $('fIcon').value = l.icon || '';
  const ic = l.icon || '';
  if (/^(data:|https?:|\/|\.)/.test(ic) && i >= 0) siteIcon = ic;      // al editar, conservar el ícono guardado como «del sitio»
  $('fIconTxt').value = !ic || ic === 'letra' || EMOJIS.includes(ic) || ic === siteIcon ? '' : ic;
  $('fDel').hidden = i < 0; renderChips(); preview();
  if (!$('dlg').open) $('dlg').showModal();
  setTimeout(() => { const f = i >= 0 || l.url ? $('fName') : $('fUrl'); f.focus(); if (l.url) f.select(); }, 50);
}

/* ---------- agregar desde una dirección: arrastrar, pegar o botón de favoritos ---------- */
function cleanTitle(t, url){
  t = strip(t || '');
  if (!t) return '';
  const parts = t.split(/\s+[|·–—-]\s+/).map(x => x.trim()).filter(Boolean);   // «Gmail - Correo de Google» → «Gmail»
  t = parts.length > 1 && parts[0].length >= 3 ? parts[0] : t;
  return cut(t, 40);
}
function guessName(url){
  const h = host(url), path = (() => { try{ return new URL(url, location.href).pathname.split('/').filter(Boolean).pop() || ''; }catch(e){ return ''; } })();
  const b = h === location.hostname.replace(/^www\./, '') && path ? path : h.split('.')[0];
  return b.replace(/[-_]+/g, ' ').replace(/^\w/, c => c.toUpperCase());
}
async function addFromUrl(url, title, group){
  url = fixUrl(url); if (!url) return;
  const dup = S.links.findIndex(l => absUrl(l.url) === absUrl(url));
  openDlg(-1, group, { url, name: cleanTitle(title) || guessName(url) });
  if (dup >= 0) $('fHint').textContent = `ya está en «${S.links[dup].group}» como «${S.links[dup].name}»`;
  try{
    $('fHint').textContent ||= 'buscando título e ícono…';
    const r = await api('titulo64=' + b64u(absUrl(url)));
    const j = r.ok ? await r.json() : {};
    if (!$('dlg').open || fixUrl($('fUrl').value) !== url) return;
    if (j.title && !nameTouched && !title) $('fName').value = cleanTitle(j.title);
    if (j.icon){ siteIcon = j.icon; if (!$('fIcon').value) $('fIcon').value = j.icon; }
    if ($('fHint').textContent.startsWith('buscando')) $('fHint').textContent = j.title ? 'listo · Enter para guardar' : '';
    renderChips(); preview();
  }catch(e){ if ($('fHint').textContent.startsWith('buscando')) $('fHint').textContent = ''; }
}
function urlFromTransfer(dt){
  let url = '', title = '';
  const moz = dt.getData('text/x-moz-url');
  if (moz){ [url, title] = moz.split('\n'); }
  if (!url){ url = (dt.getData('text/uri-list') || '').split('\n').map(x => x.trim()).find(x => x && !x.startsWith('#')) || ''; }
  const html = dt.getData('text/html');
  if (html){
    const d = new DOMParser().parseFromString(html, 'text/html'), a = d.querySelector('a[href]');
    if (!url && a) url = a.getAttribute('href');
    if (!title && a) title = a.textContent;
  }
  if (!url){ const t = (dt.getData('text/plain') || '').trim(); if (/^(https?:\/\/|www\.)\S+$/i.test(t)) url = t; }
  if (title && title.trim() === url.trim()) title = '';
  return /^https?:/i.test(fixUrl(url)) ? { url: fixUrl(url), title } : null;
}
const isUrlDrag = dt => [...(dt.types || [])].some(t => t === 'text/uri-list' || t === 'text/x-moz-url');
let dropDepth = 0, overSec = null;
function setOver(sec){ if (overSec === sec) return; overSec?.classList.remove('over'); overSec = sec; sec?.classList.add('over'); }
function endDrop(){ dropDepth = 0; document.body.classList.remove('dropping'); setOver(null); $('dropNew').classList.remove('over'); }
document.addEventListener('dragenter', e => {
  if (!isUrlDrag(e.dataTransfer) || document.body.classList.contains('dragging')) return;
  dropDepth++; document.body.classList.add('dropping');
});
document.addEventListener('dragleave', () => { if (--dropDepth <= 0) endDrop(); });
document.addEventListener('dragover', e => {
  if (!document.body.classList.contains('dropping')) return;
  e.preventDefault(); e.dataTransfer.dropEffect = 'copy';
  const onNew = e.target.closest?.('#dropNew');
  $('dropNew').classList.toggle('over', !!onNew);
  setOver(onNew ? null : e.target.closest?.('.sec.grp') || null);
});
document.addEventListener('drop', e => {
  if (!document.body.classList.contains('dropping')) return;
  e.preventDefault();
  const onNew = e.target.closest?.('#dropNew'), sec = e.target.closest?.('.sec.grp');
  const got = urlFromTransfer(e.dataTransfer); endDrop();
  if (!got){ toast('Eso no parece una dirección web'); return; }
  addFromUrl(got.url, got.title, onNew ? '' : sec ? sec.dataset.name : undefined);
  if (onNew) setTimeout(() => $('fGroup').focus(), 80);
});
document.addEventListener('paste', e => {
  if (e.target.matches?.('input,textarea') || document.querySelector('dialog[open]')) return;
  const t = (e.clipboardData?.getData('text') || '').trim();
  if (/^(https?:\/\/|www\.)\S+$/i.test(t)){ e.preventDefault(); addFromUrl(t, ''); }
});
$('mBm').onclick = () => {
  const me = location.origin + location.pathname;
  $('bmLink').href = `javascript:(()=>{window.open('${me}?add='+encodeURIComponent(location.href)+'&t='+encodeURIComponent(document.title),'_blank')})()`;
  $('mdlg').close(); $('bdlg').showModal();
};
$('bmLink').addEventListener('click', e => { e.preventDefault(); toast('Arrástralo a la barra de favoritos'); });
function addFromQuery(){
  const q = new URLSearchParams(location.search), u = q.get('add');
  if (!u) return;
  history.replaceState(null, '', location.pathname);
  fromBookmarklet = true;
  addFromUrl(u, q.get('t') || '');
}
$('fUrl').addEventListener('blur', () => {
  const v = fixUrl($('fUrl').value);
  if (v && !$('fName').value.trim()){ $('fName').value = guessName(v); preview(); }
  if (v && /^https?:/i.test(absUrl(v)) && !siteIcon && editIdx < 0 && $('dlg').open){
    api('titulo64=' + b64u(absUrl(v))).then(r => r.ok ? r.json() : {}).then(j => {
      if (!$('dlg').open) return;
      if (j.title && !nameTouched) $('fName').value = cleanTitle(j.title);
      if (j.icon){ siteIcon = j.icon; if (!$('fIcon').value) $('fIcon').value = j.icon; }
      renderChips(); preview();
    }).catch(() => {});
  }
});
$('dlgForm').addEventListener('submit', () => {
  const item = { name: $('fName').value.trim(), url: fixUrl($('fUrl').value), group: cleanGroup($('fGroup').value) || 'Otros' };
  if ($('fIcon').value.trim()) item.icon = $('fIcon').value.trim();
  const isNewGroup = !groupsOf().includes(item.group);
  if (editIdx >= 0) S.links[editIdx] = item; else S.links.push(item);
  if (isNewGroup){ S.groups.push(item.group); addToModes('g:' + item.group, true); }
  save(); renderAll(); toast(editIdx >= 0 ? 'Acceso actualizado' : 'Acceso agregado');
  if (fromBookmarklet){ fromBookmarklet = false; setTimeout(() => { if (window.opener !== null || history.length <= 1) window.close(); }, 1200); }
});
$('dlg').addEventListener('close', () => { if (fromBookmarklet && $('dlg').returnValue === '') fromBookmarklet = false; });
$('fDel').onclick = () => { if (editIdx >= 0){ S.links.splice(editIdx, 1); save(); renderAll(); toast('Acceso eliminado'); } $('dlg').close(); };

/* ---------- barra y menú ---------- */
$('addBtn').onclick = () => openDlg(-1);
function setEditing(v){
  editing = v; document.body.classList.toggle('editing', v);
  $('editBtn').classList.toggle('on', v); $('editBtn').textContent = v ? 'listo' : 'editar';
  renderModes(); applyLayout();
  if (v) toast(dash ? 'Arrastra ⠿ entre columnas; suelta a la derecha para una columna nueva' : 'Arrastra ⠿ para mover bloques, y los accesos para reordenarlos');
}
$('editBtn').onclick = () => setEditing(!editing);
$('menuBtn').onclick = () => $('mdlg').showModal();
$('mSaveMode').onclick = () => {
  const n = (prompt('Nombre del modo (p. ej. «Ensayo», «Gira», «Domingo»)') || '').trim(); if (!n) return;
  const m = mode(), id = 'm' + Date.now().toString(36);
  S.modes.push({ id, name: n, order: dash ? joinCols(currentCols()) : m.order.slice(), orderMobile: dash ? (m.orderMobile || []).slice() : currentCols().flat(), hidden: m.hidden.slice() });
  S.active = id; save(); renderAll(); $('mdlg').close(); toast('Modo «' + n + '» guardado');
};
function showEscala(){ $('zVal').textContent = Math.round(escala * 100) + '%'; }
$('zMinus').onclick = () => { escala = clamp(+(escala - .1).toFixed(2), .6, 1.8); local.set('hq.escala', escala); showEscala(); fit(); };
$('zPlus').onclick = () => { escala = clamp(+(escala + .1).toFixed(2), .6, 1.8); local.set('hq.escala', escala); showEscala(); fit(); };
$('menuBtn').addEventListener('click', showEscala);
function toggleUI(){ document.body.classList.toggle('sinui'); if (!document.body.classList.contains('sinui')) fit(); }
$('uiBack').onclick = toggleUI;
function aplicarContraste(){ const alto = !!local.get('hq.contraste', false); document.documentElement.toggleAttribute('data-contrast', alto); if (alto) document.documentElement.setAttribute('data-contrast', 'alto'); $('mContr').setAttribute('aria-pressed', alto); $('mContr').classList.toggle('on', alto); }
$('mContr').onclick = () => { local.set('hq.contraste', !local.get('hq.contraste', false)); aplicarContraste(); };
aplicarContraste();
$('mExport').onclick = () => {
  const a = document.createElement('a');
  a.href = URL.createObjectURL(new Blob([JSON.stringify(S, null, 2)], { type: 'application/json' }));
  a.download = 'hq-respaldo-' + new Date().toISOString().slice(0, 10) + '.json'; a.click();
  setTimeout(() => URL.revokeObjectURL(a.href), 1000);
};
$('mImport').onclick = () => $('impFile').click();
$('impFile').onchange = async e => {
  try{
    const j = JSON.parse(await e.target.files[0].text());
    const n = Array.isArray(j) ? { ...S, links: j } : normalize(j);
    if (!n) throw 0;
    n.links = n.links.filter(l => l && l.url && l.name); S = n; save(); renderAll(); $('mdlg').close(); toast('Respaldo importado');
  }catch(err){ alert('Ese archivo no parece un respaldo válido.'); }
  e.target.value = '';
};
$('mPass').onclick = () => { $('mdlg').close(); $('pMsg').textContent = ''; $('pForm').reset(); $('pdlg').showModal(); };
$('pForm').addEventListener('submit', async e => {
  e.preventDefault();
  try{
    const r = await api('clave', { method: 'POST', body: JSON.stringify({ actual: $('pOld').value, nueva: $('pNew').value }) });
    const j = await r.json();
    if (r.ok){ $('pdlg').close(); toast('Contraseña cambiada'); } else $('pMsg').textContent = j.error || 'No se pudo cambiar.';
  }catch(err){ $('pMsg').textContent = 'Sin conexión con el servidor.'; }
});

document.addEventListener('keydown', e => {
  if (e.target.matches('input,textarea') || document.querySelector('dialog[open]') || e.metaKey || e.ctrlKey || e.altKey) return;
  if (e.key === '/'){ e.preventDefault(); $('q').focus(); }
  else if (e.key.toLowerCase() === 'n'){ e.preventDefault(); openDlg(-1); }
  else if (e.key.toLowerCase() === 'e'){ e.preventDefault(); setEditing(!editing); }
  else if (e.key.toLowerCase() === 'h'){ e.preventDefault(); toggleUI(); }
  else if (e.key.toLowerCase() === 'm'){ e.preventDefault(); SND.toggle(); }
  else if (e.key.toLowerCase() === 'd'){ e.preventDefault(); SND.tirar(); }
  else if (e.key === 'Escape' && editing) setEditing(false);
  else if (/^[1-9]$/.test(e.key) && S.modes[+e.key - 1]){ S.active = S.modes[+e.key - 1].id; save(); applyLayout(); renderModes(); toast('Modo ' + mode().name); }
});
document.addEventListener('visibilitychange', () => { if (!document.hidden){ refreshFromServer(); tickClock(); loadNews(); } });

/* ============================================================
   FONDO VIVO: cielo según la hora real del sol, clima y aire
   ============================================================ */
(() => {
  const cv = $('sky'), cx = cv.getContext('2d');
  const still = matchMedia('(prefers-reduced-motion: reduce)').matches;
  let W, H, DPR, P = [], mx = -999, my = -999, flash = 0, nextFlash = 0, t = 0, last = performance.now();
  const cur = { ...amb };  // valores suavizados: el fondo se funde hacia el estado real
  const mix = (a, b, k) => a.map((v, i) => v + (b[i] - v) * k);
  // paletas de cielo [arriba, abajo] por altura del sol, siempre oscuras para que el texto se lea
  const SKY = [
    [-18, [5, 6, 10],   [9, 10, 15]],     // noche
    [-10, [8, 10, 22],  [16, 14, 30]],    // crepúsculo astronómico
    [-4,  [16, 22, 48], [52, 32, 48]],    // hora azul
    [2,   [26, 30, 52], [84, 50, 34]],    // amanecer / atardecer
    [10,  [22, 34, 52], [48, 42, 40]],    // hora dorada alta
    [30,  [16, 30, 44], [24, 34, 40]],    // día
    [90,  [14, 30, 46], [20, 32, 40]]
  ];
  function skyAt(alt){
    for (let i = 1; i < SKY.length; i++) if (alt <= SKY[i][0]){
      const a = SKY[i - 1], b = SKY[i], k = clamp((alt - a[0]) / (b[0] - a[0]), 0, 1);
      return [mix(a[1], b[1], k), mix(a[2], b[2], k)];
    }
    return [SKY[SKY.length - 1][1], SKY[SKY.length - 1][2]];
  }
  const rgb = (c, a = 1) => `rgba(${c[0] | 0},${c[1] | 0},${c[2] | 0},${a})`;
  function size(){
    DPR = Math.min(1.5, devicePixelRatio || 1); W = innerWidth; H = innerHeight;
    cv.width = W * DPR; cv.height = H * DPR; cx.setTransform(DPR, 0, 0, DPR, 0, 0);
    const n = Math.round(clamp(W * H / 9000, 60, 220));
    P = Array.from({ length: n }, (_, i) => ({ x: Math.random() * W, y: Math.random() * H, z: Math.random(), s: Math.random() * 6.28, i }));
  }
  function frame(now){
    const dt = Math.min(.05, (now - last) / 1000); last = now; t += dt;
    for (const k of ['alt','cloud','rain','snow','fog','storm','wind','aqi','uv','moon']) cur[k] += ((amb[k] ?? 0) - cur[k]) * Math.min(1, dt * .6);
    const night = clamp((-cur.alt - 2) / 14, 0, 1), day = clamp(cur.alt / 20, 0, 1);

    // 1 · cielo
    let [top, bot] = skyAt(cur.alt);
    const grey = [18, 20, 24];
    top = mix(top, grey, cur.cloud * .55 + cur.fog * .3); bot = mix(bot, grey, cur.cloud * .45 + cur.fog * .3);
    const haze = clamp((cur.aqi - 40) / 160, 0, 1);              // contaminación: bruma ámbar desde abajo
    bot = mix(bot, [70, 50, 28], haze * .45);
    const g = cx.createLinearGradient(0, 0, 0, H); g.addColorStop(0, rgb(top)); g.addColorStop(1, rgb(bot));
    cx.fillStyle = g; cx.fillRect(0, 0, W, H);

    // 2 · sol o luna como resplandor
    const p = clamp(amb.dayP, -0.1, 1.1);
    if (cur.alt > -8){
      const sx = W * (0.08 + 0.84 * clamp(p, 0, 1)), sy = H * (0.95 - 0.75 * clamp(cur.alt / 60, 0, 1));
      const k = clamp((cur.alt + 8) / 14, 0, 1) * (1 - cur.cloud * .7) * (0.6 + clamp(cur.uv / 10, 0, 1) * .5);
      const warm = cur.alt < 12 ? [235, 150, 80] : [235, 210, 160];
      const rg = cx.createRadialGradient(sx, sy, 0, sx, sy, Math.max(W, H) * .6);
      rg.addColorStop(0, rgb(warm, .22 * k)); rg.addColorStop(.35, rgb(warm, .07 * k)); rg.addColorStop(1, rgb(warm, 0));
      cx.fillStyle = rg; cx.fillRect(0, 0, W, H);
    }
    if (night > 0 && cur.moon > .05){
      const k = night * cur.moon * (1 - cur.cloud * .8);
      const rg = cx.createRadialGradient(W * .82, H * .14, 0, W * .82, H * .14, Math.max(W, H) * .45);
      rg.addColorStop(0, `rgba(190,205,235,${.13 * k})`); rg.addColorStop(1, 'rgba(190,205,235,0)');
      cx.fillStyle = rg; cx.fillRect(0, 0, W, H);
    }
    // niebla
    if (cur.fog > .02){ cx.fillStyle = `rgba(200,205,215,${.07 * cur.fog})`; cx.fillRect(0, 0, W, H); }

    // 3 · partículas: estrellas, polvo, lluvia o nieve
    const wr = (amb.windDir + 180) * RAD, wx = Math.sin(wr) * clamp(cur.wind / 40, 0, 1.5), wy = -Math.cos(wr) * clamp(cur.wind / 40, 0, 1.5);
    const rainN = Math.round(P.length * cur.rain), snowN = Math.round(P.length * cur.snow * .7);
    const starA = night * (1 - cur.cloud * .85) * (1 - haze * .6);
    for (const q of P){
      if (q.i < rainN){                     // lluvia: trazos finos inclinados por el viento
        const sp = (500 + q.z * 500) * dt;
        q.y += sp; q.x += wx * sp * .5 + .2 * sp * Math.sign(wx || 1) * .2;
        if (q.y > H + 20){ q.y = -20 - Math.random() * 60; q.x = Math.random() * W; }
        if (q.x > W + 20) q.x = -20; if (q.x < -20) q.x = W + 20;
        cx.strokeStyle = `rgba(170,195,215,${.12 + q.z * .18})`; cx.lineWidth = .7 + q.z * .5;
        cx.beginPath(); cx.moveTo(q.x, q.y); cx.lineTo(q.x - wx * 8 - .5, q.y - 10 - q.z * 12); cx.stroke();
      } else if (q.i < rainN + snowN){      // nieve: copos lentos que oscilan
        q.y += (20 + q.z * 40) * dt; q.x += (Math.sin(t * .8 + q.s) * 12 + wx * 30) * dt;
        if (q.y > H + 10){ q.y = -10; q.x = Math.random() * W; }
        if (q.x > W + 10) q.x = -10; if (q.x < -10) q.x = W + 10;
        cx.fillStyle = `rgba(235,238,245,${.35 + q.z * .45})`; cx.beginPath(); cx.arc(q.x, q.y, .8 + q.z * 1.8, 0, 6.283); cx.fill();
      } else {                              // deriva suave: estrellas de noche, motas de día, polvo con mal aire
        const sp = 6 + q.z * 10;
        q.x += (wx * sp + Math.sin(t * .1 + q.s) * 2) * dt; q.y += (wy * sp * .4 + Math.cos(t * .13 + q.s) * 2) * dt;
        const dx = q.x - mx, dy = q.y - my, d2 = dx * dx + dy * dy;
        if (d2 < 14400){ const f = (1 - d2 / 14400) * 40 * dt; q.x += dx / 120 * f; q.y += dy / 120 * f; }
        if (q.x < -10) q.x = W + 10; if (q.x > W + 10) q.x = -10; if (q.y < -10) q.y = H + 10; if (q.y > H + 10) q.y = -10;
        const tw = .55 + .45 * Math.sin(t * (1 + q.z * 2) + q.s);
        if (q.i % 3 === 0 && haze > .15){ cx.fillStyle = `rgba(214,170,110,${.25 * haze * (.5 + q.z)})`; cx.beginPath(); cx.arc(q.x, q.y, 1 + q.z * 1.4, 0, 6.283); cx.fill(); }
        else {
          const a = lerp(.18 + q.z * .2, (.25 + q.z * .6) * tw, starA) * (1 - cur.rain * .7);
          cx.fillStyle = q.i % 9 === 0 && !night ? `rgba(217,183,121,${a + .15})` : `rgba(236,235,231,${a})`;
          cx.beginPath(); cx.arc(q.x, q.y, .35 + q.z * (night > .5 ? 1.1 : 1.3), 0, 6.283); cx.fill();
        }
      }
    }
    // constelación solo con cielo limpio
    if (cur.rain < .1 && cur.snow < .1){
      const n = Math.min(P.length, 110), lim = 13000, la = .08 * (1 - cur.cloud * .6) * (1 - cur.fog);
      for (let i = rainN + snowN; i < n; i++) for (let j = i + 1; j < n; j++){
        const a = P[i], b = P[j], dx = a.x - b.x, dy = a.y - b.y, d2 = dx * dx + dy * dy;
        if (d2 < lim){ cx.strokeStyle = `rgba(236,235,231,${(1 - d2 / lim) * la})`; cx.lineWidth = .6; cx.beginPath(); cx.moveTo(a.x, a.y); cx.lineTo(b.x, b.y); cx.stroke(); }
      }
    }
    // 4 · marea: líneas de agua al pie de la pantalla, más altas en pleamar
    if (amb.tide){
      const base = H - 30 - amb.tide.level * 70;
      for (let k = 0; k < 3; k++){
        cx.beginPath();
        for (let x = 0; x <= W; x += 12){ const y = base + k * 14 + Math.sin(x * .008 + t * (.5 + k * .15) * (amb.tide.rising ? 1 : -1) + k) * (6 - k * 1.5); x ? cx.lineTo(x, y) : cx.moveTo(x, y); }
        cx.strokeStyle = `rgba(143,192,212,${.16 - k * .04})`; cx.lineWidth = 1; cx.stroke();
      }
    }
    // 5 · relámpagos con tormenta
    if (cur.storm > .5){
      if (t > nextFlash){ flash = .9; nextFlash = t + 5 + Math.random() * 12; }
      if (flash > .01){ cx.fillStyle = `rgba(220,225,255,${flash * .12})`; cx.fillRect(0, 0, W, H); flash *= Math.pow(.02, dt); }
    }
    // viñeta para dar profundidad y proteger el texto
    const vg = cx.createRadialGradient(W / 2, H * .45, Math.min(W, H) * .3, W / 2, H * .45, Math.max(W, H) * .8);
    vg.addColorStop(0, 'rgba(0,0,0,0)'); vg.addColorStop(1, 'rgba(0,0,0,.45)');
    cx.fillStyle = vg; cx.fillRect(0, 0, W, H);

    if (!still && !document.hidden) requestAnimationFrame(frame);
  }
  addEventListener('resize', size);
  addEventListener('pointermove', e => { mx = e.clientX; my = e.clientY; });
  document.addEventListener('visibilitychange', () => { if (!document.hidden && !still){ last = performance.now(); requestAnimationFrame(frame); } });
  if (still) setInterval(() => frame(performance.now()), 60000);
  size(); requestAnimationFrame(frame);
})();

/* ---------- arranque ---------- */
(async () => {
  setPlaceName(); showPhrase();
  await loadState();
  renderAll(); addFromQuery(); tickClock(); setInterval(tickClock, 1000);
  updateSky(); loadWeather(); setInterval(loadWeather, 20 * 6e4);
  iniciarVentana();
  renderNewsTabs(); loadNews(); trafficLabel();
  setInterval(() => { if (!document.hidden) loadNews(true); }, 10 * 6e4);
  if ($('tAuto').checked && !mode().hidden.includes('trafico')) loadTraffic();
  try{
    const st = await navigator.permissions.query({ name: 'geolocation' });
    if (st.state === 'granted') locate();
    else if (local.get('hq.place', null)) $('locBtn').textContent = 'actualizar';
  }catch(e){}
})();
</script>
</body>
</html>
