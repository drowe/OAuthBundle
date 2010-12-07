<?php

namespace Bundle\OAuthBundle\Services;

class Twitter extends Service 
{
	public $api;
	
	public function __construct($api)
	{
		$this->api = $api;
	}
	
	protected function clearSession()
	{
		$this->session->remove('oauth_bundle/twitter/request/oauth_token');
		$this->session->remove('oauth_bundle/twitter/request/oauth_token_secret');
		$this->session->remove('oauth_bundle/twitter/access/oauth_token');
		$this->session->remove('oauth_bundle/twitter/access/oauth_token_secret');
	}
	
	public function getLoginUrl($succesUrl, $failureUrl)
	{
		$this->clearSession();
		
		$requestToken = $this->api->getRequestToken($succesUrl);

		$this->session->set('oauth_bundle/twitter/request/oauth_token', $requestToken['oauth_token']);
		$this->session->set('oauth_bundle/twitter/request/oauth_token_secret', $requestToken['oauth_token_secret']);

		if($this->api->http_code == 200)
			return $this->api->getAuthorizeURL($requestToken['oauth_token']);

		return $failureUrl;
	}
	
	public function getLogoutUrl($succesUrl)
	{
		$this->clearSession();
		
		return $succesUrl;
	}
	
	public function getUser()
	{
		try {
			/**
			 * When the oauth_token is old, throw exception
			 */
			if($this->request->query->has('oauth_token')
			&& $this->session->get('oauth_bundle/twitter/request/oauth_token') !== $this->request->query->get('oauth_token'))
			{
				$this->session->remove('oauth_bundle/twitter/request/oauth_token');
				$this->session->remove('oauth_bundle/twitter/request/oauth_token_secret');

				throw new \Exception('Invalid oauth_token');
			}

			/**
			 * Check if the user has denied the token
			 */
			if($this->request->query->has('denied'))
				throw new \Exception('User has denied access');

			if($this->request->query->has('oauth_token') === FALSE
			|| $this->request->query->has('oauth_verifier') === FALSE)
				throw new \Exception('Bad request parameters');
			
			/**
			 * Add the request tokens to the API
			 */
			$this->api->setTokens(
				$this->session->get('oauth_bundle/twitter/request/oauth_token'), 
				$this->session->get('oauth_bundle/twitter/request/oauth_token_secret')
			);
			
			$accessToken = $this->api->getAccessToken($this->request->get('oauth_verifier'));

			if($this->api->http_code !== 200)
				throw new \Exception('Failed trying to get the access token');

			/**
			 * Save the access tokens. Normally these would be saved in a database for future use.
			 */
			$this->session->set('oauth_bundle/twitter/access/oauth_token', $accessToken['oauth_token']);
			$this->session->set('oauth_bundle/twitter/access/oauth_token_secret', $accessToken['oauth_token_secret']);

			/**
			 * No longer needed
			 */
			$this->session->remove('oauth_bundle/twitter/request/oauth_token');
			$this->session->remove('oauth_bundle/twitter/request/oauth_token_secret');

			/**
			 * Add the ACCESS tokens to the API
			 */
			$this->api->setTokens(
				$this->session->get('oauth_bundle/twitter/access/oauth_token'), 
				$this->session->get('oauth_bundle/twitter/access/oauth_token_secret')
			);

			$user = $this->api->get('account/verify_credentials');

			if($this->api->http_code !== 200)
				throw new \Exception('Failed getting user credientials');
			
			return array(
				'id'		=> $user->id,
				'name'		=> $user->name,
				'url'		=> 'http://www.twitter.com/' . $user->screen_name,
				'avatar'	=> $user->profile_image_url,
				'extra'		=> $this->convertToArray($user)
			);
		} catch (\Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * @author http://www.if-not-true-then-false.com/2009/php-tip-convert-stdclass-object-to-multidimensional-array-and-convert-multidimensional-array-to-stdclass-object/
	 */
	protected function convertToArray($input) 
	{
		if(is_object($input)) 
			$input = get_object_vars($input);

		if(is_array($input)) 
			return array_map(array($this, 'convertToArray'), $input);
		else
			return $input;
	}
	
	public function getAlias()
	{
		return 'twitter';
	}
}