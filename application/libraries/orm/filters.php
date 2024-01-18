<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Filters{

    public $ci;

    public function __construct() 
    {
        $this->ci =& get_instance();
    }
    
/*
/ --------------------------------------
/ DETECT FILTER FOR MVC and HMVC
/ --------------------------------------
/
*/
    public function detect($model=0,$filters_array=0,$module=false)
    {
        if($model!=false && $filters_array!=false && !empty($filters_array))
        {
            /*
             * loading model
             */
            $file_to_check = APPPATH."models/filters/$model.php";
            $module_name   = ""; 
            $model_name    = $model;
            if($module)
            {
                // expected format received
                // module_name/filter_name
                $split          = explode('/', $model);
                $module_name    = $split[0]."/";
                $model_name    = $split[1];
                $file_to_check  = APPPATH."modules/".$split[0]."/models/filters/".$split[1].".php";
            }
            if(file_exists($file_to_check))
            {
                 $this->ci->load->model($module_name.'filters/'.$model_name);
                 foreach($filters_array as $method => $params)
                 {
                     if(method_exists($this->ci->$model_name, $method))
                     {
                        // echo " ".$method."===".$params;
                        // execute method 
                        $this->ci->$model_name->$method($params);
                     }
                 }
            }
        }
    }
}