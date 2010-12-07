<?php

namespace Bundle\OAuthBundle\Services;

use Symfony\Component\HttpFoundation\Session,
	Symfony\Component\HttpFoundation\Request;

abstract class Service 
{
	protected $session;
	
	protected $request;
	
	public function setSession(Session $session)
	{
		$this->session = $session;
	}
	
	public function setRequest(Request $request)
	{
		$this->request = $request;
	}
	
	abstract public function getLoginUrl($succesUrl, $failureUrl);
	
	abstract public function getLogoutUrl($succesUrl);
	
	abstract public function getUser();
	
	abstract public function getAlias();
}