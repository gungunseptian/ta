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

class Auth extends REST_Controller
{
    
    function login_post()
    {
        $cek = $this->model_login->checkUser($this->post('email'),$this->post('password'));
		$num = $cek->num_rows();
		if($cek->num_rows() > 0){
			$r = $cek->row_array();
			$users = $this->model_login->getUser($r['id'],$r['users_level']);
			if($users){
				$messages["success"] = 1;
				$messages["users"] = $users;
			}else{
				$messages["success"] = 0;
				$messages["message"] = "you must complete your registration data";
			}
        }else{
			$messages["success"] = 0;
			$messages["message"] = "Your email or password is incorrect";
		}
        $this->response($messages, 200); // 200 being the HTTP response code
    }

}