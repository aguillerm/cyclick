<?php
$user_first_name = $this->author_data->first_name;
$user_last_name = $this->author_data->last_name;

$appointment_timeslot_text = $this->appointment_data->timeslot_text;
?>
<p>Dear <?php echo $user_first_name ?> <?php echo $user_last_name ?>,</p>
<p>Your booking appointment "<?php echo $appointment_timeslot_text ?>" has been successfully added to our calendar.</p>
<p>Please, complete the payment for an approval.</p>