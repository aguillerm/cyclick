<div id="booked-welcome-screen">
	<div class="wrap about-wrap">
		<h1><?php echo sprintf(esc_html__('Welcome to %s','booked'),'Booked '.BOOKED_VERSION); ?></h1>
		<div class="about-text">
			<?php echo sprintf(esc_html__('Thank you for choosing %s! If this is your first time using %s, you will find some helpful "Getting Started" links below. If you just updated the plugin, you can find out what\'s new in the "What\'s New" section below.','booked'),'Booked','Booked'); ?>
		</div>
		<div class="booked-badge">
			<img src="<?php echo BOOKED_PLUGIN_URL; ?>/templates/images/badge.png">
		</div>
		
		<div id="welcome-panel" class="welcome-panel">
			
			<img src="<?php echo BOOKED_PLUGIN_URL; ?>/templates/images/welcome-banner.jpg" class="booked-welcome-banner">
			
			<div class="welcome-panel-content">
				<div class="welcome-panel-column-container">
					<div class="welcome-panel-column">
					
						<iframe src="https://player.vimeo.com/video/155600760?color=56c477&title=0&byline=0&portrait=0" width="320" height="180" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen style="margin-top:5px;"></iframe>
						
						<h3 style="margin-top:30px;"><?php esc_html_e('Getting Started','booked'); ?></h3>
						<ul>
							<li><a href="https://boxystudio.ticksy.com/article/3239/" target="_blank" class="welcome-icon welcome-learn-more"><?php esc_html_e('Installation & Setup Guide','booked'); ?></a>&nbsp;&nbsp;<i class="fa fa-external-link"></i></li>
							<li><a href="https://boxystudio.ticksy.com/article/6268/" target="_blank" class="welcome-icon welcome-learn-more"><?php esc_html_e('Custom Calendars','booked'); ?></a>&nbsp;&nbsp;<i class="fa fa-external-link"></i></li>
							<li><a href="https://boxystudio.ticksy.com/article/3238/" target="_blank" class="welcome-icon welcome-learn-more"><?php esc_html_e('Default Time Slots','booked'); ?></a>&nbsp;&nbsp;<i class="fa fa-external-link"></i></li>
							<li><a href="https://boxystudio.ticksy.com/article/3233/" target="_blank" class="welcome-icon welcome-learn-more"><?php esc_html_e('Custom Time Slots','booked'); ?></a>&nbsp;&nbsp;<i class="fa fa-external-link"></i></li>
							<li><a href="https://boxystudio.ticksy.com/article/6267/" target="_blank" class="welcome-icon welcome-learn-more"><?php esc_html_e('Custom Fields','booked'); ?></a>&nbsp;&nbsp;<i class="fa fa-external-link"></i></li>
							<li><a href="https://boxystudio.ticksy.com/article/3240/" target="_blank" class="welcome-icon welcome-learn-more"><?php esc_html_e('Shortcodes','booked'); ?></a>&nbsp;&nbsp;<i class="fa fa-external-link"></i></li>
						</ul>
						<a class="button" style="margin-bottom:15px; margin-top:0;" href="https://boxystudio.ticksy.com/articles/7827/" target="_blank"><?php esc_html_e('View all Guides','booked'); ?>&nbsp;&nbsp;<i class="fa fa-external-link"></i></a>&nbsp;
						<a class="button button-primary" style="margin-bottom:15px; margin-top:0;" href="<?php echo get_admin_url().'admin.php?page=booked-settings'; ?>"><?php esc_html_e('Get Started','booked'); ?></a>
						
					</div>
					<div class="welcome-panel-column welcome-panel-last welcome-panel-updates-list">			
						<h3><?php echo sprintf( esc_html__("What's new in %s?","booked"), BOOKED_VERSION); ?> <a href="http://boxyupdates.com/changelog.php?p=booked" target="_blank"><?php esc_html_e('Full Changelog','booked'); ?>&nbsp;&nbsp;<i class="fa fa-external-link"></i></a></h3>
						<ul class="booked-whatsnew-list">
							<li><em class="fix">Fixed</em> Fixed an issue with registration where the last name would not be saved properly.</li>
							<li><em class="fix">Fixed</em> Fixed an issues with reminder emails where the last name would not be included in some cases.</li>
							<li><em class="fix">Fixed</em> Emails being sent to admins and booking agents now include a reply-to with the user's name and email address. (Note: This will not work if you're using Google SMTP to send emails, as Google doesn't allow this.)</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>