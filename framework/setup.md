Install Ubuntu 16.04.1 LTS

1. Update apt-get
    sudo apt-get update
2. Install apache2 through apt-get
    sudo apt-get install apache2
3. Install mysql-server thought apt-get
    sudo apt-get install mysql-server
4. Install php7 (default in Ubuntu 16) and modules (libapache2-mod-php, php-mcrypt, php-mysql, php-curl)
    sudo apt-get install php libapache2-mod-php, php-mcrypt, php-mysql, php-curl
5. Enable https connections with: sudo a2enmod ssl (sudo a2enmod ssl && sudo service apache2 restart)
6. Change mod for /html/cache/ folder to rwx for all users (chmod -R 777)
7a. Hide folder access to css, img and js files with htaccess AND/OR apache settings (Options -Indexes)
7b. Add pretty urls with apache settings (sudo cp /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/)
8. Edit /etc/php/7.0/apache2/php.ini to manage upload sizes (line 798 - upload_max_filesize = 512M, line 656 - post_max_size = 256M)
