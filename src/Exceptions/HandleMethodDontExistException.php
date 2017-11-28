<?php

namespace UnserializeFixer\Exceptions;

class HandleMethodDontExistException extends \Exception {

	public function __construct($method) {

		parent::__construct($method . ' is not a valid handleMethod', 500);
	}

}
