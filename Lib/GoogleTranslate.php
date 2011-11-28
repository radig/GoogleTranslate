<?php

App::uses('HttpSocket', 'Network/Http');

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
		
		$this->HttpSocket = new HttpSocket();
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
		$response = $this->HttpSocket->get($this->apiUri, $this->buildParameters());
		
		if($response->isOk())
		{
			$data = json_decode($response->body());
			
			$this->translated = implode(' ', $data['data']['translations']);
		}
		
		return $this->original;
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