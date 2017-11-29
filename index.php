<?php 
use UnserializeFixer\Fixer;
 
require_once('vendor/autoload.php');

$ipn = 'a:6:{s:8:"mc_gross";s:6:"-17.91";s:22:"protection_eligibility";s:8:"Eligible";s:12:"item_number1";s:7:"3607539";s:14:"address_street";';
var_dump(Fixer::run($ipn));