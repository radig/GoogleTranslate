<?php

class GoogleTranslate {

/**
 * Api Url
 * @var string
 */
    protected $apiUri = 'https://www.googleapis.com/language/translate/v2';

/**
 * Api Key
 * @var string
 */
    protected $apiKey = '';

/**
 * Languages to translate between
 * @var array
 */
    protected $languages = array(
        'from' => 'pt',
        'to' => 'en'
    );

/**
 * Original text to translate
 * @var string
 */
    protected $original = '';

/**
 * Result of translation
 * @var string
 */
    protected $translated = '';

/**
 * Default constructor
 *
 * @param array  $_languages  Array with languages 'from' and 'to' translate
 * @param string $_key        API Key
 */
    public function __construct($_languages = array(), $_key = null) {

        if (!empty($_languages)) {
            $this->languages = array_merge($this->languages, $_languages);
        }

        if ($_key !== null) {
            $this->apiKey = $_key;
        }
    }

/**
 * Generic setter
 *
 * @param string $attr
 * @param mixed $value
 */
    public function __set($attr, $value) {

        if ($attr == 'apiUri' && is_string($value)) {
            $this->apiUri = $value;
        }

        if ($attr == 'key' && is_string($value)) {
            $this->apiKey = $value;
        }

        if (isset($this->laguages[$attr]) && is_string($value)) {
            $this->languages[$attr] = $value;
        }
    }

/**
 * Getter
 *
 * @param  string $attr Name of property
 * @return string
 */
    public function __get($attr) {
        switch ($attr) {
            case 'text':
            case 'original':
                return $this->original;

            case 'translated':
                return $this->translated;

            case 'from':
                return $this->languages['from'];

            case 'to':
                return $this->languages['to'];

            case 'key':
            case 'apiKey':
                return $this->apiKey;

            case 'uri':
            case 'apiUri':
                return $this->apiUri;
        }

        return null;
    }

/**
 * Text to translate
 *
 * @param  string $s Your text to translate
 * @return GoogleTranslate
 */
    public function text($s) {
        $this->original = $s;

        return $this;
    }

/**
 * Define origin language
 *
 * @param  string $langFrom Origin language, supported by API
 * @return GoogleTranslate
 */
    public function from($langFrom) {
        $this->languages['from'] = $langFrom;

        return $this;
    }

/**
 * Define target language
 *
 * @param  string $langTo Target language, supported by API
 * @return GoogleTranslate
 */
    public function to($langTo) {
        $this->languages['to'] = $langTo;

        $this->translate();

        return $this->translated;
    }

/**
 * Make a call to Google Translate API and return it
 * response.
 *
 * @throws CakeException If call to API fail
 * @return String
 */
    protected function translate() {
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

        if ($response === false)  {
            throw new CakeException("Cannot read response from URL $url");
        }

        $this->translated = $response['data']['translations'][0]['translatedText'];

        return $this->translated;
    }

/**
 * Build API query parameters from lib attributes.
 *
 * @return array
 */
    private function buildParameters() {
        return array(
            'key' => $this->apiKey,
            'source' => $this->languages['from'],
            'target' => $this->languages['to'],
            'q' => $this->original
        );
    }
}
