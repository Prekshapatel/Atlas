<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Application Debug Mode
	|--------------------------------------------------------------------------
	|
	| When your application is in debug mode, detailed error messages with
	| stack traces will be shown on every error that occurs within your
	| application. If disabled, a simple generic error page is shown.
	|
	*/

	'debug' => true,

	/* Setting up config variables for ATDW Atlas API 
	*  see(http://developer.atdw.com.au/ATLAS/API/ATDWO-atlas.html) 
	*  which will be used in local development environment.
	*/ 
	'api_key' => '123456789101112', //API Key for development
	
	'api_url' => 'atlas.atdw-online.com.au' // API Base URL 
);
