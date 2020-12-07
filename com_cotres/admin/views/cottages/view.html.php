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
class CotresViewCottages extends JView
{

	/**
	 * Cottages view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
		JToolBarHelper::title(   JText::_( 'Cottage manager' ), 'generic.png' );
		// Get data from the model
		$items		= & $this->get( 'Data');
		$this->assignRef('items',		$items);

    	if( count( $items ) >= 1 )
        {
            JToolBarHelper::publishList();
            JToolBarHelper::unpublishList();

            //Add Remove button with confirmation dialog
            $bar = & JToolBar::getInstance('toolbar');
            $tmp    = JText::_("Do you really want to delete selected item(s)?");
            $tmp2   = JText::_("Please make a selection from the list to");
            $js     = "javascript:if(document.adminForm.boxchecked.value==0){alert('".$tmp2."');}else{  if (confirm('".$tmp."') == true) { submitbutton('remove'); } }";
            $html   = '<a class="toolbar" class="button validate" type="submit" onclick="'.$js.'" href="#"><span title="Delete" class="icon-32-delete"></span>'.JText::_("Delete").'</a>';
            $bar->appendButton( 'Custom', $html);
            
            JToolBarHelper::editListX();

        }
        JToolBarHelper::addNewX();

		parent::display($tpl);
	}

}
