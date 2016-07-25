<?php

class eplus_front_shortcode_attendees_list_controller extends EventPlus_Abstract_Controller {

    function index() {

        $event_id = $this->_invokeArgs['event_id'];
        $custom = $this->_invokeArgs['custom'];
        $record_template = $this->_invokeArgs['template'];

        global $wpdb;

        if ($record_template == '') {

            $codeToReturn .= '<table>';
        }

        $oAttendee = new EventPlus_Models_Attendees();
        $attendees = $oAttendee->getRecords(array('event_id' => $event_id, 'payment_status' => EventPlus_Models_Payments::PAYMENT_SUCCESS));

        if ($attendees) {

            foreach ($attendees as $attendee) {

                if ($record_template == '') {
                    $codeToReturn .= '<tr><td>{LAST}, {FIRST}</td><td>  </td><td>{ATTENDEES}</td><td>{QA}</td></tr>';
                } else {
                    $codeToReturn .= $record_template;
                }

                #List attendees
                $guest_list = '';
                $people = unserialize($attendee->attendees);

                if ($people) {

                    foreach ($people as $person) {

                        $guest_list .= $person['first_name'] . ' ' . $person['last_name'];
                    }
                }

                #List ticket types

                $tickets = unserialize($attendee->tickets);

                if ($tickets) {

                    foreach ($tickets as $ticket) {
                        
                    }
                }

                #Retrieve custom questions and responses  

                $events_answer_tbl = get_option('evr_answer');

                $events_question_tbl = get_option('evr_question');

                $qry = "SELECT " . $events_question_tbl . ".id, " .
                        $events_question_tbl . ".sequence, " .
                        $events_question_tbl . ".question, " .
                        $events_answer_tbl . ".answer " .
                        " FROM " . $events_question_tbl . ", " . $events_answer_tbl .
                        " WHERE " . $events_question_tbl . ".id = " . $events_answer_tbl . ".question_id IN (" . $custom . ")" .
                        " AND " . $events_answer_tbl . ".registration_id = " . $attendee->id .
                        " ORDER by sequence";
                $quest_answers = $wpdb->get_results($qry);

                $responses = "";

                if ($quest_answers) {

                    foreach ($quest_answers as $answer) {

                        $responses .= '<b>' . $answer->question . '</b><br/>    ' . $answer->answer . "<br/>";
                    }
                }

                #Begin to replace tags with data

                $codeToReturn = str_replace("\r\n", ' ', $codeToReturn);
                $codeToReturn = str_replace("{FIRST}", stripslashes($attendee->fname), $codeToReturn);
                $codeToReturn = str_replace("{LAST}", stripslashes($attendee->lname), $codeToReturn);
                $codeToReturn = str_replace("{NAME}", stripslashes($attendee->fname) . ' ' . stripslashes($attendee->lname), $codeToReturn);
                $codeToReturn = str_replace("{ADDRESS}", stripslashes($attendee->address), $codeToReturn);
                $codeToReturn = str_replace("{CITY}", stripslashes($attendee->city), $codeToReturn);
                $codeToReturn = str_replace("{STATE}", stripslashes($attendee->state), $codeToReturn);
                $codeToReturn = str_replace("{ZIP}", stripslashes($attendee->zip), $codeToReturn);
                $codeToReturn = str_replace("{EMAIL}", stripslashes($attendee->email), $codeToReturn);
                $codeToReturn = str_replace("{PHONE}", stripslashes($attendee->phone), $codeToReturn);
                $codeToReturn = str_replace("{COUNT}", stripslashes($attendee->quantity), $codeToReturn);
                $codeToReturn = str_replace("{TYPE}", stripslashes($attendee->reg_type), $codeToReturn);
                $codeToReturn = str_replace("{DATE}", stripslashes($attendee->date), $codeToReturn);
                $codeToReturn = str_replace("{ATTENDEES}", $guest_list, $codeToReturn);
                $codeToReturn = str_replace("{QA}", $responses, $codeToReturn);
            }

            #Close table is not a custom template

            if ($record_template == '') {

                $codeToReturn .= '</table>';
            }
        }

        $this->setResponse($codeToReturn);
    }

}
