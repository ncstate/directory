<?php

namespace NCState\People;

use NCState\People\Person;

class LdapConnector
{
	public $unity_id;
	public $first_name;
	public $last_name;
	public $email;
	public $phone;
	public $title;
	public $website;
	public $office;
	public $role;
	
	public function __construct() {
		//$this->ldap_bind = ldap_connect("ldap.ncsu.edu");
		//ldap_bind($this->ldap_bind);
	}
	
	public function get_people($unity_ids) {
		$people = array();
		$ldap_bind = ldap_connect("ldap.ncsu.edu");
		ldap_bind($ldap_bind);
		foreach($unity_ids as $unity_id) {
			$search_query = ldap_search($ldap_bind, "ou=people,dc=ncsu,dc=edu", "uid=" . $unity_id, array('uid', 'mail', 'ncsuPreferredGivenName', 'ncsuPreferredSurName','sn','title', 'ncsuWebSite', 'telephoneNumber', 'ncsuPrimaryRole', 'registeredAddress', 'givenName', 'ncsuNickname'));
			$entries = ldap_get_entries($ldap_bind, $search_query);
			$people[] = new Person(array(
				'unity_id' => $unity_id,
				'first_name' => $this->first_name($entries[0]),
				'last_name' => $this->last_name($entries[0]),
				'email' => $this->email($entries[0]),
				'phone' => $this->phone($entries[0]),
				'title' => $this->title($entries[0]),
				'website' => $this->website($entries[0]),
				'office' => $this->office($entries[0]),
				'role' => $this->role($entries[0]),
			));
		}
		return $people;
	}
	
	// takes ldap_person object and returns formatted string
	public function first_name($ldap_person) {
		$output = '';

		if (isset($ldap_person['ncsunickname'][0])) {
			$output = $ldap_person['ncsunickname'][0];
		} elseif (isset($ldap_person['ncsupreferredgivenname'][0])) {
			$output = $ldap_person['ncsupreferredgivenname'][0];
		} elseif (isset($ldap_person['givenname'][0])) {
			$output = $ldap_person['givenname'][0];
		}

		return $output;
	}
	
	public function last_name($ldap_person) {
		$output = '';

		if (isset($ldap_person['ncsupreferredsurname'][0])) {
			$output = $ldap_person['ncsupreferredsurname'][0];
		} elseif (isset($ldap_person['sn'][0])) {
			$output = $ldap_person['sn'][0];
		}

		return $output;
	}
	
	public function email($ldap_person) {
		return isset($ldap_person['mail'][0]) ? $ldap_person['mail'][0] : '';
	}
	
	public function phone($ldap_person) {
		$outut = '';
		
		if(strlen($ldap_person['telephonenumber']) == 10) {
			$output = substr($ldap_person['telephonenumber'][0], 0, 3) . "-" . substr($ldap_person['telephonenumber'][0], 3, 3) . "-" . substr($ldap_person['telephonenumber'][0], 6);
		} else {
			$output = $ldap_person['telephonenumber'][0];
		}
		
		return $output;
	}
	
	public function title($ldap_person) {
		return isset($ldap_person['title'][0]) ? $ldap_person['title'][0] : '';
	}
	
	public function website($ldap_person) {
		return isset($ldap_person['ncsuwebsite'][0]) ? $ldap_person['ncsuwebsite'][0] : '';
	}
	
	public function office($ldap_person) {
		$output = '';
		
		if (isset($ldap_person['registeredaddress'][0])) {
			if (strpos($ldap_person['registeredaddress'][0], "Box") != 0) {
				$comma = strpos($ldap_person['registeredaddress'][0], ",");
				$output = substr($ldap_person['registeredaddress'][0], 0, $comma);
			}
		}
		
		return $output;
	}
	
	public function role($ldap_person) {
		return isset($ldap_person['ncsuprimaryrole'][0]) ? $ldap_person['ncsuprimaryrole'][0] : '';
	}
}