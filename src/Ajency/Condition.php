<?php

namespace Ajency\Violations\Ajency;

use DateTime;

class Condition {
    /*
        Available conditions
        * greaterThan
     */

    public function greaterThan($lhs,$rhs,$fieldType) {
        if($fieldType == 'Time') {
            // convert them to date time objects
            $lhs = new DateTime($lhs);
            $rhs = new DateTime($rhs);
        }
        else {
            // type numeric
            $lhs = (int)$lhs;
            $rhs = (int)$rhs;
        }
        // now check the condition
        if($lhs > $rhs)
            return true;
        else
            return false;
    }

    public function less_than($lhs,$rhs,$is_time = false) {

    }

    public function equal_to($lhs,$rhs,$is_time = false) {

    }
}
