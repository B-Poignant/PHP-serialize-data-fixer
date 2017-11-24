<?php 

require_once('UnserializeFixer/Exceptions/CorruptedException.php');
require_once('UnserializeFixer/Exceptions/InvalidTypeException.php');

require_once('UnserializeFixer/Interfaces/iFixer.php');
require_once('UnserializeFixer/Fixer.php');
require_once('UnserializeFixer/Tester.php');

$ipn = file_get_contents('tests/paypal_IPN_truncated.txt');

$ipn = 'a:6:{';

var_dump(\UnserializeFixer\Fixer::run($ipn));
/*
$test = new \UnserializeFixer\Tester();

var_dump($test->runAll());*/