<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class testform extends CI_Controller {

	 public function __construct(){
        parent::__construct();
    }
	

	function index()
	{

		echo "
		<h1>Request Tripadvisor API POST</h1>
		<form action='".base_url()."hotel_availability' method='POST'>
		Trip Advisor Hotel ID <br><input type='text' name='ta_id'> Request by Tripadvisor. <br>
		<small><i>ex:Tripadvisor ID = 634509  ( format : integer ) - Hotel Santika Premiere Malang</i></small><br><br>
		Start Date<br><input type='text' name='start_date'> Request by Tripadvisor<br>
		<small><i>ex:start date = 2013-12-12  ( format : yyyy-mm-dd )</i></small><br>
		<br>End Date<br><input type='text' name='end_date'> Request by Tripadvisor<br>
		<small><i>ex:end date = 2013-12-13  ( format : yyyy-mm-dd )</i></small><br><br>
		Num Adults<br><input type='text' name='num_adults'> Request by Tripadvisor<br>
		<small><i>ex:num = 1  ( format : integer )</i></small><br><br>
		Key<br><input type='text' name='query_key'> Request by Tripadvisor<br>
		<small><i>ex: cGVnaXBlZ2lrZXlhcGk= </i></small><br><br>
		<input type='submit' value='send'>
		</form>
		";
	}

	function response_sample()
	{
		
	}

	function readJson()
	{
		$json = base_url()."hotel_api/checkHotelGet/ta_id/212/start_date/2013-09-09/end_date/2013-09-09/num_adults/1";
		$string = file_get_contents($json);
		$json_a=json_decode($string,true);
		//echo $json_a['hotels']['hotel_id'];
		
		echo "<pre>";
		print_r($json_a);
		echo "</pre>";

		// foreach ($json_a as $key) {
		// 	# code...
		// 	echo $key->api_version;
		// }
	}
}
