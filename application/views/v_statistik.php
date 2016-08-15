<div class="page-header">
	<div class="col-sm-1">
		<img src="<?= $meta['icon'] ?>" style="height: 48px">
	</div>
	<h1>
		<?= $meta['portal_title'] ?><small> (<a href="<?= $meta['url'] ?>" target="_blank"><?= $meta['url'] ?></a>)</small>
	</h1>
</div>
<div class="row text-center">
	<div class="col-md-4">
		<input type="hidden" id="total_dataset" value="<?= $package_list ?>">
		<h1 style="font-size: 150px" id="totalDataset"></h1>
		<h3>Dataset</h3>
	</div>
	<div class="col-md-4">
		<input type="hidden" id="total_org" value="<?= $org_list ?>">
		<h1 style="font-size: 150px" id="totalOrg"></h1>
		<h3>Organisasi</h3>
	</div>
	<div class="col-md-4">
		<input type="hidden" id="total_group" value="<?= $group_list ?>">
		<h1 style="font-size: 150px" id="totalGroup"></h1>
		<h3>Grup</h3>
	</div>
</div>

<hr>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">
			<i class="fa fa-fw fa-calendar"></i> Aktifitas Pengunggahan Dataset Tahun 2016
		</h3>
	</div>
	<div class="panel-body">
		<center>
			<div id="cal-heatmap"></div>
		</center>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-fw fa-clock-o"></i> 5 Dataset Terbaru <?= $meta['portal_title'] ?>
				</h3>
			</div>
			<div class="list-group">
			<?php for ($i=0; $i < count($latest_dataset); $i++): ?>
				<a href="<?= $meta['url'].'/dataset/'.$latest_dataset[$i]['name'] ?>" class="list-group-item">
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
					<i class="fa fa-fw fa-pie-chart"></i> Sebaran Grup Dataset <?= $meta['portal_title'] ?>
				</h3>
			</div>
			<div class="panel-body">
				<div id="pieGroup"></div>
			</div>
		</div>
	</div>
</div>

<hr>

<div id="top-org"></div>
<div class="text-center">
	<a class="btn btn-primary" data-toggle="modal" href='#topten-org'>
		<i class="fa fa-fw fa-eye"></i> Lihat Statistik Lebih Detail
	</a>
</div>
<hr>
<div id="top-group"></div>
<div class="text-center">
	<a class="btn btn-primary" data-toggle="modal" href='#topten-group'>
		<i class="fa fa-fw fa-eye"></i> Lihat Statistik Lebih Detail
	</a>
</div>

<div class="modal fade" id="topten-org">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">10 Organisasi dengan Jumlah Dataset Terbanyak</h4>
			</div>
			<table class="table table-striped table-bordered table-condensed">
				<thead>
					<th align="center">#</th>
					<th align="center">Nama Organisasi</th>
					<th align="center">Aksi</th>
				</thead>
				<tbody>
				<?php for ($i=0; $i < count($result_org); $i++):?>
					<tr>
						<td align="center"><?= $i+1 ?></td>
						<td><?= $result_org[$i]->display_name ?></td>
						<td align="center">
							<a href="<?= base_url('start').'/detail/'.$this->uri->segment(3).'/org/'.$result_org[$i]->name ?>" class="btn btn-success">
								<i class="fa fa-fw fa-eye"></i> Detail
							</a>
						</td>
					</tr>
				<?php endfor; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="modal fade" id="topten-group">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">10 Organisasi dengan Jumlah Dataset Terbanyak</h4>
			</div>
			<table class="table table-striped table-bordered table-condensed">
				<thead>
					<th align="center">#</th>
					<th align="center">Nama Grup</th>
					<th align="center">Aksi</th>
				</thead>
				<tbody>
				<?php for ($i=0; $i < count($result_group); $i++):?>
					<tr>
						<td align="center"><?= $i+1 ?></td>
						<td><?= $result_group[$i]->display_name ?></td>
						<td align="center">
							<a href="<?= base_url('start').'/detail/'.$this->uri->segment(3).'/group/'.$result_group[$i]->name ?>" class="btn btn-success">
								<i class="fa fa-fw fa-eye"></i> Detail
							</a>
						</td>
					</tr>
				<?php endfor; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript" src="//cdn.jsdelivr.net/countupjs/1.7.1/countUp.min.js"></script>
<script>
	//countUp.js
	var options = {
		useEasing : true, 
		useGrouping : false
	};

	var dataset = new CountUp("totalDataset", 0, document.getElementById("total_dataset").value, 0, 7, options);
	var org = new CountUp("totalOrg", 0, document.getElementById("total_org").value, 0, 7, options);
	var group = new CountUp("totalGroup", 0, document.getElementById("total_group").value, 0, 7, options);
	
	dataset.start();
	org.start();
	group.start();
</script>

<script>
	$(function () {
		var chartOrg, chartGroup;
		var options = {
			chart: {
				style: { fontFamily: 'Asap'},
				type: 'column',
				options3d: {
					enabled: true,
					alpha: 20,
					beta: 0,
					depth: 100,
					viewDistance: 25
				}
			},
			title: {},
			subtitle: {
				text: 'Sumber: <a href="<?= $meta['url']; ?>"><?= $meta['portal_title'] ?></a>'
			},
			plotOptions: { column: { depth: 25 }
			},
			yAxis: {
				min: 0,
				title: {
					text: 'Jumlah Dataset'
				}
			},
			xAxis: {},
			legend: {
				enabled: false
			},
			series: [{
				name: 'Jumlah Dataset'
			}]
		};

		options.chart.renderTo   = 'top-org';
		options.title.text       = "10 Organisasi dengan Jumlah Dataset Terbanyak";
		options.xAxis.categories = [<?= $top_org_name ?>];
		options.series[0].data   = [<?= $top_org_count ?>];

		chartOrg = new Highcharts.Chart(options);

		options.chart.renderTo   = 'top-group';
		options.title.text       = "10 Grup dengan Jumlah Dataset Terbanyak";
		options.xAxis.categories = [<?= $top_group_name ?>];
		options.series[0].data   = [<?= $top_group_count ?>];

		chartGroup = new Highcharts.Chart(options);
	});

	$(function () {
		var render = 'pieGroup';
		var options = {
			chart: {
				renderTo: render,
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie',
				style: { fontFamily: 'Asap'},
				options3d: {
					enabled: true,
					alpha: 45
				}
			},
			title: {text:''},
			subtitle: {
				text: 'Sumber: <a href="<?= $meta['url'] ?>"><?= $meta['portal_title'] ?></a>',
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
					showInLegend: true,
					innerSize: 100,
					depth: 45
				}
			},
			series: [{
				name: 'Total',
				colorByPoint: true
			}]
		};

		var active_url = window.location.toString();

		var url = active_url.replace('/statistik/', '/api/') + "/organization_list/sebaran-grup";
		var chart = new Highcharts.Chart(options);
		
		chart.showLoading("Mengambil data...")
		$.getJSON(url, function (data) {
			 options.series[0].data = data;
			 chart = new Highcharts.Chart(options);
			 chart.hideLoading();
		});
	});

	var active_url = window.location.toString();
	var url = active_url.replace('/statistik/', '/api/') + "/organization_list/aktifitas";

	console.log(url);
	
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