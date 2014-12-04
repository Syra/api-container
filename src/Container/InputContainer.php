<?php

namespace Syra\ApiContainer\Container;

class InputContainer {
	protected $data;

	public function __construct($data) {
		$this->data = $data;
	}

	public function has($param) {
		if (is_array($param)) {
			return (0 === count(array_diff($param, array_keys($this->data))));
		} else {
			return isset($this->data[$param]);
		}
	}

	public function get($param) {
		return (is_array($this->data[$param])) ? new InputContainer($this->data[$param]) : $this->data[$param];
	}
}