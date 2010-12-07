<?php

namespace Bundle\OAuthBundle\Services;

class Facebook extends Service 
{
	public $api;
	
	protected $req_perms;
	
	public function __construct($api, $req_perms)
	{
		$this->api = $api;
		
		$this->req_perms = $req_perms;
	}
	
	public function getLoginUrl($succesUrl, $failureUrl)
	{
		return $this->api->getLoginUrl(array(
			'next' 			=> $succesUrl,
			'cancel_url' 	=> $failureUrl,
			'req_perms'		=> $this->req_perms
		));
	}
	
	public function getLogoutUrl($succesUrl)
	{
		return $this->api->getLogoutUrl(array(
			'next' 			=> $succesUrl,
		));
	}
	
	public function getUser()
	{
		try {
			$user = $this->api->api('/me');
			
			return array(
				'id'		=> $user['id'],
				'name'		=> $user['name'],
				'url'		=> $user['link'],
				'avatar'	=> 'http://graph.facebook.com/' . $user['id'] . '/picture',
				'extra'		=> $user
			);
		} catch (\Exception $e) {
			throw new $e;
		}
	}
	
	public function getAlias()
	{
		return 'facebook';
	}
}