# backup_framework
==================
### Database

See test.sql is found in docs directory

### Login
- user: admin
- pass: admin

### Setting Up Your VHOST

The following is a sample VHOST you might want to consider for your project.

NameVirtualHost localhost:1234

<VirtualHost localhost:1234>
    DocumentRoot "<FULL_PATH_TO_PROJECT_TO_PUBLIC_DIRECTORY>"
    ServerName localhost
    ServerAlias localhost
	<Directory "<FULL_PATH_TO_PROJECT_TO_PUBLIC_DIRECTORY>">
		Options Indexes FollowSymLinks Includes ExecCGI
		AllowOverride All
		Order allow,deny
		Allow from all
	</Directory>
</VirtualHost>