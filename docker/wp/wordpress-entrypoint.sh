#!/bin/sh
set -eu

if [ ! -f /var/www/html/index.php ]; then
  echo "Bootstrapping WordPress core into /var/www/html..."
  cp -a /usr/src/wordpress/. /var/www/html/
fi

if [ ! -f /var/www/html/.htaccess ] || ! grep -q 'RewriteRule' /var/www/html/.htaccess; then
  cp /usr/local/share/wordpress/.htaccess /var/www/html/.htaccess
fi

/usr/local/bin/fix-permissions.sh
/usr/local/bin/sync-local-plugins.sh

exec docker-entrypoint.sh apache2-foreground
