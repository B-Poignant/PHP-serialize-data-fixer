<?php 

require_once('UnserializeFixer/Exceptions/CorruptedException.php');
require_once('UnserializeFixer/Exceptions/InvalidTypeException.php');

require_once('UnserializeFixer/Interfaces/iFixer.php');
require_once('UnserializeFixer/Fixer.php');
require_once('UnserializeFixer/Tester.php');

$ipn = file_get_contents('tests/paypal_IPN_truncated.txt');

$ipn = 'a:6:{s:8:"mc_gross";s:6:"-17.91";s:22:"protection_eligibility";s:8:"Eligible";s:12:"item_number1";s:7:"3607539";s:14:"address_street";';

//var_dump(\UnserializeFixer\Fixer::run($ipn));

$test = new \UnserializeFixer\Tester();

var_dump($test->runAll());