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

class Product extends REST_Controller
{
    
    function list_get()
    {
		if(!$this->get('token'))
        {
        	$this->response(NULL, 400);
        }
		
		$token = $this->model_login->checkToken($this->get('token'));
		if($token){
			$products = $this->model_product->getProduct();
			$products_cat = $this->model_product->getRefProduct("category");
			$products_brand = $this->model_product->getRefProduct("brand");
			$products_col = $this->model_product->getRefProduct("color");
			
			$items = $this->model_product->getItem();
			$items_ref = $this->model_product->getRefItem();
			
			$messages["success"] = 1;
			$messages["products"] = $products;
			$messages["products_category"] = $products_cat;
			$messages["products_brand"] = $products_brand;
			$messages["products_color"] = $products_col;
			$messages["items"] = $items;
			$messages["items_ref"] = $items_ref;
		}else{
			$messages["success"] = 0;
			$messages["message"] = "Token expired. Please login again";
		}
        $this->response($messages, 200); // 200 being the HTTP response code
    }

    function test_get()
    {

    	 $products = $this->model_product->getProduct();
    	 $messages["products"] = $products;
    	 $this->response($messages, 200);
    	 // echo "<pre>";
    	 // print_r(json_encode($messages));
    	 //  echo "</pre>";
    	 // $messages["products"] = $products;
    	 // $this->response($messages, 200); // 200 being the HTTP response code
    }

}