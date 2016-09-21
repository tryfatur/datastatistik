<div class="page-header">
	<div class="col-sm-1">
		<img src="<?= $meta['icon'] ?>" style="height: 48px">
	</div>
	<h1>
		<?= $meta['portal_title'] ?><small> (<a href="<?= $meta['url'] ?>" target="_blank"><?= $meta['url'] ?></a>)</small>
	</h1>
</div>
<div class="row">
	<!-- <div class="col-md-12">
		<form>
			<div class="form-group">
				<input type="text" name="search" class="form-control input-lg" placeholder="Organisasi apa yang Anda cari ?" list="orgList">
			</div>
		</form>
	</div> -->
	<?php foreach ($list as $key => $value): ?>
	<div class="col-sm-6 col-md-3">
		<div class="thumbnail">
			<img src="<?= $value->image_display_url ?>">
			<div class="caption">
				<h4><?= $value->title; ?></h4>
			</div>
			<p class="text-center">
				<a href="<?= base_url('start/detail').'/'.$this->uri->segment(3).'/org/'.$value->name ?>" class="btn btn-primary" role="button">
					Lihat Statistik
				</a>
			</p>
		</div>
	</div>
	<?php endforeach ?>
</div>