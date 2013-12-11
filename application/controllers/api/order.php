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

class Order extends REST_Controller
{
  
	function add_post()
    {
		if(!$this->post('token'))
        {
        	$this->response(NULL, 400);
        }
		$token = $this->model_login->checkToken($this->post('token'));
		if($token){
			$checkin = $this->model_jobs->updateJobsStatus(
															   $this->post('id'),
															   $this->post('status'),
															   $this->post('note')
														   );
			
			$messages["success"] = 1;
			$messages["checkin"] = $checkin;
		}else{
			$messages["success"] = 0;
			$messages["message"] = "Token expired. Please login again";
		}
        $this->response($messages, 200); // 200 being the HTTP response code
    }
	
	
	function purchase_post()
    {
		// if(!$this->post('token'))
  //       {
  //       	$this->response(NULL, 400);
  //       }
		// $token = $this->model_login->checkToken($this->post('token'));
		// if($token){
		// 	$r = $this->model_login->getUserFromToken($this->post('token'));
			
		// 	$detail = $this->post('detail');
		// 	$detail = json_decode($detail,true);
		
		// 	$orders = $this->model_order->updateOrder(
		// 													   $this->post('id'),
		// 													   $r[0]['id'],
		// 													   $this->post('customer_id'),
		// 													   $this->post('job_customer_id'),
		// 													   $this->post('status_order'),
		// 													   $this->post('create_date'),
		// 													   $detail
		// 												   );
			
		// 	$messages["success"] = 1;
		// 	$messages["order"] = $orders;
		// }else{
		// 	$messages["success"] = 0;
		// 	$messages["message"] = "Token expired. Please login again";
		// }
  //       $this->response($messages, 200); // 200 being the HTTP response code

    	if(!$this->post('token'))
        {
        	$this->response(NULL, 400);
        }
		$token = $this->model_login->checkToken($this->post('token'));
		if($token){
			$r = $this->model_login->getUserFromToken($this->post('token'));
			
			$jum = count($this->post('product_id'));
			$product_id = $this->post('product_id');
			$qty = $this->post('qty');
			$price_pcs = $this->post('price_pcs');
			$discount_reg = $this->post('discount_reg');
			$discount_promo = $this->post('discount_promo');
			$price_pcs_discount = $this->post('price_pcs_discount');
			$price_qty_discount = $this->post('price_qty_discount');
			$note = $this->post('note');

			$detail = $this->post('detail');
			$detail = json_decode($detail,true);
		
			$orders = $this->model_order->updateOrder(
															   $this->post('id'),
															   $r[0]['id'],
															   $this->post('customer_id'),
															   $this->post('job_customer_id'),
															   $this->post('status_order'),
															   $this->post('sub_amount'),
															   $this->post('discount_amount'),
															   $this->post('total_amount'),
															   $this->post('create_date'),
															   $detail
														   );
			
			$messages["success"] = 1;
			$messages["order"] = $orders;
		}else{
			$messages["success"] = 0;
			$messages["message"] = "Token expired. Please login again";
		}
        $this->response($messages, 200); // 200 being the HTTP response code
    }
	
	function purchasesample_post()
    {
		if(!$this->post('token'))
        {
        	$this->response(NULL, 400);
        }
		$token = $this->model_login->checkToken($this->post('token'));
		if($token){
			$r = $this->model_login->getUserFromToken($this->post('token'));
			
			$jum = count($this->post('product_id'));
			$product_id = $this->post('product_id');
			$qty = $this->post('qty');
			$price_pcs = $this->post('price_pcs');
			$discount_reg = $this->post('discount_reg');
			$discount_promo = $this->post('discount_promo');
			$price_pcs_discount = $this->post('price_pcs_discount');
			$price_qty_discount = $this->post('price_qty_discount');
			$note = $this->post('note');

			for($i = 0; $i < $jum; $i++){
				$detail[] = array(
										"product_id"=>$product_id[$i],
										"qty"=>$qty[$i],
										"price_pcs"=>$price_pcs[$i],
										"discount_reg"=>$discount_reg[$i],
										"discount_promo"=>$discount_promo[$i],
										"price_pcs_discount"=>$price_pcs_discount[$i],
										"price_qty_discount"=>$price_qty_discount[$i],
										"note"=>$note[$i]
									    );
			}
			
			
			$detail = json_encode($detail);
			$detail = json_decode($detail,true);
		
			$orders = $this->model_order->updateOrder(
															   $this->post('id'),
															   $r[0]['id'],
															   $this->post('customer_id'),
															   $this->post('job_customer_id'),
															   $this->post('status_order'),
															   $this->post('sub_amount'),
															   $this->post('discount_amount'),
															   $this->post('total_amount'),
															   $this->post('create_date'),
															   $detail
														   );
			
			$messages["success"] = 1;
			$messages["order"] = $orders;
		}else{
			$messages["success"] = 0;
			$messages["message"] = "Token expired. Please login again";
		}
        $this->response($messages, 200); // 200 being the HTTP response code
    }
	
	function sendemailsample_get()
	{
		$send = $this->model_order->send_email_sample("noreply@nextwebtechnologies.net","Information Services","itsmegoens@gmail.com","randikha01@gmail.com");
		if($send){
			$messages["success"] = 1;
			$messages["message"] = "Sending email success";
		}else{
			$messages["success"] = 0;
			$messages["message"] = "Sending email failed";
		}
		$this->response($messages, 200); // 200 being the HTTP response code
	}
	
}