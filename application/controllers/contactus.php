<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class contactus extends CI_Controller
{
    var $code    = 1;
    var $message = "Failed to send an email at this time. Please try again later. Thank you.";
    var $data = array();
    public function __construct()
    {
      parent::__construct();
    }
    public function index()
    {
        if($this->input->post())
        {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('name','Fullname','required');
            $this->form_validation->set_rules('email','Email','required|valid_email'); 
            $this->form_validation->set_rules('message','Message','required');
            if($this->form_validation->run())
            {
                $data               = array();
                $data               = $_POST;

                $message = $this->load->view('common/email-contactus-template',$data,TRUE);
                $this->load->config('email');
                $config = $this->config->item('me_email'); 
                $this->load->library('email',$config);  

                $this->email->from('smarterchoicelv@gmail.com', 'Smarterchoice');
                $this->email->to('sna.john90@gmail.com,searesrusseljhon@gmail.com ');
                $this->email->subject('New Smarterchoice Inquiry');
                $this->email->message($message);
                if ($this->email->send())
                {
                   $this->code      = 0;
                   $this->message   = "Thank you for contacting us.";
                }
                else
                {
                    $this->email->print_debugger() ; echo "wala";
                     $this->message   = "Failed to send an email at this time. Please try again later. Thank you.";
                }

            }
            else
            {
              $this->message = validation_errors();
            }
            header('Content-type: text/json');
            echo json_encode(array(
                            "error"     => $this->code,
                            "message"   => $this->message
                        ));
            exit;
        }

    }
}
