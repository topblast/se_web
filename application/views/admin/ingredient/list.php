<div class="page-header">
	<h1>Administrator</h1>
</div>
<div class="panel panel-default">
	<div class="panel-heading"><h2>Ingredients List</h2></div>
	<div class="panel-body">
		<a href="<?php echo site_url('admin/ingredients/add'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-plus-sign"></span> New</a>
	</div>
	
	<table class="table">
		<tr>
			<th>#</th>
			<th>Name</th>
			<th>Available</th>
			<th>Stock</th>
			<th>Controls</th>
		</tr>
		<?php foreach($list as $row):?>
		<tr<?php if (!$row->available) echo ' class="danger"'; else if ($row->stock == 0) echo ' class="warning"' ?>>
			<td><?php echo $row->id; ?></td>
			<td><?php echo $row->name; ?></td>
			<td style="color:<?php echo ($row->available) ? (($row->stock > 0) ? 'green' : 'orange' ) : 'red'; ?>"><span class="glyphicon glyphicon-<?php echo ($row->available) ? (($row->stock > 0) ? 'ok' : 'warning-sign' ) : 'exclamation-sign'; ?>"></span><?php echo ($row->available) ? (($row->stock > 0) ? 'Available' : ' Out of stock' ) : ' Not Available'; ?></td>
			<td><?php echo $row->stock; ?></td>
			<td>
				<a href="<?php echo site_url('admin/ingredients/modify/' . (int)$row->id); ?>" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span> Modify</a>
				<a href="<?php echo site_url('admin/ingredients/delete/' . (int)$row->id); ?>" class="btn btn-default"><span class="glyphicon glyphicon-remove"></span> Remove</a>
			</td>
		</tr><?php endforeach; ?>
	</table>
</div>