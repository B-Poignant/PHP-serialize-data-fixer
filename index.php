<?php 
 use UnserializeFixer\Fixer;
  
require_once('vendor/autoload.php');

$ipn = 'a:3:{s:11:"userdetails";a:4:{s:2:"abcdefghijklmnopqrs';

var_dump(Fixer::run($ipn));