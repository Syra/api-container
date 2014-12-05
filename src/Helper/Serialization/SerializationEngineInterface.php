<?php

namespace Syra\ApiContainer\Helper\Serialization;


interface SerializationEngineInterface {

	public function serialize($data);

	public function unserialize($data);

} 