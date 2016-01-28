<?php
class Model_app extends CI_Model
{
    function __construct(){
        parent::__construct();
    }
	
	
public function getAllData($table)
    {
        return $this->db->get($table)->result();
    }
    public function getSelectedData($table,$data)
    {
        return $this->db->get_where($table, $data);
    }
    function updateData($table,$data,$field_key)
    {
        $this->db->update($table,$data,$field_key);
    }
    function deleteData($table,$data)
    {
        $this->db->delete($table,$data);
    }
    function insertData($table,$data)
    {
        $this->db->insert($table,$data);
    }
    function manualQuery($q)
    {
        return $this->db->query($q);
    }
	
	
	
	function getMaxTarget()
	{
		$query = $this->db->query("select max(id_target) as maxtarget from target");
		return $query->row();
	}
	
	function insertSequence($table,$data,$column,$value)
	{
		$this->db->set($column,$value,false);
		$this->db->insert($table,$data);
		
	}
	function login($username, $password) 
	{
        //create query to connect user login database
        $this->db->select('*');
        $this->db->from('tbl_user');
        $this->db->where('username', $username);
        $this->db->where('password', MD5($password));
		$this->db->limit(1);

        //get query and processing
        $query = $this->db->get();
        if($query->num_rows() == 1) 
		{
            return $query->result(); //if data is true
        } else {
            return false; //if data is wrong
        }
	}
	
	public function membertambah()
	{
		$pass = password_hash(($this->input->post('password')), PASSWORD_DEFAULT);
		$akses = '2';
		$data=array
		(
			'nama_pengguna'=>$this->input->post('username'),
			'email'=>$this->input->post('email'),
			'kata_kunci'=>$pass,
			'akses' => $akses,
		);
		$data1=array
		(
			'nama_pengguna'=>$this->input->post('username'),
			'email'=>$this->input->post('email'),
			'nama_identitas'=>$this->input->post('nama'),
			'nama_toko'=>$this->input->post('toko'),
		);
		$this->db->insert('hitam_user',$data);
		$this->db->insert('hitam_informasi_user',$data1);
	}
	
	function masuk($username,$password)
    {
		$this->db->where('USERNAME',$username);   
        $query=$this->db->get('WEB_USERMANAGER');
        if($query->num_rows()>0)
        {
         	foreach($query->result() as $rows)
            {    $hash= $rows->PASSWORD;
				
						 $newdata = array(
                	   	'hitamuser_id' 		=> $rows->ID_USER,
                    	'hitamuser_name' 	=> $rows->FULLNAME,
		                //'hitamuser_email'    => $rows->email,
	                    'hitamlogged_in' 	=> TRUE,
										);
					
            	
               
			}
			if (password_verify($password, $hash)) 
					{
            	$this->session->set_userdata($newdata);
                return true;    
					
					}
					 else {
				return false;
			
						
							}
		}
		else 
		return false;
		
		
    }
	
	function lupa($email)
    {
		$this->db->where("email",$email);
        
            
        $query=$this->db->get("hitam_user");
        if($query->num_rows()>0)
        {
			return true;
			
		}
		else
		{
			return false;
		}
	
	}
	
	function kuncilupa($email,$randkunci)
    {
		$data=array
		(
			'email'=>$email,
			'kunci'=>$randkunci,
			'tanggal_input'=>date("Y-m-d H:i:s"),
		);
		$this->db->insert('hitam_lupa_password',$data);
		
		
	}
		
	function masukkunci($email,$kunci)
    {
		$this->db->where("email",$email);
		$this->db->where("kunci",$kunci);
		$this->db->where("status",0);
		$this->db->order_by("tanggal_input", "desc");
		$this->db->limit(1);
        
            
        $query=$this->db->get("hitam_lupa_password");
        if($query->num_rows()>0)
        {
					$id['email'] = $email;
					$data=array
					(
						'status'=>1,
						
					);
					$this->model_app->updateData('hitam_lupa_password',$data,$id);
					return true;
			
		}
		else
		{
			return false;
		}
	
	}
		
	function edit_password($email,$password)
	{
		$id['email'] = $email;
		$pass = password_hash(($password), PASSWORD_DEFAULT);
		$data=array
		(
			'kata_kunci'=>$pass,
            
        );
        $this->model_app->updateData('hitam_user',$data,$id);
		return true;
		
		
	}
	
function menus() 
	{
		$this->db->select("*");
		$this->db->from("MENU1");
		$q = $this->db->get();

		$final = array();
		if ($q->num_rows() > 0) 
		{
			foreach ($q->result() as $row) 
			{
				$this->db->select("*");
				$this->db->from("MENU2");
				$this->db->where("ID1", $row->ID);
				$q = $this->db->get();
				if ($q->num_rows() > 0) 
				{
                $row->children = $q->result();
				}
            array_push($final, $row);
			}
		}
    return $final;
	}	
	
	
	function alltarget ($id)
	{
	$this->db->select('*');
	$this->db->from('TARGET');
	$this->db->join('PING', 'ID_TARGET = ID_TARGET_PING','LEFT');
	$this->db->join('PATTERN', 'ID_TARGET = ID_TARGET_PATTERN','INNER');
	$this->db->join('DB', 'ID_TARGET_DB = ID_TARGET_PATTERN','INNER');
	$this->db->where('ID_TARGET =', $id); 
	$query = $this->db->get();
	return $query->result();

	}
	
	
	function web ($type)
	{
	$this->db->select('*');
	$this->db->from('TARGET');
	$this->db->join('PING', 'ID_TARGET = ID_TARGET_PING','LEFT');
	$this->db->join('PATTERN', 'ID_TARGET = ID_TARGET_PATTERN','INNER');
	$this->db->where('ID_TYPE =', $type); 
	$query = $this->db->get();
	return $query->result();

	}
		function webcek ($target)
	{
	$this->db->select('*');
	$this->db->from('TARGET');
	$this->db->join('PING', 'ID_TARGET = ID_TARGET_PING','LEFT');
	$this->db->where('ID_TARGET =', $target); 
	$query = $this->db->get();
	return $query->result();

	}
	
		function bulanErrorcode ()
		
	{
	$tgl = date('m-Y');
	$this->db->select('ERROR_MESSAGE,count (*) as JUMLAH');
	$this->db->from('T_LOG_ERROR');	
	$this->db->where("to_char(TIME_DOWN ,'MM-YYYY')=", $tgl); 
	$this->db->group_by('ERROR_MESSAGE'); 
	$this->db->order_by("ERROR_MESSAGE", "desc"); 
	$query = $this->db->get();
	return $query->result();

	}
	function bulanLaluErrorcode ()
		
	{
	$tgl = date('m-Y', strtotime('last month'));
	$this->db->select('ERROR_MESSAGE,count (*) as JUMLAH');
	$this->db->from('T_LOG_ERROR');	
	$this->db->where("to_char(TIME_DOWN ,'MM-YYYY')=", $tgl); 
	$this->db->group_by('ERROR_MESSAGE'); 
	$this->db->order_by("ERROR_MESSAGE", "desc"); 
	$query = $this->db->get();
	return $query->result();

	}
		function deskripsiErrorcode ()
		
	{
	$tgl = date('m-Y', strtotime('last month'));
	$this->db->select('ERROR_CODE,ERROR_MESSAGE');
	$this->db->from('T_LOG_ERROR');	
	$this->db->group_by('ERROR_CODE,ERROR_MESSAGE'); 
	$this->db->order_by("ERROR_CODE", "desc"); 
	$query = $this->db->get();
	return $query->result();

	}
	function countAll ($table,$kolom,$cond)
		
	{
	if  (!empty($kolom))
	{
	$this->db->select('count (*) as Jumlah');
	$this->db->from($table);
	$this->db->where($kolom, $cond); 
	$query = $this->db->get();	
	}
	
	else 
	{
	$this->db->select('count (*) as Jumlah');
	$this->db->from($table);	
	$query = $this->db->get();
	}
	return $query->result();

	}
	
	function kategoriError ()
		
	{
	$tgl = date('m-Y');
	$this->db->select('ERROR_MESSAGE,count (*) as JUMLAH');
	$this->db->from('T_LOG_ERROR');	
	$this->db->where("to_char(TIME_DOWN ,'MM-YYYY')=", $tgl); 
	$this->db->group_by('ERROR_MESSAGE'); 
	$this->db->order_by("ERROR_MESSAGE", "desc"); 
	$query = $this->db->get();
	return $query->result();

	}
	
	
	
		function liveweb ($fav)
	{
	$this->db->select('*');
	$this->db->from('T_TARGET');
/* 	$this->db->join('PING', 'ID_TARGET = ID_TARGET_PING','LEFT');
	$this->db->join('PATTERN', 'ID_TARGET = ID_TARGET_PATTERN','INNER'); */
	$this->db->where('FAV =', $fav); 
	$query = $this->db->get();
	return $query->result();

	}
	
	
	
	
	
	
	
	
	
	
	
	
}
	

	
	
