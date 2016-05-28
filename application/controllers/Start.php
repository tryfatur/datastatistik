<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Start extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->library('statistik');
	}

	public function index()
	{
		$data['content'] = $this->load->view('v_home', '', TRUE);

		$this->load->view('template/v_base_template', $data);
	}

	public function statistik()
	{
		$portal = $this->uri->segment(3);

		$status = $this->statistik->portal_status($portal);

		if ($status)
		{
			if (isset($portal))
			{
				$this->statistik->set_portal($portal);

				$data['meta']           = $this->statistik->portal_metadata($portal);
				$data['latest_dataset'] = $this->statistik->latest_portal_dataset();

				$data['package_list'] = $this->statistik->total_package();
				$data['org_list']     = $this->statistik->total_org();
				$data['group_list']   = $this->statistik->total_group();

				$data['result_org']    = $this->statistik->get_top_org();
				$data['top_org_name']  = $this->statistik->export_axis('x', $data['result_org']);
				$data['top_org_count'] = $this->statistik->export_axis('y', $data['result_org']);

				$data['result_group']    = $this->statistik->get_top_group();
				$data['top_group_name']  = $this->statistik->export_axis('x', $data['result_group']);
				$data['top_group_count'] = $this->statistik->export_axis('y', $data['result_group']);

				$data['content'] = $this->load->view('v_statistik', $data, TRUE);

				$this->load->view('template/v_base_template', $data);
			}
			else
			{
				show_404();
			}
		}
		else
		{
			show_404();
		}
	}

	public function detail()
	{
		$this->statistik->set_portal($this->uri->segment(3));

		$data['meta'] = $this->statistik->portal_metadata($this->uri->segment(3));

		if ($this->uri->segment(4) == 'org')
		{
			$org_name = $this->uri->segment(5);
			$this->statistik->set_action('organization_show?id='.$org_name);

			$data['result']         = $this->statistik->process_api()->result;
			$data['dataset_list']   = $this->statistik->dataset_list($org_name, 'org');
			$data['latest_dataset'] = $this->statistik->latest_dataset($org_name, 'org');
			$data['detail_org_x']   = $this->statistik->aktifitas_organisasi($org_name, 'x');
			$data['detail_org_y']   = $this->statistik->aktifitas_organisasi($org_name, 'y');
		}

		if ($this->uri->segment(4) == 'group')
		{
			$group_name = $this->uri->segment(5);
			$this->statistik->set_action('group_show?id='.$group_name);

			$data['result']         = $this->statistik->process_api()->result;
			$data['dataset_list']   = $this->statistik->dataset_list($group_name, 'group');
			$data['latest_dataset'] = $this->statistik->latest_dataset($group_name, 'group');
			$data['detail_org_x']   = $this->statistik->aktifitas_group($group_name, 'x');
			$data['detail_org_y']   = $this->statistik->aktifitas_group($group_name, 'y');
		}

		$data['content']      = $this->load->view('v_detail_statistik', $data, TRUE);

		$this->load->view('template/v_base_template', $data);
	}

	public function page_404() 
	{
		$this->output->set_status_header('404'); 
		
		$data['content'] = $this->load->view('template/v_404_error', '', TRUE);

		$this->load->view('template/v_base_template', $data);
	}

	public function debug()
	{
		/*$this->load->library('statistik');
		$this->statistik->set_portal('bandung');*/
		
		$ahay = $this->statistik->portal_status('nasional');

		var_dump($ahay);
	}
}

/* End of file Start.php */
/* Location: ./application/controllers/Start.php */
