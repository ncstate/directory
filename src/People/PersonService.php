<?php

namespace NCState\People;

use NCState\People\LdapConnector;

class PersonService
{
	private $ldap_connector;
	
	public function __construct() {
		
		$this->ldap_connector = new LdapConnector();
		$people = $this->ldap_connector->get_people(array('csthomp2','apmatthe'));
		echo '<pre>';
		var_dump($people);
		echo '</pre>';
	}

}