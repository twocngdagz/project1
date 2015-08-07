Clinic Rise

TODO:
- Cleanup steps - we need a common env for dev & test (SQL, email sevrer etc) - need to take card about it
- php artisan serve should also work so you don't need to setup a full server (seems to work) - not nessesary imho(brcomm)
- need to cover composer update step - this is needed on any new deploy - done

Steps needed to setup project everywhere: 

1) clone repo

2) edit bootstrap/start.php
  find this code and add yourth enviroments: 
```
       $env = $app->detectEnvironment(array(

        'local' => array('VortexMobile'),  // VortexMobile - is my notebook`s hostname 
        'servername' => array('SomeServerHostname'), // that is yourth environment example

       ));
```
3) go to app/config and create directory like yourth environment(all pathes for unixway, if winway use other slashes/commands) 
   mkdir servername
   then copy all files from local/ directory(my enviroment) to yourth servername/ 
   
4) go to your environment directory app/config/servername/  and edit a few vars in files(all variables well documented)
```
  app.php: 
     'debug' => true,  // need false if it is production
     'url' => 'http://hostname',
     'timezone' => 'UTC',
  database.php(db access here):
      'mysql' => array(
            'driver'    => 'mysql',
            'host'      => '127.0.0.1',
            'database'  => 'clinicrise',
            'username'  => 'clinicrise',
            'password'  => 'clinicrise',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
       ),
  mail.php: well documented options about how project will send mails. login pass smtp server.
```
Then you need there app/storage have write permissions for webserver
       
Steps 2-4 needed for correct work of artisan in console



5) if mysql working - now we ready to migrate database and setup first settings
   go to your laravel directory where artisan and composer.phar(can be downloaded from https://getcomposer.org/) lie

```
   php composer.phar update    // update all components to the latest state in project
   php artisan key:generate      // regeneration secret keys
   php artisan migrate             // migrating db-schema to database
   php artisan db:seed             // seeding created database with initial info
```

5.1) For those who need to start server from console, check php install first:

```
  PHP >= 5.4
  MCrypt PHP Extension
```
 Then you can after all try to start localfastdevserver, where artisan lie:
 
```
  php artisan serve
```


 
 
6) if all above successful: set in you webserver DocumentRoot to public/ directory in project

7) setup your webserver for "pretty" urls in apache/nginx without index.php in query

  If Apache, you have public/.htaccess
  
```
  Options +FollowSymLinks
  RewriteEngine On

  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteRule ^ index.php [L]
```

  If Nginx, edit your site config add this directive
  
```
  location / {
       try_files $uri $uri/ /index.php?$query_string;
  }
```
