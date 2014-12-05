<?php

namespace Syra\ApiContainer\Engine;


abstract class AbstractEngine implements EngineInterface {

	protected $defaultConfiguration = [];
	protected $configuration;

	public function __construct($params = []) {
		$this->configuration = array_merge($this->defaultConfiguration, $params);
	}

} 