<?php 

require_once('UnserializeFixer/Exceptions/CorruptedException.php');
require_once('UnserializeFixer/Exceptions/InvalidTypeException.php');

require_once('UnserializeFixer/Interfaces/iFixer.php');
require_once('UnserializeFixer/Fixer.php');
require_once('UnserializeFixer/Tester.php');

$ipn = 'a:3:{s:11:"userdetails";a:20:{s:2:"nfsssdfsfdamenfsssdfsfdamenfsssdfsfdamenfsssdfsfdamenfsssdfsfdamenfsssdfsfdame";s:4:"bas";s:8:"lastname";s:7:"schmitz";s:12:"email";s:13:"email@test.de";s:6:"street";s:10:"frstenwall";s:7:"street2";s:0:"";s:7:"company";s:0:"";s:3:"zip";s:5:"40215";s:9:"residence";s:9:"dsseldorf";s:7:"country";s:7:"Germany";s:5:"phone";s:7:"3033185";s:3:"fax";s:0:"";s:10:"customerID";i:202771;s:2:"nr";s:3:"228";s:6:"region";s:3:"nrw";s:10:"phone_code";s:3:"211";s:8:"fax_code";s:0:"";s:10:"salutation";s:2:"Mr";s:5:"sales";s:0:"";s:12:"country_code";s:0:"";s:10:"vat_number";s:0:"";}s:6:"domain";s:15:"bas-schmitz2.de";s:10:"has_domain";b:1;}';

$ipn = substr($ipn,0,rand(0,100));

$ipn = 'a:3:{s:11:"userdetails";a:4:{s:2:"abcdefghijklmnopqrs';
//var_dump(\UnserializeFixer\Fixer::run($ipn));

$test = new \UnserializeFixer\Tester();

var_dump($test->runAll());