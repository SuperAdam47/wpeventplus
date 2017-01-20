<?php

foreach ($values as $key => $value) {
    $checked = in_array($value, $answers) ? ' checked="checked"' : "";
    echo '<label class="checkb0x"><input  id="'.$value.'" type="checkbox" name="MULTIPLE_'.$question->id.'[]" value="'.$value.'" '.$checked.'> '.$value.'</label>';
}
                