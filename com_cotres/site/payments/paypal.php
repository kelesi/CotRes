<?php

/*  PHP Paypal IPN Integration Class Demonstration File
 *  4.16.2005 - Micah Carrick, email@micahcarrick.com
 *
 *  This file demonstrates the usage of paypal.class.php, a class designed  
 *  to aid in the interfacing between your website, paypal, and the instant
 *  payment notification (IPN) interface.  This single file serves as 4 
 *  virtual pages depending on the "action" varialble passed in the URL. It's
 *  the processing page which processes form data being submitted to paypal, it
 *  is the page paypal returns a user to upon success, it's the page paypal
 *  returns a user to upon canceling an order, and finally, it's the page that
 *  handles the IPN request from Paypal.
 *
 *  I tried to comment this file, aswell as the acutall class file, as well as
 *  I possibly could.  Please email me with questions, comments, and suggestions.
 *  See the header of paypal.class.php for additional resources and information.
*/

$configModel  =  $this->getModel('config');
$config       = $configModel->getData();

// Setup class
require_once (JPATH_COMPONENT_SITE.DS.'payments'.DS.'paypal.class.php'); // include the class file
$p = new paypal_class;             // initiate an instance of the class

if ($config->testing)
{
    $p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url
}
else
{
    $p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';     // paypal url
}

// setup a variable for this script (ie: 'http://www.micahcarrick.com/paypal.php')
$link_success   = JURI::base().'/index.php?option=com_cotres&controller=payments&task=paypal_success&cid='.$data->id;
$link_cancel    = JURI::base().'/index.php?option=com_cotres&controller=payments&task=paypal_cancel&cid='.$data->id;
$link_ipn       = JURI::base().'/index.php?option=com_cotres&controller=payments&task=paypal_ipn&cid='.$data->id;

$link_cancel =

    
// Process and order...
// There should be no output at this point.  To process the POST data,
// the submit_paypal_post() function will output all the HTML tags which
// contains a FORM which is submited instantaneously using the BODY onload
// attribute.  In other words, don't echo or printf anything when you're
// going to be calling the submit_paypal_post() function.

// This is where you would have your form validation  and all that jazz.
// You would take your POST vars and load them into the class like below,
// only using the POST values instead of constant string expressions.

// For example, after ensureing all the POST variables from your custom
// order form are valid, you might have:
//
// $p->add_field('first_name', $_POST['first_name']);
// $p->add_field('last_name', $_POST['last_name']);

$p->add_field('business', $config->paypal);
$p->add_field('return', $link_success);
$p->add_field('cancel_return', $link_cancel);
$p->add_field('notify_url', $link_ipn);
$p->add_field('item_name', 'Platba za rezevaciu zrubov na portali Bystra. Objednavka c.'.$data->id);
$p->add_field('mc_currency', 'EUR');
$p->add_field('currency_code', 'EUR');
$p->add_field('amount', sprintf("%.02d", $data->reservation_fee));

$p->submit_paypal_post(); // submit the fields to paypal
//$p->dump_fields();      // for debugging, output a table of all the fields
die;
