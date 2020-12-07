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
class CotresViewSeason extends JView
{
	/**
	 * display method of Season view
	 * @return void
	 **/
	function display($tpl = null)
	{
		//get the Season
		$season		=& $this->get('Data');
		$isNew		= ($season->id < 1);

		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'Season' ).': <small><small>[ ' . $text.' ]</small></small>' );
        //save
        $html='<a class="toolbar" class="button validate" type="submit" onclick="javascript: return myValidate(adminForm);" href="#">
<span title="Save" class="icon-32-save"></span>'.JText::_("Save").'</a>';
        //JToolBarHelper::title(JTEXT::_('Edit Placement List'),'sections.png');
        $bar = & JToolBar::getInstance('toolbar');
        $bar->appendButton( 'Custom', $html);

		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		$this->assignRef('season',		$season);
		$this->assignRef('years',		$this->get('Years'));

		parent::display($tpl);
	}
}
