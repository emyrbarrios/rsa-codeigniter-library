<?
class Rsa_model extends CI_Model{
	
	function rsa_model(){
		parent:: __construct();
		$this->load->helper('url');				
	}
	
	//INSERT query
	function entry_insert(){
		$this->load->database();
		$data = array(
			'uniqueUrl'=>$this->security->xss_clean($this->input->post('uniqueUrl')),
			//'uniqueUrl' = (random(0,50);),
			'senderName'=>$this->security->xss_clean($this->input->post('senderName')),
			'senderEmail'=>$this->security->xss_clean($this->input->post('senderEmail')),
			'recipientName'=>$this->security->xss_clean($this->input->post('recipientName')),
			'recipientEmail'=>$this->security->xss_clean($this->input->post('recipientEmail')),
			'publicKey'=>$this->security->xss_clean($this->input->post('publicKey')),
		);
		$this->db->insert('rsa',$data);
	}
	
	//SELECT * query
	function projects_getall()
	{
		$this->load->database();
		$query = $this->db->get('rsa');
		return $query->result();
	} 
	
	//SELECT * WHERE query
	function get($id)
	{
		$this->load->database();
		$query = $this->db->get_where('rsa',array('id'=>$id));
		return $query->row_array();		  
	}

	//UPDATE query
	function entry_update()
	{
		$this->load->database();
		$data = array(
		'senderName'=>$this->security->xss_clean($this->input->post('senderName')),
			'senderEmail'=>$this->security->xss_clean($this->input->post('senderEmail')),
			'recipientName'=>$this->security->xss_clean($this->input->post('recipientName')),
			'recipientEmail'=>$this->security->xss_clean($this->input->post('recipientEmail')),
			'publicKey'=>$this->security->xss_clean($this->input->post('publicKey')),
        );
		$this->db->where('id',$this->input->post('id'));
		$this->db->update('rsa',$data);  
	}
 	
	//DELETE query
	function delete($id){
  	$this->load->database();
  	$this->db->delete('rsa', array('id' => $id)); 
	}
	
	//Show everything
	function general(){
		$this->load->library('MyMenu');
		$menu = new MyMenu;
		$data['base']				= $this->config->item('base_url');
		$data['css']				= $this->config->item('css');		
		$data['menu'] 				= $menu->show_menu();
		$data['webtitle']			= 'Rsa';
		$data['websubtitle']		= 'We do RSA protection';
		$data['webfooter']			= '© copyright by portfolizr';
	
		$data['fid']['value']		= 0;
		$data['fyear']['value'] 	= 0;
						   
		//Labels for input form
		$data['id']	 				= 'id';
		$data['senderName']			= 'senderName';
		$data['senderEmail']		= 'senderEmail';
		$data['recipientName']		= 'recipientName';				
		$data['recipientEmail']	 	= 'recipientEmail';
		$data['publicKey']			= 'publicKey';
		$data['uniqueUrl']			= 'uniqueUrl';
		
		//Input names with sizes
		$data['fid']				= array('name'=>'id', 'size'=>30);
		$data['fsenderName']		= array('name'=>'senderName', 'size'=>30);
		$data['fsenderEmail']		= array('name'=>'senderEmail', 'size'=>30);
  	$data['frecipientName']		= array('name'=>'recipientName', 'size'=>30);
		$data['funiqueUrl']			= array('name'=>'uniqueUrl', 'size'=>30);
		//$data['funiqueUrl']			= array('name'=>'uniqueUrl', 'value'=>33303838, 'size'=>30);
		$data['frecipientEmail']	= array('name'=>'recipientEmail', 'size'=>30);
  	$data['fpublicKey']			= array('name'=>'publicKey', 'size'=>30);

		return $data;	
	}
}

/* End of file rsa_model.php */
/* Location: ./application/models/projects_model.php */
