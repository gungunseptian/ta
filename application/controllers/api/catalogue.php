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

class Catalogue extends REST_Controller
{
    
    function list_get()
    {
		if(!$this->get('token'))
        {
        	$this->response(NULL, 400);
        }
		
		$token = $this->model_login->checkToken($this->get('token'));
		if($token){
			$catalogues = $this->model_catalogue->getCatalogue();
			$catalogues_cat = $this->model_catalogue->getRefCatalogue("catalogue");
			
			$messages["success"] = 1;
			$messages["catalogue"] = $catalogues;
			$messages["catalogue_ref"] = $catalogues_cat;
		}else{
			$messages["success"] = 0;
			$messages["message"] = "Token expired. Please login again";
		}
        $this->response($messages, 200); // 200 being the HTTP response code
    }

}