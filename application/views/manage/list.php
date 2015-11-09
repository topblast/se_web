<div class="page-header">
	<h1>Administrator</h1>
</div>
<div class="panel panel-default">
	<div class="panel-heading"><h2>Staff Account List</h2></div>
	<div class="panel-body">
		<a href="<?php echo site_url('manage/add'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-plus-sign"></span> Add Staff Account</a>
	</div>
	
	<table class="table">
		<tr>
			<th>#</th>
			<th>Username</th>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Last Logged</th>
			<th>Controls</th>
		</tr>
		<?php foreach($list as $row):?>
		<tr>
			<td><?php echo $row->id; ?></td>
			<td><?php echo $row->username; ?></td>
			<td><?php echo $row->firstname; ?></td>
			<td><?php echo $row->lastname; ?></td>
			<td><?php echo $row->lastlogged; ?></td>
			<td>
				<a href="<?php echo site_url('manage/modify/' . (int)$row->id); ?>" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span> Modify</a>
				<a href="<?php echo site_url('manage/delete/' . (int)$row->id); ?>" class="btn btn-default"><span class="glyphicon glyphicon-remove"></span> Remove</a>
			</td>
		</tr><?php endforeach; ?>
	</table>
</div>