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
jimport('joomla.html.html.select');

/**
 * Season View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class CotresViewConfig extends JView
{
	/**
	 * display method of Season view
	 * @return void
	 **/
	function display($tpl = null)
	{
        JToolBarHelper::title(   JText::_( 'Cottage Reservation')." - ".JText::_('Configuration') , 'generic.png' );
		//get the Season
		$config		=& $this->get('Data');
		$articles	=& $this->get('Articles');
		$modules    =& $this->get('Modules');
		
		//$config->id = 1;

        //get the toolbar
        $bar = & JToolBar::getInstance('toolbar');

        //save button
        $html='<a class="toolbar" class="button validate" type="submit" onclick="javascript: return myValidate(adminForm,\'save\');" href="#">
<span title="Save" class="icon-32-save"></span>'.JText::_("Save").'</a>';
        //JToolBarHelper::title(JTEXT::_('Edit Placement List'),'sections.png');
        $bar->appendButton( 'Custom', $html);

        //apply button
        $html='<a class="toolbar" class="button validate" type="submit" onclick="javascript: return myValidate(adminForm,\'apply\');" href="#">
<span title="Save" class="icon-32-apply"></span>'.JText::_("Apply").'</a>';
        //JToolBarHelper::title(JTEXT::_('Edit Placement List'),'sections.png');
        $bar->appendButton( 'Custom', $html);

		JToolBarHelper::cancel();

		$this->assignRef('config',		$config);
		$this->assignRef('articles',	$articles);
		$this->assignRef('modules',	    $modules);

		parent::display($tpl);
	}
}
