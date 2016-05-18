<?php
	include 'Open_data.php';
	$open_data = new Open_data('jkt');
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
							<h1 style="font-size: 150px"><?php echo $open_data->basic_stats('dataset') ?></h1>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="thumbnail">
						<div class="caption text-center">
							<h4>Total Organisasi</h4>
							<h1 style="font-size: 150px"><?php echo $open_data->basic_stats('org') ?></h1>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="thumbnail">
						<div class="caption text-center">
							<h4>Total Grup</h4>
							<h1 style="font-size: 150px"><?php echo $open_data->basic_stats('group') ?></h1>
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
					alpha: 15,
					beta: 15,
					depth: 50,
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

		$('#top-group').highcharts({
			chart: {
				type: 'column'
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
				data: [<?php echo $open_data->export_axis('y', $grup) ?>],
			}]
		});
	});
</script>