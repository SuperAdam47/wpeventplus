<?php
$requiredEsterik = $question->required == 'Y' ? '*' : '';
echo '<textarea placeholder="'.$question->question.' '.$requiredEsterik.'" class="'.$requiredClass.'" title="'.$question->question.'" id="TEXTAREA_' . $question->id . '" name="TEXTAREA_' . $question->id . '"  placeholder="' . $placeholder . '" rows="5">' . $answer . '</textarea>';

