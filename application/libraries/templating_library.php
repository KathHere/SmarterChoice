<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');  ?>
<?php
class templating_library
{
    var $template_data = array();

    function set($name, $value)
    {
        $this->template_data[$name] = $value;
    }

    //Setting up the view to load and display
    function set_view($template = '', $view = '' , $view_data = array(), $return = FALSE)
    {
        $this->CI       =& get_instance();
        // Ibalik if nag error.
        // if(!$this->CI->input->is_ajax_request())
        // {
        //     //revalidate pages
        //     $this->CI->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
        //     $this->CI->output->set_header("Cache-Control: post-check=0, pre-check=0");
        //     $this->CI->output->set_header("Pragma: no-cache");
        // }
        $this->set('contents', $this->CI->load->view($view, $view_data, TRUE));

        return $this->CI->load->view($template, $this->template_data, $return);
    }

    //Setting up the javascript and stylesheet file
    function set_html_head($filenames =array(),$base_path='',$type='',$ext='')
    {
        $return = '';

        foreach($filenames as $name){
                //$return .= $this->get_head($base_path.$name.".$ext",$type);
            $return .= $this->get_head($name.".$ext",$type);
        }//endforeach

        return $return;
    }

    //Inserting the javascript and stylesheet file into HTML head
    private function get_head($src='',$type='')
    {
        $head_names = array('link'=>'<link rel="stylesheet" type="text/css" href="'.base_url().$src.'" />',
                        'script'=>'<script type="text/javascript" src="'.base_url().$src.'"></script>'
                        );

        return $head_names[$type];
    }
}