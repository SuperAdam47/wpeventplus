<?php
if (is_array($messages)) :
    if (count($messages)):
        ?>
        <ul>
            <?php foreach ($messages as $i => $message) : ?>
                <li><?php echo $message; ?></li>
            <?php endforeach; ?>
        </ul>
        <?php
    endif; 
endif; 
