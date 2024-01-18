<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * system email config
 */
// $config['me_email']['protocol']    = 'smtp';  
// $config['me_email']['smtp_host']   = 'mail.smarterchoice.us';  
// $config['me_email']['smtp_port']   = '26';  
// $config['me_email']['smtp_timeout']= '30';  
// $config['me_email']['smtp_user']   = 'orders@smarterchoice.us';  
// $config['me_email']['smtp_pass']   = 'u,MN}qNU(3qn4GY';  
// $config['me_email']['charset']     = 'utf-8';  
// $config['me_email']['mailtype']    = 'html';
// $config['me_email']['newline']     = "\r\n";


$config['me_email']['protocol']    = 'smtp';  
$config['me_email']['smtp_host']   = 'ssl://smtp.googlemail.com';  
$config['me_email']['smtp_port']   = '465';  
$config['me_email']['smtp_timeout']= '30';  
$config['me_email']['smtp_user']   = 'smarterchoicelv@gmail.com';  
$config['me_email']['smtp_pass']   = 'Ahms2016!';  
$config['me_email']['charset']     = 'utf-8';  
$config['me_email']['mailtype']    = 'html';
$config['me_email']['newline']     = "\r\n"; 

$config['billing_email']['protocol']    = 'smtp';  
$config['billing_email']['smtp_host']   = 'ssl://mail.ahmslv.com';  
$config['billing_email']['smtp_port']   = '465';  
$config['billing_email']['smtp_timeout']= '30';  
$config['billing_email']['smtp_user']   = 'billing@ahmslv.com';  
$config['billing_email']['smtp_pass']   = 'Billing2023!';  
$config['billing_email']['charset']     = 'utf-8';  
$config['billing_email']['mailtype']    = 'html';
$config['billing_email']['newline']     = "\r\n"; 
