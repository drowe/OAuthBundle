OAuthBundle
===========

OAuthBundle for Symfony2 that uses Twitter and Facebook

Installation
============

  1. Add my fork of twitteroauth, the facebook php sdk and the OAuthBundle as submodule

        git submodule add git://github.com/ruudk/twitteroauth.git vendor/twitteroauth
		
        git submodule add git://github.com/facebook/php-sdk.git vendor/facebook-php-sdk

        git submodule add git://github.com/ruudk/OAuthBundle.git src/OnePlusOne/OAuthBundle


  2. Add this bundle to your application's kernel:

          // application/AppKernel.php
          public function registerBundles()
          {
              return array(
                  // ...
                  new OnePlusOne\OAuthBundle\OAuthBundle(),
                  // ...
              );
          }

  3. Add OnePlusOne and TwitterOAuth to the autoloader

          $loader->registerNamespaces(array(
            // ...
            'OnePlusOne'                     => __DIR__.'/../src',
            'TwitterOAuth'                   => __DIR__.'/../vendor/twitteroauth',
          ));

  4. Configure the `oauth` service in your config:

          # application/config/config.yml
          oauth.facebook:
            appId:  123
            secret: abc
          
            #optional
            req_perms: 'publish_stream'
          
          oauth.twitter:
            key: 123
            secret: abc

  5. Check if the PHP template engine is enabled

          # application/config/config.yml
          app.config:
            // ...
            templating:    { engines: ['php', 'twig'] }

  6. Load the routings in your routing.yml:

          OAuthBundle:
            resource: OAuthBundle/Resources/config/routing.yml

Usage
=====

Go to http://localhost/login and choose your service and connect!

Enjoy

- Ruud