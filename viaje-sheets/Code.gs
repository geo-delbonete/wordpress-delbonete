// Viaje Maschwitz -> Retiro. Script vinculado a la planilla "Viaje Retiro 28-11".
// Pestaña 1 ("Joven"): listado de jóvenes. Autos y Reservas se crean solas.

const ZONAS = ["Maschwitz pueblo","La Bota","Villanueva","Cantón / San Matías / Puertos","Escobar","Savio / Ruta 26","Otro"];
const H_AUTOS = ["id","driver","kidId","kid","zona","ref","seats","uid","ts"];
const H_RES = ["id","kidId","kid","zona","autoId","uid","ts"];

function doGet() {
  return HtmlService.createHtmlOutputFromFile("Index")
    .setTitle("Viaje a Retiro")
    .addMetaTag("viewport", "width=device-width, initial-scale=1, viewport-fit=cover");
}

function slugify_(s) {
  return String(s).normalize("NFD").replace(/[̀-ͯ]/g, "").toLowerCase().replace(/[^a-z0-9]+/g, "-").replace(/^-+|-+$/g, "");
}
function sheet_(name, headers) {
  const ss = SpreadsheetApp.getActive();
  let sh = ss.getSheetByName(name);
  if (!sh) { sh = ss.insertSheet(name); sh.appendRow(headers); }
  return sh;
}
function rows_(sh, headers) {
  const v = sh.getDataRange().getValues();
  return v.slice(1).filter(r => r[0] !== "").map(r => { const o = {}; headers.forEach((h, i) => o[h] = r[i]); return o; });
}
function write_(sh, headers, list) {
  sh.clearContents();
  sh.getRange(1, 1, 1, headers.length).setValues([headers]);
  if (list.length) sh.getRange(2, 1, list.length, headers.length).setValues(list.map(o => headers.map(h => o[h] === null || o[h] === undefined ? "" : o[h])));
}
function clean_(s, n) { return String(s || "").replace(/<[^>]*>/g, "").trim().slice(0, n || 80); }

// Única función que llama la página.
function viajeApi(name, body, uid) {
  const lock = LockService.getScriptLock();
  lock.waitLock(20000);
  try {
    uid = clean_(uid, 40);
    body = body || {};
    const shA = sheet_("Autos", H_AUTOS), shR = sheet_("Reservas", H_RES);
    let autos = rows_(shA, H_AUTOS), res = rows_(shR, H_RES);
    const kids = SpreadsheetApp.getActive().getSheets()[0].getDataRange().getValues().slice(1)
      .map(r => String(r[0]).trim()).filter(Boolean).map(n => ({ id: slugify_(n), name: n }));

    const libres = a => Math.max(0, Number(a.seats) - res.filter(r => r.autoId === a.id).length);
    const conAuto = k => autos.some(a => a.kidId === k) || res.some(r => r.kidId === k && r.autoId);
    const dropWaiting = k => { res = res.filter(r => !(r.kidId === k && !r.autoId)); };
    let err = null, changed = false;

    if (name === "add_auto") {
      const driver = clean_(body.driver), ref = clean_(body.ref, 120), kidId = clean_(body.kidId, 100), kid = clean_(body.kid, 100);
      const zona = Number(body.zona), seats = Number(body.seats);
      if (!driver || !kidId || !kid || !ref || !uid || !(seats >= 1 && seats <= 8) || !(zona >= 0 && zona < ZONAS.length)) err = "Faltan datos";
      else if (conAuto(kidId)) err = "Ese joven ya tiene lugar";
      else { dropWaiting(kidId); autos.push({ id: Utilities.getUuid().slice(0, 8), driver, kidId, kid, zona, ref, seats, uid, ts: Date.now() }); changed = true; }
    } else if (name === "add_res") {
      const kidId = clean_(body.kidId, 100), kid = clean_(body.kid, 100), zona = Number(body.zona), autoId = body.autoId || "";
      if (!kidId || !kid || !uid || !(zona >= 0 && zona < ZONAS.length)) err = "Faltan datos";
      else if (conAuto(kidId)) err = "Ese joven ya tiene lugar";
      else {
        if (autoId) {
          const a = autos.filter(x => x.id === autoId)[0];
          if (!a) err = "Ese auto ya no existe"; else if (libres(a) < 1) err = "Se completó ese auto";
        }
        if (!err) { dropWaiting(kidId); res.push({ id: Utilities.getUuid().slice(0, 8), kidId, kid, zona, autoId, uid, ts: Date.now() }); changed = true; }
      }
    } else if (name === "del_auto") {
      autos = autos.filter(a => !(a.id === body.id && a.uid === uid));
      const ids = autos.map(a => a.id);
      res = res.map(r => (r.autoId && ids.indexOf(r.autoId) < 0) ? Object.assign(r, { autoId: "" }) : r);
      changed = true;
    } else if (name === "del_res") {
      res = res.filter(r => !(r.id === body.id && r.uid === uid));
      changed = true;
    }

    if (err) return { error: err };
    if (changed) { write_(shA, H_AUTOS, autos); write_(shR, H_RES, res); }
    const mark = list => list.map(x => { const o = Object.assign({}, x); o.mine = (x.uid === uid); delete o.uid; if ("autoId" in o) o.autoId = o.autoId || null; return o; });
    return { kids, autos: mark(autos), res: mark(res) };
  } finally {
    lock.releaseLock();
  }
}
