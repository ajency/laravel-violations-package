<?php
namespace Ajency\Comm\ViolationRules;

use Ajency\Violations\Models\Violation;

/*
 * A base class that lets us define Violation rule methods
 *
 * Violation rule methods are any methods utilized to create/ fetch violations based on rules
 */

class ViolationRules
{
	public function getEnabledViolationTypes(){
		// @todo       (fetch this array from violations table having flage enabled as true)
		$enabled_violations = ['late_alert', 'minimum_hrs_of_day', 'minimum_hrs_of_week', 'minimum_hrs_of_month'];
		return $enabled_violations;
	}

	public function addViolation($violation_type, $data, $async=False, $send_mail=False){

	}

	public function ruleViolated($violation_type, $data){

		return True;
	}

	public function checkForViolation($violation_type, $data, $async=False, $send_mail=False){
		$result = [
			'violation'=> []
		];
		if( in_array($vio_type , getEnabledViolationTypes()) ){
			if(ruleViolated($violation_type, $data)){
				$violation = addViolation($violation_type, $data);
				$result['violation'] = $violation;
			}
		}
		return $result;
	}

	// converts the json rules to an object
	/**
	 * converts the rules to an object
	 * @return object
	 */
	public function readViolationRules() {
		return(json_encode(config('aj-vio-config.create_violation_rules')));
	}
}

?>
