<?php

class EventPlus_Models_Questions extends EventPlus_Abstract_Model {

    function __construct() {
        parent::__construct();

        $this->_table = get_option('evr_question');
    }

    function getTotalQuestions($event_id) {
        $sql = "SELECT count(1) as totRecords FROM " . $this->_table . " WHERE event_id = '" . (int) $event_id . "' LIMIT 1";

        $row = $this->QuickArray($sql);

        return $row['totRecords'];
    }

    function getData($id) {
        $sql = "SELECT * FROM " . $this->_table . " WHERE id = '" . (int) $id . "' LIMIT 1";
        $row = $this->QuickArray($sql);

        if (is_array($row)) {
            return $row;
        } else {
            return false;
        }
    }

    function addQuestion($params, $oEvent) {
        $wpdb = $this->getWpDb();

        $event_id = $params['event_id'];
        $question = $params['question'];
        $question_type = $params['question_type'];
        $values = $params['values'];
        $required = ($params['required'] == 'Y') ? 'Y' : 'N';
        $remark = $params['remark'];
        $sequence = $wpdb->get_var("SELECT max(sequence) FROM " . get_option('evr_question') . " where event_id = '$event_id' LIMIT 1") + 1;


        if ($wpdb->query("INSERT INTO " . get_option('evr_question') . " (`event_id`, `sequence`, `question_type`, `question`, `response`, `required`,`remark`)" . " values('$event_id', '$sequence', '$question_type', '$question', '$values', '$required', '$remark')")) {
            $this->setMessage(__('The question has been added.', 'evrplus_language'));
            return true;
        } else {
            $this->setMessage(__('There was an error in your submission, please try again. The question was not saved!', 'evrplus_language'));
            return false;
        }
    }

    function updateQuestion($params, $dbRow, $oEvent) {
        $wpdb = $this->getWpDb();

        $event_id = (int) $params['event_id'];
        $question_text = $params['question'];
        $question_id = (int) $params['question_id'];
        $question_type = $params['question_type'];
        $values = $params['values'];
        $required = ($params['required'] == 'Y') ? 'Y' : 'N';
        $remark = $params['remark'];

        $wpdb->query("UPDATE " . get_option('evr_question') . " set `question_type` = '$question_type', `question` = '$question_text', " . " `response` = '$values', `required` = '$required', `remark` = '$remark' where id = $question_id ");

        $this->setMessage(__('The question has been updated.', 'evrplus_language'));
        return true;
    }

    function getRecords($params) {

        $sql = "SELECT q.question_type, q.required, q.question, q.id, q.event_id, q.sequence "
                . " FROM " . $this->_table . " q ";

        $sql .= " WHERE 1=1 ";
        if ($params['event_id']) {
            $sql .= " AND q.event_id = '" . (int) $params['event_id'] . "'";
        }

        $sql .= ' ORDER BY q.sequence ASC';

        if ($params['limit_str'] != '') {
            $sql .= ' ' . $params['limit_str'];
        }

        return $this->getResults($sql);
    }

    function deleteQuestion($id) {
        return $this->deleteRow('id', $id, __('Question has been deleted.', 'evrplus_language'), __("Question couldn't deleted.", 'evrplus_language'));
    }

    function sortQuestions($params) {

        if (is_array($params['item'])) {
            if (count($params['item'])) {
                foreach ($params['item'] as $key => $value) {
                    $this->getWpDb()->query("UPDATE " . $this->_table . " SET sequence = '" . (int) $key . "' WHERE id ='" . (int) $value . "';");
                }
            }
        }

        return true;
    }

    function getByEventId($event_id) {
        $qry = "select question, sequence from " . $this->_table . " where event_id = '" . (int) $event_id . "' order by sequence";
        return $this->getWpDb()->get_results($qry, ARRAY_A);
    }

}
