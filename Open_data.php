<?php
class Open_data
{
	private $_portal_url;
	private $_api_url;
	private $_url_to_process;

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

		$result = json_decode($result);

		return $result;
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
}
?>