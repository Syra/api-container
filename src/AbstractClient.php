<?php

namespace Syra\ApiContainer;


use Syra\ApiContainer\Container\ResultContainer;
use Syra\ApiContainer\Exception\HandlerException;
use Syra\ApiContainer\Exception\MisconfigurationException;
use Syra\ApiContainer\Exception\ReservedException;

abstract class AbstractClient {

	/**
	 * @var AbstractClient
	 */
	protected static $instance;

	protected $additionalRequestParams = [];
	protected $reservedRequestParams = [
		'namespace',
		'method',
		'params'
	];
	protected $defaultConfiguration = [];
	protected $configuration;

	public function __construct($params = []) {
		$this->configuration = array_merge($this->defaultConfiguration, $params);
	}

	public static function getInstance($params = null) {
		if ($params) {
			self::$instance = new static($params);
		} elseif (empty(self::$instance)) {
			throw new MisconfigurationException('Instance needed to be configured.');
		}
		return self::$instance;
	}

	public function set($name, $value) {
		if (in_array($name, $this->reservedRequestParams)) {
			throw new ReservedException('Reserved request param override: ' . $name);
		}
		$this->additionalRequestParams[$name] = $value;
		return $this;
	}

	public function removeAdditionalParams() {
		$this->additionalRequestParams = [];
	}

	public static function get($namespace, $method, $params = null, $page = null) {
		$Instance = static::getInstance();

		$apiRequestParams = [
			'namespace' => $namespace,
			'method' => $method,
			'params' => $params,
		];
		$apiRequestParams = array_merge($Instance->additionalRequestParams, $apiRequestParams);

		$result = $Instance->sendRequest($apiRequestParams);
		$result = $Instance->unserializeData($result);

		if (isset($result['error'])) {
			return $Instance->apiErrorHandler(func_get_args(), $apiRequestParams, $result);
		}

		if ($result) {
			$Instance->toMagicClass($result);
		}

		return $result;
	}

	protected function unserializeData($data) {
		return json_decode($data, true);
	}

	abstract protected function sendRequest($apiRequestParams);

	protected function toMagicClass(&$data) {
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				if (is_array($data[$key])) {
					$this->toMagicClass($data[$key]);
					$data[$key] = new ResultContainer($data[$key]);
				}
			}
			$data = new ResultContainer($data);
		}
	}

	protected function apiErrorHandler($callParams, $requestParams, $response) {
		$exceptionData['call params'] = $callParams;
		$exceptionData['request params'] = $requestParams;
		$exceptionData['response'] = $response;
		throw (new HandlerException('Api returned an error.'))->setDebugData($exceptionData);
	}

}
