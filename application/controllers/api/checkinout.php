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

class Checkinout extends REST_Controller
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
			$checkin = $this->model_checkinout->getCheckinout($r[0]['id']);
			
			$messages["success"] = 1;
			$messages["checkin"] = $checkin;
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
			$checkin = $this->model_checkinout->updateCheckinout(
															   $r[0]['id'],
															   $this->post('customer_id'),
															   $this->post('checkin_lat'),
															   $this->post('checkin_lon'),
															   $this->post('checkin_acc')
													);
			if($checkin){
				$messages["success"] = 1;
				$messages["checkin"] = $checkin;
			}else{
				$messages["success"] = 0;
				$messages["message"] = "The customer isn't yours";
			}
		}else{
			$messages["success"] = 0;
			$messages["message"] = "Token expired. Please login again";
		}
        $this->response($messages, 200); // 200 being the HTTP response code
    }

}