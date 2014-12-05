<?php

namespace Syra\ApiContainer\Engine;


class CurlEngine extends AbstractEngine {

	protected $defaultConfiguration = [
		'host' => 'localhost',
		'port' => 80
	];

	public function sendRequest($apiRequestParams) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->configuration['host']);
		curl_setopt($ch, CURLOPT_PORT, $this->configuration['port']);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['data' => $apiRequestParams]));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

}
