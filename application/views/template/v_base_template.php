<!DOCTYPE html>
<html lang="">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Indonesia Open Data Statistics</title>

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="<?= base_url('assets/css/lumen.css') ?>">
		<link rel="stylesheet" href="<?= base_url('assets/css/font-awesome.min.css') ?>">
		<link rel="stylesheet" href="<?= base_url('assets/css/datastatisik.css') ?>">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<?= $this->load->view('template/v_navbar.php', null, TRUE); ?>

		<div class="container">

			<?= $content; ?>
			
		</div> <!-- end.container -->
		<hr>
		<div class="container">
			<div class="text-muted text-right">
				<p>@tryfatur &#149; Indonesia Open Data Statistics &#149; 2016</p>
			</div>
		</div>
		<!-- jQuery -->
		<script src="<?= base_url('assets/js/jquery-1.12.4.min.js') ?>"></script>
		<!-- Bootstrap JavaScript -->
		<script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
		<script src="http://code.highcharts.com/highcharts.js"></script>
		<script src="https://code.highcharts.com/highcharts-3d.js"></script>
		<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
		<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
		<script src="<?= base_url('assets/js/countUp.js') ?>"></script>
		<script src="<?= base_url('assets/js/moment.min.js') ?>"></script>
		<script>
			$('#datasetList_paginate').addClass("pull-right");
			$('#datasetList').DataTable({
				"language": {
							"url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Indonesian.json"
						},
				"searching" : false,
			});

			$('#datasetData').hover().addClass("info");
		</script>
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
						categories: [<?= $top_org_name ?>],
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
						data: [<?= $top_org_count ?>]
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
						categories: [<?= $top_group_name ?>],
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
						data: [<?= $top_group_count ?>],
					}]
				});
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
	</body>
</html>