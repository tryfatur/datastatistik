<!DOCTYPE html>
<html lang="">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Indonesia Open Data Portal Statistics</title>

		<link rel="stylesheet" href="<?= base_url('assets/css/lumen.css') ?>">
		<link rel="stylesheet" href="<?= base_url('assets/css/font-awesome.min.css') ?>">
		<link rel="stylesheet" href="<?= base_url('assets/css/datastatisik.css') ?>">

		<script src="<?= base_url('assets/js/jquery-1.12.4.min.js') ?>"></script>
		<script src="http://code.highcharts.com/highcharts.js"></script>
		<script src="https://code.highcharts.com/highcharts-3d.js"></script>
	</head>
	<body>
		<?= $this->load->view('template/v_navbar.php', null, TRUE); ?>

		<div class="container">

			<?= $content; ?>
			
		</div> <!-- end.container -->
		<hr>
		<div class="container">
			<div class="text-muted text-right">
				<p>@tryfatur &#149; Indonesia Open Data Portal Statistics &#149; 2016</p>
			</div>
		</div>
		
		<script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
	</body>
</html>