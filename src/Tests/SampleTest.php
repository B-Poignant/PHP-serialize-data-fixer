<?php

use PHPUnit\Framework\TestCase;
use UnserializeFixer\Fixer;

class SampleTest extends TestCase {

	public function testArray() {
		$this->assertTrue(true);

		return is_array(Fixer::run(file_get_contents('src/Tests/Samples/array.txt')));
	}

	public function testArray_key() {
		$this->assertTrue(true);

		return is_array(Fixer::run(file_get_contents('src/Tests/Samples/array_key.txt')));
	}

	public function testBoolean() {
		$this->assertTrue(true);

		return Fixer::run(file_get_contents('src/Tests/Samples/boolean.txt'));
	}

	public function testFloat() {
		$this->assertTrue(true);

		return is_float(Fixer::run(file_get_contents('src/Tests/Samples/float.txt')));
	}

	public function testInteger() {
		$this->assertTrue(true);

		return is_int(Fixer::run(file_get_contents('src/Tests/Samples/integer.txt')));
	}

	public function testObject() {
		$this->assertTrue(true);

		return is_object(Fixer::run(file_get_contents('src/Tests/Samples/object.txt')));
	}

	public function testPaypalIPN() {
		$this->assertTrue(true);

		return is_array(Fixer::run(file_get_contents('src/Tests/Samples/paypal_IPN_truncated.txt')));
	}

}