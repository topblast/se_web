<div class="page-header">
	<h1>Modify Ingredient</h1>
</div>
<div class="panel panel-default">
	<div class="panel-heading"><h2>Ingredient</h2></div>
	<div class="panel-body">
		<?php echo validation_errors('<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">Error:</span>', '</div>'); ?>
		<?php if ($error) : ?><div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">Error:</span>Failed to add item!</div><?php endif; ?>
		<?php echo form_open("admin/ingredients/modify/verify/$id"); ?>
		<div class="input-group">
			<span class="input-group-addon" id="name-addon">Name&nbsp;</span>
			<input type="text" name="name" class="form-control" placeholder="name" value="<?php echo $name; ?>" aria-describeby="name-addon">
		</div> 
		
		<ul class="list-group checked-list-box">
			<li class="list-group-item" data-checked=<?php echo ($available) ? 'true' : 'false' ?> data-name="available">Available</li>
		</ul>

		<div class="input-group">
			<span class="input-group-addon">Stock</span>
			<input type="number" name="stock" class="form-control" value="<?php echo $stock; ?>" aria-label="Items in stock">
		</div>
		
		<input type="submit" class="btn btn-default btn-block" value="Submit">
	</div>
</div>