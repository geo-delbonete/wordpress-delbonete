#!/usr/bin/env bash
# Backup de archivos del sitio vía FTP (mirror completo a carpeta local).
# Requiere lftp instalado (macOS: brew install lftp | Debian/Ubuntu: apt install lftp).
#
# Uso:
#   export FTP_HOST="delbonete-dg.com.ar"
#   export FTP_USER="delbonete"
#   export FTP_PASS='tu-contraseña-ftp'   # entre comillas simples si tiene caracteres especiales
#   ./scripts/backup-ftp.sh
#
# No hardcodear las credenciales acá ni commitear este archivo con valores reales.

set -euo pipefail

: "${FTP_HOST:?Falta FTP_HOST}"
: "${FTP_USER:?Falta FTP_USER}"
: "${FTP_PASS:?Falta FTP_PASS}"

STAMP="$(date +%Y%m%d-%H%M%S)"
OUT_DIR="backups/files-${STAMP}"

mkdir -p "$OUT_DIR"

echo "Descargando archivos del sitio por FTP a ${OUT_DIR} ..."

lftp -u "${FTP_USER},${FTP_PASS}" "ftp://${FTP_HOST}" <<EOF
set ftp:ssl-allow yes
set ssl:verify-certificate no
mirror --verbose --parallel=4 . "${OUT_DIR}"
bye
EOF

echo "Listo. Comprimiendo..."
tar -czf "backups/files-${STAMP}.tar.gz" -C "backups" "files-${STAMP}"
rm -rf "$OUT_DIR"

echo "Backup de archivos en: backups/files-${STAMP}.tar.gz"
