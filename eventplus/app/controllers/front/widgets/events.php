<?php

class eplus_front_widgets_events_controller extends EventPlus_Abstract_Controller {

	function index() {

		$record_limit = $this->_invokeArgs['record_limit']; // Defaults to 5 
		$event_desc_count = $this->_invokeArgs['event_desc_count']; // Defaults to 5
		$record_category = $this->_invokeArgs['record_category']; // Defaults to 0 (All)
		$event_template = $this->_invokeArgs['event_template'];

		$events_list = $this->makeEventsList($record_limit, $event_desc_count, $record_category, $event_template);

		$viewParams = $this->_invokeArgs;
		$viewParams['events_list'] = $events_list;

		$output = $this->oView->View('front/widgets/events', $viewParams);

		$this->setResponse($output);
	}

	protected function makeEventsList($record_limit = '5', $event_desc_count, $record_category = '0', $record_template = '') {

		$wpdb = EventPlus::getRegistry()->db->getDb();

		$curdate = date_i18n("Y-m-d");
		$company_options = EventPlus_Models_Settings::getSettings();
		$category_query = '';

		if (intval($record_limit) > 20)
			$record_limit = 20;


		if ($record_category != '0' && $record_category > 0)
			$category_query = " AND category_id LIKE '%:\"$record_category\"%' ";

		$orderby = $company_options['order_event_list'];

		$sql = "SELECT * FROM " . get_option('evr_event') . " WHERE str_to_date(end_date, '%Y-%m-%e') >= curdate() $category_query ORDER BY str_to_date(start_date, '%Y-%m-%e') " . $orderby . " LIMIT 0," . $record_limit;
		$rows = $wpdb->get_results($sql);

		$codeToReturn = "";

		if ($rows) {
			$count = 1;
			foreach ($rows as $event) {
				if ($record_template == '') {
					
					$codeToReturn .= '<div class="i8em">
										<div class="col-md-4 col-sm-3 col-xs-4 t7umb">
											<a href="{EVENT_URL}"><img src="{EVENT_THUMBNAIL}" alt="{EVENT_NAME}"></a>
										</div>
										<div class="col-md-8 col-sm-9 col-xs-8">
											<h2 class="ti8le"><a href="{EVENT_URL}">{EVENT_NAME}</a></h2>
											<div class="me8a">
												<span><i class="fa fa-calendar" aria-hidden="true"></i>
													{EVENT_DATE_START}
												</span>
												<span><i class="fa fa-clock-o" aria-hidden="true"></i>
													{EVENT_TIME_START}
												</span>
											</div>
											<p class="d3sc">{EVENT_DESC}</p>
										</div>
										<div class="clearfix"></div>
									</div>';
				} else {
					$codeToReturn .= $record_template;
				}

				$post_id = (int) $company_options['evrplus_page_id'];

				if( isset($event->outside_reg) && $event->outside_reg == 'Y' ) {
					$event_url = !empty( $event->external_site ) ? $event->external_site : get_permalink($post_id);
				} else {
					$permaLink = get_permalink(get_page_by_path('evrplus_registration'));
					if ($post_id > 0) {
						$permaLink = get_permalink($post_id);
					}

					$event_url = add_query_arg(array('action' => 'evrplusegister', 'event_id' => $event->id), $permaLink);
				}

				$event_name = stripslashes($event->event_name);
				$event_desc = stripslashes($event->event_desc);
				$codeToReturn = str_replace("\r\n", '', $codeToReturn);
				$codeToReturn = str_replace("{EVENT_URL}", $event_url, $codeToReturn);

				$codeToReturn = str_replace("{EVENT_ID}", $event->id, $codeToReturn);
				$codeToReturn = str_replace("{EVENT_NAME}", evrplus_truncateWords(stripslashes($event->event_name), 8, ""), $codeToReturn);
				$desc = stripslashes($event->event_desc);

				if (strlen($desc) > $event_desc_count) {
					$desc = substr($desc, 0, $event_desc_count) . '...';
				}

				$opt = EventPlus_Models_Settings::getSettings();

				$date_format = "M j, Y";
				if( isset($opt['date_format']) && $opt['date_format'] == 'eur' ) {
                    $date_format = "j M Y";
                }

				$date_start = date_i18n( $date_format, strtotime($event->start_date) );
				$date_end = date_i18n( $date_format, strtotime($event->end_date) );

				$time_start = $event->start_time;
				$time_end = $event->end_time;
				if( isset($opt['time_format']) && $opt['time_format'] == '24hrs' ) {
					$time_start = date_i18n('H:i', strtotime($event->start_time));
					$time_end = date_i18n('H:i', strtotime($event->end_time));
				}

				$codeToReturn = str_replace("{EVENT_DESC}", html_entity_decode($desc), $codeToReturn);
				$codeToReturn = str_replace("{EVENT_LOC}", stripslashes($event->event_location), $codeToReturn);
				$codeToReturn = str_replace("{EVENT_ADDRESS}", stripslashes($event->event_address), $codeToReturn);
				$codeToReturn = str_replace("{EVENT_CITY}", stripslashes($event->event_city), $codeToReturn);
				$codeToReturn = str_replace("{EVENT_STATE}", stripslashes($event->event_state), $codeToReturn);
				$codeToReturn = str_replace("{EVENT_POSTAL}", stripslashes($event->event_postal), $codeToReturn);
				$codeToReturn = str_replace("{EVENT_MONTH_START_NUMBER}", $event->start_month, $codeToReturn);
				$codeToReturn = str_replace("{EVENT_MONTH_START_NAME}", date_i18n("F", strtotime($event->start_date)), $codeToReturn);
				$codeToReturn = str_replace("{EVENT_MONTH_START_NAME_3}", date_i18n("M", strtotime($event->start_date)), $codeToReturn);
				$codeToReturn = str_replace("{EVENT_DAY_START_NUMBER}", $event->start_day, $codeToReturn);
				$codeToReturn = str_replace("{EVENT_DAY_START_NAME}", date_i18n("l", strtotime($event->start_date)), $codeToReturn);
				$codeToReturn = str_replace("{EVENT_DAY_START_NAME_3}", date_i18n("D", strtotime($event->start_date)), $codeToReturn);
				$codeToReturn = str_replace("{EVENT_YEAR_START}", $event->start_year, $codeToReturn);
				$codeToReturn = str_replace("{EVENT_TIME_START}", $time_start, $codeToReturn);
				$codeToReturn = str_replace("{EVENT_DATE_START}", $date_start, $codeToReturn);
				$codeToReturn = str_replace("{EVENT_MONTH_END_NUMBER}", $event->end_month, $codeToReturn);
				$codeToReturn = str_replace("{EVENT_MONTH_START_NAME}", date_i18n("F", strtotime($event->end_date)), $codeToReturn);
				$codeToReturn = str_replace("{EVENT_MONTH_END_NAME_3}", date_i18n("M", strtotime($event->end_date)), $codeToReturn);
				$codeToReturn = str_replace("{EVENT_DAY_END_NUMBER}", $event->end_day, $codeToReturn);
				$codeToReturn = str_replace("{EVENT_DAY_END_NAME}", date_i18n("l", strtotime($event->start_date)), $codeToReturn);
				$codeToReturn = str_replace("{EVENT_DAY_END_NAME_3}", date_i18n("D", strtotime($event->start_date)), $codeToReturn);
				$codeToReturn = str_replace("{EVENT_YEAR_END}", $event->end_year, $codeToReturn);
				$codeToReturn = str_replace("{EVENT_DATE_END}", $date_end, $codeToReturn);
				$codeToReturn = str_replace("{EVENT_TIME_END}", $time_end, $codeToReturn);
				$codeToReturn = str_replace("{EVENT_AVAIL_SPOTS}", $event->reg_limit, $codeToReturn);
				$codeToReturn = str_replace("{EVENT_DESC1}", substr(stripslashes($event->event_desc), 0, 24), $codeToReturn);

				if ($event->image_link != "") {
					$codeToReturn = str_replace("{EVENT_THUMBNAIL}", $event->image_link, $codeToReturn);
				} else {
					$codeToReturn = str_replace("{EVENT_THUMBNAIL}", EventPlus_Helpers_Funx::assetUrl('images/event_icon.png.png'), $codeToReturn);
				}
				
				$count++;
			}
		}

		return $codeToReturn;
	}

}