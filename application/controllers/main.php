<?php
/**
 * Created by Rizky.
 * User: Blackbox
 * Date: 28/01/16
 * Time: 14:18 AM
 */
class Main extends CI_Controller{
    function __construct(){
        parent::__construct();      
        $this->load->model('model_app');
    } 

    function index(){
		$this->load->helper(array('form', 'url'));
      /*   $this->load->library('form_validation');
		$type = 1;
        $data=array(
			'title'=>'Monitoring',
            'dt_web'=>$this->model_app->web($type),
        );
		$this->load->view('bagian/atas1',$data);
		$menus = $this->model_app->menus();
		$menu = array('menus' => $menus);
		$this->load->view('bagian/samping',$menu);  */
		$this->load->view('side/top');
		$this->load->view('page/v_main');
		$this->load->view('side/footer');
		
        
    }
	
	
	
	
	
}
?>