<?php

namespace Syra\ApiContainer\Container;

use Syra\ApiContainer\Exception\ResultContainerException;

class ResultContainer implements \Iterator, \ArrayAccess {
	protected $data;

	public function __construct($data) {
		$this->data = $data;
	}

	public function __get($name) {
		if (array_key_exists($name, $this->data)) {
			return $this->data[$name];
		} else {
			throw new ResultContainerException('Undefined param: ' . $name);
		}
	}

	public function __isset($name) {
		return isset($this->data[$name]);
	}

	public function __toString() {
		return (string)$this->getFirstEl($this->data);
	}

	protected function getFirstEl($data) {
		if (is_array($data)) {
			$values = array_values($data);
			if (isset($values[0])) {
				return $this->getFirstEl($values[0]);
			} else {
				return null;
			}
		} else {
			return $data;
		}
	}

	public function toArray() {
		return $this->data;
	}

	public function slice($offset, $length) {
		return array_slice($this->data, $offset, $length);
	}

	public function rewind() {
		reset($this->data);
	}

	public function current() {
		return current($this->data);
	}

	public function key() {
		return key($this->data);
	}

	public function next() {
		return next($this->data);
	}

	public function valid() {
		$key = key($this->data);
		return ($key !== NULL && $key !== FALSE);
	}

	public function offsetSet($offset, $value) {
		if (is_null($offset)) {
			$this->data[] = $value;
		} else {
			$this->data[$offset] = $value;
		}
	}

	public function offsetExists($offset) {
		return isset($this->data[$offset]);
	}

	public function offsetUnset($offset) {
		unset($this->data[$offset]);
	}

	public function offsetGet($offset) {
		return isset($this->data[$offset]) ? $this->data[$offset] : null;
	}

	public function isOk() {
		if (isset($this->data['__status']) && $this->data['__status'] == true) {
			return true;
		}
	}

}