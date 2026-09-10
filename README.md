# Backup delbonete-dg.com.ar

Este repo guarda backups del sitio WordPress. Como el acceso saliente de la sesión
remota de Claude Code está bloqueado por política (no llega ni a HTTPS 443 del
dominio ni al panel en :8443, y FTP tampoco pasa por ese proxy), el backup se
genera corriendo estos pasos en tu máquina.

## 1. Archivos (FTP)

```bash
export FTP_HOST="delbonete-dg.com.ar"
export FTP_USER="delbonete"
export FTP_PASS='tu-contraseña-ftp'   # comillas simples si tiene caracteres especiales
./scripts/backup-ftp.sh
```

Las credenciales van solo como variables de entorno en tu terminal, nunca en
archivos de este repo.

Genera `backups/files-<fecha>.tar.gz` con todo lo que haya en la cuenta FTP
(típicamente `public_html` / `wp-content`, etc).

## 2. Base de datos

No hay WP-CLI/SSH, así que la forma más directa es una de estas dos:

**Opción A — phpMyAdmin desde el panel (recomendado):**
1. Entrá a `https://www.delbonete-dg.com.ar:8443` con `delbonetedg` / la clave del panel.
2. Buscá la base de datos del sitio (Databases / Bases de datos) y abrí phpMyAdmin.
3. Exportá la base completa (Export → SQL) y guardá el archivo como
   `backups/db-<fecha>.sql`.

**Opción B — plugin de WordPress:**
1. Entrá a `https://delbonete-dg.com.ar/wp-login.php` con `delbonete` / la clave de wp-admin.
2. Instalá un plugin de export de DB (ej. "WP-DBManager" o el export nativo de
   Herramientas → Exportar si te alcanza) y descargá el dump.

## 3. Subir el backup a este repo

Los `.tar.gz` / `.sql` pueden pesar bastante — si superan ~50-100MB conviene NO
commitearlos directo a git (o usar Git LFS) para no inflar el repo. Alternativa:
subirlos a un storage aparte (Drive, Dropbox) y dejar acá solo un link.

```bash
git add backups/ scripts/ README.md
git commit -m "Backup del sitio delbonete-dg.com.ar"
git push -u origin claude/backup-delbonete-website-sugsmh
```
