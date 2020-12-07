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

//require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS."calendar".DS.'view.html.php');

/**
 * HTML View class for the HelloWorld Component
 *
 * @package		Joomla.Tutorials
 * @subpackage	Components
 */
class CotresViewCalendarFE extends JView
{
	/**
	 * Cottages view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
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

