<style>
	.input-group-addon{
		width: 140px;
	}
</style>
<div class="page-header">
	<h1>Modify Staff Account</h1>
</div>
<div class="panel panel-default">
	<div class="panel-heading"><h2>Staff Information</h2></div>
	<div class="panel-body">
		<?php echo validation_errors('<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">Error:</span>', '</div>'); ?>
		<?php if ($error) : ?><div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">Error:</span>Failed to add item!</div><?php endif; ?>
		<?php echo form_open("manage/modify/verify/$id"); ?>
		<?php if (!$hidepass) : ?>
		<div class="input-group">
			<span class="input-group-addon" id="oldpass-addon">Password</span>
			<input type="password" name="password" class="form-control" placeholder="Old Password" aria-describeby="oldpass-addon">
		</div>
		<?php endif; ?>
		<div class="input-group">
			<span class="input-group-addon" id="pass-addon">New Password</span>
			<input type="password" name="newpassword" class="form-control" placeholder="New Password" aria-describeby="pass-addon">
		</div> 
		
		<div class="input-group">
			<span class="input-group-addon" id="conpass-addon">Confirm Password</span>
			<input type="password" name="confirmpassword" class="form-control" placeholder="Confirm Password" aria-describeby="conpass-addon">
		</div> 
		
		<div class="input-group">
			<span class="input-group-addon" id="fname-addon">First Name&nbsp;</span>
			<input type="text" name="fname" class="form-control" placeholder="John" value="<?php echo $firstname; ?>" aria-describeby="fname-addon">
		</div> 
		
		<div class="input-group">
			<span class="input-group-addon" id="lname-addon">Last Name&nbsp;</span>
			<input type="text" name="lname" class="form-control" placeholder="Doe" value="<?php echo $lastname; ?>" aria-describeby="lname-addon">
		</div>
		<?php if ($hidepass): ?><ul class="list-group checked-list-box">
			<li class="list-group-item" data-name="admin"<?php if ($isadmin) echo ' data-checked="true"'; ?>>Manager</li>
		</ul><?php endif; ?>
		<input type="submit" class="btn btn-default" value="Submit">
	</div>
</div>