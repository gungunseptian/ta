<?php

class model_review extends CI_Model {


    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function insert($ip_address,$ip_date)
    {

      	$data_post = $this->input->post();
      	$data_post['ip_address'] =  $ip_address;
        $data_post['ip_date'] =  $ip_date;
        $this->db->insert('f_reviews',$data_post);
    }

    function get_total()
    {
       $this->db->select("COUNT(id) as total");
       $query = $this->db->get("f_reviews");
       if($query->num_rows() > 0)
       {
            return $query->row()->total;
       }
       else
       {
            return 0;
       }
    }

    function get($limit,$pg)
    {

        $this->db->join("f_airline","f_airline.carrier_cd=f_reviews.carrier_cd");
        $this->db->order_by("create_date","desc");
        $query = $this->db->get("f_reviews",$pg,$limit);
        return $query;
    }

    function cek_ipaddress($ip_address,$ip_date)
    {
    

        $this->db->where('ip_address',$ip_address);
        $this->db->where('ip_date',$ip_date);
        $query = $this->db->get('f_reviews');

        // checking if the ip address duplicate in one day, return false else return true
        if($query->num_rows() > 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    function getAirlaneName($carrier_cd)
    {
        $query = $this->db->get_where("f_airline",array("carrier_cd"=>$carrier_cd));
        if($query->num_rows() > 0)
        {
           foreach ($query->result() as $row) {
             # code...
              return $row->airline_name;
           }
        }
        else
        {
              return 0;
        }
       
    }

}

?>