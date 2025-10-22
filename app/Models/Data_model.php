<?php namespace App\Models;

use CodeIgniter\Model;

class Data_model extends Model {
    public $db;
    public $session;
    protected function initialize()
    {
        $this->session = session();
    }

     /**
	 * Get all the states
	 *
	 * @return array array of states
	 *
	 */
	public function get_states_array() {
		$states = array(
				"AL" => "Alabama",
				"AK" => "Alaska",
				"AZ" => "Arizona",
				"AR" => "Arkansas",
				"CA" => "California",
				"CO" => "Colorado",
				"CT" => "Connecticut",
				"DE" => "Delaware",
				"FL" => "Florida",
				"GA" => "Georgia",
				"HI" => "Hawaii",
				"ID" => "Idaho",
				"IL" => "Illinois",
				"IN" => "Indiana",
				"IA" => "Iowa",
				"KS" => "Kansas",
				"KY" => "Kentucky",
				"LA" => "Louisiana",
				"ME" => "Maine",
				"MD" => "Maryland",
				"MA" => "Massachusetts",
				"MI" => "Michigan",
				"MN" => "Minnesota",
				"MS" => "Mississippi",
				"MO" => "Missouri",
				"MT" => "Montana",
				"NE" => "Nebraska",
				"NV" => "Nevada",
				"NH" => "New Hampshire",
				"NJ" => "New Jersey",
				"NM" => "New Mexico",
				"NY" => "New York",
				"NC" => "North Carolina",
				"ND" => "North Dakota",
				"OH" => "Ohio",
				"OK" => "Oklahoma",
				"OR" => "Oregon",
				"PA" => "Pennsylvania",
				"RI" => "Rhode Island",
				"SC" => "South Carolina",
				"SD" => "South Dakota",
				"TN" => "Tennessee",
				"TX" => "Texas",
				"UT" => "Utah",
				"VT" => "Vermont",
				"VA" => "Virginia",
				"WA" => "Washington",
				"WV" => "West Virginia",
				"WI" => "Wisconsin",
				"WY" => "Wyoming",
				"AS" => "American Samoa",
				"DC" => "District of Columbia",
				"FM" => "Federated States of Micronesia",
				"GU" => "Guam",
				"MH" => "Marshall Islands",
				"MP" => "Northern Mariana Islands",
				"PW" => "Palau",
				"PR" => "Puerto Rico",
				"VI" => "Virgin Islands"
		);

		return $states;
	}

	/**
	* Getter for ham licenses
	* @return string $retarr[] array with licenses
	*/
	public function get_lic() {
		$retarr = array(
			"SWL" => "SWL",
			"Technician" => "Technician",
			"General" => "General",
			"Advanced" => "Advanced",
			"Amateur Extra" => "Amateur Extra"
		);
		return $retarr;
	}

	/**
	* Getter for ham licenses with int as a key
	* @return string $retarr[] array with licenses
	*/
	public function get_license($i) {
		$retarr = array(
			1 => "SWL",
			2 => "Technician",
			3 => "General",
			4 => "Advanced",
			5 => "Amateur Extra"
		);
		return $retarr[$i];
	}

    
}