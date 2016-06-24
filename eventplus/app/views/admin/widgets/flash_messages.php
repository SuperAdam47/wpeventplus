<?php
if (is_array($flash_messages)) {
    if (count($flash_messages)) {
        foreach ($flash_messages as $class => $messages) {
            foreach ($messages as $message) {
                ?><div class="<?php echo $class; ?>"><p><?php echo $message; ?></p></div><?php
            }
        }
    }
}
