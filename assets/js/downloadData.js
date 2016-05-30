$('#downloadButton').attr('disabled', 'disable');
$('#jenis').attr('disabled', 'disable');
$('#data').attr('disabled', 'disable');

function selectPortal(portal) {
	var url;
	var action;

	if (portal == 'bandung') {
		url = 'http://data.bandung.go.id/api/3/action/';
	}

	if (portal == 'jakarta') {
		url = 'http://data.jakarta.go.id/api/3/action/';
	}

	if (portal == 'nasional') {
		url = 'http://data.go.id/api/3/action/';
	}

	$('#jenis').empty();
	$('#jenis').append('<option value="">-- Pilih Organisasi/Grup --</option>',
		'<option value="organization_list">Organisasi</option>',
		'<option value="group_list">Grup</option>');

	$('#jenis').on('change', function () {
		$.ajax({
			dataType: "jsonp",
			url: url + this.value + '?all_fields=true',
			success: function(data) {
				$('#data').empty();
				$('#data').append('<option value="">-- Pilih Organisasi/Grup --</option>');
				$.each(data.result, function (i, val) {
					$('#data').append(
						'<option value="' + val.name + '">' + val.title + '</option>'
					);
				});
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
	}
});