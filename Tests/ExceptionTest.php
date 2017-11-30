<?php

use PHPUnit\Framework\TestCase;
use UnserializeFixer\Fixer;
use UnserializeFixer\Config;

class ExceptionTest extends TestCase {

	public function testCorruptedException() {
		$this->expectException('\UnserializeFixer\Exceptions\CorruptedException');

		Fixer::run('XXXX');
	}

	public function testInvalidTypeException() {
		$this->expectException('\UnserializeFixer\Exceptions\InvalidTypeException');

		Fixer::handleLastItemByType([], '', 'Z');
	}

	public function testHandleMethodDontExistException() {
		$this->expectException('\UnserializeFixer\Exceptions\HandleMethodDontExistException');

		Fixer::setStepsLeft(['php-unitTest']);

		Fixer::treat('XXXX');
	}

	public function testInvalidResolveMethodException() {
		$this->expectException('\UnserializeFixer\Exceptions\InvalidResolveMethodException');

		Config::setResolveMethod('php-unitTest');
	}
	
	public function testNotImplementedWetException() {
		$this->expectException('\UnserializeFixer\Exceptions\NotImplementedWetException');

		Config::setResolveMethod('remove');
	}
}
