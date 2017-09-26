<?php

class eplus_front_shortcode_attendees_short_controller extends EventPlus_Abstract_Controller {

    function index() {

        $event_id = (int) $this->_invokeArgs['event_id'];

        if ($event_id <= 0) {
            $this->setResponse(__("Event id not supplied", 'evrplus_language'));
            return;
        }

        $oEvent = new EventPlus_Models_Events();
        $oAttendee = new EventPlus_Models_Attendees();
        $event = $oEvent->getData($event_id);

        $participants = $oAttendee->getRecords(array('event_id' => $event_id, 'payment_status' => 'success'));

        $outputStr = "<h2>" . __('Attendee List for ', 'evrplus_language') . stripslashes($event->event_name) . "</h2>";

        $people = array();
        if ($participants) {

            foreach ($participants as $participant) {
                $attendee_array = unserialize($participant->attendees);
                
                if(count($attendee_array) && is_array($attendee_array)){
                    foreach($attendee_array as $k => $attendee_arr){
                        if(isset($attendee_arr['first_name'])){
                            if($attendee_arr['first_name'] != ''){
                                 array_push($people, $attendee_arr);
                            }
                        }
                    }
                }
            }
        }

        $tmp = Array();
        foreach ($people as $aSingleArray)
            $tmp[] = $aSingleArray["last_name"];

        $tmp = array_map('strtolower', $tmp);
        array_multisort($tmp, $people);

        if (count($people) > "0") {
            $i = 0;
            $outputStr .= '<table class="evrplus_events">';
            $outputStr .= '<thead>'
                    . '<tr>'
                    . '<th>' . __('Attendee #', 'evrplus_language') . '</th>';
            $outputStr .= '<th>' . __('Attendee Name', 'evrplus_language') . '</th>'
                    . '</tr>'
                    . '</thead>';

            do {
                $evodd = '';
                $digit = $i + 1;
                if ($digit % 2 == 0) {
                    $evodd = 'even';
                } else {
                    $evodd = 'odd';
                }
                $outputStr .= '<tr>'
                        . '<td class="er_title er_ticket_info ' . $evodd . '">';
                $outputStr .= $digit . '.</td>  '
                        . '<td class="er_title er_ticket_info ' . $evodd . '">' . $people[$i]["first_name"] . " " . $people[$i]['last_name'] . "</td>"
                        . "</tr>";
                ++$i;
            } while ($i < count($people));
            
            $outputStr .= '</table>';
        }

        $this->setResponse($outputStr);
    }

}
