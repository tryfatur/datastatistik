<?php
class Open_data
{
	private $_portal_url;
	private $_api_url;
	private $_url_to_process;
	private $_result;

	function __construct($portal = 'bdg')
	{
		if ($portal == 'bdg')
			$this->_portal_url = 'http://data.bandung.go.id';

		if ($portal == 'jkt')
			$this->_portal_url = 'http://data.jakarta.go.id';

		if ($portal == 'nasional')
			$this->_portal_url = 'http://data.go.id';

		$this->_api_url = '/api/3/action/';
	}

	public function set_action($action)
	{
		return $this->_url_to_process = $this->_portal_url.$this->_api_url.$action;
	}

	public function process_api($url = '')
	{
		if (!isset($url))
			$result = file_get_contents($this->_url_to_process);
		else
			$result = file_get_contents($url);

		return json_decode($result);
	}

	public function basic_stats($type)
	{
		if ($type == 'org')
			$url = $this->set_action('organization_list');

		if ($type == 'group')
			$url = $this->set_action('group_list');

		if ($type == 'dataset')
			$url = $this->set_action('package_list');

		$result = $this->process_api($url);

		return count($result->result);
	}

	public function get_top_org()
	{
		$this->set_action('organization_list');

		$result = $this->process_api($this->_url_to_process);
		$temp = 0;

		for ($i=0; $i < count($result->result); $i++)
		{
			$dataset_count = $this->process_api($this->set_action("package_search?q=organization:".$result->result[$i]."&start=0&rows=500"));

			$data[$result->result[$i]] = $dataset_count->result->count;
		}
		arsort($data);

		return $this->_result = array_slice($data, 0, 9, true);
	}

	public function get_top_grup()
	{
		$this->set_action('group_list');

		$result = $this->process_api($this->_url_to_process);
		$temp = 0;

		for ($i=0; $i < count($result->result); $i++)
		{
			$dataset_count = $this->process_api($this->set_action("package_search?q=groups:".$result->result[$i]."&start=0&rows=500"));

			$data[$result->result[$i]] = $dataset_count->result->count;
		}
		arsort($data);

		return $this->_result = array_slice($data, 0, 9, true);
	}

	private function _rename_title($title)
	{
		return ucwords(strtolower(str_replace('-', ' ', $title)));
	}

	public function export_axis($axis, $data = null)
	{
		if (is_null($data))
		{
			foreach ($this->_result as $key => $value)
			{
				$xAxies[] = "'".$this->_rename_title($key)."'";
				$yAxies[] = $value;
			}
		}
		else
		{
			foreach ($data as $key => $value)
			{
				$xAxies[] = "'".$this->_rename_title($key)."'";
				$yAxies[] = $value;
			}
		}

		if ($axis == 'x')
			return implode(',', $xAxies);
		else
			return implode(',', $yAxies);
	}
}
?>