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

class Customer extends REST_Controller
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
			$customers = $this->model_customer->getAssignedCustomer($r[0]['id']);
			
			$messages["success"] = 1;
			$messages["customers"] = $customers;
		}else{
			$messages["success"] = 0;
			$messages["message"] = "Token expired. Please login again";
		}
        $this->response($messages, 200); // 200 being the HTTP response code
    }
	
	function all_get()
    {
		if(!$this->get('token'))
        {
        	$this->response(NULL, 400);
        }
		
		$token = $this->model_login->checkToken($this->get('token'));
		if($token){
			$customers = $this->model_customer->getCustomer();
			
			$messages["success"] = 1;
			$messages["customers"] = $customers;
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
			$r = $this->model_login->getUserFromToken($this->post('token'));
			$sales_id = $r[0]['id'];
			$customers = $this->model_customer->updateCustomer(
															   $sales_id,
															   $this->post('id'),
															   $this->post('email'),
															   $this->post('password'),
															   $this->post('password_old'),
															   $this->post('customer_code'),
															   $this->post('name'),
															   $this->post('owner'),
															   $this->post('dob'),
															   $this->post('address'),
															   $this->post('city'),
															   $this->post('zip_code'),
															   $this->post('country'),
															   $this->post('phone'),
															   $this->post('fax'),
															   $this->post('handphone'));
			
			$messages["success"] = 1;
			$messages["customers"] = $customers;
		}else{
			$messages["success"] = 0;
			$messages["message"] = "Token expired. Please login again";
		}
        $this->response($messages, 200); // 200 being the HTTP response code
    }

}