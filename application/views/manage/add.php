<div class="page-header">
	<h1>New Staff Account</h1>
</div>
<div class="panel panel-default">
	<div class="panel-heading"><h2>Staff Account</h2></div>
	<div class="panel-body">
		<?php echo validation_errors('<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">Error:</span>', '</div>'); ?>
		<?php if ($error) : ?><div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">Error:</span>Failed to add item!</div><?php endif; ?>
		<?php echo form_open('manage/add/verify'); ?>
		<div class="input-group">
			<span class="input-group-addon" id="name-addon">Userame&nbsp;</span>
			<input type="text" name="username" class="form-control" placeholder="Username" aria-describeby="name-addon">
		</div> 
		<div class="input-group">
			<span class="input-group-addon" id="pass-addon">Password</span>
			<input type="password" name="password" class="form-control" placeholder="Password" aria-describeby="pass-addon">
		</div> 
		
		<div class="input-group">
			<span class="input-group-addon" id="conpass-addon">Confirm</span>
			<input type="password" name="confirmpassword" class="form-control" placeholder="Confirm Password" aria-describeby="conpass-addon">
		</div> 
		
		<div class="input-group">
			<span class="input-group-addon" id="fname-addon">First Name&nbsp;</span>
			<input type="text" name="fname" class="form-control" placeholder="John" aria-describeby="fname-addon">
		</div> 
		
		<div class="input-group">
			<span class="input-group-addon" id="lname-addon">Last Name&nbsp;</span>
			<input type="text" name="lname" class="form-control" placeholder="Doe" aria-describeby="lname-addon">
		</div>
		
		<ul class="list-group checked-list-box">
			<li class="list-group-item" data-name="admin">Manager</li>
		</ul>
		
		<input type="submit" class="btn btn-default btn-block" value="Submit">
	</div>
</div>