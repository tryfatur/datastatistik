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

	function set_portal($portal = 'bandung')
	{
		if ($portal == 'bandung')
			$this->_portal_url = 'http://data.bandung.go.id';

		if ($portal == 'jakarta')
			$this->_portal_url = 'http://data.jakarta.go.id';

		if ($portal == 'nasional')
			$this->_portal_url = 'http://data.go.id';

		if ($portal == 'us')
			$this->_portal_url = 'http://catalog.data.gov';

		$this->_api_url = '/api/3/action/';

		//$this->_base_url = $this->_portal_url.$this->_api_url;
	}

	public function portal_status($portal)
	{
		$this->set_portal($portal);
		$this->set_action('site_read');
		
		$result = $this->process_api();

		if ($result === FALSE)
			return FALSE;
		else
			return $result->result;
	}

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

	public function set_action($action)
	{
		$this->_url_to_process = $this->_portal_url.$this->_api_url.$action;
	}

	public function process_api($url = '')
	{
		if (empty($url))
			$result = file_get_contents($this->_url_to_process);
		else
			$result = file_get_contents($url);

		return json_decode($result);
	}

	public function total_org()
	{
		$this->set_action('organization_list');
		$result = $this->process_api();

		return count($result->result);
	}

	public function total_group()
	{
		$this->set_action('group_list');
		$result = $this->process_api();

		return count($result->result);
	}

	public function total_package()
	{
		$this->set_action('package_list');
		$result = $this->process_api();

		return count($result->result);
	}

	public function get_top_org()
	{
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

	public function get_top_group()
	{
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

	public function dataset_list($param, $type)
	{
		if ($type == 'org')
			$this->set_action('package_search?start=0&rows=200&sort=created%20desc&q=organization:'.$param);
		else
			$this->set_action('package_search?start=0&rows=200&sort=created%20desc&q=groups:'.$param);

		$result = $this->process_api()->result;

		for ($i=0; $i < count($result->results); $i++)
		{ 
			for ($j=0; $j < count($result->results[$i]); $j++)
			{
				$created = explode('T', $result->results[$i]->resources[$j]->created);

				$data_created[$i]['name']         = trim($result->results[$i]->title);
				$data_created[$i]['uri']          = $result->results[$i]->name;
				$data_created[$i]['date_created'] = $created[0];
				$data_created[$i]['time_created'] = $created[1];
			}
		}

		return $data_created;
	}

	public function aktifitas_organisasi($org, $axis)
	{
		$data = $this->dataset_list($org, 'org');

		for ($i=0; $i < count($data); $i++)
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

	public function aktifitas_group($group, $axis)
	{
		$data = $this->dataset_list($group, 'group');

		for ($i=0; $i < count($data); $i++)
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

	public function latest_dataset($param, $type)
	{
		if ($type == 'org')
			$this->set_action('package_search?start=0&rows=5&sort=created%20desc&q=organization:'.$param);
		else
			$this->set_action('package_search?start=0&rows=5&sort=created%20desc&q=groups:'.$param);

		$result = $this->process_api()->result->results;

		for ($i=0; $i < count($result); $i++)
		{ 
			for ($j=0; $j < count($result[$i]); $j++)
			{
				$date = explode('T', $result[$i]->resources[$j]->created);

				$new_array[$i]['name']       = $result[$i]->name;
				$new_array[$i]['title']      = $result[$i]->title;
				$new_array[$i]['notes']      = $result[$i]->notes;
				$new_array[$i]['format']     = $result[$i]->resources[$j]->format;
				$new_array[$i]['date']       = $date[0];
				$new_array[$i]['time']       = $date[1];
				$new_array[$i]['org_tiltle'] = $result[$i]->organization->title;
				$new_array[$i]['org_name']   = $result[$i]->organization->name;
			}
		}

		return $new_array;
	}

	public function latest_portal_dataset()
	{
		$this->set_action('current_package_list_with_resources?limit=5');

		$result = $this->process_api()->result;

		for ($i=0; $i < count($result); $i++)
		{ 
			for ($j=0; $j < count($result[$i]); $j++)
			{
				$date = explode('T', $result[$i]->resources[$j]->created);

				$new_array[$i]['name']       = $result[$i]->name;
				$new_array[$i]['title']      = $result[$i]->title;
				$new_array[$i]['notes']      = $result[$i]->notes;
				$new_array[$i]['format']     = $result[$i]->resources[$j]->format;
				$new_array[$i]['date']       = $date[0];
				$new_array[$i]['time']       = $date[1];
				$new_array[$i]['org_tiltle'] = $result[$i]->organization->title;
				$new_array[$i]['org_name']   = $result[$i]->organization->name;
			}
		}

		return $new_array;
	}

	private function _rename_title($title)
	{
		return ucwords(strtolower(str_replace('-', ' ', $title)));
	}

	public function export_axis($axis, $data)
	{
		for ($i=0; $i < count($data); $i++)
			$xAxies[$i] = "'".$data[$i]->display_name."'";

		for ($i=0; $i < count($data); $i++)
			$yAxies[$i] = $data[$i]->package_count;

		if ($axis == 'x')
			return implode(',', $xAxies);
		else
			return implode(',', $yAxies);
	}

	public function split_created($created)
	{
		return explode('T', $created);
	}

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
}

/* End of file Statistik.php */
/* Location: ./application/libraries/Statistik.php */