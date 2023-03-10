<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reservation extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

	public function check_login()
	{
		if(!UID)
			redirect("login");
	} 

	public function check() {
		
		$post = $this->input->post();
		
		
		$data = array('title' => 'Add Customer - ', 'page' => 'reservation');
		$this->load->view('header', $data);

		if(isset($viewdata['error'])){
			$room_types = $this->room_m->get_room_types();
			$viewdata['room_types'] = $room_types;
			$this->load->view('reservation/add',$viewdata);
		} else {
			$rooms = $this->reservation_m->get_available_rooms($post['room_type'], $post['checkin_date'], $post['checkout_date']);
			$viewdata['rooms'] = $rooms;
			
			$viewdata['checkin_date'] = $post['checkin_date'];
			$viewdata['checkout_date'] = $post['checkout_date'];
			$viewdata['room_type'] = $post['room_type'];
//			echo "<pre>";
//			var_dump($viewdata);return;echo "</pre>";
			$this->load->view('reservation/list',$viewdata);
		}

		$this->load->view('footer');

	}
	public function index()
	{
		$this->check_login();

		$room_types = $this->room_m->get_room_types();
		$viewdata = array('room_types' => $room_types);
		$data = array('title' => 'Reservation - ', 'page' => 'reservation');
		$this->load->view('header', $data);
		$this->load->view('reservation/add', $viewdata);
		$this->load->view('footer');
	}
	
	public function add($ref="")
	{
		// 	customer_id	customer_firstname	customer_lastname	customer_TCno	customer_city	customer_country	customer_telephone	customer_email
		$data = $this->input->post();
		if(isset($data["customer_TCno"]) && $data["customer_TCno"])
		{
			$this->customer_m->add_customer($data);
			redirect("$jalur");
		}

		$viewdata = array('reference' => 'reservation/list');
		$data = array('title' => 'Add Customer - ', 'page' => 'reservation/list');
		$this->load->view('header', $data);
		$this->load->view('customer/add',$viewdata);
		$this->load->view('footer');
	}
	public function make()
	{
		$post = $this->input->post();
		
		
		$data = array();
		
		$data['nik'] = $post['nik'];
		$data['nama'] = $post['nama'];
		$data['room_id'] = $post['room_id'];
		$data['checkin_date'] = $post['checkin_date'];
		$data['checkout_date'] = $post['checkout_date'];
		$data['employee_id'] = UID;

		$date = new DateTime();
		$date_s = $date->format('Y-m-d');
		if($date_s>$data['checkin_date']) {
			$viewdata['error'] = "Checkin can't be before then today";
		} else {
			$this->reservation_m->add_reservation($data);
			$this->room_m->add_room_sale($data, $date_s);
			$viewdata['success'] = 'Reservation successfully made';
		}

		$room_types = $this->room_m->get_room_types();
		$viewdata['room_types'] = $room_types;

		$data = array('title' => 'Reservation - ', 'page' => 'reservation');
		$this->load->view('header', $data);
		$this->load->view('reservation/add', $viewdata);
		$this->load->view('footer');
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */