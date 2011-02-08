<h1>Login</h1>

<p>Choose your service to login</p>

<ul>
	<li><a href="<?php echo $view['router']->generate('auth_service_login', array('service' => 'facebook')) ?>">Facebook</a></li>
	<li><a href="<?php echo $view['router']->generate('auth_service_login', array('service' => 'twitter')) ?>">Twitter</a></li>
</ul>