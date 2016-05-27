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
<div class="page-header">
	<h1>5 Dataset Terbaru <?= $result->display_name.' '.$meta['title'] ?></h1>
</div>
<?php for ($i=0; $i < count($latest_dataset); $i++): ?>
	<h3><?= $latest_dataset[$i]['title'] ?></h3>
<?php endfor; ?>
<hr>
<div id="detailStatistik"></div>
<hr>
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

<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script>
	$('#datasetList').DataTable({
		"language": {
					"url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Indonesian.json"
				},
		"searching" : false,
	});

	$(function () {
		$('#detailStatistik').highcharts({
			chart: {
				type: 'column'
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