<div class="page-header">
	<h1>Administrator Login</h1>
</div>
<div class="panel panel-default">
	<div class="panel-heading"><h2>Login</h2></div>
	<div class="panel-body">
		<?php echo validation_errors('<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">Error:</span>', '</div>'); ?>
		<?php echo form_open('login/verify'); ?>
		<div class="input-group">
			<span class="input-group-addon" id="username-addon">Username</span>
			<input type="text" name="username" class="form-control" placeholder="Username" aria-describeby="username-addon">
		</div>
		<div class="input-group">
			<span class="input-group-addon" id="password-addon">Password&nbsp;</span>
			<input type="password" name="password" class="form-control" placeholder="Password" aria-describeby="password-addon">
		</div>
		<input type="submit" class="btn btn-default" value="Login">
		<a href="<?php echo site_url(); ?>" class="btn btn-default">Home</a>
	</div>
</div>