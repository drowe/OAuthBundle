<?php

namespace Bundle\OAuthBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;


/**
 *
 * @author Fabien Potencier <fabien.potencier@symfony-project.com>
 */
class OAuthExtension extends Extension
{
	protected function loadDefaults($container)
	{
		if (!$container->hasDefinition('oauth.session')) {
			$loader = new XmlFileLoader($container, __DIR__.'/../Resources/config');
			$loader->load('session.xml');
		}
		
		if (!$container->hasDefinition('oauth.views')) {
			$loader = new XmlFileLoader($container, __DIR__.'/../Resources/config');
			$loader->load('views.xml');
		}
	}
	
	/**
     * Load facebook configuration
     *
     * @param array            $config    An array of configuration settings
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    public function facebookLoad($config, ContainerBuilder $container)
    {
		$this->loadDefaults($container);

		$loader = new XmlFileLoader($container, __DIR__.'/../Resources/config');
		$loader->load('facebook.xml');
		
		if (isset($config['service']['class']))
			$container->setParameter('oauth.facebook.service.class', $config['service']['class']);
			
		if (isset($config['req_perms']))
			$container->setParameter('oauth.facebook.req_perms', $config['req_perms']);
		
		if (isset($config['appId']))
			$container->setParameter('oauth.facebook.appId', $config['appId']);

		if (isset($config['secret']))
			$container->setParameter('oauth.facebook.secret', $config['secret']);

		if (isset($config['cookie']))
			$container->setParameter('oauth.facebook.cookie', $config['cookie']);

		if (isset($config['file']))
			$container->setParameter('oauth.facebook.api.file', $config['file']);
	}
	
	/**
     * Load twitter configuration
     *
     * @param array            $config    An array of configuration settings
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    public function twitterLoad($config, ContainerBuilder $container)
    {
		$this->loadDefaults($container);
	
		$loader = new XmlFileLoader($container, __DIR__.'/../Resources/config');
		$loader->load('twitter.xml');
		
		if (isset($config['service']['class']))
			$container->setParameter('oauth.twitter.service.class', $config['service']['class']);
			
		if (isset($config['api']['class']))
			$container->setParameter('oauth.twitter.api.class', $config['api']['class']);
			
		if (isset($config['key']))
			$container->setParameter('oauth.twitter.key', $config['key']);

		if (isset($config['secret']))
			$container->setParameter('oauth.twitter.secret', $config['secret']);
	}

    /**
     * Returns the base path for the XSD files.
     *
     * @return string The XSD base path
     */
    public function getXsdValidationBasePath()
    {
        return __DIR__.'/../Resources/config/schema';
    }

    public function getNamespace()
    {
        return 'http://www.symfony-project.org/schema/dic/oauth';
    }

    public function getAlias()
    {
        return 'oauth';
    }
}