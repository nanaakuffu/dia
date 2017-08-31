<?php

	require_once "public_functions.php";

	$user_name = $_SESSION['new_user'];
	$new_id = encrypt_data($user_name);
	$up_3 = encrypt_data('2');

	login_check();

	base_header("User Privelege");
	create_header();
?>
<br>
<div class="container">
	<div class="row">
		<div class="col-sm-3">
			<br />
		</div>
		<div class="col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading"><h5>Set User Priveleges</h5></div>
				<div class="panel-body"> You have not set the access priveleges for, <b><?php echo $user_name ?></b>.
					You need to set this before this user can be active on this system. <br><br> If you want to set them now please click <b>Yes</b>, otherwise click <b>No</b>. <br><br>
					<?php echo "<a class='btn btn-primary' style='width:15%' href=user_levels.php?level={$new_id}&upd={$up_3}> Yes </a>"; ?>
					<a class="btn btn-primary" style="width:15%" href="users_page.php"> No </a>
				</div>
			</div>
		</div>
		<div class="col-sm-3">
			<br />
		</div>
	</div>
</div>
<?php
	create_footer();
?>
