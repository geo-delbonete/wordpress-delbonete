<?php
// Viaje Maschwitz -> Retiro. Un solo archivo: página + API. Guarda en data.json (misma carpeta).
// Sin cuentas ni contraseñas: cualquiera con el link puede anotarse.

$JOVENES = [
 "Andreis, Juan Pablo","Cañas Vivaldi, Lourdes Juana","Casetti Bragoni, Juana","Chaves, Juan Martín","D’Aurizio, Molly",
 "Dolzani, Paloma","Dyment, Joaquín","Escribano, Allegra Lucía","Fontana Bareiro, Salvador","Furnari, Martina Pia",
 "Herrero, Camilo","Korach, Simón","Lezcano Quiroga, Federico","Lopez, Mariano Joaquín","Mainardi, Teo",
 "Mancini Bellini, Salvador","Menendez Percara, Nazareno","Mosto, Catalina","Okretic, Luba","Perez Leiros, Martina",
 "Plotkin, Olivia","Podestá, Candelaria","Ramos Alloatti, Macarena","Rébora, Galileo","Rigo, Simón",
 "Rivera, Guido","Rodriguez Cabral, Juana","Solorzano Pereyra, Iker","Vargas, Belén","Wald, Teo",
];
$ZONAS = ["Maschwitz pueblo","La Bota","Villanueva","Cantón / San Matías / Puertos","Escobar","Savio / Ruta 26","Otro"];
$FILE = __DIR__ . "/data.json";

function slugify($s){ $s=iconv("UTF-8","ASCII//TRANSLIT",$s); $s=strtolower(preg_replace("/[^a-z0-9]+/i","-",$s)); return trim($s,"-"); }

if (isset($_GET["api"])) {
  header("Content-Type: application/json; charset=utf-8");
  header("Cache-Control: no-store");
  $fh = fopen($FILE, "c+"); flock($fh, LOCK_EX);
  $raw = stream_get_contents($fh);
  $db = $raw ? json_decode($raw, true) : null;
  if (!is_array($db)) $db = ["autos"=>[], "res"=>[]];
  $kids = array_map(fn($n)=>["id"=>slugify($n),"name"=>$n], $JOVENES);
  $in = json_decode(file_get_contents("php://input"), true) ?: [];
  $err = null; $api = $_GET["api"];
  $libres = function($a) use (&$db){ return max(0, $a["seats"] - count(array_filter($db["res"], fn($r)=>$r["autoId"]===$a["id"]))); };
  $conAuto = function($kid) use (&$db){
    foreach ($db["autos"] as $a) if ($a["kidId"]===$kid) return true;
    foreach ($db["res"] as $r) if ($r["kidId"]===$kid && $r["autoId"]) return true;
    return false;
  };
  $clean = fn($s,$n=80)=>mb_substr(trim(strip_tags((string)$s)),0,$n);
  $uid = $clean($in["uid"] ?? ($_GET["uid"] ?? ""), 40);

  if ($api === "add_auto") {
    $driver=$clean($in["driver"]??""); $ref=$clean($in["ref"]??"",120); $kidId=$clean($in["kidId"]??"",100); $kid=$clean($in["kid"]??"",100);
    $zona=(int)($in["zona"]??0); $seats=(int)($in["seats"]??0);
    if (!$driver||!$kidId||!$kid||!$ref||$seats<1||$seats>8||$zona<0||$zona>=count($ZONAS)||!$uid) $err="Faltan datos";
    elseif ($conAuto($kidId)) $err="Ese joven ya tiene lugar";
    else {
      // si el joven estaba esperando auto, se saca de la espera
      $db["res"] = array_values(array_filter($db["res"], fn($r)=>!($r["kidId"]===$kidId && !$r["autoId"])));
      $db["autos"][] = ["id"=>bin2hex(random_bytes(5)),"driver"=>$driver,"kidId"=>$kidId,"kid"=>$kid,"zona"=>$zona,"ref"=>$ref,"seats"=>$seats,"uid"=>$uid,"ts"=>time()];
    }
  } elseif ($api === "add_res") {
    $kidId=$clean($in["kidId"]??"",100); $kid=$clean($in["kid"]??"",100); $zona=(int)($in["zona"]??0); $autoId=$in["autoId"]??null;
    if (!$kidId||!$kid||!$uid||$zona<0||$zona>=count($ZONAS)) $err="Faltan datos";
    elseif ($conAuto($kidId)) $err="Ese joven ya tiene lugar";
    else {
      if ($autoId) {
        $a = null; foreach ($db["autos"] as $x) if ($x["id"]===$autoId) $a=$x;
        if (!$a) $err="Ese auto ya no existe"; elseif ($libres($a)<1) $err="Se completó ese auto";
      }
      if (!$err) {
        $db["res"] = array_values(array_filter($db["res"], fn($r)=>!($r["kidId"]===$kidId && !$r["autoId"])));
        $db["res"][] = ["id"=>bin2hex(random_bytes(5)),"kidId"=>$kidId,"kid"=>$kid,"zona"=>$zona,"autoId"=>$autoId?:null,"uid"=>$uid,"ts"=>time()];
      }
    }
  } elseif ($api === "del_auto") {
    $id=$in["id"]??"";
    $db["autos"] = array_values(array_filter($db["autos"], fn($a)=>!($a["id"]===$id && $a["uid"]===$uid)));
    $ids = array_column($db["autos"],"id");
    foreach ($db["res"] as &$r) if ($r["autoId"] && !in_array($r["autoId"],$ids)) $r["autoId"]=null; unset($r);
  } elseif ($api === "del_res") {
    $id=$in["id"]??"";
    $db["res"] = array_values(array_filter($db["res"], fn($r)=>!($r["id"]===$id && $r["uid"]===$uid)));
  }
  if (in_array($api,["add_auto","add_res","del_auto","del_res"]) && !$err) { ftruncate($fh,0); rewind($fh); fwrite($fh, json_encode($db, JSON_UNESCAPED_UNICODE)); }
  flock($fh, LOCK_UN); fclose($fh);
  if ($err) { http_response_code(400); echo json_encode(["error"=>$err]); exit; }
  // el uid no se expone a otros: solo se marca cuáles son "míos"
  $mark = function($list) use ($uid){ return array_map(function($x) use ($uid){ $x["mine"]=($x["uid"]===$uid); unset($x["uid"]); return $x; }, $list); };
  echo json_encode(["kids"=>$kids,"autos"=>$mark($db["autos"]),"res"=>$mark($db["res"])], JSON_UNESCAPED_UNICODE);
  exit;
}
?><!doctype html>
<html lang="es"><head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<meta name="robots" content="noindex,nofollow">
<title>Viaje a Retiro</title>
<style>
:root{
  --bg:#f2f2f7; --card:#fff; --fg:#1c1c1e; --muted:#6c6c70; --line:#e5e5ea;
  --accent:#0a7c66; --accent-fg:#fff; --accent-soft:#dcf2ec; --warn:#b45309; --warn-soft:#fdf0d9; --bad:#c62828;
  --font:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,system-ui,sans-serif; color-scheme:light;
}
@media (prefers-color-scheme:dark){:root{
  --bg:#000; --card:#1c1c1e; --fg:#f2f2f7; --muted:#9a9aa0; --line:#2c2c2e;
  --accent:#3ccfae; --accent-fg:#04251e; --accent-soft:#0f3a31; --warn:#f5b04c; --warn-soft:#3a2a0c; --bad:#ff6b6b; color-scheme:dark}}
*{box-sizing:border-box}
body{background:var(--bg);color:var(--fg);font:16px/1.4 var(--font);margin:0;
  padding:8px max(16px,env(safe-area-inset-right)) 96px max(16px,env(safe-area-inset-left));-webkit-text-size-adjust:100%}
main{max-width:560px;margin:0 auto}
h1{font-size:30px;line-height:1.1;margin:18px 0 4px;letter-spacing:-.02em}
.sub{color:var(--muted);margin:0 0 16px}
h2{font-size:13px;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);font-weight:600;margin:24px 8px 8px}
.card{background:var(--card);border-radius:14px;overflow:hidden}
.row{padding:12px 16px;border-top:1px solid var(--line);display:flex;gap:12px;align-items:center;min-width:0}
.row:first-child{border-top:0}
.grow{flex:1;min-width:0}
.t{font-weight:600;overflow-wrap:anywhere}
.s{color:var(--muted);font-size:14px;overflow-wrap:anywhere}
.pill{font-size:13px;font-weight:600;padding:4px 10px;border-radius:99px;background:var(--accent-soft);color:var(--accent);white-space:nowrap}
.pill.full{background:var(--line);color:var(--muted)}
.pill.warn{background:var(--warn-soft);color:var(--warn)}
.meter{height:10px;background:var(--line);border-radius:6px;overflow:hidden;margin-top:10px}
.meter>i{display:block;height:100%;background:var(--accent);border-radius:6px}
.hero{padding:16px}
.big{font-size:34px;font-weight:700;letter-spacing:-.02em}
.stats{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-top:12px}
.stats div{font-size:13px;color:var(--muted)}.stats b{display:block;font-size:22px;color:var(--fg)}
label{display:block;font-size:13px;font-weight:600;color:var(--muted);margin:14px 4px 6px}
input,select{width:100%;font:inherit;font-size:16px;color:var(--fg);background:var(--card);border:1px solid var(--line);border-radius:12px;padding:13px 14px;min-height:48px}
input:focus,select:focus,button:focus-visible{outline:2px solid var(--accent);outline-offset:1px}
.stepper{display:flex;align-items:center;gap:16px}
.stepper button{width:48px;height:48px;border-radius:24px;border:1px solid var(--line);background:var(--card);color:var(--fg);font-size:24px}
.stepper b{font-size:24px;min-width:28px;text-align:center}
.btn{width:100%;min-height:50px;border:0;border-radius:14px;background:var(--accent);color:var(--accent-fg);font:600 17px var(--font);margin-top:20px}
.btn.sm{width:auto;min-height:40px;padding:0 16px;margin:0;font-size:15px;border-radius:20px}
.btn.ghost{background:var(--accent-soft);color:var(--accent)}
.btn:disabled{opacity:.45}
.link{background:none;border:0;color:var(--bad);font:500 14px var(--font);padding:8px 0}
.empty{padding:22px 16px;color:var(--muted);text-align:center}
.opts{border:1px solid var(--line);border-radius:12px;margin-top:6px;overflow:hidden;background:var(--card)}
.opts button{display:block;width:100%;text-align:left;background:none;border:0;border-top:1px solid var(--line);color:var(--fg);font:16px var(--font);padding:12px 14px;min-height:46px}
.opts button:first-child{border-top:0}
.opts button[disabled]{color:var(--muted)}
.chosen{display:flex;align-items:center;gap:10px;background:var(--accent-soft);color:var(--accent);border-radius:12px;padding:12px 14px;font-weight:600}
.chosen button{margin-left:auto;background:none;border:0;color:inherit;font:500 14px var(--font);text-decoration:underline}
nav{position:fixed;left:0;right:0;bottom:0;background:var(--card);border-top:1px solid var(--line);display:flex;
  padding:6px max(8px,env(safe-area-inset-right)) calc(6px + env(safe-area-inset-bottom)) max(8px,env(safe-area-inset-left))}
nav button{flex:1;background:none;border:0;color:var(--muted);font:500 11px var(--font);display:flex;flex-direction:column;align-items:center;gap:3px;min-height:52px;border-radius:10px}
nav button svg{width:26px;height:26px;stroke:currentColor;fill:none;stroke-width:1.7;stroke-linecap:round;stroke-linejoin:round}
nav button[aria-selected="true"]{color:var(--accent)}
.note{font-size:13px;color:var(--muted);margin:8px 8px 0}
.toast{position:fixed;left:50%;transform:translateX(-50%);bottom:calc(86px + env(safe-area-inset-bottom));background:var(--fg);color:var(--bg);padding:10px 16px;border-radius:20px;font-size:14px;max-width:90%}
[hidden]{display:none!important}
</style></head>
<body>
<main>
  <h1>Viaje Maschwitz → Retiro</h1>
  <p class="sub">Autos, lugares libres y jóvenes que necesitan viaje.</p>
  <div class="card" style="padding:12px 16px;margin-bottom:8px"><div class="t">Sábado 28/11 · 20:00</div><div class="s">Cada auto sale desde la zona que anota el conductor</div></div>
  <div id="view"></div>
</main>
<nav id="tabs">
  <button data-t="inicio"><svg viewBox="0 0 24 24"><path d="M4 11l8-7 8 7v9H4z"/></svg>Resumen</button>
  <button data-t="auto"><svg viewBox="0 0 24 24"><path d="M5 16V11l2-5h10l2 5v5M5 16h14M5 16v2M19 16v2"/><circle cx="8.5" cy="13.5" r=".6"/><circle cx="15.5" cy="13.5" r=".6"/></svg>Llevo gente</button>
  <button data-t="lugar"><svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="3.5"/><path d="M5 20c1-4 4-6 7-6s6 2 7 6"/></svg>Necesito lugar</button>
</nav>
<div id="toast" class="toast" hidden></div>
<script>
const ZONAS=<?= json_encode($ZONAS, JSON_UNESCAPED_UNICODE|JSON_HEX_TAG) ?>;
const $=s=>document.querySelector(s);
let UID="";try{UID=localStorage.getItem("viaje-uid")||"";if(!UID){UID=Math.random().toString(36).slice(2)+Date.now().toString(36);localStorage.setItem("viaje-uid",UID)}}catch(e){UID=UID||Math.random().toString(36).slice(2)}
const state={tab:"inicio",autos:[],res:[],kids:[],loaded:false,form:{seats:3,zona:0,kid:null,q:""}};
try{const t=localStorage.getItem("viaje-tab");if(t)state.tab=t}catch(e){}

const norm=s=>(s||"").normalize("NFD").replace(/[̀-ͯ]/g,"").toLowerCase().replace(/[^a-z0-9 ]/g," ").replace(/\s+/g," ").trim();
const slug=s=>norm(s).replace(/ /g,"-").slice(0,80);
const zdist=(a,b)=>a===b?0:(a===ZONAS.length-1||b===ZONAS.length-1)?50:Math.abs(a-b);
function el(tag,attrs={},...kids){
  const e=document.createElement(tag);
  for(const[k,v]of Object.entries(attrs)){
    if(k==="class")e.className=v;
    else if(k.startsWith("on"))e.addEventListener(k.slice(2),v);
    else if(v!==false&&v!=null)e.setAttribute(k,v);
  }
  for(const c of kids.flat())if(c!=null&&c!==false)e.append(c.nodeType?c:document.createTextNode(c));
  return e;
}
function toast(m){const t=$("#toast");t.textContent=m;t.hidden=false;clearTimeout(toast.h);toast.h=setTimeout(()=>t.hidden=true,2800)}
const libres=a=>Math.max(0,a.seats-state.res.filter(r=>r.autoId===a.id).length);
function conAuto(id){return state.autos.some(a=>a.kidId===id)||state.res.some(r=>r.kidId===id&&r.autoId)}
const total=()=>state.kids.length||30;

async function api(name,body){
  const r=await fetch("?api="+name,{method:body?"POST":"GET",headers:{"Content-Type":"application/json"},body:body?JSON.stringify({...body,uid:UID}):undefined});
  const j=await r.json().catch(()=>({error:"Sin conexión"}));
  if(!r.ok)throw new Error(j.error||"Error");
  state.kids=j.kids;state.autos=j.autos;state.res=j.res;state.loaded=true;
  return j;
}
const refresh=()=>api("state"+(UID?"&uid="+encodeURIComponent(UID):""),null).then(render).catch(()=>{});
async function act(name,body,ok){try{await api(name+"&uid="+encodeURIComponent(UID),body);if(ok)toast(ok);return true}catch(e){toast(e.message);return false}}

function render(){
  document.querySelectorAll("#tabs button").forEach(b=>b.setAttribute("aria-selected",b.dataset.t===state.tab));
  const v=$("#view");
  if(v.dataset.t===state.tab&&state.tab!=="inicio"&&v.contains(document.activeElement)&&document.activeElement.matches("input,select"))return;
  v.dataset.t=state.tab;v.replaceChildren();
  if(!state.loaded){v.append(el("div",{class:"card empty"},"Cargando…"));return}
  if(state.tab==="inicio")vistaInicio(v);
  if(state.tab==="auto")vistaAuto(v);
  if(state.tab==="lugar")vistaLugar(v);
}

function picker(){
  const wrap=el("div");
  const draw=()=>{
    wrap.replaceChildren();
    const sel=state.form.kid;
    if(sel){wrap.append(el("div",{class:"chosen"},sel.name,el("button",{type:"button",onclick:()=>{state.form.kid=null;state.form.q="";draw()}},"Cambiar")));return}
    const inp=el("input",{id:"kid-q",type:"search",placeholder:"Escribí el apellido o el nombre",autocomplete:"off",value:state.form.q});
    const box=el("div");
    const list=()=>{
      box.replaceChildren();
      const q=norm(inp.value);state.form.q=inp.value;
      if(!q)return;
      const hits=state.kids.filter(k=>norm(k.name).includes(q)).slice(0,6);
      const o=el("div",{class:"opts"});
      hits.forEach(k=>{
        const ya=conAuto(k.id);
        o.append(el("button",{type:"button",disabled:ya||false,onclick:()=>{state.form.kid={id:k.id,name:k.name};draw()}},k.name+(ya?" · ya tiene lugar":"")));
      });
      if(!hits.length)o.append(el("div",{class:"s",style:"padding:12px 14px"},"No aparece en el listado."));
      box.append(o);
    };
    inp.addEventListener("input",list);
    wrap.append(inp,box);list();
  };
  draw();return wrap;
}

function vistaInicio(v){
  const T=total();
  const conViaje=state.kids.filter(k=>conAuto(k.id)).length;
  const lib=state.autos.reduce((n,a)=>n+libres(a),0);
  const falta=Math.max(0,T-conViaje);
  v.append(el("div",{class:"card hero"},
    el("div",{class:"big"},conViaje," de ",T),
    el("div",{class:"s"},"jóvenes con viaje asegurado"),
    el("div",{class:"meter"},el("i",{style:"width:"+Math.min(100,conViaje/T*100)+"%"})),
    el("div",{class:"stats"},
      el("div",{},el("b",{},state.autos.length),"autos"),
      el("div",{},el("b",{},lib),"lugares libres"),
      el("div",{},el("b",{},falta),"sin lugar"))));
  if(falta>lib)v.append(el("p",{class:"note"},"Faltan "+(falta-lib)+" lugares: hacen falta más autos."));
  v.append(el("h2",{},"Autos"));
  if(!state.autos.length)v.append(el("div",{class:"card empty"},"Todavía no hay autos anotados. Sumá el tuyo en “Llevo gente”."));
  else{
    const c=el("div",{class:"card"});
    state.autos.slice().sort((a,b)=>a.zona-b.zona).forEach(a=>{
      const l=libres(a),kids=state.res.filter(r=>r.autoId===a.id);
      c.append(el("div",{class:"row"},
        el("div",{class:"grow"},
          el("div",{class:"t"},a.driver),
          el("div",{class:"s"},"Sale de: "+ZONAS[a.zona]+(a.ref?" · "+a.ref:"")),
          el("div",{class:"s"},"Lleva a: "+[a.kid,...kids.map(k=>k.kid)].join(", "))),
        el("span",{class:"pill"+(l?"":" full")},l?l+(l===1?" lugar":" lugares"):"Completo")));
    });
    v.append(c);
  }
  const sin=state.kids.filter(k=>!conAuto(k.id));
  if(sin.length){
    v.append(el("h2",{},"Sin auto todavía ("+sin.length+")"));
    const c=el("div",{class:"card"});
    sin.forEach(k=>{
      const r=state.res.find(x=>x.kidId===k.id);
      c.append(el("div",{class:"row"},el("div",{class:"grow"},el("div",{class:"t"},k.name),r?el("div",{class:"s"},ZONAS[r.zona]):null),r?el("span",{class:"pill warn"},"espera auto"):null));
    });
    v.append(c);
  }
}

function zonaSelect(id){
  const s=el("select",{id});
  ZONAS.forEach((z,i)=>s.append(el("option",{value:i},z)));
  s.value=state.form.zona;
  s.addEventListener("change",()=>{state.form.zona=+s.value;if(state.tab==="lugar"){const q=$("#kid-q");if(q)state.form.q=q.value;$("#view").dataset.t="";render()}});
  return s;
}
function campo(id,lab,ph){return[el("label",{for:id},lab),el("input",{id,type:"text",placeholder:ph,autocomplete:"off"})]}
function stepper(key,min,max){
  const out=el("b",{},state.form[key]);
  const mk=(txt,d)=>el("button",{type:"button","aria-label":d>0?"más":"menos",onclick:()=>{state.form[key]=Math.min(max,Math.max(min,state.form[key]+d));out.textContent=state.form[key]}},txt);
  return el("div",{class:"stepper"},mk("−",-1),out,mk("+",1));
}

function vistaAuto(v){
  const mine=state.autos.filter(a=>a.mine);
  if(mine.length){
    v.append(el("h2",{},"Tus autos"));
    const c=el("div",{class:"card"});
    mine.forEach(a=>{
      const kids=state.res.filter(r=>r.autoId===a.id);
      c.append(el("div",{class:"row",style:"align-items:flex-start"},
        el("div",{class:"grow"},
          el("div",{class:"t"},"Salís de: "+ZONAS[a.zona]+(a.ref?" · "+a.ref:"")),
          el("div",{class:"s"},libres(a)+" libres de "+a.seats),
          el("div",{class:"s",style:"margin-top:6px;font-weight:600;color:var(--fg)"},"Tenés que llevar a:"),
          el("div",{class:"s"},"• "+a.kid+" (tu hije)"),
          ...kids.map(k=>el("div",{class:"s"},"• "+k.kid+" · vive en "+ZONAS[k.zona])),
          kids.length?null:el("div",{class:"s"},"Todavía nadie reservó lugar.")),
        el("button",{class:"link",onclick:async()=>{if(await act("del_auto",{id:a.id},"Auto borrado"))render()}},"Borrar")));
    });
    v.append(c);
  }
  v.append(el("h2",{},"Sumar un auto"));
  state.form.kid=null;state.form.q="";
  const f=el("div",{class:"card",style:"padding:4px 16px 16px"});
  f.append(...campo("a-name","Tu nombre","Ej: Laura Pérez"),
    el("label",{for:"kid-q"},"Tu hije (viaja con vos)"),picker(),
    el("label",{for:"a-zona"},"Zona de salida"),zonaSelect("a-zona"),
    ...campo("a-ref","Desde dónde salís: calle o referencia (sin número exacto)","Ej: Av. San Martín y Ruta 26"),
    el("label",{},"Lugares libres para otros jóvenes"),stepper("seats",1,8));
  const b=el("button",{class:"btn",type:"button",onclick:async()=>{
    const driver=$("#a-name").value.trim(),ref=$("#a-ref").value.trim(),k=state.form.kid;
    if(!driver){toast("Falta tu nombre");return}
    if(!k){toast("Elegí a tu hije en la lista");return}
    if(!ref){toast("Falta desde dónde salís");return}
    b.disabled=true;
    if(await act("add_auto",{driver,kidId:k.id,kid:k.name,zona:state.form.zona,ref,seats:state.form.seats},"Auto anotado")){state.tab="inicio";render()}
    b.disabled=false;
  }},"Anotar mi auto");
  f.append(b);v.append(f);
}

function vistaLugar(v){
  const mias=state.res.filter(r=>r.mine);
  if(mias.length){
    v.append(el("h2",{},"Tus reservas"));
    const c=el("div",{class:"card"});
    mias.forEach(r=>{
      const a=state.autos.find(x=>x.id===r.autoId);
      c.append(el("div",{class:"row"},
        el("div",{class:"grow"},el("div",{class:"t"},r.kid),el("div",{class:"s"},a?"Va con "+a.driver+" · sale de "+ZONAS[a.zona]+(a.ref?" · "+a.ref:""):"Sin auto todavía")),
        el("button",{class:"link",onclick:async()=>{if(await act("del_res",{id:r.id},"Reserva borrada"))render()}},"Borrar")));
    });
    v.append(c);
  }
  v.append(el("h2",{},"Buscar lugar para tu hije"));
  const f=el("div",{class:"card",style:"padding:4px 16px 16px"});
  f.append(el("label",{for:"kid-q"},"Joven"),picker(),
    el("label",{for:"p-zona"},"Zona donde vivís"),zonaSelect("p-zona"));
  v.append(f);
  v.append(el("h2",{},"Autos más cerca de tu casa"));
  const c=el("div",{class:"card"});
  const lista=state.autos.filter(a=>libres(a)>0).sort((a,b)=>zdist(state.form.zona,a.zona)-zdist(state.form.zona,b.zona));
  if(!lista.length)c.append(el("div",{class:"empty"},"No hay autos con lugar libre todavía."));
  lista.forEach(a=>{
    const d=zdist(state.form.zona,a.zona);
    const cerca=d===0?"Misma zona":d>=50?"Zona sin calcular":d===1?"Zona vecina":"A "+d+" zonas";
    c.append(el("div",{class:"row"},
      el("div",{class:"grow"},el("div",{class:"t"},a.driver),el("div",{class:"s"},ZONAS[a.zona]+(a.ref?" · "+a.ref:"")),el("div",{class:"s"},cerca+" · "+libres(a)+" libres")),
      el("button",{class:"btn sm",type:"button",onclick:()=>reservar(a)},"Reservar")));
  });
  v.append(c);
  v.append(el("button",{class:"btn ghost",type:"button",onclick:()=>reservar(null)},"Anotarme sin auto por ahora"));
  v.append(el("p",{class:"note"},"Si no hay auto cerca, anotate así y quien tenga lugar lo va a ver en el resumen."));
}

async function reservar(a){
  const k=state.form.kid;
  if(!k){toast("Elegí al joven en la lista");return}
  if(await act("add_res",{kidId:k.id,kid:k.name,zona:state.form.zona,autoId:a?a.id:null},a?"Reservado con "+a.driver:"Anotado, falta asignar auto")){state.form.kid=null;state.tab="inicio";render()}
  else{await refresh()}
}

document.querySelectorAll("#tabs button").forEach(b=>b.addEventListener("click",()=>{
  state.tab=b.dataset.t;try{localStorage.setItem("viaje-tab",state.tab)}catch(e){}
  $("#view").dataset.t="";render();window.scrollTo(0,0)}));
render();refresh();setInterval(refresh,10000);
</script>
</body></html>
