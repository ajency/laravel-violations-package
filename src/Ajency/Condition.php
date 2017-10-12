<?php

namespace Ajency\Violations\Ajency;

use DateTime;

class Condition {
    /*
        Available conditions
        * greaterThan
        * greaterThanEqualTo
        * lessThan
        * lessThanEqualTo
        * equalTo
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

    public function greaterThanEqualTo($lhs,$rhs,$fieldType) {
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
        if($lhs >= $rhs)
            return true;
        else
            return false;
    }

    public function lessThan($lhs,$rhs,$fieldType) {
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
        if($lhs < $rhs)
            return true;
        else
            return false;
    }

    public function lessThanOrEqualTo($lhs,$rhs,$fieldType) {
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
        if($lhs <= $rhs)
            return true;
        else
            return false;
    }

    public function equalTo($lhs,$rhs,$fieldType) {
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
        if($lhs == $rhs)
            return true;
        else
            return false;
    }
}
