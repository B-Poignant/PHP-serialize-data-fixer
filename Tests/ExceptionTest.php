<?php

use PHPUnit\Framework\TestCase;
use UnserializeFixer\Fixer;

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

		Fixer::run('XXXX');
	}

}
