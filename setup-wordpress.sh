#!/bin/bash

# Génère uploads.ini avec les variables d'environnement
cat > /usr/local/etc/php/conf.d/uploads.ini <<EOL
file_uploads = On
upload_max_filesize = ${UPLOAD_MAX_FILESIZE}
post_max_size = ${POST_MAX_SIZE}
memory_limit = ${MEMORY_LIMIT}
max_execution_time = ${MAX_EXECUTION_TIME}
EOL

# Fixe les permissions
chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html

# Lance l'entrypoint d'origine avec tous les arguments
exec php-fpm
