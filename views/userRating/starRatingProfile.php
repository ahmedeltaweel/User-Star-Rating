<br>
<?php if ($sr_user_reward_image == 'gold') :?>

<div class="sr-icon-container">
	<img src="<?php echo $sr_gold_image ?>" alt="gold">
</div>
<?php
elseif ($sr_user_reward_image == 'silver'): ?>
<div class="sr-icon-container">
	<img src="<?php echo $sr_silver_image ?>" alt="silver">
</div>
<?php endif; ?>