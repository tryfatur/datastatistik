<?php
	include 'Open_data.php';
	$open_data = new Open_data();
?>
<!DOCTYPE html>
<html lang="">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Bandung Open Data Statistics</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	</head>
	<body>
		<h1 class="text-center">Bandung Open Data Statistic</h1>
		<hr>
		<div class="container">
			<div class="row">
				<div class="col-md-4">
					<div class="thumbnail">
						<div class="caption text-center">
							<h4>Total Dataset</h4>
							<input type="hidden" id="total_dataset" value="<?php echo $open_data->basic_stats('dataset') ?>">
							<h1 style="font-size: 150px" id="totalDataset"></h1>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="thumbnail">
						<div class="caption text-center">
							<h4>Total Organisasi</h4>
							<input type="hidden" id="total_org" value="<?php echo $open_data->basic_stats('org') ?>">
							<h1 style="font-size: 150px" id="totalOrg"></h1>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="thumbnail">
						<div class="caption text-center">
							<h4>Total Grup</h4>
							<input type="hidden" id="total_group" value="<?php echo $open_data->basic_stats('group') ?>">
							<h1 style="font-size: 150px" id="totalGroup"></h1>
						</div>
					</div>
				</div>
			</div>
			<div id="top-org"></div>
			<hr>
			<div id="top-group"></div>
		</div>

		<!-- jQuery -->
		<script src="//code.jquery.com/jquery.js"></script>
		<!-- Bootstrap JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
		<script src="http://code.highcharts.com/highcharts.js"></script>
		<script src="https://code.highcharts.com/highcharts-3d.js"></script>
		<script src="countUp.js"></script>
	</body>
</html>
<?php $org = $open_data->get_top_org(); ?>
<?php $grup = $open_data->get_top_grup(); ?>
<script>
	$(function () {
		$('#top-org').highcharts({
			chart: {
				type: 'column',
				options3d: {
					enabled: true,
					alpha: 20,
					beta: 0,
					depth: 100,
					viewDistance: 25
				}
			},
			title: {
				text: '10 Organisasi dengan Jumlah Dataset Terbanyak'
			},
			subtitle: {
				text: 'Sumber: <a href="http://data.bandung.go.id">Open Data Bandung</a>'
			},
			plotOptions: {
				column: {
					depth: 25
				}
			},
			xAxis: {
				categories: [<?php echo $open_data->export_axis('x', $org) ?>],
			},
			yAxis: {
				min: 0,
				title: {
					text: 'Jumlah Dataset'
				}
			},
			legend: {
				enabled: false
			},
			series: [{
				name: 'Jumlah Dataset',
				data: [<?php echo $open_data->export_axis('y', $org) ?>]
			}]
		});

		// Set up the chart
		var chart = new Highcharts.Chart({
			chart: {
				renderTo: 'top-group',
				type: 'column',
				options3d: {
					enabled: true,
					alpha: 20,
					beta: 0,
					depth: 100,
					viewDistance: 25
				}
			},
			title: {
				text: '10 Grup dengan Jumlah Dataset Terbanyak'
			},
			subtitle: {
				text: 'Sumber: <a href="http://data.bandung.go.id">Open Data Bandung</a>'
			},
			xAxis: {
				categories: [<?php echo $open_data->export_axis('x', $grup) ?>],
			},
			plotOptions: {
				column: {
					depth: 25
				}
			},
			legend: {
				enabled: false
			},
			series: [{
				name: 'Jumlah Dataset',
				data: [<?php echo $open_data->export_axis('y', $grup) ?>],
			}]
		});

		function showValues() {
			$('#alpha-value').html(chart.options.chart.options3d.alpha);
			$('#beta-value').html(chart.options.chart.options3d.beta);
			$('#depth-value').html(chart.options.chart.options3d.depth);
		}

		// Activate the sliders
		$('#sliders input').on('input change', function () {
			chart.options.chart.options3d[this.id] = this.value;
			showValues();
			chart.redraw(false);
		});

		showValues();
	});

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