<?php
$requiredEsterik = $question->required == 'Y' ? '*' : '';
echo '<input placeholder="'.$question->question.' '.$requiredEsterik.'" class="'.$requiredClass.'" title="'.$question->question.'" type="text" name="TEXT_'.$question->id.'" id="TEXT_'.$question->id.'" placeholder="'.$placeholder.'" value="'.$answer.'" '.$required.' />';

