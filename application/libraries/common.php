<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');  
class common
{
    var $code       = 1;
    var $message    = "Failed";
    var $data       = array();
    public function convert_tojson($data=array(),$return=false)
    {
        header('Content-type: text/json');
        $encoded = json_encode($data);
        if($return)
        {
            return $encoded;
        }
        else 
        {
            echo $encoded;
        }
    }
    public function convert_fromjson($json_data="",$type=true,$return=false)
    {
        $decoded = json_decode($json_data,$type);
        if($return)
        {
            return $decoded;
        }
        else 
        {
            echo $decoded;
        }
    }
    public function response($return=true)
    {
        header('Content-type: text/json');
        $json = json_encode(array(
                                    "error"     => $this->code,
                                    "message"   => $this->message,
                                    "data"      => $this->data
                            ));
        if($return)
        {
            return $json;
        }
        echo $json;
    }
}