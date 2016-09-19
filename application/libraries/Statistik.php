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
		{
			/* Mengambil jumlah dataset yang kemudian akan digunakan untuk looping pengambil list dataset*/
			$this->set_action('package_search?start=0&q=organization:'.$param);
			$package_count = $this->process_api()->result->count;

			$this->set_action('package_search?start=0&rows='.$package_count.'&sort=created%20desc&q=organization:'.$param);
		}
		else
		{
			$this->set_action('package_search?start=0&q=groups:'.$param);
			$package_count = $this->process_api()->result->count;

			$this->set_action('package_search?start=0&rows='.$package_count.'&sort=created%20desc&q=groups:'.$param);
		}

		$result = $this->process_api()->result;

		if ($result->count > 0)
		{
			$total_results = (int)count($result->results);
			for ($i=0; $i < $total_results; $i++)
			{ 
				$total_results_array = (int)count($result->results[$i]);
				for ($j=0; $j < $total_results_array; $j++)
				{
					$name                        = trim(str_replace(',', '-', $result->results[$i]->title));
					$data_created[$i]['name']    = ucwords(strtolower($name));
					$data_created[$i]['org']     = $result->results[$i]->organization->title;
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

						$data_created[$i]['date_created'] = strtotime($created[0]);
						$data_created[$i]['time_created'] = strtotime($time[0]);
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

				$new_array[$i]['name']      = $result[$i]->name;
				$new_array[$i]['title']     = $result[$i]->title;
				$new_array[$i]['notes']     = $result[$i]->notes;
				$new_array[$i]['format']    = $result[$i]->resources[$j]->format;
				$new_array[$i]['date']      = $date[0];
				$new_array[$i]['time']      = $date[1];
				$new_array[$i]['org_title'] = $result[$i]->organization->title;
				$new_array[$i]['org_name']  = $result[$i]->organization->name;
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

				$new_array[$i]['name']      = $result[$i]->name;
				$new_array[$i]['title']     = $result[$i]->title;
				$new_array[$i]['notes']     = $result[$i]->notes;
				$new_array[$i]['format']    = $result[$i]->resources[$j]->format;
				$new_array[$i]['date']      = $date[0];
				$new_array[$i]['time']      = $date[1];
				$new_array[$i]['org_title'] = $result[$i]->organization->title;
				$new_array[$i]['org_name']  = $result[$i]->organization->name;
			}
		}

		return $new_array;
	}

	/*
		Mengeksport data portal berdasarkan grup/organisasi dan portal tertentu dalam format CSV atau JSON
	*/
	public function export($portal, $source, $api_type, $param, $format = 'csv')
	{
		($source == 'group_list') ? $source = 'group' : $source = 'org';

		$this->set_portal($portal);
		$result = $this->dataset_list($param, $source);

		if (is_array($result))
		{
			if ($api_type == 'unduh')
			{
				if ($format == 'csv')
				{
					$total = (int)count($result);
					for ($i=0; $i < $total; $i++)
						$merger[$i] = implode(',', $result[$i]);
					
					$filename = 'data-'.$portal.'-'.$source.'-'.$param;

					$this->_generate_csv($merger, $filename);
				}
				else
				{
					return json_encode($result);
				}
			}
			elseif ($api_type == 'sebaran-grup')
			{
				return $this->sebaran_grup($result, 'single');
			}
			elseif ($api_type == 'aktifitas')
			{
				return $this->aktifitas($result, 'single');
			}
			else
			{
				return FALSE;
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
	public function export_bulk($portal, $source, $api_type, $format = 'csv')
	{
		$this->set_portal($portal);
		$this->set_action($source.'?all_fields=true');

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

		($source == 'group_list') ? $source = 'group' : $source = 'org';

		$total_list = (int)count($list);
		for ($i=0; $i < $total_list; $i++)
			$dataset_list[] = $this->dataset_list($list[$i], $source);

		if ($api_type === 'unduh')
		{
			if ($format == 'csv')
			{
				$total_dataset_list = (int)count($dataset_list);
				for ($i=0; $i < $total_dataset_list; $i++)
				{ 
					$total_dataset_list_array = (int)count($dataset_list[$i]);
					for ($j=0; $j < $total_dataset_list_array ; $j++)
						$merger[$i][$j] = implode(',', $dataset_list[$i][$j]);
				}

				$filename = 'data-'.$source.'-'.$portal;

				$this->_generate_csv($merger, $filename, true);
			}
			else
			{
				return json_encode($dataset_list);
			}
		}
		elseif ($api_type == 'sebaran-grup')
		{
			return $this->sebaran_grup($dataset_list, 'bulk');
		}
		elseif ($api_type == 'aktifitas')
		{
			return $this->aktifitas($dataset_list, 'bulk');
		}
		else
		{
			return FALSE;
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

		fputcsv($output, array('dataset', 'organisasi', 'uri_org', 'group', 'uri_group', 'tanggal_unggah', 'waktu_unggah', 'uri'), ','); // Header kolom

		if ($bulk)
		{
			$total_data = (int)count($data);
			for ($i=0; $i < $total_data; $i++)
			{
				foreach ($data[$i] as $line)
					fputcsv($output, explode(',', $line), ',');
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

	public function aktifitas($data, $source)
	{
		$total_data = (int)count($data);
		if ($source == 'bulk')
		{
			for ($i=0; $i < $total_data; $i++)
			{
				$total_data_array = (int)count($data[$i]);
				for ($j=0; $j < $total_data_array; $j++)
					if (!empty($data[$i][$j]['date_created']))
						$date_list[] = $data[$i][$j]['date_created'];
			}
		}
		else
		{
			for ($i=0; $i < $total_data; $i++)
				if (!empty($data[$i]['date_created']))
					$date_list[] = $data[$i]['date_created'];
		}

		$result = array_count_values($date_list);

		return json_encode($result);
	}

	public function sebaran_grup($data, $source)
	{
		$total_data = (int)count($data);
		
		if ($source == 'bulk')
		{
			for ($i=0; $i < $total_data; $i++)
			{
				$total_data_array = (int)count($data[$i]);
				for ($j=0; $j < $total_data_array; $j++)
					$list[] = $data[$i][$j]['groups'];
			}
		}
		else
		{
			for ($i=0; $i < $total_data; $i++)
				$list[] = $data[$i]['groups'];
		}

		$populated_grup = array_count_values($list);
		$total = count($list);

		$i = 0;
		foreach ($populated_grup as $key => $value)
		{
			if (!empty($key))
				$result[$i]['name'] = $key;
			else
				$result[$i]['name'] = 'Tidak Memiliki Grup';

			$persentase = ($value/$total) * 100;
			$result[$i]['y'] = round($persentase, 2);

			$i++;
		}

		return json_encode($result);
	}

	/*
		Konversi tanggal ke format Indonesia
	*/
	public function indonesian_date($date)
	{
		if (is_int($date))
			$date = date('Y-m-d', $date);

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