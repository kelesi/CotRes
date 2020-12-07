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
class CotresViewOrderStep3 extends JView
{
	function display($tpl = null)
	{
        $order      =& $this->get('DataSession',       'orderfe');
        $cottages   =& $this->get('CottagesSession',   'orderfe');
        $price_arr  =& $this->get('PriceSession',      'orderfe');
        $config     =& $this->get('Data',              'config');

		$this->assignRef( 'order',        $order );
		$this->assignRef( 'cottages',     $cottages );
		$this->assignRef( 'price_arr',    $price_arr );
		$this->assignRef( 'config',       $config );

	//	CotResHelper::p_r($price_arr);
	//CotResHelper::p_r($config);

		parent::display($tpl);
	}
}
?>
