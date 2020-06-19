<IfModule mod_rewrite.c>
Options +FollowSymlinks
RewriteEngine On

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

RewriteRule ^(.*)$ {PROJECTBASEPATH}api.php?request=$1&%{QUERY_STRING} [PT,L]
RewriteRule .* - [E=HTTP_Authorization:%{HTTP:Authorization}]

</IfModule>
