<?php
extract($invoke_params);


if( count($rows) ): ?>
<?php /*
<style>
.media-box [data-width][data-height] img{
    top: 0 !important; 
    left: -100% !important; 
    right: -100% !important; 
    bottom: 0 !important;
    width: auto !important;
    max-width: none !important;
    height: 100%;
}
</style>
*/ ?>

	<div class="grid-section">
		<div class="content grid-container">
			<?php
			if( $cats ): ?>
				<div class="filters-container">
					<?php
					$html = '<input type="text" id="evr-search" class="media-boxes-search" placeholder="'.__('Search By Title', 'evrplus_language').'">';
					echo apply_filters( 'evrplus_filter_container_html', $html, $rows ); ?>

					<ul class="media-boxes-filter eventplus-grid-filter" id="evr-filter">
						<li><a class="selected" href="#" data-filter="*"><?php _e('All', 'evrplus_language'); ?></a></li>
						<?php foreach ($categories as $cat) { ?>
						<li><a href="#" data-filter=".<?php echo $cat->category_identifier; ?>"><?php echo $cat->category_name; ?></a></li>
						<?php } ?>
					</ul>
				</div>
			<?php
			endif;

			$box_width = apply_filters( 'evrplus_event_grid_box_width', '250' );
			if( $load_new_events != 0 ) {
				$load_new_events = $init_events;
			} ?>

			<div id="evr-grid" class="evr-grid-container" data-boxesToLoadStart="<?php echo $init_events; ?>" data-boxesToLoad="<?php echo $load_new_events; ?>" data-boxesWidth="<?php echo $box_width; ?>">

				<?php
				foreach( $rows as $event ) :

					if( $ordered != 'yes' ) {
						$height = rand( 150, 300 );
					}

					$this_cats = maybe_unserialize($event->category_id);
					$temp = '';
					$curr = EventPlus_Helpers_Event::check_recurrence($event->id);
					$parms = array('action' => 'evrplusegister', 'event_id' => $event->id);
					if( $curr ) {
						$parms['recurr'] = $curr;
					}

					$link = add_query_arg($parms, get_permalink(get_page_by_path('evrplus_registration')));
					$d_format = date_i18n($evrplus_date_format, strtotime($event->start_date));
					if( $curr ) {
						$d_format = date_i18n($evrplus_date_format, $curr);
					}

					$start_time = $event->start_time;
					if( isset($company_options['time_format']) && $company_options['time_format'] == '24hrs' ) {
						$start_time = date_i18n('H:i', strtotime($start_time));
					}

					$catStr = '';
					if( is_array($this_cats) ) {
						foreach( $this_cats as $cat ) {
							$catStr .= EventPlus_Helpers_Event::get_category_identifier_by_id($cat) . ' ';
						}
					}

					$extraClasses = apply_filters( 'evrplus_filter_extra_classes', array(), $event );
					$extraStr = '';
					if( is_array($extraClasses) ) {
						if( count($extraClasses) ) {
							$extraStr = ' ' . implode(' ', $extraStr);
						}
					} ?>

                    <div class="media-box <?php echo $catStr; echo $extraStr; ?>" data-columns="<?php echo $col; ?>">

						<?php
						$defaultImage = $this->assetUrl('images/calendar-icon.png'); ?>
						<div class="media-box-image">
							<div data-thumbnail="<?php echo ($event->image_link) ? $event->image_link : $defaultImage; ?>" data-width="240" data-height="151"></div>
							<div class="thumbnail-overlay">
								<a href="<?php echo EventPlus_Helpers_Event::permalink($company_options['evrplus_page_id']); ?>action=evrplusegister&event_id=<?php echo $event->id . ( ($recurr) ? '&recurr=' . $recurr : '' ) ?>"><i class="fa fa-link"></i></a>
							</div>
						</div>

						<div class="media-box-content" style="background-color: #f5f5f5;">
							<?php
							do_action( 'evrplus_grid_before_box_conent', $event ) ?>

							<div class="media-box-title">
								<a href="<?php echo EventPlus_Helpers_Event::permalink($company_options['evrplus_page_id']); ?>action=evrplusegister&event_id=<?php echo $event->id . ( ($recurr) ? '&recurr=' . $recurr : '' ) ?>"><?php echo stripslashes($event->event_name); ?></a>
							</div>

							<?php
							do_action( 'evrplus_grid_after_box_title', $event ) ?>

							<div class="media-box-date"><span style="font-size:15px;color: #666;" class="dashicons dashicons-calendar-alt"></span><?php echo $d_format; ?></div>
							<div class="media-box-date"><span style="font-size:15px;color: #666;" class="dashicons dashicons-clock"></span><?php echo $start_time; ?></div>

							<?php
							do_action( 'evrplus_grid_after_datetime', $event ) ?>

							<div class="media-box-text">
								<?php
								if( $show_excerpt == 'yes' ) {

									$content = strip_tags( stripslashes($event->event_desc) );
									$content = strip_shortcodes( $content );

									$endChar = '';
									if( strlen($content) > $character_limit ) {
										$endChar = '...';
									}
									echo substr($content, 0, $character_limit) . $endChar;
								} ?>
							</div>

							<?php
							do_action( 'evrplus_grid_after_box_text', $event ) ?>

							<div class="media-box-more"><a href="<?php echo EventPlus_Helpers_Event::permalink($company_options['evrplus_page_id']); ?>action=evrplusegister&event_id=<?php echo $event->id . ( ($recurr) ? '&recurr=' . $recurr : '' ) ?>"><?php _e('Read more', 'evrplus_language'); ?></a></div>

							<?php
							do_action( 'evrplus_grid_after_box_content', $event ) ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
<?php
endif;