<div class="col-md-6 col-sm-12">
    <!-- BEGIN PORTLET-->
    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption caption-md">
                <i class="icon-bar-chart theme-font-color hide"></i>
                <span class="caption-subject theme-font-color bold uppercase"><?php _e('LATEST PAYMENTS', 'evrplus_language'); ?></span>
            </div>

        </div>
        <div class="portlet-body">
            <div class="table-scrollable table-scrollable-borderless">
                <table class="table table-hover table-light">
                    <table class="table table-hover table-light">
                        <thead>
                            <tr class="uppercase">
                                <th>
                                    <?php _e('Payer', 'evrplus_language'); ?> 
                                </th>

                                <th>										
                                    <?php _e('Amount', 'evrplus_language'); ?>  									
                                </th>
                                <th>
                                    <?php _e('Item Quantity', 'evrplus_language'); ?>  									
                                </th>
                                <th>
                                   							
                                </th>
                            </tr>

                        </thead>
                        <tbody><tr>

                                <?php
                                if (count($payments) > 0):
                                    ?>
                                    <?php
                                    foreach ($payments as $payment) {
                                        ?>
                                    <tr>
                                        <td><?php echo $payment->fname . " " . $payment->lname; ?></td>
                                        <td><?php echo $payment->mc_gross; ?></td>
                                        <td><?php echo $payment->quantity; ?></td>
                                         <td><a href="<?php echo $this->adminUrl('admin_payments', array('event_id' => $payment->event_id, 'id' => $payment->id)); ?>" class="btn btn-mini btn-info">View</a></td>
                                    </tr>
                                <?php } ?>
                            <p  style="text-align: right"><a href="<?php echo $this->adminUrl('admin_payments'); ?>"><?php _e('View All', 'evrplus_language'); ?></a></p>

                        <?php else: ?>

                            <tr><td colspan="3"> <h4 style="text-align: center"><?php _e('No Payments Found!', 'evrplus_language'); ?> </h4></td></tr>
                        <?php endif; ?>



                        </tbody></table>
            </div>


        </div>
    </div>

</div>