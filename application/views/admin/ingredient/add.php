<div class="page-header">
	<h1>New Ingredient</h1>
</div>
<div class="panel panel-default">
	<div class="panel-heading"><h2>Ingredient</h2></div>
	<div class="panel-body">
		<?php echo validation_errors('<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">Error:</span>', '</div>'); ?>
		<?php echo form_open('admin/ingredients/add/verify'); ?>
		<div class="input-group">
			<span class="input-group-addon" id="name-addon">Name&nbsp;</span>
			<input type="text" name="name" class="form-control" placeholder="name" aria-describeby="name-addon">
		</div> 
		
		<ul class="list-group checked-list-box">
			<li class="list-group-item" data-name="available">Available</li>
		</ul>

		<div class="input-group">
			<span class="input-group-addon">Stock</span>
			<input type="number" name="stock" class="form-control" aria-label="Item available?">
		</div>
		
		<input type="submit" class="btn btn-default btn-block" value="Submit">
	</div>
</div>