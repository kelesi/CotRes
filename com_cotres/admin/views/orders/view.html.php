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
jimport( 'joomla.application.component.model' );

/**
 * Cottages View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class CotresViewOrders extends JView
{
	/**
	 * Cottages view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
        JHTML::_('behavior.calendar');
        JHTML::_('behavior.tooltip');
        
        JToolBarHelper::title(   JText::_( 'Cottage Reservation')." - ".JText::_('Manage orders') , 'generic.png' );

		// Get data from the model
		$items		 =& $this->get( 'Data');
        $pagination  =& $this->get( 'Pagination' );
        $list        =& $this->get( 'List' );

        //Get the component configuration from the config model
        $config      =& $this->get( 'Data','config' );

        //Get the cottages for orders
        $modelOrder  =& $this->getModel('order');
        //Get the Config model
        $modelConfig =& $this->getModel('config');

        foreach ($items as &$item)
        {
            $modelOrder->setId($item->id);
            foreach ($modelOrder->getCottages() as $cot)
            {
                $item->cottages .= $cot->name . ", ";
            }
            $item->cottages = substr($item->cottages,0,-2);
        }
        

        // push data into the template
        $this->assignRef('pagination',  $pagination);
		$this->assignRef('items',	    $items);
		$this->assignRef('list',	    $list);
		$this->assignRef('modelConfig',	$modelConfig);

    	if( count( $items ) >= 1 )
        {
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
