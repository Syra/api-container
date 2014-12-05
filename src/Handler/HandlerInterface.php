<?php

namespace Syra\ApiContainer\Handler;

interface HandlerInterface {

	public function getResult();

	public function getRequest();

	public function setRequest($data);

} 