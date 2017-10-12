<?php
namespace Ajency\Violations\Ajency;

use Ajency\Violations\Models\Violation;
use Ajency\Violations\Ajency\Condition;

use Symfony\Component\Console\Output\ConsoleOutput;


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

	/** [original]
	 * checks for violation and adds the violation
	 * @param  [type]  $violation_type [description]
	 * @param  [type]  $data           [description]
	 * @param  boolean $async          [description]
	 * @param  boolean $send_mail      [description]
	 * @return [type]                  [description]
	 */
	public function checkForViolation($violation_type, $data, $async=False, $send_mail=False){
		$result = [
			'violation'=> []
		];
		// if violation exists in the enabled violations
		if( in_array($vio_type , getEnabledViolationTypes()) ){
			if(ruleViolated($violation_type, $data)){
				$violation = addViolation($violation_type, $data);
				$result['violation'] = $violation;
			}
		}

		// check if any rules are violated [ async??? ]
		if($this->checkViolationRules($violation_type,$data)) {
			// rule violated
		}
		// send a mail if necessary


		return $result;
	}

	/**
	 * checks if a violation rule is satisfied i.e. if rule is violated
	 * @param  [type] $violationType [description]
	 * @param  [type] $data          [description]
	 * @return [type] $status        true / false (whether the rules were violated)
	 */
	public function checkViolationRules($violationType,$data) {
		// first get all the rules for the violation
		$violationRules = $this->getViolationRules($violationType);
		// if no violation rules exist
		if($violationRules == null) {
			// no rules exists
			return false;
		}

		// loop through each rule and check for violation
		foreach($violationRules->rules as $rule) {
			// key field and value field
			$keyField = $data['rule_key_fields'][$rule->key_field];
			$valueField = $data['rule_rhs'][$rule->value];
			if($valueField == null)	// then take the preset value
				$value = $rule->preset_value;

			// condition(key_field,value)
			$condition = $rule->condition;
			return (new Condition)->$condition($keyField,$valueField,$rule->field_type);
		}
	}

	/**
	 * converts the rules to an object
	 * @param  $type  violation type
	 * @return object
	 */
	public function getViolationRules($type) {
		$allViolations = json_decode(config('aj-vio-config.create_violation_rules'));
		foreach($allViolations as $violation) {
			if($violation->violation_type == $type)
				return $violation;
		}
		// if that rule doesn't exist
		return null;
	}
}

?>
