<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

<script src="<?php echo $assets_url; ?>admin/js/modernizr.custom.js"></script>
<script src="<?php echo $assets_url; ?>admin/js/custom.js"></script>

<div class="wrap" style="text-align:center; margin-top: 20px;">
    
    <?php echo $icons; ?>

    <div class="row">
        <?php echo $portlets['events']; ?>
        <?php echo $portlets['payments']; ?>
    </div>

    <div class="row">
        <?php echo $portlets['attendees']; ?>
        <?php echo $portlets['categories']; ?>
    </div>

</div>