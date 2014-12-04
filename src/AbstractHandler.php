<?php

namespace Syra\ApiContainer;

abstract class AbstractHandler {
	protected $__result;
	protected $__request;

	public function __set($name, $value) {
		$this->__result[$name] = $value;
	}

	public function __get($name) {
		return $this->__result[$name];
	}

	public function getResult() {
		return $this->__result;
	}

	public function setStatusOk($ok = true) {
		$this->__status = $ok;
	}

	public function setStatusFail() {
		$this->__status = false;
	}

	public function getRequest() {
		return $this->__request;
	}

	public function setRequest($data) {
		$this->__request = $data;
	}

}

