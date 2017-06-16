<?php

namespace NCState\People;

use Exception;

class Person {
	
	public $unity_id;
	public $first_name;
	public $last_name;
	public $email;
	public $phone;
	public $title;
	public $website;
	public $office;
	
	public function __construct($person) {
		
		$this->unity_id = $person['unity_id'];
		$this->first_name = $person['first_name'];
		$this->last_name = $person['last_name'];
		$this->email = $person['email'];
		$this->phone = $person['phone'];
		$this->title = $person['title'];
		$this->website = $person['website'];
		$this->office = $person['office'];

	}

}