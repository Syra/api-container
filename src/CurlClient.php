<?php

namespace Syra\ApiContainer;

class CurlClient extends AbstractClient {

	protected $URI;
	protected $port;

	protected $defaultConfiguration = [
		'host' => 'localhost',
		'port' => 80
	];

	public function __construct($params) {
		parent::__construct($params);

		$this->URI = $this->configuration['host'];
		$this->port = $this->configuration['port'];
	}

	protected function sendRequest($apiRequestParams) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->URI);
		curl_setopt($ch, CURLOPT_PORT, $this->port);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['data' => $apiRequestParams]));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

}
