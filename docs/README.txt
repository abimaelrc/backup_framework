README
======

This directory should be used to place project specfic documentation including
but not limited to project notes, generated API/phpdoc documentation, or
manual files generated or hand written.  Ideally, this directory would remain
in your development environment only and should not be deployed with your
application to it's final production location.


Setting Up Your VHOST
=====================

The following is a sample VHOST you might want to consider for your project.

NameVirtualHost localhost:1234

<VirtualHost localhost:1234>
    DocumentRoot "C:/test/public"
    ServerName localhost
    ServerAlias localhost
	<Directory "C:/test/public">
		Options Indexes FollowSymLinks Includes ExecCGI
		AllowOverride All
		Order allow,deny
		Allow from all
	</Directory>
</VirtualHost>


Login Info
==========
User: admin
Pass: admin