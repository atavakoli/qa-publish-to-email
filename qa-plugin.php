<?php

/*
	Plugin Name: Publish To Email
	Plugin URI: http://gitlab.cs.sandvine.com/staff-engineers/q2a-plugins
	Plugin Description: Sends email for all questions/answers/comments with full text
	Plugin Version: 0.1
	Plugin Date: 2014-04-14
	Plugin Author: Ali Tavakoli
	Plugin Author URI: https://github.com/atavakoli
	Plugin License: MIT License
	Plugin Minimum Question2Answer Version: 1.6
	Plugin Update Check URI: 
*/

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../');
	exit;
}

qa_register_plugin_module(
	'event',
	'publish-to-email.php',
	'qa_publish_to_email',
	'Publish To Email'
);

/*
        Omit PHP closing tag to help avoid accidental output
*/

