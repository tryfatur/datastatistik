<?php $created = $this->statistik->split_created($result->created) ?>
<div class="page-header">
	<h1><?= $result->display_name.' '.$meta['title'] ?></h1>
	<blockquote>
	<?php if (empty($result->description)): ?>
		Organisasi/Group ini tidak memiliki deskripsi.
	<?php else: ?>
		<p><?= $result->description; ?></p>
	<?php endif ?>
	</blockquote>
	<p class="text-right">Bergabung sejak <?= $this->statistik->indonesian_date($created[0]) ?> &#149; <?= $result->package_count ?> dataset &#149; <?= $result->state ?></p>
</div>
<h2>5 Dataset Terakhir <?= $result->display_name.' '.$meta['title'] ?></h2>
<div class="list-group">
<?php for ($i=0; $i < count($latest_dataset); $i++): ?>
	<a href="<?= $meta['url'].'/dataset/'.$latest_dataset[$i]['name'] ?>" class="list-group-item" target="_blank">
		<h4 class="list-group-item-heading">
			<?= $latest_dataset[$i]['title'] ?>
			<small>
				| <?= $this->statistik->indonesian_date($latest_dataset[$i]['date']); ?>
				| <?= $latest_dataset[$i]['org_title'] ?>
			</small>
		</h4>
		<p class="list-group-item-text">
			<?= substr($latest_dataset[$i]['notes'], 0, 50) ?>...
		</p>
	</a>
<?php endfor; ?>
</div>
<hr>
<div id="detailStatistik"></div>
<hr>
<table class="table table-bordered table-condensed" id="datasetList">
	<thead>
		<th>No</th>
		<th>Dataset</th>
		<th>Grup</th>
		<th>Tanggal Unggah</th>
		<th>Waktu Unggah</th>
	</thead>
	<tbody>
	<?php $i = 1; ?>
	<?php foreach ($dataset_list as $key => $value): ?>
		<tr>
			<td align="center"><?= $i ?></td>
			<td><a href="<?= $meta['url'].'/dataset/'.$value['uri'] ?>" target="_blank"><?=$value['name'] ?></a></td>
			<td><?= $value['groups'] ?></td>
			<td align="center"><?= $this->statistik->indonesian_date($value['date_created'])?></td>
			<td align="center"><?=$value['time_created'] ?></td>
		</tr>
		<?php $i++; ?>
	<?php endforeach ?>
	</tbody>
</table>

<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script>
	$('#datasetList').DataTable({
		"language": {
					"url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Indonesian.json"
				}
	});

	$(function () {
		$('#detailStatistik').highcharts({
			chart: {
				type: 'column',
				style: { fontFamily: 'Asap'}
			},
			title: {
				text: 'Aktivitas Pengunggahan Dataset ' + '<?= $result->display_name ?>'
			},
			subtitle: {
				text: 'Sumber: <a href="<?= $meta['url'] ?>"><?= $meta['portal_title'] ?></a>'
			},
			plotOptions: {
	            line: {
	                dataLabels: {
	                    enabled: true
	                },
	                enableMouseTracking: false
	            }
	        },
	        xAxis: {
				categories: [<?= $detail_org_x; ?>],
			},
			yAxis: {
				min: 0,
				title: {
					text: 'Jumlah Dataset yang diunggah'
				}
			},
			legend: {
				enabled: false
			},
			series: [{
				name: 'Jumlah Dataset yang diunggah',
				data: [<?= $detail_org_y ?>]
			}]
		});
	});
</script>