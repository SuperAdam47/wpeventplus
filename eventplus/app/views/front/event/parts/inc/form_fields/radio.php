<?php

foreach ($values as $key => $value) {
    $checked = in_array($value, $answers) ? ' checked="checked"' : "";
    echo '<label class="radi0"><input title="'.$question->question.'" type="radio" name="MULTIPLE_'.$question->id.'[]" value="'.$value.'" '.$checked.'> '.$value.'</label>';
}
                