<?php
$category_id = 0;
$form_url = $this->adminUrl('admin_categories', array('method' => 'add'));
if (!empty($row) && is_array($row)) {
    $category_id = (int) $row['id'];
    $category_name = stripslashes(htmlspecialchars_decode($row['category_name']));
    $category_identifier = stripslashes(htmlspecialchars_decode($row['category_identifier']));
    $category_desc = stripslashes(htmlspecialchars_decode($row['category_desc']));
    $display_category_desc = $row['display_desc'];
    $category_color = $row['category_color'];
    $font_color = $row['font_color'];
    $form_url = $this->adminUrl('admin_categories', array('method' => 'edit', 'id' => $category_id));
}else{
	$category_id = 0;
    $category_name = "";
    $category_identifier = "";
    $category_desc = "";
    $display_category_desc = "";
    $category_color = "";
    $font_color = "";
    $form_url = "";
}
?>

<h3 class='hndle lt'><span><?php echo $form_heading; ?></span></h3>
<div class="inside">
    <div class="padding">
        <form method="post" action="<?php echo $form_url; ?>">
            <input type="hidden" name="id" value="<?php echo $category_id; ?>" />
            <ul class="po">
                <li>
                    <div class="pass1"><label><?php _e('Category Name', 'evrplus_language'); ?></label></div>
                    <div class="pass2"> <input type="text" name="category_name" size="25" value="<?php echo $category_name; ?>"></div>
                </li>
                <li>
                    <div class="pass1"><label><?php _e('Unique ID For Category', 'evrplus_language'); ?></label></div>
                    <div class="pass2">
                        <input type="text" name="category_identifier" value="<?php echo $category_identifier; ?>" />
                </li>
                <li>
                    <div class="pass1">
                        <?php _e('Do you want to display the category description on the events page?', 'evrplus_language'); ?>
                        <div>
                            <br/>
                            <div class="pass2">

                                <input id="sx" type="radio" name="display_desc" value="Y" <?php
                                if ($display_category_desc == "Y") {
                                    echo "checked";
                                };
                                ?>/><label for="sx"><?php _e('Yes', 'evrplus_language'); ?></label>


                                <input id="sx2" type="radio" name="display_desc" value="N" <?php
                                if ($display_category_desc == "N") {
                                    echo "checked";
                                };
                                ?>/><label for="sx2"><?php _e('No', 'evrplus_language'); ?></label>
                            </div>
                        </div>
                </li>
                <li><div class="pass1"><?php _e('Category Description', 'evrplus_language'); ?></div><div class="pass2">
                        <p>   
                            <?php
                            $settings = array(
                                'media_buttons' => true,
                                'quicktags' => array('buttons' => 'b,i,ul,ol,li,link,close'),
                                'tinymce' => array('theme_advanced_buttons1' => 'bold,italic,bullist,numlist,|,justifyleft,justifycenter,justifyright,|,link,unlink,|,fullscreen')
                            );
							
                            wp_editor($category_desc, 'category_desc', false, false);
                            
                            ?>                                   

                        </p>
                    </div>
                </li>
            </ul>
            <br/>
            <span class="steptitle">
                <img class="stepimg t7" src="<?php echo $this->assetUrl('images/calendar-color-icon.png'); ?>"><?php _e('Select color for Calendar Display', 'evrplus_language'); ?>:</span><ul class="po">
                <script type="text/javascript" charset="utf-8">
                    jQuery(document).ready(function () {

                        jQuery('#picker').hide();

                        jQuery.farbtastic('#picker').linkTo('#cat_back');


                        jQuery("#cat_back").on('click',function () {
                            jQuery('#picker').slideToggle();
                        });


                    });

                </script>
                <!-- <small>Click on each field to display the color picker. Click again to close it.</small> -->
                <li><br/><div class="pass1"><label for="color"><?php _e('Category Background Color', 'evrplus_language'); ?>:</label></div><div class="pass2"> 

                        <?php
                        $bkgd = "#123456";
                        if ($category_color != "") {
                            $bkgd = $category_color;
                        }
                        ?>
                        <input type="text" id="cat_back" name="cat_back" value="<?php echo $bkgd; ?>"  style="width: 195px"/>
                    </div>
                </li>
                <li> <div class="pass1"><div id="picker" style="margin-bottom: 1em;"></div></div></li>
                <li>
                    <div class="pass1 ccl"><?php _e('Category Text Color', 'evrplus_language'); ?>:</div>
                    <div class="pass2"> 

                        <?php
                        $colors = array(
                            '#000000' => __('Black', 'evrplus_language'),
                            '#FFFFFF' => __('White', 'evrplus_language'),
                        );
                        ?>
                        <select style="width:70px;" name='cat_text'>
                            <?php foreach ($colors as $color_code => $color_label): ?>
                                <option value="<?php echo $color_code; ?>"<?php if ($font_color == $color_code): ?> selected="selected"<?php endif; ?>><?php echo $color_label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </li>
            </ul>
            <p class="att" id="uyt"><input class="satt" class="button-primary" type="submit" name="Submit" value="<?php echo $button_label; ?>" /></p>
        </form>
        <br/><br/>
    </div>
</div>

