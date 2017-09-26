<h3><?php _e('Current Categories', 'evrplus_language'); ?></h3>

<table class="widefat cats">
    <thead>
        <tr>
            <th><?php _e('ID', 'evrplus_language'); ?></th>
            <th><?php _e('Name ', 'evrplus_language'); ?></th>
            <th><?php _e('Identifier', 'evrplus_language'); ?></th>
            <th><?php _e('Shortcode', 'evrplus_language'); ?></th>
            <th><?php _e('Action', 'evrplus_language'); ?></th>
        </tr>
    </thead>

    <tfoot>

        <tr>
            <th><?php _e('ID', 'evrplus_language'); ?></th>
            <th><?php _e('Name ', 'evrplus_language'); ?></th>
            <th><?php _e('Identifier', 'evrplus_language'); ?></th>
            <th><?php _e('Shortcode', 'evrplus_language'); ?></th>
            <th><?php _e('Action', 'evrplus_language'); ?></th>
        </tr>
    </tfoot>

    <?php
    if (count($categories) > 0):
        foreach ($categories as $row):


            $category_id = $row->id;
            $category_name = stripslashes(htmlspecialchars_decode($row->category_name));
            $category_identifier = stripslashes(htmlspecialchars_decode($row->category_identifier));
            $category_color = $row->category_color;

            $font_color = $row->font_color;
            $style = "background-color:" . $category_color . " ; color:" . $font_color . " ;";
            ?>


            <tr><td><?php echo $category_id ?></td>
                <td class="cname"><span style="<?php echo $style; ?>"><?php echo $category_name ?></span></td>
                <td><?php echo $category_identifier ?></td>
                <td style="white-space: nowrap;">
                    [eventsplus_list event_category_id="<?php echo $category_id; ?>"]
                    [PLUS_CALENDAR:<?php echo $category_identifier ?>]
                </td>
                <td class="catc">
                    <a id="update_button" href="<?php echo $this->adminUrl('admin_categories', array('method' => 'edit', 'id' => $category_id)); ?>"><?php _e('Edit', 'evrplus_language'); ?></a>  |

                    <a  id="delete_button" href="<?php echo $this->adminUrl('admin_categories', array('method' => 'delete', 'id' => $category_id)); ?>" ONCLICK="return confirm('<?php _e('Are you sure you want to delete category?', 'evrplus_language'); ?>')"><?php _e('Delete', 'evrplus_language'); ?></a></td>
            </tr>


            <?php
        endforeach;

    else:
        ?> 
        <tr>
            <td><?php _e('No Record Found!', 'evrplus_language'); ?></td>
        </tr>

    <?php endif; ?>
</tbody>
</table>