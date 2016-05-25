<div class="page-header">
	<div class="col-sm-1">
		<img src="<?= $icon ?>" style="height: 48px">
	</div>
	<h1><?= $title ?><small> (<a href="<?= $url ?>" target="_blank"><?= $url ?></a>)</small></h1>
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
							<a href="<?= base_url('start').'/detail/'.$this->uri->segment(3).'/'.$result_org[$i]->name ?>" class="btn btn-success">
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
			<div class="modal-body">
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>