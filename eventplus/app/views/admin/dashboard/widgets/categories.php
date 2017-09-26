<div class="col-md-6 col-sm-12">

    <div class="portlet light ">
        <div class="portlet-title">
            <div class="caption caption-md">
                <i class="icon-bar-chart theme-font-color hide"></i>
                <span class="caption-subject theme-font-color bold uppercase"><?php _e('LATEST CATEGORIES', 'evrplus_language'); ?></span>
                <span class="caption-helper hide"><?php _e('weekly stats...', 'evrplus_language'); ?></span>
            </div>

        </div>

        <div class="table-scrollable table-scrollable-borderless">
            <table class="table table-hover table-light">

                <thead>
                    <tr>&nbsp;</tr>
                    <tr class="uppercase">

                        <th>

                            <?php _e('Category Name', 'evrplus_language'); ?>

                        </th>
                        <th>

                            <?php _e('Identifier', 'evrplus_language'); ?>

                        </th>
                    </tr>

                </thead>
                <tbody><tr>


                        <?php
                        if (count($categories) > 0):
                            ?>

                            <?php
                            $count = 1;
                            foreach ($categories as $cat) {
                                ?>
                            <tr>
                                <td><span class="cat_<?php echo $count++; ?>"><?php echo $cat->category_name; ?></span></td>
                                <td><span class="test"><?php echo $cat->category_identifier; ?></span></td>
                                <!--<td><?php /* echo $cat */ ?></td>-->
                            </tr>
                        <?php } ?>

                    <?php else: ?>
                        <tr>
                            <td colspan ="2"><h4 style="text-align: center"><?php _e('No Categoies Found!', 'evrplus_language'); ?></h4></td> </tr>
                    <?php endif; ?>

                </tbody></table>
        </div>
        <p style="text-align: right"><a href="<?php echo $this->adminUrl('admin_categories'); ?>"><?php _e('View All', 'evrplus_language'); ?></a></p>

    </div>
</div>