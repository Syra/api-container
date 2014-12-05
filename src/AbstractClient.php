<?php

namespace Syra\ApiContainer;


use Syra\ApiContainer\Container\ResultContainer;
use Syra\ApiContainer\Engine\EngineInterface;
use Syra\ApiContainer\Exception\MisconfigurationException;
use Syra\ApiContainer\Exception\ReservedException;
use Syra\ApiContainer\Helper\EngineFactory;
use Syra\ApiContainer\Helper\Serialization\SerializationEngine;
use Syra\ApiContainer\Helper\Serialization\SerializationEngineInterface;

abstract class AbstractClient {

	/**
	 * @var AbstractClient
	 */
	protected static $Instance;

	protected $additionalRequestParams = [];
	protected $reservedRequestParams = [
		'namespace',
		'method',
		'params'
	];
	protected $defaultConfiguration = [];
	protected $configuration;
	protected $EngineFactory;
	/**
	 * @var EngineInterface
	 */
	protected $Engine;
	/**
	 * @var SerializationEngineInterface
	 */
	protected $SerializationEngine;

	public function __construct($params = [], EngineInterface $Engine = null, SerializationEngineInterface $SerializationEngine = null) {
		$this->configuration = array_merge($this->defaultConfiguration, $params);
		$this->EngineFactory = $Engine ? $Engine : new EngineFactory();
		$this->SerializationEngine = $SerializationEngine ? $SerializationEngine : new SerializationEngine();
	}

	public static function getInstance($params = [], EngineInterface $Engine = null, SerializationEngineInterface $SerializationEngine = null) {
		if ($params) {
			self::$Instance = new static($params, $Engine, $SerializationEngine);
		} elseif (empty(self::$Instance)) {
			throw new MisconfigurationException('Instance needed to be configured.');
		}
		return self::$Instance;
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

	public static function get($type = null, $namespace, $method, $params = null) {
		if (!$type) {
			throw new MisconfigurationException('Request type should be provided.');
		}
		$Instance = static::getInstance();

		$Instance->Engine = $Instance->EngineFactory->createEngine($type, $Instance->configuration);

		$apiRequestParams = [
			'namespace' => $namespace,
			'method' => $method,
			'params' => $params,
		];
		$apiRequestParams = array_merge($Instance->additionalRequestParams, $apiRequestParams);

		$result = $Instance->Engine->sendRequest($apiRequestParams);
		$result = $Instance->SerializationEngine->unserialize($result);

		if (isset($result['error'])) {
			return $Instance->apiErrorHandler(func_get_args(), $apiRequestParams, $result);
		}

		return new ResultContainer($result);
	}

	public function __call($method, $arguments) {
		if (substr($method, 0, 3) === 'get') {
			$type = substr($method, 3);
			return call_user_func_array([$this, 'get'], array_merge([$type], $arguments));
		}
		throw new MisconfigurationException('Invalid method call.');
	}

	abstract protected function apiErrorHandler($callParams, $requestParams, $response);

}
