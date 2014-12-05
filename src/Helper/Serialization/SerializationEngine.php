<?php

namespace Syra\ApiContainer\Helper\Serialization;


class SerializationEngine implements SerializationEngineInterface {

	public function serialize($data) {
		return json_encode($data);
	}

	public function unserialize($data) {
		return json_decode($data, true);
	}

} 