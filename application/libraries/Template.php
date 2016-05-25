<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template
{
	protected $ci;

	public function __construct()
	{
        $this->ci =& get_instance();
	}

	public function header()
	{
		$this->ci->load->view('template/header.php');
	}

	public function footer()
	{
		$this->ci->load->view('template/footer');
	}
}

/* End of file Template.php */
/* Location: ./application/libraries/Template.php */