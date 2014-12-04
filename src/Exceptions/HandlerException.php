<?php

namespace Syra\ApiContainer\Exception;

class HandlerException extends \Exception {
	protected $debugData;

	public function setDebugData($data) {
		$this->debugData = $data;
		return $this;
	}

	public function getDebugData() {
		return $this->debugData;
	}

} 