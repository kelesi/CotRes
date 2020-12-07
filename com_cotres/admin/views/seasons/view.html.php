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
class CotresViewSeasons extends JView
{
	/**
	 * Cottages view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
        JToolBarHelper::title(   JText::_( 'Cottage Reservation')." - ".JText::_('Season prices manager') , 'generic.png' );
		// Get data from the model
		$items   	 =& $this->get( 'Data');
        $list        =& $this->get( 'List' );
        $years       =& $this->get( 'Years' );

		$this->assignRef('items',		$items);
		$this->assignRef('list',	    $list);
		$this->assignRef('years',	    $years);

    	if( count( $items ) >= 1 )
        {
            JToolBarHelper::publishList();
            JToolBarHelper::unpublishList();

            //Add Remove button with confirmation dialog
            $bar    = & JToolBar::getInstance('toolbar');
            $tmp    = JText::_("Do you really want to delete selected item(s)?");
            $tmp2   = JText::_("Please make a selection from the list to");
            $js     = "javascript:if(document.adminForm.boxchecked.value==0){alert('".$tmp2."');}else{  if (confirm('".$tmp."') == true) { submitbutton('remove'); } }";
            $html   = '<a class="toolbar" class="button validate" type="submit" onclick="'.$js.'" href="#"><span title="Delete" class="icon-32-delete"></span>'.JText::_("Delete").'</a>';
            $bar->appendButton( 'Custom', $html);

            //Add Copy button to actual year
            $tmp    = JText::_("Please select, which year you want to copy to"." ".date("Y"));
            $js     = "javascript:if(document.adminForm.boxchecked.value==0){alert('".$tmp."');}else{ submitbutton('copy'); }";
            $html   = '<a class="toolbar" class="button validate" type="submit" onclick="'.$js.'" href="#"><span title="Copy" class="icon-32-copy"></span>'.JText::_("Copy to")." ".date("Y").'</a>';
            $bar->appendButton( 'Custom', $html);

            //Add Copy button to next year
            $tmp    = JText::_("Please select, which year you want to copy to"." ".(date("Y")+1));
            $js     = "javascript:if(document.adminForm.boxchecked.value==0){alert('".$tmp."');}else{ submitbutton('copy2next'); }";
            $html   = '<a class="toolbar" class="button validate" type="submit" onclick="'.$js.'" href="#"><span title="Delete" class="icon-32-copy"></span>'.JText::_("Copy to")." ".(date("Y")+1).'</a>';
            $bar->appendButton( 'Custom', $html);

            JToolBarHelper::editListX();
        }
        JToolBarHelper::addNewX();

		parent::display($tpl);
	}
}
