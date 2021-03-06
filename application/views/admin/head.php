<nav class="navbar navbar-inverse">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu-collapse" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php echo site_url('admin'); ?>">Administrator</a>
		</div>
		<div class="collapse navbar-collapse" id="menu-collapse">
			<ul class="nav navbar-nav">
				<li><a href="<?php echo site_url(); ?>">Home</a></li>
				<li class="<?php if ($this->uri->segment(2) == 'menu' || $this->uri->segment(2) == FALSE) echo 'active'; ?>"><a href="<?php echo site_url('admin/menu'); ?>">Menu</a></li>
				<li class="<?php if ($this->uri->segment(2) == 'ingredients') echo 'active'; ?>"><a href="<?php echo site_url('admin/ingredients'); ?>">Ingredients</a></li>
				<?php if ($isadmin): ?><li><a href="<?php echo site_url('manage'); ?>">Management</a></li><?php endif; ?>
			</ul>
			<a class="navbar-right btn btn-default navbar-btn" style="margin-left: 15px;" href="<?php echo site_url('admin/logout'); ?>"><span class="glyphicon glyphicon-lock"></span>Logout</a>
			<p class="navbar-text navbar-right">Hello <?php echo $firstname. ' ' .$lastname; ?>,</p>
		</div>
	</div>
</nav>