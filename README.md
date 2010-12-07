OAuthBundle
===========

OAuthBundle for Symfony2 that uses Twitter and Facebook

Installation
============

  1. Add my fork of twitteroauth, the facebook php sdk and the OAuthBundle als submodule

		$ git submodule add git://github.com/ruudk/twitteroauth.git src/vendor/twitteroauth
		
		$ git submodule add git://github.com/facebook/php-sdk.git src/vendor/facebook-php-sdk

  		$ git submodule add git://github.com/ruudk/OAuthBundle.git src/Bundle/OAuthBundle


  2. Add this bundle to your application's kernel:

          // application/ApplicationKernel.php
          public function registerBundles()
          {
              return array(
                  // ...
                  new Bundle\OAuthBundle\OAuthBundle(),
                  // ...
              );
          }

  3. Add TwitterOAuth to the autoloader

		$loader->registerNamespaces(array(
			// ...
		    'TwitterOAuth'                   => $vendorDir.'/twitteroauth',
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

  5. Load the routings in your routing.yml:

		OAuthBundle:
		  resource: OAuthBundle/Resources/config/routing.yml

Usage
=====

Go to http://localhost/login and choose your service and connect!

Enjoy

- Ruud