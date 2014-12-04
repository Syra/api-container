<?php

use Syra\ApiContainer\Container\InputContainer;

class InputContainerTest extends PHPUnit_Framework_TestCase {

	public function testInputContainer() {
		$array = [
			'first' => '1',
			'second' => '2',
			'third' => '3'
		];
		$Input = new InputContainer($array);
		$this->assertTrue($Input->has('first'));
		$this->assertTrue($Input->has(['first', 'second', 'third']));
		$this->assertFalse($Input->has(['first', 'fourth']));
		$this->assertFalse($Input->has('fourth'));
	}


}

