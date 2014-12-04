<?php

namespace Syra\ApiContainer;


use Syra\ApiContainer\Exception\RouterException;
use Syra\ApiContainer\Container\InputContainer;
use Syra\ApiContainer\Helper\Observer;

abstract class AbstractRouter {

	const INVALID_DATA_PROVIDED_ERROR = 'Invalid data provided';

	public function route($inputData) {
		try {
			$Input = new InputContainer($inputData);

			if (!$Input->has('data')) {
				throw new RouterException(static::INVALID_DATA_PROVIDED_ERROR);
			}
			$requestData = $Input->get('data');

			if (!$requestData->has(['namespace', 'method'])) {
				throw new RouterException(static::INVALID_DATA_PROVIDED_ERROR);
			}

			$namespace = $requestData->get('namespace');
			$method = $requestData->get('method');
			$methodParams = [];
			if ($requestData->has('params')) {
				$methodParams = $requestData->get('params');
			}
			if (is_array($methodParams)) {
				$methodParams = $this->completeParams($methodParams);
			}

			$ActionHandler = $this->getHandler($namespace);
			$ActionHandler->setRequest($requestData);

			if (!is_callable([$ActionHandler, $method])) {
				throw new RouterException('Handler method not found.');
			}

			Observer::fireEvent(Observer::getEventName('before', $ActionHandler, $method));

			$Reflection = new \ReflectionMethod($ActionHandler, $method);
			if ($Reflection->getNumberOfParameters() == 1) {
				call_user_func([$ActionHandler, $method], $methodParams);
			} else {
				call_user_func_array([$ActionHandler, $method], $methodParams);
			}

			Observer::fireEvent(Observer::getEventName('after', $ActionHandler, $method));

			return $this->serializeData($ActionHandler->getResult());
		} catch (\Exception $E) {
			return $this->serializeData([
				'error' => true,
				'errorMessage' => $E->getMessage(),
				'errorCode' => $E->getCode(),
				'params' => (isset($methodParams)) ? $methodParams : 'none gotten',
				'trace' => debug_backtrace()
			]);
		}
	}

	protected function serializeData($data) {
		return json_encode($data);
	}

	/**
	 * @param string $namespace
	 * @return Handler
	 */
	abstract public function getHandler($namespace);

	public function completeParams($array) {
		end($array);
		if (key($array) + 1 > count($array)) {
			reset($array);
			$prevKey = key($array);
			$additionalArray = [];
			foreach ($array as $key => $el) {
				if ($key - $prevKey > 1) {
					$additionalArray = array_fill($prevKey + 1, $key - $prevKey - 1, null);
				}
			}
			$array = $array + $additionalArray;
			ksort($array);
			return $array;
		}
		return $array;
	}

}
