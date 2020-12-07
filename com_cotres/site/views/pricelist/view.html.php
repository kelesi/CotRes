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
 * Pricelist View class for the CotRes Component
 *
 * @package		Sigil.Cotres
 * @subpackage	Components
 */
class CotresViewPricelist extends JView
{
	/**
	 * Cottages view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
		// Get data from the model
		$items		=& $this->get( 'Data');
        $config     =& $this->get('Data',              'config');
        
		$this->assignRef( 'items',	    $items);
		$this->assignRef( 'config',     $config );
		
		parent::display($tpl);
	}
}

