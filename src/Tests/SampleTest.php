<?php

use PHPUnit\Framework\TestCase;
use UnserializeFixer\Fixer;

class SampleTest extends TestCase
{
	
	public function testArray()
	{
		$this->assertTrue(is_array(Fixer::run(file_get_contents('src/Tests/Samples/array.txt'))));
	}
	
	public function testArray_key()
	{
		$this->assertTrue(is_array(Fixer::run(file_get_contents('src/Tests/Samples/array_key.txt'))));
	}
	
	public function testBoolean()
	{
		$this->assertTrue(Fixer::run(file_get_contents('src/Tests/Samples/boolean.txt')));
	}
	
	public function testDecimal()
	{
		$this->assertTrue(is_float(Fixer::run(file_get_contents('src/Tests/Samples/decimal.txt'))));
	}
	
	public function testInteger()
	{
		$this->assertTrue(is_int(Fixer::run(file_get_contents('src/Tests/Samples/integer.txt'))));
	}
	
	public function testObject()
	{
		$this->assertTrue(is_object(Fixer::run(file_get_contents('src/Tests/Samples/object.txt'))));
	}
	
	public function testNull()
	{
		$this->assertNull(Fixer::run(file_get_contents('src/Tests/Samples/null.txt')));
	}
	
	public function testPaypalIPN()
	{
		$this->assertTrue(is_array(Fixer::run(file_get_contents('src/Tests/Samples/paypal_IPN_truncated.txt'))));
	}
	
}
