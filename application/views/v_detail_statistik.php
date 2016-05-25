<?php $created = $this->statistik->split_created($result->created) ?>
<div class="page-header">
	<h1><?= $result->display_name ?></h1>
	<p>Bergabung sejak <?= $this->statistik->indonesian_date($created[0]) ?> &#149; <?= $result->package_count ?> dataset &#149; <?= $result->state ?></p>
</div>
<table class="table table-bordered table-condensed" id="datasetList">
	<thead align="center">
		<th>No</th>
		<th>Dataset</th>
		<th>Tanggal Unggah</th>
		<th>Waktu Unggah</th>
	</thead>
	<tbody>
	<?php $i = 1; ?>
	<?php foreach ($dataset_list as $key => $value): ?>
		<tr id="#datasetData">
			<td align="center"><?= $i ?></td>
			<td><?=$value['name'] ?></td>
			<td align="center"><?= $this->statistik->indonesian_date($value['date_created'])?></td>
			<td align="center"><?=$value['time_created'] ?></td>
		</tr>
		<?php $i++; ?>
	<?php endforeach ?>
	</tbody>
</table>