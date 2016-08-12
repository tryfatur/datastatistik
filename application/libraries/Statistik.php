<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Statistik
{
	protected $ci;
	private $_portal_url;
	private $_api_url;
	private $_url_to_process;
	private $_result;
	private $_base_url;

	function __construct()
	{
		$this->ci =& get_instance();
	}

	/* Set portal data */
	function set_portal($portal = 'bandung')
	{
		if ($portal == 'bandung')
			$this->_portal_url = 'http://data.bandung.go.id';

		if ($portal == 'jakarta')
			$this->_portal_url = 'http://data.jakarta.go.id';

		if ($portal == 'nasional')
			$this->_portal_url = 'http://data.go.id';

		$this->_api_url = '/api/3/action/';
	}

	/*
	 Memeriksa apakah API portal bisa di akses atau tidak
	*/
	public function portal_status($portal)
	{
		$this->set_portal($portal);
		$this->set_action('site_read');

		$headers = get_headers($this->_url_to_process);

		$response_code = substr($headers[0], 9, 3);

		if ($response_code != "200")
			return FALSE;

		return TRUE;
	}

	/*
		Data keterangan portal data
	*/
	public function portal_metadata($portal)
	{
		switch ($portal)
		{
			case 'bandung':
				$meta['url']          = 'http://data.bandung.go.id';
				$meta['title']        = 'Pemerintah Kota Bandung';
				$meta['portal_title'] = 'Portal Data Bandung';
				$meta['icon']         = base_url('assets/img/pemkot-bandung.png');
			break;

			case 'jakarta':
				$meta['url']          = 'http://data.jakarta.go.id';
				$meta['title']        = 'Pemerintah Provinsi DKI Jakarta';
				$meta['portal_title'] = 'Portal Data DKI Jakarta';
				$meta['icon']         = base_url('assets/img/pemprov-dki-jakarta.png');
			break;

			case 'nasional':
				$meta['url']          = 'http://data.go.id';
				$meta['title']        = 'Republik Indonesia';
				$meta['portal_title'] = 'Portal Data Indonesia';
				$meta['icon']         = base_url('assets/img/nasional.png');
			break;
		}

		return $meta;
	}

	/*
		Mengkonfirgurasi aksi yang akan di eksekusi
	*/
	public function set_action($action)
	{
		$this->_url_to_process = $this->_portal_url.$this->_api_url.$action;
	}

	/*
		Memproses API berdasarkan URL
	*/
	public function process_api($url = '')
	{
		if (empty($url))
			$result = file_get_contents($this->_url_to_process);
		else
			$result = file_get_contents($url);

		return json_decode($result);
	}

	/*
		Mengambil jumlah total organisasi dalam sebuah portal
	*/
	public function total_org()
	{
		$this->set_action('organization_list');
		$result = $this->process_api();

		return count($result->result);
	}

	/*
		Mengambil jumlah total grup dalam sebuah portal
	*/
	public function total_group()
	{
		$this->set_action('group_list');
		$result = $this->process_api();

		return count($result->result);
	}

	/*
		Mengambil jumlah total dataset dalam sebuah portal
	*/
	public function total_package()
	{
		$this->set_action('package_list');
		$result = $this->process_api();

		return count($result->result);
	}

	/*
		Mengambil top ten org berdasarkan dataset
	*/
	public function get_top_org()
	{
		if ($this->_portal_url == 'http://data.go.id')
			$this->set_action('organization_list?sort=packages%20desc&all_fields=true&limit=10');
		else
			$this->set_action('organization_list?sort=package_count%20desc&all_fields=true&limit=10');

		$result = $this->process_api();

		if (count($result->result) > 10)
		{
			for ($i=0; $i < 10; $i++)
				$new_array[$i] = $result->result[$i];

			return $new_array;
		}
		
		return $result->result;
	}

	/*
		Mengambil top ten group berdasarkan dataset
	*/
	public function get_top_group()
	{
		if ($this->_portal_url == 'http://data.go.id')
			$this->set_action('group_list?sort=packages%20desc&all_fields=true&limit=10');
		else
			$this->set_action('group_list?sort=package_count%20desc&all_fields=true&limit=10');

		$result = $this->process_api();

		if (count($result->result) > 10)
		{
			for ($i=0; $i < 10; $i++)
				$new_array[$i] = $result->result[$i];

			return $new_array;
		}
		
		return $result->result;
	}

	/*
		Mengambil daftar dataset pada organisasi atau grup tertentu
	*/
	public function dataset_list($param, $type)
	{
		if ($type == 'org')
			$this->set_action('package_search?start=0&rows=318&sort=created%20desc&q=organization:'.$param);
		else
			$this->set_action('package_search?start=0&rows=157&sort=created%20desc&q=groups:'.$param);

		$result = $this->process_api()->result;

		if ($result->count > 0)
		{
			$total_results = (int)count($result->results);
			for ($i=0; $i < $total_results; $i++)
			{ 
				$total_results_array = (int)count($result->results[$i]);
				for ($j=0; $j < $total_results_array; $j++)
				{
					$data_created[$i]['org']     = $result->results[$i]->organization->title;
					$data_created[$i]['name']    = trim($result->results[$i]->title);
					$data_created[$i]['org_uri'] = $result->results[$i]->organization->name;

					// Jika dataset tidak memiliki grup
					if (!empty($result->results[$i]->groups))
					{
						$data_created[$i]['groups'] = $result->results[$i]->groups[0]->title;
						$data_created[$i]['groups_uri'] = strtolower(str_replace(' ', '-', $result->results[$i]->groups[0]->title));
					}
					else
					{
						$data_created[$i]['groups'] = '';
						$data_created[$i]['groups_uri'] = '';
					}

					// Jika dataset tidak memiliki resource <- Aneh
					if (!empty($result->results[$i]->resources))
					{
						$created = explode('T', $result->results[$i]->resources[$j]->created);
						$time = explode('.', $created[1]);

						$data_created[$i]['date_created'] = $created[0];
						$data_created[$i]['time_created'] = $time[0];
					}
					else
					{
						$data_created[$i]['date_created'] = '';
						$data_created[$i]['time_created'] = '';
					}

					$data_created[$i]['uri'] = $result->results[$i]->name;
				}
			}
			return $data_created;
		}
		else
		{
			return FALSE;
		}
	}

	/*
		Melihat aktifitas organisasi berdasarkan tanggal upload dataset
	*/
	public function aktifitas_organisasi($org, $axis)
	{
		$data = $this->dataset_list($org, 'org');

		$total = (int)count($data);
		for ($i=0; $i < $total; $i++)
		{
			if (!empty($data[$i]['date_created']))
			{
				$month = explode('-', $data[$i]['date_created']); // Memisahkan tahun, bulan dan hari
				$date_created[] = $month[0].'-'.$month[1]; // Menggabungkan tahun dan bulan
			}
		}

		$populated = array_count_values($date_created); // Pengelompokan berdasarkan tahun dan bulan

		ksort($populated); // Pengurutan ascending

		if ($axis == 'x')
		{
			foreach ($populated as $key => $value)
				$date[] = "'".$key."'";

			return $date_populated = implode(',', $date);
		}
		else
		{
			return $date_populated = implode(',', $populated);
		}
	}

	/*
		Melihat aktifitas group berdasarkan tanggal upload dataset
	*/
	public function aktifitas_group($group, $axis)
	{
		$data = $this->dataset_list($group, 'group');

		$total = (int)count($data);
		for ($i=0; $i < $total; $i++)
		{
			$month = explode('-', $data[$i]['date_created']); // Memisahkan tahun, bulan dan hari
			$date_created[] = $month[0].'-'.$month[1]; // Menggabungkan tahun dan bulan
		}

		$populated = array_count_values($date_created); // Pengelompokan berdasarkan tahun dan bulan

		ksort($populated); // Pengurutan ascending

		if ($axis == 'x')
		{
			foreach ($populated as $key => $value)
				$date[] = "'".$key."'";

			return $date_populated = implode(',', $date);
		}
		else
		{
			return $date_populated = implode(',', $populated);
		}
	}

	/*
		Mengambil 5 dataset terakhir dari grup/organisasi tertentu
	*/
	public function latest_dataset($param, $type)
	{
		if ($type == 'org')
			$this->set_action('package_search?start=0&rows=5&sort=created%20desc&q=organization:'.$param);
		else
			$this->set_action('package_search?start=0&rows=5&sort=created%20desc&q=groups:'.$param);

		$result = $this->process_api()->result->results;

		$total_result = (int)count($result);
		for ($i=0; $i < $total_result; $i++)
		{ 
			$total_results_array = (int)count($result[$i]);
			for ($j=0; $j < $total_results_array; $j++)
			{
				$date = explode('T', $result[$i]->resources[$j]->created);

				$new_array[$i]['name']       = $result[$i]->name;
				$new_array[$i]['title']      = $result[$i]->title;
				$new_array[$i]['notes']      = $result[$i]->notes;
				$new_array[$i]['format']     = $result[$i]->resources[$j]->format;
				$new_array[$i]['date']       = $date[0];
				$new_array[$i]['time']       = $date[1];
				$new_array[$i]['org_title'] = $result[$i]->organization->title;
				$new_array[$i]['org_name']   = $result[$i]->organization->name;
			}
		}

		return $new_array;
	}

	/*
		Mengambil 5 dataset terakhir dari portal tertentu
	*/
	public function latest_portal_dataset()
	{
		$this->set_action('current_package_list_with_resources?limit=5');

		$result = $this->process_api()->result;

		$total = (int)count($result);
		for ($i=0; $i < $total; $i++)
		{ 
			$total_results_array = (int)count($result[$i]);
			for ($j=0; $j < $total_results_array; $j++)
			{
				$date = explode('T', $result[$i]->resources[$j]->created);

				$new_array[$i]['name']       = $result[$i]->name;
				$new_array[$i]['title']      = $result[$i]->title;
				$new_array[$i]['notes']      = $result[$i]->notes;
				$new_array[$i]['format']     = $result[$i]->resources[$j]->format;
				$new_array[$i]['date']       = $date[0];
				$new_array[$i]['time']       = $date[1];
				$new_array[$i]['org_title']  = $result[$i]->organization->title;
				$new_array[$i]['org_name']   = $result[$i]->organization->name;
			}
		}

		return $new_array;
	}

	/*
		Mengeksport data portal berdasarkan grup/organisasi dan portal tertentu dalam format CSV atau JSON
	*/
	public function export($portal, $type, $param, $format = 'csv')
	{
		($type == 'group_list') ? $type = 'group' : $type = 'org';

		$this->set_portal($portal);
		$result = $this->dataset_list($param, $type);

		if (is_array($result))
		{
			if ($format == 'json')
			{
				return json_encode($result);
			}
			elseif ($format == 'text')
			{
				return json_encode($this->sebaran_grup($result));
			}
			else
			{
				$total = (int)count($result);
				for ($i=0; $i < $total; $i++)
					$merger[$i] = implode(';', $result[$i]);
				
				$filename = 'data-'.$portal.'-'.$type.'-'.$param;

				$this->_generate_csv($merger, $filename);
			}
		}
		else
		{
			return FALSE;
		}
	}

	/*
		Mengeksport seluruh data portal berdasarkan grup/organisasi dan portal tertentu dalam format CSV atau JSON
	*/
	public function export_bulk($portal, $type, $format = 'csv')
	{
		$this->set_portal($portal);
		$this->set_action($type.'?all_fields=true');

		$result = $this->process_api()->result;

		$total = (int)count($result);

		if ($portal == 'nasional')
		{
			for ($i=0; $i < $total; $i++)
			{ 
				if ($result[$i]->packages > 0)
					$list[] = $result[$i]->name;
			}
		}
		else
		{
			for ($i=0; $i < $total; $i++)
			{ 
				if ($result[$i]->package_count > 0)
					$list[] = $result[$i]->name;
			}
		}

		($type == 'group_list') ? $type = 'group' : $type = 'org';

		$total_list = (int)count($list);
		for ($i=0; $i < $total_list; $i++)
			$dataset_list[] = $this->dataset_list($list[$i], $type);

		$total_dataset_list = (int)count($dataset_list);
		for ($i=0; $i < $total_dataset_list; $i++)
		{ 
			$total_dataset_list_array = (int)count($dataset_list[$i]);
			for ($j=0; $j < $total_dataset_list_array ; $j++)
				$merger[$i][$j] = implode(';', $dataset_list[$i][$j]);
		}

		if ($format == 'json')
		{
			return json_encode($dataset_list);
		}
		elseif ($format == 'text')
		{
			return json_encode($this->sebaran_grup($dataset_list, 'bulk'));
		}
		else
		{
			$filename = 'data-'.$type.'-'.$portal;

			$this->_generate_csv($merger, $filename, true);
		}
	}

	/*
		Meng-generate dokumen CSV
	*/
	private function _generate_csv($data, $filename, $bulk = false)
	{
		// Output headers, file CSV akan langsung di unduh (autodownload)
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename='.$filename.'.csv');

		$output = fopen('php://output', 'w'); // Create a file pointer connected to the output stream

		fputcsv($output, array('organisasi', 'dataset', 'uri_org', 'group', 'tanggal_unggah', 'waktu_unggah', 'uri'), ';'); // Header kolom

		if ($bulk)
		{
			$total_data = (int)count($data);
			for ($i=0; $i < $total_data; $i++)
			{
				foreach ($data[$i] as $line)
					fputcsv($output, explode(';', $line), ';');
			}
		}
		else
		{
			foreach ($data as $line)
				fputcsv($output, explode(',', $line));
		}
	}

	/*
		Mengeksport sumbu x dan y untuk melihat aktifitas organisasi/grup
	*/
	public function export_axis($axis, $data)
	{
		$total_data = (int)count($data);
		for ($i=0; $i < $total_data; $i++)
			$xAxies[$i] = "'".$data[$i]->display_name."'";

		if ($this->_portal_url == 'http://data.go.id')
		{
			for ($i=0; $i < $total_data; $i++)
				$yAxies[$i] = $data[$i]->packages;
		}
		else
		{
			for ($i=0; $i < $total_data; $i++)
			$yAxies[$i] = $data[$i]->package_count;
		}

		if ($axis == 'x')
			return implode(',', $xAxies);
		else
			return implode(',', $yAxies);
	}

	public function sebaran_grup($data, $type = 'single')
	{
		$total_data = (int)count($data);
		if ($type == 'bulk')
		{
			for ($i=0; $i < $total_data; $i++)
			{
				$total_data_array = (int)count($data[$i]);
				for ($j=0; $j < $total_data_array; $j++)
					$group_list[] = $data[$i][$j]['groups'];
			}
		}
		else
		{
			for ($i=0; $i < $total_data; $i++)
				$group_list[] = $data[$i]['groups'];
		}

		$x_data = array_count_values($group_list);
		$total = count($group_list);

		$i = 0;
		foreach ($x_data as $key => $value)
		{
			if (!empty($key))
				$a_data[$i]['name'] = $key;
			else
				$a_data[$i]['name'] = 'Lain-lain';

			$persentase = ($value/$total) * 100;
			$a_data[$i]['y'] = round($persentase, 2);

			$i++;
		}

		return $a_data;
	}

	/*
		Konversi tanggal ke format Indonesia
	*/
	public function indonesian_date($date)
	{
		$BulanIndo = array("Januari", "Februari", "Maret",
						   "April", "Mei", "Juni",
						   "Juli", "Agustus", "September",
						   "Oktober", "November", "Desember");
	
		$tahun = substr($date, 0, 4); // memisahkan format tahun menggunakan substring
		$bulan = substr($date, 5, 2); // memisahkan format bulan menggunakan substring
		$tgl   = substr($date, 8, 2); // memisahkan format tanggal menggunakan substring
		
		$result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;
		return($result);
	}

	public function split_created($date)
	{
		return explode('T', $date);
	}
}

/* End of file Statistik.php */
/* Location: ./application/libraries/Statistik.php */