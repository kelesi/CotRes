<?php
/**
 * Hello View for Hello World Component
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license		GNU/GPL
 */

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the HelloWorld Component
 *
 * @package		Joomla.Tutorials
 * @subpackage	Components
 */
class CotresViewOrderStep2 extends JView
{
	function display($tpl = null)
	{
        $order      =& $this->get('DataSession',       'orderfe');
        $cottages   =& $this->get('CottagesSession',   'orderfe');
        $price_arr  =& $this->get('PriceSession',      'orderfe');
        $config     =& $this->get('Data',              'config');

        $payments= array();
        $payments[0]["value"] = "bank_transfer";
        $payments[0]["text"] = JText::_("Platba bankovým prevodom");
        $payments[1]["value"] = "paypal";
        $payments[1]["text"] = JText::_("Online platba realizovaná pomocu")." PayPal";
        $payments[2]["value"] = "cardpay";
        $payments[2]["text"] = JText::_("Online platba realizovaná pomocu")." CardPay";
        if (!$order->payment_type) $order->payment_type = "bank_transfer";


		$this->assignRef( 'order',        $order );
		$this->assignRef( 'cottages',     $cottages );
		$this->assignRef( 'price_arr',    $price_arr );
		$this->assignRef( 'config',       $config );
		$this->assignRef( 'payments',     $payments );

//CotResHelper::p_r($payments);
//CotResHelper::p_r($price_arr);
//CotResHelper::p_r($config);

		parent::display($tpl);
	}
}
?>
