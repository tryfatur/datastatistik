<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
		<nav class="navbar navbar-blue navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="container-fluid">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="<?= base_url(); ?>">Indonesia Open Data Portal Statistics</a>
					</div>
				
					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse navbar-ex1-collapse">
						<ul class="nav navbar-nav navbar-right">
							<li><a href="<?= base_url() ?>">BERANDA</a></li>
							<li><a href="#">ISTILAH</a></li>
							<li><a href="#">CARA KERJA</a></li>
							<li><a href="https://github.com/tryfatur/datastatistik">TENTANG</a></li>
						</ul>
					</div><!-- /.navbar-collapse -->
				</div>
			</div>
		</nav>

		<div class="container">

			<div class="alert alert-danger page404">
				<h1 class="text-center" style="color: #fff"><i class="fa fa-frown-o fa-2x"></i></h1>
				<h1 class="text-center" style="color: #fff">Mohon Maaf.</h1>
				<h3 class="text-center" style="color: #fff">
					Halaman yang Anda maksud tidak ada atau portal yang bersangkutan tidak bisa diakses.
				</h3>
			</div>
			
		</div> <!-- end.container -->
		<hr>
		<div class="container">
			<div class="text-muted text-right">
				<p>@tryfatur &#149; Indonesia Open Data Statistics &#149; 2016</p>
			</div>
		</div>
		
		<script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
	</body>
</html>