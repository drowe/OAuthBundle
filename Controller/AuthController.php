<?php

namespace Bundle\OAuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AuthController extends Controller
{
	public function loginAction($service = null)
	{
		if($service === null)
			return $this->render($this->container->getParameter('oauth.views.service'));
		
		if($this->get('oauth.session')->hasService($service) === FALSE)
			return $this->render($this->container->getParameter('oauth.views.error'), array(
				'error' => 'That service does not exist',
			));
			
		if($this->get('oauth.session')->isAuthenticated() === TRUE)
			return $this->redirect($this->generateUrl('auth_profile'));
		
		$loginUrl = $this->get('oauth.session')->getLoginUrl(
			$service,
			$this->generateUrl('auth_callback', array('service' => $service), true),
			$this->generateUrl('auth_denied', array(), true)
		);
		
		return $this->redirect($loginUrl);
	}
	
	public function callbackAction($service)
	{
		if($this->get('oauth.session')->hasService($service) === FALSE)
			return $this->render($this->container->getParameter('oauth.views.service'), array(
				'error' => 'That service does not exist',
			));
			
		if($this->get('oauth.session')->verifyCallback($service) === TRUE)
			return $this->redirect($this->generateUrl('auth_profile'));
		
		return $this->render($this->container->getParameter('oauth.views.service'), array(
			'error' => 'Something went wrong',
		));
	}
	
	public function logoutAction()
	{
		if($this->get('oauth.session')->isAuthenticated() === TRUE)
		{
			$logoutUrl = $this->get('oauth.session')->getLogoutUrl(
				$this->get('oauth.session')->getServiceName(),
				$this->generateUrl('auth_logout', array(), true)
			);
			
			$this->get('oauth.session')->logout();

			return $this->redirect($logoutUrl);
		}
		
		return $this->render($this->container->getParameter('oauth.views.logout'));
	}
	
	public function profileAction()
	{
		if($this->get('oauth.session')->isAuthenticated() === FALSE)
			return $this->redirect($this->generateUrl('auth_login'));
			
		return $this->render($this->container->getParameter('oauth.views.profile'), array(
			'user' => $this->get('oauth.session')->getUser()
		));
	}
	
	public function deniedAction()
	{
		return $this->render($this->container->getParameter('oauth.views.error'), array(
			'error' => 'You canceled or denied access',
		));
	}
}