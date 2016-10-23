ImageForum
==========


A simple Image Forum using symfony 3.1.


Installation
============


Nginx setup:
[Example of Nginx](docs/nginx.txt)

Execute composer install, to install of the dependencies

Change your parameters.yml inside app/config, to fullfill your db.
In the parameter_test.yml we setup a sqlite connection inside the cache dir /var/cache with the name of 
image_forum.sqlite
This is the db we are going to use in the tests

Create the db with:
php bin/console doctrine:database:create

Create the schema:
php bin/console doctrine:schema:create

If you want to generate some fixtures:
php bin/console doctrine:fixtures:load

(If you want to generate the test env, use the same previous commands with --env="test")


FrontEnd
========

I used [initializr](http://initializr.com) with the responsive template / bootstrap

Bonus: Infinite Paginator
In order to make the infinite paginator I used the infite paginator jquery plugin (http://jscroll.com/)


Dependencies
============
liuggio/excelbundle

FileUploader
============
In order to upload the services, inside the AppBundle / Services we have create a service that will manage the uploads
to the folder we specified in the parameters.
(We load this services, via a loader extension inside the AppBundle/DependencyInjection)
So, in the future if we want to call a rabbit to redimensionate this images, or other, we can do via this service.

