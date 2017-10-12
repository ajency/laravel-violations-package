<?php

	// Can use this config in php code as config('aj-vio-config.create_violation_rules') etc.

	return [

    /*
    |-------------------------------------------------------------------------------------------
    | Rules / simple DSL to define when violation and what type violation should be created
    |-------------------------------------------------------------------------------------------
    |
    | Default value is empty array :
    | 	'aj_create_violation_rules' => '[]'
    |
    | The value is a json which has an array of rules for different violation types. A sample json value is provided below.
    |
    |	'aj_create_violation_rules' => 	'[
    |											  {
    |											    "violation_type": "late_alert",
    |											    "violation_data": {
    |											      "cc_list": ["organisation_time_manager","organisation_owner"],
    |											      "bcc_list": []
    |											    },
    |   										    "rules": [
    |   										      {
    |   										        "title": "Start time",
    |   										        "key_field": "start_time",
    |										            "field_type": "Numeric",
    |										            "condition": "greater_than",
    |										            "value": "organisation_start_time",
    |										            "preset_value": 11
    |										          }
    |										        ]
    |										      }
    |								    ]'
    |
    |
    | JSON fields property description:
    | 1. violation_type - Mandatory field | Defines what type of violation. Same would be used whenever reference is made.
    | 2. violation_data -
    |		- JSON explaining who should be in cc_list and bcc_list of violation.
    |		- Each value would be treated as an multi valued array.
    |		- Accepts variable names that would be same as in the application. Each variable's actual value will be interpreted as an array. Make sure same field name is used when the violation is actually created.
    |		- If not defined cc_list and bcc_list would be treated empty.
    | 3. rules -
    |		- Mandatory
    |		- An array of json objects defining rules for a violation
    |		- Assumed to be empty array if no rules defined. Violation will not get created when empty.
    |		- Field property description :
    |
    |			a. key_field : Mandatory | unique identifier afainst which a rule is made
    |
    |			b. field_type : Mandatory | defines the type of key_field | Possible values supported - "String", "Numeric", "Boolean"
    |
    |			c. condition : Mandatory | operator for the rule | Possible values supported:
	|				greaterThan                     // true if $a > $b
	|				greaterThanOrEqualTo            // true if $a >= $b
	|				lessThan                        // true if $a < $b
	|				lessThanOrEqualTo               // true if $a <= $b
	|				equalTo                         // true if $a == $b
	|				notEqualTo                      // true if $a != $b
	|				stringContains                  // true if strpos($b, $a) !== false
	|				stringDoesNotContain            // true if strpos($b, $a) === false
	|				stringContainsInsensitive       // true if stripos($b, $a) !== false
	|				stringDoesNotContainInsensitive // true if stripos($b, $a) === false
	|				startsWith                      // true if strpos($b, $a) === 0
	|				startsWithInsensitive           // true if stripos($b, $a) === 0
	|				endsWith                        // true if strpos($b, $a) === len($a) - len($b)
	|				endsWithInsensitive             // true if stripos($b, $a) === len($a) - len($b)
	|				sameAs                          // true if $a === $b
	|				notSameAs                       // true if $a !== $b
    |
    |			d. value : Accepts a variable name - same as what would be passed at the time of creating violation. If not passed preset_value will be used
    |
    |			e. preset_value : A default value for the key_field. If set and if the value is not sent at the time of creating violation then this value will be used to
    |
    */
		'create_violation_rules' => '[

                          {
                            "violation_type": "late_alert",
                            "violation_data": {
                              "cc_list": [
                                "organisation_time_manager",
                                "organisation_owner"
                              ],
                              "bcc_list": []
                            },
                            "rules": [
                              {
                                "title": "Start time",
                                "key_field": "start_time",
                                "field_type": "Time",
                                "condition": "greaterThan",
                                "value": "organisation_start_time",
                                "preset_value": 11
                              }
                            ]
                          },
                          {
                            "violation_type": "minimum_hrs_of_day",
                            "violation_data": {
                              "cc_list": [
                                "organisation_time_manager",
                                "organisation_owner"
                              ],
                              "bcc_list": []
                            },
                            "rules": [
                              {
                                "title": "Total Hours in a day",
                                "key_field": "total_hrs_in_day",
                                "field_type": "Numeric",
                                "condition": "less_than",
                                "value": "organisation_minimum_hrs_in_day",
                                "preset_value": 5
                              }
                            ]
                          },
                          {
                            "violation_type": "minimum_hrs_of_week",
                            "violation_data": {
                              "cc_list": [
                                "organisation_time_manager",
                                "organisation_owner"
                              ],
                              "bcc_list": []
                            },
                            "rules": [
                              {
                                "title": "Total hours in a week",
                                "key_field": "total_hrs_in_week",
                                "field_type": "Numeric",
                                "condition": "less_than_expression",
                                "value": "organisation_total_hrs_of_day * violator_total_week_days",
                                "preset_value": 45
                              }
                            ]
                          },
                          {
                            "violation_type": "minimum_hrs_of_month",
                            "violation_data": {
                              "cc_list": [
                                "organisation_time_manager",
                                "organisation_owner"
                              ],
                              "bcc_list": []
                            },
                            "rules": [
                              {
                                "title": "Total hours in a month",
                                "key_field": "total_hrs_in_month",
                                "field_type": "Numeric",
                                "condition": "less_than_expression",
                                "value": "organisation_total_hrs_of_day * violator_total_month_days",
                                "preset_value": 20
                              }
                            ]
                          }

                        ]'

	];


 ?>
