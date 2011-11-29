<?php
class GoogleTranslate {
	protected $apiUri = 'https://www.googleapis.com/language/translate/v2';
	
	protected $apiKey = '';
	
	protected $languages = array(
		'from' => 'pt',
		'to' => 'en'
	);
	
	protected $original = '';
	
	protected $translated = '';
	
	protected $HttpSocket = null;
	
	public function __construct($_languages = array(), $_key = null)
	{
		if(!empty($_languages))
		{
			$this->languages = array_merge($this->languages, $_languages);
		}
		
		if($_key !== null)
		{
			$this->apiKey = $_key;
		}
	}
	
	/**
	 * Generic setter
	 * 
	 * @param string $attr
	 * @param mixed $value 
	 */
	public function __set($attr, $value)
	{
		if($attr == 'apiUri' && is_string($value))
		{
			$this->apiUri = $value;
		}
		
		if($attr == 'key' && is_string($value))
		{
			$this->apiKey = $value;
		}
		
		if(isset($this->laguages[$attr]) && is_string($value))
		{
			$this->languages[$attr] = $value;
		}
	}
	
	/**
	 * 
	 */
	public function text($s)
	{
		$this->original = $s;
		
		return $this;
	}
	
	/**
	 * 
	 */
	public function from($langFrom)
	{
		$this->languages['from'] = $langFrom;
		
		return $this;
	}
	
	/**
	 * 
	 */
	public function to($langTo)
	{
		$this->languages['to'] = $langTo;
		
		$this->translate();
		
		return $this->translated;
	}
	
	/**
	 * 
	 */
	protected function translate()
	{
		// create a connection to the API endpoint
		$ch = curl_init($this->apiUri);

		// tell cURL to return the response rather than outputting it
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// write the form data to the request in the post body
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->buildParameters()));

		// include the header to make Google treat this post request as a get request
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: GET'));

		// execute the HTTP request
		$json = curl_exec($ch);
		curl_close($ch);
		
		// decode the response data
		$response = json_decode($json, true);
		
		if ($response === false) 
		{
			throw new CakeException("Não foi possível ler a resposta de $url");
		}
		
		$this->translated = $response['data']['translations'][0]['translatedText'];
		
		return $this->translated;
	}
	
	private function buildParameters()
	{
		return array(
			'key' => $this->apiKey,
			'source' => $this->languages['from'],
			'target' => $this->languages['to'],
			'q' => $this->original
		);
	}
}