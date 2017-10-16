<?php

namespace Ajency\Violations\Ajency;

use Symfony\Component\Console\Output\ConsoleOutput;


/*
 * A base class for Violation Email methods
 *
 */

class ViolationEmail {

    /**
     * returns all the data required to send a mail
     * @param   $violation_type type of violation
     * @return                  view name and subject line
     */
    public function getEmailData($violation_type) {
        // get all the violation email data
        $violationEmailData = json_decode(config('aj-vio-config.violation_email'));
        foreach($violationEmailData as $emailData) {
            if($violation_type == $emailData->violation_type) {
                return ['view' => $emailData->view, 'subject' => $emailData->subject];
            }
        }
        return null;
    }
}
