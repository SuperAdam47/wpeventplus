<?php

$requiredClass = '';
if ($question->required == "Y") {
    $requiredClass = ' eplus_required_cq';
}

$placeholder = '';
if ($question->remark) {
    $placeholder = $question->remark;
}

$question_type = $question->question_type;

if ($question_type == 'TEXT') {
    echo $this->View('front/event/parts/inc/form_fields/text', array(
        'question' => $question,
        'answer' => $answer,
        'requiredClass' => $requiredClass,
        'placeholder' => $placeholder,
    ));
} elseif ($question_type == 'TEXTAREA') {
    echo $this->View('front/event/parts/inc/form_fields/textarea', array(
        'question' => $question,
        'answer' => $answer,
        'requiredClass' => $requiredClass,
        'placeholder' => $placeholder,
    ));
} elseif ($question_type == 'SINGLE') {
    $values = explode(",", $question->response);
    $answers = explode(",", $answer);
    echo $this->View('front/event/parts/inc/form_fields/radio', array(
        'question' => $question,
        'answer' => $answer,
        'answers' => $answers,
        'values' => $values,
        'requiredClass' => $requiredClass,
        'placeholder' => $placeholder,
    ));
} elseif ($question_type == 'MULTIPLE') {
    $values = explode(",", $question->response);
    $answers = explode(",", $answer);
    echo $this->View('front/event/parts/inc/form_fields/checkbox', array(
        'question' => $question,
        'answer' => $answer,
        'answers' => $answers,
        'values' => $values,
        'requiredClass' => $requiredClass,
        'placeholder' => $placeholder,
    ));
} elseif ($question_type == 'DROPDOWN') {
    $values = explode(",", $question->response);
    $answers = explode(",", $answer);
    echo $this->View('front/event/parts/inc/form_fields/dropdown', array(
        'question' => $question,
        'answer' => $answer,
        'answers' => $answers,
        'values' => $values,
        'requiredClass' => $requiredClass,
        'placeholder' => $placeholder,
    ));
}