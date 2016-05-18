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
			<div id="top-group"></div>
		</div>

		<!-- jQuery -->
		<script src="//code.jquery.com/jquery.js"></script>
		<!-- Bootstrap JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
		<script src="http://code.highcharts.com/highcharts.js"></script>

	</body>
</html>
<script>
	$(function () {
		$('#top-org').highcharts({
			chart: {
				type: 'column'
			},
			title: {
				text: '10 Organisasi dengan Jumlah Dataset Terbanyak'
			},
			subtitle: {
				text: 'Sumber: <a href="http://data.bandung.go.id">Open Data Bandung</a>'
			},
			xAxis: {
				categories: ['dinas-kependudukan-dan-pencatatan-sipil',
							 'dinas-kesehatan',
							 'badan-perencanaan-pembangunan-daerah',
							 'sekretariat-daerah',
							 'dinas-kebudayaan-dan-pariwisata',
							 'dinas-pendidikan',
							 'badan-kepegawaian-daerah',
							 'badan-pusat-statistik',
							 'dinas-perhubungan'],
				labels: {
					rotation: -45,
				}
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
				data: [69, 39, 33, 28, 25, 25, 23, 22, 22]
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
				type: 'category',
				labels: {
					rotation: -45,
				}
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
				data: [
					['kependudukan-dan-ketenagakerjaan', 107],
					['ekonomi-dan-keuangan', 75],
					['lingkungan', 52],
					['infrastruktur', 40],
					['sosial', 38],
					['kesehatan', 34],
					['pendidikan', 29],
					['pariwisata-dan-kebudayaan', 26],
					['perhubungan', 11],
					['kebencanaan', 8]
				],
				dataLabels: {
					enabled: true,
					rotation: -90,
					color: '#FFFFFF',
					align: 'right',
					y: 10, // 10 pixels down from the top
					style: {
						fontSize: '13px',
						fontFamily: 'Verdana, sans-serif'
					}
				}
			}]
		});
	});
</script>