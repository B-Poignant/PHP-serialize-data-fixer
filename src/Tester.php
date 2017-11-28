<?php

class Tester
{
	private $_test_dir = 'Tests';
	private $_possible_tests = [];
	
	public function __construct(){
		foreach (glob($this->get_test_dir().'/*.txt') as $file){
			$this->add_possible_tests(str_replace([$this->get_test_dir().'/','.txt'],'',$file));
		}
	}
	
	public function run(array $tests=[]){
		$results = [];
		foreach($tests as $test){
			$results[$test] = Fixer::run(file_get_contents($this->get_test_dir().'/'.$test.'.txt')); 
		}
		
		return $results;
	}
	
	public function runAll(){
		return $this->run($this->get_possible_tests());
	}
	
	
	private function get_test_dir() {
		return $this->_test_dir;
	}

	private function get_possible_tests() {
		return $this->_possible_tests;
	}

	private function set_possible_tests(array $_possible_tests) {
		$this->_possible_tests = $_possible_tests;
	}
	
	private function add_possible_tests(string $_possible_test) {
		$this->_possible_tests[] = $_possible_test;
	}

}

