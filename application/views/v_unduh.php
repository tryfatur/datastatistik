<ul class="nav nav-tabs">
	<li class="active">
		<a href="#singleDownload" data-toggle="tab" aria-expanded="true">
			<i class="fa fa-fw fa-file"></i> Unduh Data Per Organisasi/Grup
		</a>
	</li>
	<li>
		<a href="#bulkDownload" data-toggle="tab" aria-expanded="false">
			<i class="fa fa-fw fa-suitcase"></i> Unduh Data Gabungan
		</a>
	</li>
</ul>
<div id="downloadTabContent" class="tab-content">
	<div class="tab-pane fade active in" id="singleDownload">
		<div class="page-header">
			<h1><i class="fa fa-fw fa-file"></i> Unduh Data Per Organisasi/Grup</h1>
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
				<div class="col-sm-8"></div>
				<div class="col-sm-2">
					<a href="#" target="_blank" id="apiDataSingle" class="btn btn-success btn-lg pull-right">
						<i class="fa fa-fw fa-code"></i> API Data
					</a>
				</div>
				<div class="col-sm-2">
					<button id="downloadButton" type="submit" class="btn btn-success btn-lg pull-right">
						<i class="fa fa-fw fa-download"></i> Unduh Data
					</button>
				</div>
			</div>
		</form>
	</div>
	<div class="tab-pane fade" id="bulkDownload">
		<div class="page-header">
			<h1><i class="fa fa-fw fa-suitcase"></i> Unduh Data Gabungan</h1>
		</div>
		<form class="form-horizontal" id="bulkDownload" method="post" action="<?= base_url('start/unduh_data') ?>">
			<div class="form-group">
				<label class="col-sm-2 control-label">Portal</label>
				<div class="col-sm-10">
					<select name="unduh_gabung[portal]" id="portalGabung" class="form-control" onchange="selectPortal(this.value);">
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
					<select name="unduh_gabung[jenis]" class="form-control" id="jenisGabung">
						<option value="">-- Pilih Organisasi/Grup --</option>
						<option value="organization_list">Organisasi</option>
						<option value="group_list">Grup</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-8"></div>
				<div class="col-sm-2">
					<a href="#" target="_blank" id="apiData" class="btn btn-success btn-lg pull-right">
						<i class="fa fa-fw fa-code"></i> API Data
					</a>
				</div>
				<div class="col-sm-2">
					<button id="bulkButton" type="submit" class="btn btn-success btn-lg pull-right">
						<i class="fa fa-fw fa-download"></i> Unduh Data
					</button>
				</div>
			</div>
		</form>
	</div>
</div>

<script>
	$('#downloadButton').attr('disabled', 'disable');
	$('#jenis').attr('disabled', 'disable');
	$('#data').attr('disabled', 'disable');
	$('#jenisGabung').attr('disabled', 'disable');
	$('#bulkButton').attr('disabled', 'disable');
	$('#apiDataSingle').hide();
	$('#apiData').hide();

	function selectPortal(portal) {
		var portal_url;
		var action;

		if (portal == 'bandung') {
			portal_url = 'http://data.bandung.go.id/api/3/action/';
		}

		if (portal == 'jakarta') {
			portal_url = 'http://data.jakarta.go.id/api/3/action/';
		}

		if (portal == 'nasional') {
			portal_url = 'http://data.go.id/api/3/action/';
		}

		$('#jenis').empty();
		$('#jenis').append('<option value="">-- Pilih Organisasi/Grup --</option>',
			'<option value="organization_list">Organisasi</option>',
			'<option value="group_list">Grup</option>');

		$('#jenis').on('change', function () {
			$.ajax({
				dataType: "jsonp",
				url: portal_url + this.value + '?all_fields=true',
				success: function(data) {
					console.log(this.url);
					$('#data').empty();
					$('#data').append('<option value="">-- Pilih Organisasi/Grup --</option>');

					if (portal == 'nasional') {
						$.each(data.result, function (i, val) {
							if (val.packages > 0) {
								$('#data').append(
									'<option value="' + val.name + '">' + val.title + '</option>'
								);
							}
						});
					}
					else
					{
						$.each(data.result, function (i, val) {
							if (val.package_count > 0) {
								$('#data').append(
									'<option value="' + val.name + '">' + val.title + '</option>'
								);
							}
						});
					}
				}
			});
		});
	}

	$('#portal').change(function () {
		if ($('#portal').val()) {
			$('#jenis').removeAttr('disabled');
			$('#data').empty();
		}
	});

	$('#jenis').change(function () {
		if ($('#jenis').val()) {
			$('#data').removeAttr('disabled');
		}
	});

	$('#data').change(function () {
		if ($('#data').val()) {
			$('#downloadButton').removeAttr('disabled');
			$('#apiDataSingle').show();

			var portal = $('#portal').val();
			var jenis = $('#jenis').val();
			var data = $('#data').val();


			$('#apiDataSingle').attr('href', 'api/' + portal + '/' + jenis + '/' + data);
		}
	});

	$('#portalGabung').change(function () {
		if ($('#portalGabung').val()) {
			$('#jenisGabung').removeAttr('disabled');
		}
	});

	$('#jenisGabung').change(function () {
		if ($('#jenisGabung').val()) {
			$('#bulkButton').removeAttr('disabled');
			$('#apiData').show();

			var portal = $('#portalGabung').val();
			var jenis = $('#jenisGabung').val();


			$('#apiData').attr('href', 'api/' + 'bulk/' + portal + '/' + jenis);
		}
	});
</script>