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

		if (isset($portal))
		{
			switch ($portal)
			{
				case 'bandung':
					$data['title'] = 'Portal Data Bandung';
					$data['url']   = 'http://data.bandung.go.id';
					$data['icon']  = base_url('assets/img/pemkot-bandung.png');
				break;

				case 'jakarta':
					$data['title'] = 'Portal Data Jakarta';
					$data['url']   = 'http://data.jakarta.go.id';
					$data['icon']  = base_url('assets/img/pemprov-dki-jakarta.png');
				break;

				case 'nasional':
					$data['title'] = 'Portal Data Indonesia';
					$data['url']   = 'http://data.go.id';
					$data['icon']  = base_url('assets/img/nasional.png');
				break;
				
				default:
					show_404();
					break;
			}

			$this->statistik->set_portal($portal);

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
			echo "False";
		}
	}

	public function detail()
	{
		$this->statistik->set_portal($this->uri->segment(3));
		$this->statistik->set_action('organization_show?id='.$this->uri->segment(4));

		$data['result']       = $this->statistik->process_api()->result;
		$data['dataset_list'] = $this->statistik->dataset_list($this->uri->segment(4));
		$data['detail_org_x'] = $this->statistik->aktifitas_organisasi($this->uri->segment(4), 'x');
		$data['detail_org_y'] = $this->statistik->aktifitas_organisasi($this->uri->segment(4), 'y');

		$data['content']      = $this->load->view('v_detail_statistik', $data, TRUE);

		$this->load->view('template/v_base_template', $data);
	}

	public function debug()
	{
		$this->load->library('statistik');
		$this->statistik->set_portal('jakarta');
		//$this->statistik->set_action('organization_activity_list?id=dinas-pariwisata&limit=100');
		$result = $this->statistik->dataset_list('dinas-pariwisata');
		//result = $this->statistik->process_api();

		for ($i=0; $i < count($result); $i++)
		{
			$month = explode('-', $result[$i]['date_created']);
			$date_created[] = $month[0].'-'.$month[1];;
		}

		/*for ($i=0; $i < count($result->result) ; $i++)
		{
			$timestamp = explode('T', $result->result[$i]->timestamp);
			$month = explode('-', $timestamp[0]);

			$new_array[$i] = $month[0].'-'.$month[1];
			$new_array[$i]['timestamp_date'] = $timestamp[0];
			$new_array[$i]['timestamp_time'] = $timestamp[1];
			$new_array[$i]['activity_type'] = $result->result[$i]->activity_type;
			$new_array[$i]['id'] = $result->result[$i]->id;
		}*/

		$populated = array_count_values($date_created);

		ksort($populated);

		foreach ($populated as $key => $value)
			$date[] = "'".$key."'";

		$date_ = implode(',', $date);

		echo "<pre>";
		print_r ($date_);
		echo "</pre>";
	}
}

/* End of file Start.php */
/* Location: ./application/controllers/Start.php */