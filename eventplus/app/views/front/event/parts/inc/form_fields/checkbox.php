<p><?php echo $question->question; ?><?php echo $question->required == 'Y' ? ' *' : ''; ?></p>
<?php

foreach ($values as $key => $value) {
    $checked = in_array($value, $answers) ? ' checked="checked"' : "";
    echo '<label class="checkb0x"><input class="'.$requiredClass.'" title="'.$question->question.'"  id="'.$value.'" type="checkbox" name="MULTIPLE_'.$question->id.'[]" value="'.$value.'" '.$checked.'> '.$value.'</label>';
}
                