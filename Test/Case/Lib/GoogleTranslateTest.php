<?php
App::uses('GoogleTranslate', 'GoogleTranslate.Lib');

class GoogleTranslateTest extends CakeTestCase
{
    public $object = null;

    public function setUp() {
        parent::setUp();
        $this->object = new GoogleTranslate();
    }

    public function testConstruct() {
    }

    public function testSetters() {
        $expected = 'SECRET KEY';
        $this->object->key = $expected;

        $this->assertEquals($expected, $this->object->key);


        $expected = 'API URI';
        $this->object->apiUri = $expected;

        $this->assertEquals($expected, $this->object->uri);
    }

    public function testGetters() {
        $expected = 'What does fox say?';
        $this->object->text($expected);
        $this->assertEquals($expected, $this->object->text);
        $this->assertEquals($expected, $this->object->original);

        $expected = 'chinese';
        $this->object->from($expected);
        $this->assertEquals($expected, $this->object->from);

        $expected = 'german';
        $this->object->to($expected);
        $this->assertEquals($expected, $this->object->to);

        $expected = 'API URI';
        $this->object->apiUri = $expected;
        $this->assertEquals($expected, $this->object->uri);
        $this->assertEquals($expected, $this->object->apiUri);

        $expected = 'API KEY';
        $this->object->key = $expected;
        $this->assertEquals($expected, $this->object->key);
        $this->assertEquals($expected, $this->object->apiKey);

        $expected = '';
        $this->assertEquals($expected, $this->object->translated);
    }

    public function testChaining() {
        $this->object
            ->text('What does fox say?')
            ->from('en')
            ->to('pt');

        $this->assertEquals('What does fox say?', $this->object->text);
        $this->assertEquals('en', $this->object->from);
        $this->assertEquals('pt', $this->object->to);
    }

/**
 *
 * @todo Need some type of mock of API
 *
 * @return [type] [description]
 */
    public function testRequest() {
        // $this->object->api = '<<! YOUR KEY HERE >>';

        // $result = $this->object->text('casa')->from('pt')->to('en');
        // $expected = 'home';

        // $this->assertIdentical($result, $expected);
    }
}
