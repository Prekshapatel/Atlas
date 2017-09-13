<?php

/** 
 * Autoload libraries pulled from composer so no need to 
 * include single class everytime.
 */
require '../vendor/autoload.php';

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	/**
	 * Setup the request function for Atlas API
	 *
	 */
    public function Request($uri, $method, $query) {	
	  
		$this->api_uri = Config::get('api_uri', 'atlas.atdw-online.com.au'); // Get the base uri for api
		$this->client = new \GuzzleHttp\Client(['base_uri' => $this->base_uri]); // Create \GuzzleHttp\Client with base uri
		
		// Create headers array to create request
		$headers = array( 
		  'Accept-Charset'    => 'UTF-8',
		  'Accept-Encoding'   => 'compress, gzip',
		  'Accept'            => 'application/json',
		  'Content-Type'      => 'application/json',
		  'Origin'            => '*',
		);

		// try catch for request exception
		try {
		  $response = $this->client->request($method, $uri, ['query' => $query], ['headers' => $headers]); // send request with query and headers
		}
		catch(RequestException $e) {
		  $response = $this->StatusCodeHandling($e); // call StatusCodeHandling function
		  return $response; // return response
		}
	}

	// function to handle response status code and returns value accordingly.
	public function StatusCodeHandling($e) {
	  // if status code is 200 convert responseBody from XML to JSON.
	  if ($e->getStatusCode() == ‘200’) {
		$responseBody =  $response->getBody(); // Get the response body
		$json = json_encode(simplexml_load_string($responseBody,'SimpleXMLElement',LIBXML_NOCDATA)); // Encode XML into JSON
		return $jsonResponse; // return JSON
	  } else {
		return; //return
	  }
	}
}
