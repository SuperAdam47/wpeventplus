<?php
echo $before_widget;
if ($title) {
    echo $before_title . $title . $after_title;
}
?>
<ul style="max-width: 500px;  border: 1px solid #cdcdcd; border-radius:2px; padding:0;">
    <?php
    echo $events_list;
    ?></ul>
<?php
echo $after_widget;
