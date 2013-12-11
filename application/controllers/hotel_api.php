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
 * 
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Hotel_api extends REST_Controller
{
    /* API response POST request */
    function checkHotel_post()
    {
        date_default_timezone_set('Asia/Jakarta');
        // POST Request from Trip Advisor
		$ta_id = trim($this->post("ta_id")); // TripAdvisor hotel IDs (integer). Request by TripAdvisor
        $start_date = trim(date('d-M-Y',strtotime($this->post("start_date")))); // Start date (yyyy-mm-dd). Request by TripAdvisor
        $end_date = trim(date('d-M-Y',strtotime($this->post("end_date"))));  // end date (yyyy-mm-dd). Request by TripAdvisor
        $year_stay = trim(date('Y',strtotime($this->post("start_date"))));
        $month_stay = trim(date('m',strtotime($this->post("start_date"))));
        $day_stay = trim(date('d',strtotime($this->post("start_date"))));
        $num_adults = trim($this->post("num_adults")); // number adults (integer). Request by TripAdvisor
        $key = base64_decode(trim($this->post("query_key")));

        // count interval start date - end date
        $datetime1 = new DateTime($start_date);
        $datetime2 = new DateTime($end_date);
        $interval = $datetime1->diff($datetime2);
        $count = $interval->format('%a');

        $num_hotels = 1; // number hotel that had availabilty
        $hotels=$room_types="";
        $tax_val = $fee_val = 0;



        // this is query
        $this->db->select("YAD_NO,TAID,YAD_NAME,ROOM_TYPE_NAME,ADULT_SAL_PRICE_1,stay_day,service_charge,SUPPLY_RM_CNT,RSV_RM_CNT");
        $this->db->limit(3,0);
        $this->db->where("TAID",$ta_id);
       // $this->db->where("J_ROOM_DAY_STOCK.BH_FLG",1);
        $this->db->where("J_ROOM_DAY_STOCK.STAY_DAY >=",$start_date);
        $this->db->where("J_ROOM_DAY_STOCK.STAY_DAY <=",$end_date);
        $this->db->where("J_ROOM_PLAN_DAY_PRICE.STAY_DAY",$start_date);
        $this->db->where("J_ROOM_DAY_STOCK.SUPPLY_RM_CNT > RSV_RM_CNT");
        //$this->db->where("J_ROOM_DAY_STOCK.RSV_RM_CNT < J_ROOM_DAY_STOCK.SUPPLY_RM_CT");
        $this->db->join("USR_JIDSRV01.j_tripadvisor_hotel","USR_JIDSRV01.j_tripadvisor_hotel.YAD_NO=J_YAD_KHN.YAD_NO");
        $this->db->join("USR_JIDSRV01.J_ROOM_PLAN_DAY_PRICE","J_ROOM_PLAN_DAY_PRICE.YAD_NO=J_YAD_KHN.YAD_NO");
        $this->db->join("USR_JIDSRV01.J_ROOM_TYPE","J_ROOM_TYPE.ROOM_TYPE_CD=J_ROOM_PLAN_DAY_PRICE.ROOM_TYPE_CD");
        $this->db->join("USR_JIDSRV01.J_ROOM_DAY_STOCK","J_ROOM_DAY_STOCK.ROOM_TYPE_CD=J_ROOM_TYPE.ROOM_TYPE_CD");
        $this->db->join("USR_JIDSRV01.j_yad_area_map","j_yad_area_map.YAD_NO=J_YAD_KHN.YAD_NO");
        $this->db->join("USR_JIDSRV01.j_sml_area_out","j_sml_area_out.SML_CD=j_yad_area_map.SML_CD");
        $this->db->order_by("J_ROOM_PLAN_DAY_PRICE.ADULT_SAL_PRICE_1","asc");
        $hotels = $this->db->get("USR_JIDSRV01.J_YAD_KHN");
       // echo $this->db->last_query();
//        echo "<pre>";
//        print_r($hotels);
//        echo "</pre>";
//        die();
        if($key=='pegipegikeyapi')
        {
            if($hotels->num_rows() > 0)
            {
                foreach ($hotels->result() as $hotel) {

                    // hotel name key
                    $hotel_name = str_replace("-","_",url_title($hotel->YAD_NAME));
                    $hotel_id = $hotel->YAD_NO;
                    $hotelNameKey = rawurlencode($hotel->YAD_NAME);

                    // url detail to pegipegi.com
                    $url = "http://www.pegipegi.com/hotel/hotel/".$hotel_name."_".$hotel_id."/?stayYear=".$year_stay."&stayMonth=".$month_stay."&stayDay=".$day_stay."&stayCount=". $count."&hotelNameKey=".$hotelNameKey."&roomCrack=200000";

                    //check the hotel id, if doesn't set in hotel list, return to error
                    if($this->_checkHotelList($hotel_id)==true)
                    {



                        // get tax and fee
    //                    $taxFee = $this->_getTaxAndFee($hotel_id);
    //                    foreach($taxFee as $tf)
    //                    {
    //                        $tax_val = $tf->TAX;
    //                        $fee_val = $tf->serviceCharge;
    //                    }

                        # tax
                        $tax = ( $hotel->ADULT_SAL_PRICE_1 * 10 )/100;
                        $tax = floor($tax);

                        # fee
                        $fee = ( $hotel->ADULT_SAL_PRICE_1 * $hotel->SERVICE_CHARGE )/100;
                        $fee = floor($fee);

                        # final price
                        $final_price = $hotel->ADULT_SAL_PRICE_1 + $tax +$fee;
                        $final_price = $this->pembulatan($final_price);

                        //echo $tax;
                        $detail = array(
                                    "url" =>$url, // url hotel id
                                    "price"=>number_format($hotel->ADULT_SAL_PRICE_1), // price
                                    "fees"=>number_format($fee), // fees
                                    "taxes"=>number_format($tax), // taxes
                                    "final_price"=>$final_price, // final price
                                    "currency"=>"IDR" // currency format/code

                        );

                        // room name list
                        $hotel->ROOM_TYPE_NAME;
                        $room_types[$hotel->ROOM_TYPE_NAME] = $detail;

                    }

                       // hotel list
                        $hotels = array("hotel_id"=>$ta_id,"room_types"=>$room_types);

                    // if hotel available, error is false
                    $errors = "";
                    
                }else{

                     $hotels = array();
                     $errors = array("error_code"=>3,
                                "message"=>"Hotel code ".$ta_id." is no longer used.",
                                "timeout"=>600,
                                "hotel_ids"=>$ta_id);
                }
            }
            else
            {
                // if hotel is not available, error message will showed
                $hotels = array();
                $errors = array("error_code"=>3,
                                "message"=>"Hotel code ".$ta_id." is no longer used.",
                                "timeout"=>600,
                                "hotel_ids"=>$ta_id);
            }

            // parsing to json
            $messages["api_version"] = 2;
            $messages["hotel_ids"] = array($ta_id);
            $messages["start_date"] = $start_date;
            $messages["end_date"] = $end_date;
            $messages["lang"] = "id_ID";
            $messages["num_adults"] = $num_adults;
            $messages["num_hotels"] = $num_hotels;
            $messages["hotels"] = $hotels;
            $messages["errors"] = $errors;
        }
        else
        {
            // parsing to json
            $messages["errors"] = "Invalid key. Could not connect to the API";
        }

        // show response
        $this->response($messages, 200); // 200 being the HTTP response code
    }


    /* Just for example API response. GET request */
    function checkHotelGet_get()
    {

        $ta_id = $this->get("ta_id");
        $start_date = $this->get("start_date");
        $end_date = $this->get("end_date");
        $num_adults = $this->get("num_adults");
        $num_hotels = 1;
        $hotels=array();

        //check available hotel in database
        $this->db->where('ta_id',$ta_id);
        $query = $this->db->get("hotellist");
        if($query->num_rows() > 0)
        {

            $url = base_url()."testform/detail/".$ta_id;
            $detail = array(
                        "url" =>$url,
                        "price"=>"1000",
                        "fees"=>"101",
                        "taxes"=>"5",
                        "final_price"=>"1106",
                        "currency"=>"IDR"

            );
            $room_types = array("Fenway Room"=>$detail);
            $hotels = array("hotel_id"=>$ta_id,"room_types"=>$room_types);

            $errors = "";
        }
        else
        {
            $errors = array("error_code"=>3,
                            "message"=>"Hotel code ".$ta_id." is no longer used.",
                            "timeout"=>600,
                            "hotel_ids"=>$ta_id);
        }

        $messages["api_version"] = 2; 
        $messages["hotel_ids"] = array($ta_id);
        $messages["start_date"] = $start_date; 
        $messages["end_date"] = $end_date; 
        $messages["lang"] = "id_ID";
        $messages["num_adults"] = $num_adults;
        $messages["num_hotels"] = $num_hotels;
        $messages["hotels"] = $hotels;
        $messages["errors"] = $errors;
        $this->response($messages, 200); // 200 being the HTTP response code
    }

    private function _getTaxAndFee($yad_no)
    {
        $taxAndfee = $this->db->query("select j_value_add_tax_mst.value_add_tax as tax,
        NVL(j_out_yad_ctl.tax_rate,
        j_sml_area_out.service_charge) as serviceCharge,
        j_out_yad_ctl.sys_use_rate as comission
        from j_yad_area_map, j_value_add_tax_mst, j_out_yad_ctl, j_sml_area_out
        where j_out_yad_ctl.yad_no = ".$yad_no."
        ");

        return $taxAndfee->result();
    }

    private function pembulatan($price)
    {
        $ratusan = substr($price, -3);
        if($ratusan<500)$akhir = $price - $ratusan;
        else$akhir = $price + (1000-$ratusan);
        return number_format($akhir, 0, ',', ',');
    }

    private function _checkHotelList($hotel_id)
    {
        //hotel list ID Array
        $hotels=array(
            '9001',
            '9002',
            '9003',
            '9004'
            );

        if(in_array($hotel_id, $hotels))
        {
            return true;
        }else{
            return false;
        }

       

    }


}