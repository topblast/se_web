<div class="page-header">
	<h1>New Menu Item</h1>
</div>
<div class="panel panel-default">
	<div class="panel-heading"><h2>Menu Item</h2></div>
	<div class="panel-body">
		<?php echo validation_errors('<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">Error:</span>', '</div>'); ?>
		<?php echo form_open('admin/menu/add/verify'); ?>
		<div class="input-group">
			<span class="input-group-addon" id="name-addon">Name&nbsp;</span>
			<input type="text" name="name" class="form-control" placeholder="name" aria-describeby="name-addon">
		</div>
		<div class="input-group">
			<span class="input-group-addon">Price $</span>
			<input type="text" name="price" class="form-control" placeholder="1.25" aria-label="Amount">
		</div>
		<ul class="list-group checked-list-box"><?php foreach($list as $row):?>
			<li class="list-group-item" <?php if ($row->available === 0) echo 'data-color="warning"'; echo "data-name=check[$row->id]" ?>><?php echo $row->name; ?></li>
        <?php endforeach; ?></ul>
		<input type="submit" class="btn btn-default" value="Submit">
	</div>
</div>