# Deny access to everything by default
Order Deny,Allow
deny from all

# Allow access to html files
<Files *.html>
    allow from all
</Files>

# Deny access to sub directory
<Files subdirectory/*>
    deny from all
</Files>
