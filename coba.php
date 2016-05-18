<?php
// header("content-type: application/json"); 
/*$result = $open_data->process_api('http://data.bandung.go.id/api/3/action/group_list');
	$temp = 0;

	for ($i=0; $i < count($result->result); $i++)
	{ 
		$dataset_count = $open_data->process_api("http://data.bandung.go.id/api/3/action/package_search?q=groups:".$result->result[$i]."&start=0&rows=500");

		$data[$result->result[$i]] = $dataset_count->result->count;
	}

	arsort($data);

	$top_ten = array_slice($data, 0, 9, true);

	echo "<pre>";
	print_r ($data);
	echo "</pre>";*/

	/*$top_ten = array('dinas-kependudukan-dan-pencatatan-sipil' => 69,
				'dinas-kesehatan' => 39,
				'badan-perencanaan-pembangunan-daerah' => 33,
				'sekretariat-daerah' => 28,
				'dinas-kebudayaan-dan-pariwisata' => 25,
				'dinas-pendidikan' => 25,
				'badan-kepegawaian-daerah' => 23,
				'badan-pusat-statistik' => 22,
				'dinas-perhubungan' => 22);*/

	$chart_groups = array("kependudukan-dan-ketenagakerjaan" => 107,
						"ekonomi-dan-keuangan" => 75,
						"lingkungan" => 52,
						"infrastruktur" => 40,
						"sosial" => 38,
						"kesehatan" => 34,
						"pendidikan" => 29,
						"pariwisata-dan-kebudayaan" => 26,
						"perhubungan" => 11,
						"kebencanaan" => 8);

	foreach ($chart_groups as $key => $value)
	{
		$xAxies[] = $key;
		$yAxies[] = $value;
	}

	echo implode(',', $xAxies);

?>