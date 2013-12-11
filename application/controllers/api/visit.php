<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Visit extends REST_Controller
{


	function list_get()
    {
		if(!$this->get('token'))
        {
        	$this->response(NULL, 400);
        }
		
		$token = $this->model_login->checkToken($this->get('token'));
		if($token){
			$r = $this->model_login->getUserFromToken($this->get('token'));
			$visit = $this->model_visit->getVisit($r[0]['id']);
			
			$messages["success"] = 1;
			$messages["visit"] = $visit;
		}else{
			$messages["success"] = 0;
			$messages["message"] = "Token expired. Please login again";
		}
        $this->response($messages, 200); // 200 being the HTTP response code
    }


	function add_post()
    {
		if(!$this->post('token'))
        {
        	$this->response(NULL, 400);
        }
		$token = $this->model_login->checkToken($this->post('token'));
		if($token){
			$r = $this->model_login->getUserFromToken($this->get('token'));
			$visit = $this->model_visit->updateVisit(
															    $this->post('checkin_out_id'),
															    $r[0]['id'],
															    $this->post('customer_id'),
															    $this->post('item_existing'),
																$this->post('item_new'),
																$this->post('agen'),
																$this->post('tailor'),
																$this->post('eceran'),
																$this->post('ab'), 	
																$this->post('am'), 	
																$this->post('m10'), 	
																$this->post('note'), 	
																$this->post('merk1'),
																$this->post('price1'),
																$this->post('merk2'),
																$this->post('price2'),
																$this->post('merk3'),
																$this->post('price3'),
																$this->post('merk4'),
																$this->post('price4'),
																$this->post('merk5'),
																$this->post('price5')
															);
			$messages["success"] = 1;
			$messages["visit"] = $visit;
		}else{
			$messages["success"] = 0;
			$messages["message"] = "Token expired. Please login again";
		}
        $this->response($messages, 200); // 200 being the HTTP response code
    }

}