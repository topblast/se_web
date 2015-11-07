<div class="page-header">
	<h1>Administrator</h1>
</div>
<div class="panel panel-default">
	<div class="panel-heading"><h2>Menu List</h2></div>
	<div class="panel-body">
		<a href="<?php echo site_url('admin/menu/add'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-plus-sign"></span> Add Item</a>
	</div>
	
	<table class="table">
		<tr>
			<th>#</th>
			<th>Image</th>
			<th>Name</th>
			<th>Available</th>
			<th>Price $</th>
			<th>Controls</th>
		</tr>
		<?php foreach($list as $row):?>
		<tr>
			<td><?php echo $row->id; ?></td>
			<td>
				<?php if (!is_null($row->image)) :?><img class="media-object" src="<?php echo base_url('images/'.$row->image); ?>" width="64" height="64" alt="Image of <?php echo $row->name; ?>">
				<?php else: ?><span class="glyphicon glyphicon-picture"></span><?php endif; ?>
			</td>
			<td><?php echo $row->name; ?></td>
			<td style="color:<?php echo ($row->available) ? 'green' : 'red'; ?>"><span class="glyphicon glyphicon-<?php echo ($row->available) ? 'ok' : 'exclamation-sign'; ?>"></span><?php echo ($row->available) ? 'Available' : ' Not Available'; ?></td>
			<td><?php echo number_format($row->price, 2); ?></td>
			<td>
				<a href="<?php echo site_url('admin/menu/modify/' . (int)$row->id); ?>" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span> Modify</a>
				<a href="<?php echo site_url('admin/menu/delete/' . (int)$row->id); ?>" class="btn btn-default"><span class="glyphicon glyphicon-remove"></span> Remove</a>
			</td>
		</tr><?php endforeach; ?>
	</table>
</div>