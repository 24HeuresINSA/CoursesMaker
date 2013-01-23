# CoursesMaker #

## Installation ##

### 0) Requirements

* Apache
* PHP 5.3 
* PHP Intl extension
* PHP APC or another accelerator
* MySql database 

### 1) Retrieve files

Upload files, then run ```composer update``` (if you don't have composer take a look [here](http://getcomposer.org) - you can just download the .phar archive)

### 2) Configuration

Copy ```app/config/parameters.yml.dist``` to ```app/config/parameters.yml```, then edit with your values.

### 3) Database

Update your database schema with the command ```app/console doctrine:schema:update --force```

