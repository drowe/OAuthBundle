<?php

namespace OnePlusOne\OAuthBundle;

use Symfony\Component\HttpFoundation\Session as HttpSession;

use OnePlusOne\OAuthBundle\Services\Service;

class Session
{
	protected $session;
	
	protected $services = array();
	
	public function __construct(HttpSession $session)
	{
		$this->session = $session;
	}
	
	public function addService(Service $service)
	{
		$this->services[$service->getAlias()] = $service;
	}
	
	public function isAuthenticated()
	{
		return $this->session->has('oauth_bundle/authenticated');
	}
	
	public function getUser()
	{
		if($this->session->has('oauth_bundle/user'))
			return $this->session->get('oauth_bundle/user');
		else
			return null;
	}
	
	public function getService($service)
	{
		if(!isset($this->services[$service]))
			throw new \InvalidArgumentException(sprintf('No service found with name "%s"', $service));
		
		return $this->services[$service];
	}
	
	public function hasService($service)
	{
		return isset($this->services[$service]);
	}
	
	public function getServiceName()
	{
		if($this->session->has('oauth_bundle/service'))
			return $this->session->get('oauth_bundle/service');
		else
			return null;
	}
	
	public function getLoginUrl($service, $succesUrl, $failureUrl)
	{
		return $this->getService($service)->getLoginUrl($succesUrl, $failureUrl);
	}
	
	public function getLogoutUrl($service, $succesUrl)
	{
		return $this->getService($service)->getLogoutUrl($succesUrl);
	}
	
	public function verifyCallback($service) 
	{
		try
		{
			$user = $this->getService($service)->getUser();
			
			$this->session->set('oauth_bundle/user', $user);
			$this->session->set('oauth_bundle/authenticated', true);
			$this->session->set('oauth_bundle/service', $service);
			
			return true;
		} 
		catch (\Exception $e)	
		{
			return false;
		}
	}
	
	public function logout()
	{
		$this->session->remove('oauth_bundle/user');
		$this->session->remove('oauth_bundle/authenticated');
		$this->session->remove('oauth_bundle/service');
		
		return true;
	}
}