# INSTALLATION
==============
### Steps
- Download and extract files
- Setup a VHOST. The following is a sample VHOST you might want to consider for your project.

```conf
NameVirtualHost localhost:1234

<VirtualHost localhost:1234>
    DocumentRoot "<FULL_PATH_TO_PROJECT_PUBLIC_DIRECTORY>"
    ServerName localhost
    ServerAlias localhost
	<Directory "<FULL_PATH_TO_PROJECT_PUBLIC_DIRECTORY>">
		Options Indexes FollowSymLinks Includes ExecCGI
		AllowOverride All
		Order allow,deny
		Allow from all
	</Directory>
</VirtualHost>
```
- Create file ```/library/Db/Config.php``` with class ```Db_Config``` and the method ```config``` must return what is mention in the comments in file ```/library/Db/Db.php```
- Create database with the same name you set in your ```/library/Db/Config.php``` file
- Modify variables ```additionalParams``` in file ```/application/configs/application.ini``` with what you want
- Modify variables in file ```/public/install.php``` with what you want
- Write in url ```http://<HOST_OR_IP>/install.php```
- Login with user and pass that you set