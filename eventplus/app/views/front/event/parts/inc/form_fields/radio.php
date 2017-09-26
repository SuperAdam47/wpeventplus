<p><?php echo $question->question; ?><?php echo $question->required == 'Y' ? ' *' : ''; ?></p>
<?php

foreach ($values as $key => $value) {
    $checked = in_array($value, $answers) ? ' checked="checked"' : "";
    echo '<label class="radi0"><input title="'.$question->question.'" class="'.$requiredClass.'" type="radio" id="'.$value.'" name="SINGLE_'.$question->id.'" value="'.$value.'" '.$checked.'> '.$value.'</label>';
}