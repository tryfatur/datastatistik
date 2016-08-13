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

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">
			<i class="fa fa-fw fa-calendar"></i> Aktifitas Pengunggahan Dataset 
		</h3>
	</div>
	<div class="panel-body">
		<center>
			<div id="cal-heatmap"></div>
		</center>
	</div>
</div>

<hr>



<?php if ($this->uri->segment(4) === 'group'): ?>
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">
				<i class="fa fa-fw fa-clock-o"></i> 5 Dataset Terakhir
			</h3>
		</div>
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
	</div>
<?php else: ?>
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						<i class="fa fa-fw fa-clock-o"></i> 5 Dataset Terakhir
					</h3>
				</div>
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
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						<i class="fa fa-fw fa-pie-chart"></i> Sebaran Grup Dataset
					</h3>
				</div>
				<div class="panel-body">
					<div id="chartGrup"></div>
				</div>
			</div>
		</div>
	</div>
<?php endif ?>

<hr>


<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">
			<i class="fa fa-fw fa-database"></i> Daftar Dataset
		</h3>
	</div>
	<div class="panel-body">
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
	</div>
</div>

<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script>
	$('#datasetList').DataTable({
		"language": {
					"url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Indonesian.json"
				}
	});

	//Sebaran Grup Dataset
	$(function () {
		var render = 'chartGrup';
		var options = {
			chart: {
				renderTo: render,
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie',
				style: { fontFamily: 'Asap'}
			},
			title: {
				text: ''
			},
			subtitle: {
				text: 'Sumber: <a href="<?= $meta['url'] ?>"><?= $meta['portal_title'] ?></a>'
			},
			tooltip: {
				pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: false
					},
					showInLegend: true
				}
			},
			series: [{
				name: 'Total',
				colorByPoint: true
			}]
		};

		var active_url = window.location.toString();
		var url = active_url.replace('detail', 'api');
			url = url.replace('org', 'organization_list/sebaran-grup');

		var chart = new Highcharts.Chart(options);

		chart.showLoading("Mengambil data...");

		$.getJSON(url, function (data) {
			 options.series[0].data = data;
			 chart = new Highcharts.Chart(options);
			 chart.hideLoading();
		});
	});

	var active_url = window.location.toString();
	var url = active_url.replace('detail', 'api');
		url = url.replace('org', 'organization_list/aktifitas');
	
	var cal = new CalHeatMap();
	cal.init({
		domain: "month",
		subDomain: "day",
		range: 12,
		cellSize: 13,
		domainGutter: 10,
		data: url,
		displayLegend: true,
		start: new Date(2016, 0),
		minDate: new Date(2016, 0),
		maxDate: new Date(2016, 12),
		tooltip: true,
		legend: [20, 40, 60, 80, 100],
		legendHorizontalPosition: "right"
	});
</script>