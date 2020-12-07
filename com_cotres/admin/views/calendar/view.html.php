<?php
/**
 * Cottages View for CotRes Component
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
 * Cottages View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class CotresViewCalendar extends JView
{

	/**
	 * Cottages view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
		JToolBarHelper::title(   JText::_( 'Calendar' ), 'generic.png' );
        //Get the Config model
        $modelConfig =& $this->getModel('config');
		// Get data from the model
		$items		= & $this->get( 'Data');
		$list		= & $this->get( 'List');

		$this->assignRef('items',	$items);
		$this->assignRef('list',	$list);
		$this->assignRef('modelConfig',	$modelConfig);

		parent::display($tpl);
	}

}
