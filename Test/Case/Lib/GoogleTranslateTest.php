<?php
App::uses('GoogleTranslate', 'GoogleTranslate.Lib');

class GoogleTranslateTest extends CakeTestCase
{
	public $object = null;
	
	public function setUp()
	{
		parent::setUp();
		
		$this->object = new GoogleTranslate();
	}
	
	public function testeRequest()
	{
		$this->object->api = '<<! YOUR KEY HERE >>';
		
		$result = $this->object->text('casa')->from('pt')->to('en');
		$expected = 'home';
		
		$this->assertIdentical($result, $expected);
	}
}