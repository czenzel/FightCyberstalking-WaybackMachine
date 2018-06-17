# Internet Wayback Archive - Blocking using EICAR Test File

## Code Changes Required

* E-mail addresses (from and to) with proper SPF in account
* Link to one page in e-mail to archive.org

## Enhancements

* Auto e-mail adovcate groups, officals to call for action on cyber stalking

## Wordpress and cPanel

Example Usage:

.htaccess

``
ErrorDocument 403 /wp-content/.../cybercrime/403.php

<Files 403.php>
order allow,deny
allow from all
</Files>

<IfModule mod_rewrite.c>
RewriteEngine on
RewriteBase /
RewriteCond %{REMOTE_ADDR} ^(207\.241\.|208\.70\.)
RewriteRule .* wp-content/..../cybercrime/403.php [QSA,NC,L]
</IfModule>

<IfModule mod_rewrite.c>
RewriteEngine on
RewriteBase /
RewriteCond %{REQUEST_URI} !(c403\.php|[\/]?wp-content\/...\/cybercrime\/)
RewriteCond %{HTTP:VIA}                 !^$ [OR]
RewriteCond %{HTTP:FORWARDED}           !^$ [OR]
RewriteCond %{HTTP:USERAGENT_VIA}       !^$ [OR]
RewriteCond %{HTTP:X_FORWARDED_FOR}     !^$ [OR]
RewriteCond %{HTTP:PROXY_CONNECTION}    !^$ [OR]
RewriteCond %{HTTP:XPROXY_CONNECTION}   !^$ [OR]
RewriteCond %{HTTP:HTTP_PC_REMOTE_ADDR} !^$ [OR]
RewriteCond %{HTTP:X-NEWRELIC-ID}       !^$ [OR]
RewriteCond %{HTTP:HTTP_CLIENT_IP}      !^$
RewriteRule ^(.*)$ wp-content/...//cybercrime/403.php?na=1 [L,R=302]
</IfModule>
``
