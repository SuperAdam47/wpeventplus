<p><?php echo $question->question; ?><?php echo $question->required == 'Y' ? ' *' : ''; ?></p>
<?php

echo "<select class='".$requiredClass."' name='DROPDOWN_" . $question->id . "' $required id='DROPDOWN_" . $question->id . "' title='" . $question->question . "' />";
echo "<option value='' disabled='disabled'>" . _e('Select One', 'evrplus_language') . "</option>";
foreach ($values as $key => $value) {
    $checked = in_array($value, $answers) ? " selected='selected'" : "";
    echo "<option value'" . $value . "'>$value</option>";
}
echo "</select>";
