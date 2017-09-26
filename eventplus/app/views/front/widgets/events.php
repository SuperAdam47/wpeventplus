<?php
echo $before_widget;
if ($title) {
    echo $before_title . $title . $after_title;
}
?>
<div class="events-plus-2">
    <div class="events-list events-list-widget">
        <?php
        echo $events_list;
        ?>
    </div>
</div>
<?php
echo $after_widget;
