<?php 

require_once('UnserializeFixer/Fixer.php');


$test = [
	'mc_gross'=>"-17.91",
	'protection_eligibility'=>'Eligible',
	'item_number1'=>'360753991770',
	'address_street'=>1,
	1=>['aaa'=>'bbb'],
	3=>4,
];
var_dump(serialize($test));


$post = 'a:6:{s:8:"mc_gross";s:6:"-17.91";s:22:"protection_eligibility";s:8:"Eligible";s:12:"item_number1";s:7:"3607539";s:14:"address_street";';


var_dump(\UnserializeFixer\Fixer::run($post));