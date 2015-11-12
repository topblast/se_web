<style>
	.navbar-header{
		color: #9d9d9d;
	}
	
	.panel-footer{
		background-color: rgba(90, 10, 10, 0.5);
		color: #fff;
		border-color: #000;
	}
	.thumbnail{
		max-width: 256px;
		margin: 0 auto;
	}
	.thumbnail .caption {
		padding: 0px;
	}
	.some-panel{
		margin-bottom:10px;
	}
	.panel {
		background-color: rgba(0, 0, 0, 0.5);
		border-color: #000;
	}
	.panel-footer > p {
		margin-bottom: 0px;
	}
	.panel-footer > p:not(:first-child){
		margin-left: 20px;
	}
</style>
<nav class="navbar navbar-inverse">
	<div class="container-fluid">
		<div class="navbar-header">
			<h1>Welcome to The Kiosk</h1>
		</div>
	</div>
	<div class="panel-footer">
		<p>Mondays - Fridays: 10:30am - 7:00pm | Saturdays: 11:00am - 3:00pm | Sundays: CLOSED</p> 
	</div>
</nav>

<div class="panel panel-default">
	<div class="panel-heading"><h2>On The Menu</h2></div>
	<div class="panel-body">			
		<div class="rows">
		<?php foreach($list as $row): ?>
			<div class="col-xs-6 col-sm-6 col-md-3 some-panel" style="display:inline-block">
				<div class="thumbnail<?php if (!$row->available) echo ' alert-danger';?>">
					<img src="<?php echo base_url('images/'.(($row->image != NULL) ? $row->image : 'no_image.png') ); ?>" alt="Image of <?php echo $row->name; ?>">
					<div class="caption">
						<?php if (!$row->available):?> 
						<div class="alert-danger">
							<div class="col-xs-12" style="text-align:center">
								<span class="glyphicon glyphicon-exclamation-sign"></span>Not Available
							</div>
						</div>
						<?php endif; ?>
						<h3><?php echo $row->name; ?></h3>
						<p><?php echo '$ '. number_format($row->price, 2); ?></p>
					</div>
				</div>		
			</div>
		<?php endforeach; ?>
		</div>
	</div>
	<div class="panel-footer">
		<?php if ($loggedin): ?>
		<a href="<?php echo site_url('admin/menu'); ?>" class="btn btn-default">Admin Menu</a>
		<a href="<?php echo site_url('admin/ingredients'); ?>" class="btn btn-default">Admin Ingredients</a>
		<?php else: ?>
		<a href="<?php echo site_url('login'); ?>" class="btn btn-default">Admin Login</a>
		<?php endif; ?>
	</div>
</div>


