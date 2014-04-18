<?php

/*
	Plugin Name: Publish To Email
	Plugin URI: https://github.com/atavakoli/qa-publish-to-email
	Plugin Description: Email posts to configured emails.
	Plugin Version: 1.0
	Plugin Date: 2014-04-18
	Plugin Author: Ali Tavakoli
	Plugin Author URI: https://github.com/atavakoli
	Plugin License: MIT
	Plugin Minimum Question2Answer Version: 1.6
	Plugin Update Check URI: https://raw.githubusercontent.com/atavakoli/qa-publish-to-email/master/qa-plugin.php
*/

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../');
	exit;
}

qa_register_plugin_module(
	'event',
	'qa-publish-to-email.php',
	'qa_publish_to_email_event',
	'Publish To Email'
);

/*
        Omit PHP closing tag to help avoid accidental output
*/

