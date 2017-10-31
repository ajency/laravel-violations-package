<?php
namespace Ajency\Violations\Ajency;

use App;

use Ajency\Violations\Models\Violation;
use Ajency\Violations\Ajency\Operator;
use Ajency\Violations\Ajency\ViolationEmail;
use Illuminate\Support\Facades\Mail;

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

	/**
	 * adds a violation to the aj_vio_violations table
	 * @param [type]  $violation_type [description]
	 * @param [type]  $data           [description]
	 * @param boolean $async          [description]
	 * @param boolean $send_mail      [description]
	 */
	public function addViolation($violation_type, $data){
		try {
			$violationEntry = new Violation;
			$violationEntry->status = 0; // [ TODO | NOTE ] change this to the status codes
			$violationEntry->type = $violation_type;
			$violationEntry->who_id = $data['violation_data']['who_id'];
			$violationEntry->who_type = $data['violation_data']['who_type'];
			$violationEntry->who_meta = serialize($data['violation_data']['who_meta']);
			$violationEntry->whom_meta = serialize($data['rule_key_fields']);
			$emailData = $this->getEmailData($violation_type,$data);
			$violationEntry->cc_list = serialize($emailData['cc_list']);
			$violationEntry->bcc_list = serialize($emailData['bcc_list']);
			$violationEntry->save();
		}
		catch(Exception $e) {
			return ['status' => 'error', 'message' => $e->getMessage()];
		}
		return ['status' => 'success'];
	}

	public function ruleViolated($violation_type, $data){

		return True;
	}

	/** [ original | entry method ]
	 * checks for violation and adds the violation
	 * @param  [type]  $violation_type type of violation
	 * @param  [type]  $data           all the data required to create the violation
	 * @param  boolean $async          false | if you need to queue the violation
	 * @param  boolean $send_mail      default false
	 * @return [type]                  [description]
	 */
	public function checkForViolation($violation_type, $data, $async=false, $send_mail=false){
		// $result = [
		// 	'violation'=> []
		// ];
		// if violation exists in the enabled violations
		if( !in_array($violation_type , $this->getEnabledViolationTypes()) ){
			// if(ruleViolated($violation_type, $data)){
			// 	$violation = addViolation($violation_type, $data);
			// 	$result['violation'] = $violation;
			// }
			return ['status' => 'error', 'message' => 'Violation not enabled.'];
		}

		// check if any rules are violated [ async??? ]
		if($this->checkViolationRules($violation_type,$data)) {
			// add violation
			$response = $this->addViolation($violation_type,$data);
			if($send_mail == true) {
				// send a mail if necessary
				// fetch the email data
				$emailData = $this->getEmailData($violation_type,$data);
				$name = explode(' ',$data['violation_data']['who_meta']['name']);
				Mail::send('violations/'.$violation_type, ['rule_key_fields' => $data['rule_key_fields'], 'name' => $name[0]], function($message) use($data, $emailData) {
				$message->from(isset($data['from']) ? $data['from'] : config('aj-vio-config.default_email_sender'));
				$message->to($data['violation_data']['who_meta']['email'])
						->cc($emailData['cc_list'])
						->bcc($emailData['bcc_list'])
						->subject($emailData['subject']);
				});
			}
			return ['status' => 'violation', 'message' => 'Violation check complete.'];
		}

		return ['status' => 'no-violation', 'message' => 'Violation check complete.'];
	}

	/**
	 * checks if a violation rule is satisfied i.e. if a rule is violated
	 * @param  [type] $violationType [description]
	 * @param  [type] $data          [description]
	 * @return [type] $status        true / false (whether the rules were violated)
	 */
	public function checkViolationRules($violationType,$data) {
		$vioData = $this->getViolationRules($violationType);
		$logicalOperator = $vioData->rule_operator;
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
			if($valueField == null)	// then take the p	return ['status' => 'error', 'message' => $e->getMessage()];reset value
				$value = $rule->preset_value;

			// condition(key_field,value)
			$condition = $rule->condition;
			 if((new Operator)->$condition($keyField,$valueField,$rule->field_type)) {
			 	// if rule violated return true else check next rule
				if($logicalOperator == "or")
			 		return true;
			}
			else {
				if($logicalOperator == "and")
					return false;
			}
			if($logicalOperator == "or")
				return false;
			else if($logicalOperator == "and")
				return true;
		}
		return false;
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

	/**
	 * [ TODO ] make all fields optional
	 * return an array of violations based on the the filters provided
	 * @param  [type]  $filters       Contains date_range[], type, who_id, status
	 * @param  string  $sortBy        Possible values --> date_range, who_id, type
	 * @param  string  $order         asc/desc - default asc
	 * @param  integer $page          Page number - default 1
	 * @param  integer $display_limit Number of records to return - default 30
	 * @return array                  An array of violations
	 */
	public function getViolations($filters,$sortBy = 'created_on',$order = 'asc',$page = 1,$display_limit = 30) {
		try {
			// extract the date filters - determine the start and end date
			$startDate = $filters['date_range']['start'];
			if(isset($filters['date_range']['end']))
				$endDate = $filters['date_range']['end'];
			else
				$endDate = $filters['date_range']['start'].' 23:59:59';
			// the query to fetch the violations
			$queryResults = Violation::whereBetween('created_at',[$startDate,$endDate])->where(['type' => $filters['type'], 'who_id' => $filters['who_id']/*, 'status' => $filters['status']*/])->get();

			// created the output array
			$output = [];

			foreach($queryResults as $query) {
				$queryObj['id'] = $query->id;
				$queryObj['status'] = $query->status;
				$queryObj['type'] = $query->type;
				$queryObj['who_id'] = $query->who_id;
				$queryObj['who_type'] = $query->who_type;
				$queryObj['who_meta'] = unserialize($query->who_meta);
				$queryObj['whom_id'] = $query->whom_id;
				$queryObj['whom_type'] = $query->whom_type;
				$queryObj['whom_meta'] = unserialize($query->whom_meta);
				$queryObj['cc_list'] = unserialize($query->cc_list);
				$queryObj['bcc_list'] = unserialize($query->bcc_list);

				array_push($output,$queryObj);
			}

			return $output;
		}
		catch(Exception $e) {
				return ['status' => 'error', 'message' => $e->getMessage()];
		}

	}

	/**
	 * return email specific data from the violations config. Contains subject, cc_list and bcc_list
	 * @param  [type] $violation_type [description]
	 * @param  [type] $data           [description]
	 * @return [type]                 [description]
	 */
	public function getEmailData($violation_type,$data) {
		$vioData = $this->getViolationRules($violation_type);
		$return['subject'] = isset($vioData->violation_data->subject_line) ? $vioData->violation_data->subject_line : 'Violation alert';
		if(App::environment() == 'local')
			$return['subject'] = '[ TEST EMAIL - PLEASE IGNORE ] '.$return['subject'];
		$ccList = [];
		foreach($vioData->violation_data->cc_list as $cc) {
			array_push($ccList,$data['violation_data']['mailing_list'][$cc]);
		}
		$return['cc_list'] = $ccList;
		$bccList = [];
		foreach($vioData->violation_data->bcc_list as $bcc) {
			array_push($bccList,$data['violation_data']['mailing_list'][$bcc]);
		}
		$return['bcc_list'] = $bccList;
		return $return;
	}
}

?>
