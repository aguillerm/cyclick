<?php
	
if(!class_exists('BookedFEA_Ajax')) {
	class BookedFEA_Ajax {
		
		public function __construct() {
			
			// Ajax Actions
			add_action('wp_ajax_booked_fea_delete_appt', array(&$this,'booked_fea_delete_appt'));
			add_action('wp_ajax_booked_fea_approve_appt', array(&$this,'booked_fea_approve_appt'));
			
			// Ajax Loaders
			add_action('wp_ajax_booked_fea_user_info_modal', array(&$this,'booked_fea_user_info_modal'));
		
		}
			
		// Delete an Appointment
		public function booked_fea_delete_appt(){
			
			if (isset($_POST['appt_id'])):
			
				$time_format = get_option('time_format');
				$date_format = get_option('date_format');
		
				$appt_id = $_POST['appt_id'];
				$appt = get_post($appt_id);
				$user_id = $appt->post_author;
				$timestamp = get_post_meta($appt_id,'_appointment_timestamp',true);
				$cf_meta_value = get_post_meta($appt_id,'_cf_meta_value',true);
		
				$timeslot = get_post_meta($appt_id,'_appointment_timeslot',true);
				$timeslots = explode('-',$timeslot);
		
				if ($timeslots[0] == '0000' && $timeslots[1] == '2400'):
					$timeslotText = __('All day','booked-frontend-agents');
				else :
					$timeslotText = date_i18n($time_format,$timestamp);
				endif;
				
				$appointment_calendar_id = get_the_terms( $appt_id,'booked_custom_calendars' );
				if (!empty($appointment_calendar_id)):
					foreach($appointment_calendar_id as $calendar):
						$calendar_id = $calendar->term_id;
						break;
					endforeach;
				else:
					$calendar_id = false;
				endif;
				
				if (!empty($calendar_id)): $calendar_term = get_term_by('id',$calendar_id,'booked_custom_calendars'); $calendar_name = $calendar_term->name; else: $calendar_name = false; endif;
		
				// Send an email to the user?
				$email_content = get_option('booked_cancellation_email_content');
				$email_subject = get_option('booked_cancellation_email_subject');
				if ($email_content && $email_subject):
				
					$guest_name = get_post_meta($appt_id, '_appointment_guest_name',true);
					$guest_email = get_post_meta($appt_id, '_appointment_guest_email',true);
				
					if (!$guest_name):
						$user_name = booked_get_name($user_id);
						$user_data = get_userdata( $user_id );
						$email = $user_data->user_email;
					else:
						$user_name = $guest_name;
						$email = $guest_email;
					endif;
					
					$tokens = array('%name%','%date%','%time%','%customfields%','%calendar%','%email%');
					$replacements = array($user_name,date_i18n($date_format,$timestamp),$timeslotText,$cf_meta_value,$calendar_name,$email);
					$email_content = htmlentities(str_replace($tokens,$replacements,$email_content), ENT_QUOTES | ENT_IGNORE, "UTF-8");
					$email_content = html_entity_decode($email_content, ENT_QUOTES | ENT_IGNORE, "UTF-8");
					$email_subject = str_replace($tokens,$replacements,$email_subject);
					booked_mailer( $email, $email_subject, $email_content );
				endif;
		
				wp_delete_post($appt_id,true);
				wp_die();
			
			endif;
			
		}
		
		// Approve an Appointment
		public function booked_fea_approve_appt(){
			
			if (isset($_POST['appt_id'])):
		
				$appt_id = $_POST['appt_id'];
		
				$time_format = get_option('time_format');
				$date_format = get_option('date_format');
		
				$appt = get_post($appt_id);
				$user_id = $appt->post_author;
				$timestamp = get_post_meta($appt_id,'_appointment_timestamp',true);
				$cf_meta_value = get_post_meta($appt_id,'_cf_meta_value',true);
		
				$timeslot = get_post_meta($appt_id,'_appointment_timeslot',true);
				$timeslots = explode('-',$timeslot);
		
				if ($timeslots[0] == '0000' && $timeslots[1] == '2400'):
					$timeslotText = __('All day','booked-frontend-agents');
				else :
					$timeslotText = date_i18n($time_format,$timestamp);
				endif;
				
				$appointment_calendar_id = get_the_terms( $appt_id,'booked_custom_calendars' );
				if (!empty($appointment_calendar_id)):
					foreach($appointment_calendar_id as $calendar):
						$calendar_id = $calendar->term_id;
						break;
					endforeach;
				else:
					$calendar_id = false;
				endif;
				
				if (!empty($calendar_id)): $calendar_term = get_term_by('id',$calendar_id,'booked_custom_calendars'); $calendar_name = $calendar_term->name; else: $calendar_name = false; endif;
		
				// Send an email to the user?
				$email_content = get_option('booked_approval_email_content');
				$email_subject = get_option('booked_approval_email_subject');
				if ($email_content && $email_subject):
				
					$guest_name = get_post_meta($appt_id, '_appointment_guest_name',true);
					$guest_email = get_post_meta($appt_id, '_appointment_guest_email',true);
					
					if (!$guest_name):
						$user_name = booked_get_name($user_id);
						$user_data = get_userdata( $user_id );
						$email = $user_data->user_email;
					else:
						$user_name = $guest_name;
						$email = $guest_email;
					endif;
					
					$tokens = array('%name%','%date%','%time%','%customfields%','%calendar%','%email%');
					$replacements = array($user_name,date_i18n($date_format,$timestamp),$timeslotText,$cf_meta_value,$calendar_name,$email);
					$email_content = htmlentities(str_replace($tokens,$replacements,$email_content), ENT_QUOTES | ENT_IGNORE, "UTF-8");
					$email_content = html_entity_decode($email_content, ENT_QUOTES | ENT_IGNORE, "UTF-8");
					$email_subject = str_replace($tokens,$replacements,$email_subject);
					booked_mailer( $email, $email_subject, $email_content );
				endif;
		
				wp_publish_post( $appt_id );
				wp_die();
				
			endif;
			
		}
		
		// Display the Appointment/User Info Modal
		public function booked_fea_user_info_modal(){
			
			if (isset($_POST['user_id'])):
			
				ob_start();
				
				echo '<div class="booked-scrollable">';
					echo '<p class="booked-title-bar"><small>' . __('Appointment Information','booked-frontend-agents') . '</small></p>';
			
					if (!$_POST['user_id'] && isset($_POST['appt_id'])):
					
						$guest_name = get_post_meta($_POST['appt_id'], '_appointment_guest_name',true);
						$guest_email = get_post_meta($_POST['appt_id'], '_appointment_guest_email',true);
					
						echo '<p class="fea-modal-title">'.__('Contact Information','booked-frontend-agents').'</p>';
						echo '<p><strong class="booked-left-title">'.__('Name','booked-frontend-agents').':</strong> '.$guest_name.'<br>';
						if ($guest_email) : echo '<strong class="booked-left-title">'.__('Email','booked-frontend-agents').':</strong> <a href="mailto:'.$guest_email.'">'.$guest_email.'</a>'; endif;
						echo '</p>';
						
					else :
			
						// Customer Information
						$user_info = get_userdata($_POST['user_id']);
						$display_name = booked_get_name($_POST['user_id']);
						$email = $user_info->user_email;
						$phone = get_user_meta($_POST['user_id'], 'booked_phone', true);
				
						echo '<p class="fea-modal-title">'.__('Contact Information','booked-frontend-agents').'</p>';
						echo '<p><strong class="booked-left-title">'.__('Name','booked-frontend-agents').':</strong> '.$display_name.'<br>';
						if ($email) : echo '<strong class="booked-left-title">'.__('Email','booked-frontend-agents').':</strong> <a href="mailto:'.$email.'">'.$email.'</a><br>'; endif;
						if ($phone) : echo '<strong class="booked-left-title">'.__('Phone','booked-frontend-agents').':</strong> <a href="tel:'.preg_replace('/[^0-9+]/', '', $phone).'">'.$phone.'</a>'; endif;
						echo '</p>';
			
					endif;
			
					// Appointment Information
					if (isset($_POST['appt_id'])):
			
						$time_format = get_option('time_format');
						$date_format = get_option('date_format');
						$appt_id = $_POST['appt_id'];
			
						$timestamp = get_post_meta($appt_id, '_appointment_timestamp',true);
						$timeslot = get_post_meta($appt_id, '_appointment_timeslot',true);
						$cf_meta_value = get_post_meta($appt_id, '_cf_meta_value',true);
			
						$date_display = date_i18n($date_format,$timestamp);
						$day_name = date_i18n('l',$timestamp);
			
						$timeslots = explode('-',$timeslot);
						$time_start = date($time_format,strtotime($timeslots[0]));
						$time_end = date($time_format,strtotime($timeslots[1]));
			
						if ($timeslots[0] == '0000' && $timeslots[1] == '2400'):
							$timeslotText = 'All day';
						else :
							$timeslotText = $time_start.' '.__('to','booked-frontend-agents').' '.$time_end;
						endif;
			
						echo '<p class="fea-modal-title fea-bordered">'.__('Appointment Information','booked-frontend-agents').'</p>';
						do_action('booked_before_appointment_information_admin');
						echo '<p><strong class="booked-left-title">'.__('Date','booked-frontend-agents').':</strong> '.$day_name.', '.$date_display.'<br>';
						echo '<strong class="booked-left-title">'.__('Time','booked-frontend-agents').':</strong> '.$timeslotText.'</p>';
						echo ($cf_meta_value ? '<div class="cf-meta-values">'.$cf_meta_value.'</div>' : '');
						do_action('booked_after_appointment_information_admin');
			
					endif;
			
					// Close button
					echo '<a href="#" class="close"><i class="fa fa-remove"></i></a>';
				echo '</div>';
				
				echo ob_get_clean();
				wp_die();
				
			endif;
			
		}
	}
}