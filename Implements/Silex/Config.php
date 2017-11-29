<?php

class Config extends UnserializeFixer\Fixer{
	//https://silex.symfony.com/doc/2.0/cookbook/validator_yaml.html
	public static function getData() {
		return new Symfony\Component\Validator\Mapping\Loader\YamlFileLoader(__DIR__.'/serialize-data-fixer.yml');		
	}
}