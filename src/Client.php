<?php

namespace Syra\ApiContainer;

use Syra\ApiContainer\Exception\HandlerException;

class Client extends AbstractClient {

	protected function apiErrorHandler($callParams, $requestParams, $response) {
		$exceptionData['call params'] = $callParams;
		$exceptionData['request params'] = $requestParams;
		$exceptionData['response'] = $response;
		throw (new HandlerException('Api returned an error.'))->setDebugData($exceptionData);
	}

}
