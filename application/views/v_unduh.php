<div class="page-header">
	<h1><i class="fa fa-fw fa-download"></i> Unduh Data Mentah</h1>
</div>
<form class="form-horizontal" id="downloadForm" method="post" action="<?= base_url('start/unduh_data') ?>">
	<div class="form-group">
		<label class="col-sm-2 control-label">Portal</label>
		<div class="col-sm-10">
			<select name="unduh[portal]" id="portal" class="form-control" onchange="selectPortal(this.value);">
				<option value="">-- Pilih Portal --</option>
				<option value="nasional">Portal Data Indonesia</option>
				<option value="jakarta">Portal Data Pemerintah Provinsi DKI Jakarta</option>
				<option value="bandung">Portal Data Pemerintah Kota Bandung</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Jenis</label>
		<div class="col-sm-10">
			<select name="unduh[jenis]" class="form-control" id="jenis">
				<option value="">-- Pilih Organisasi/Grup --</option>
				<option value="organization_list">Organisasi</option>
				<option value="group_list">Grup</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Organisasi/Grup</label>
		<div class="col-sm-10">
			<select name="unduh[data]" class="form-control" id="data">
				<option value="">-- Pilih Organisasi/Grup --</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button id="downloadButton" type="submit" class="btn btn-success btn-lg pull-right"><i class="fa fa-fw fa-download"></i> Unduh Data</button>
		</div>
	</div>
</form>

<script src="<?= base_url('assets/js/downloadData.js') ?>"></script>