# fony-php
A Rapid development PHP API framework

Welcome to Fony API PHP framework. The whole idea of this project is to allow the fast development of APIs or PoC over data definitions.

## Basic Installation
1. Initialize your project using Composer:
```
$ composer init
```
2. Add the fony dependency:
```
$ composer require geekcow/fony
```
3. Add the installation script into the `composer.json` file:
```json
  "scripts": {
     "setup-fony": "Geekcow\\Fony\\Installer\\Setup::init"
  }
```
4. Proceed to the installation script:
```
$ composer run-script setup-fony
```

## Requirements:
Currently Fony API has support for `apache` and `mysql`. However there is work in progress for `nginx` and multiple DBMS (via dbCore)

Also need to have installed `mod_rewrite` module for Apache

Additionally, need to have the following changes in your directory configuration for your virtual server in Apache:
```
  <Directory /var/www/yourfolderlocationforyourproject>
      Options FollowSymLinks
      AllowOverride All
      Require all granted
  </Directory>
```
Note we removed the `Indexes` option from the directory, in order to not display the tree view.

### PHP Built-in server
You can now run a local instance of your built API using a shell command in your root folder
```
$ ./fony-serve.sh {PORT}
```
The default port is `8080` but you can customize your own port

If you cannot execute the shell command, you can manually use the php command to serve your own application.
You need to be aware as Fony overrides the URL requests, you need to use the custom router for the built-in PHP server.
The router is included as a hidden file as `.router.php` in your root folder. 
```
$ php -S localhost:8080 .router.php
```

### Docker
There is some work creating a docker image to host your Fony implementation. That will be available soon.



## Notes:
June 2020: There is still a lot of work to do.
* Unit tests are not done
* Tooling is still in progress
* I want to add a sample of a complete implementation in the Installer.
* Wiki only has titles.

## Contributing

1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Add some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create new Pull Request
