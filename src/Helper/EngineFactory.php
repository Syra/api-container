<?php

namespace Syra\ApiContainer\Helper;


use Syra\ApiContainer\Engine\CurlEngine;
use Syra\ApiContainer\Exception\EngineFactoryException;

class EngineFactory implements EngineFactoryInterface {

	public function createEngine($type = 'curl', $params = []) {
		$type = strtolower($type);
		switch ($type) {
			case 'curl':
				return new CurlEngine($params);
			case 'socket':
				return false;
			default:
				throw new EngineFactoryException('Nothing to produce. Check requested type.');
		}
	}

}
