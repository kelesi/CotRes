<?php
/**
 * Season View for CotRes Component
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

/**
 * Season View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class CotresViewOrder extends JView
{
	/**
	 * display method of Season view
	 * @return void
	 **/
	function display($tpl = null)
	{
        JHTML::_('behavior.tooltip');
        JHTML::_('behavior.calendar');
        JHTML::_('behavior.formvalidation');

		//get the Season
		$order        =& $this->get('Data');
		$cottages     =& $this->get('Cottages');
/*
$m = $this->getModel('order');
$m->getPrice($m->getData());
*/
        //echo ($price);
        
        //Get the Config model
        $modelConfig =& $this->getModel('config');

		$isNew		= ($order->id < 1);
		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );

		JToolBarHelper::title(   JText::_( 'Order' ).': <small><small>[ ' . $text.' ]</small></small>' );
        //save
        $html='<a class="toolbar" class="button validate" type="submit" onclick="javascript: return myValidate(adminForm);" href="#">
<span title="Save" class="icon-32-save"></span>'.JText::_("Save").'</a>';
        //JToolBarHelper::title(JTEXT::_('Edit Placement List'),'sections.png');
        $bar = & JToolBar::getInstance('toolbar');
        $bar->appendButton( 'Custom', $html);

		JToolBarHelper::cancel( 'cancel', 'Close' );

		$this->assignRef('order',         $order);
		$this->assignRef('cottages',      $cottages);
		$this->assignRef('modelConfig',	  $modelConfig);

		parent::display($tpl);
	}
}
