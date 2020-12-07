<?php
/**
 * Cottage View for CotRes Component
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
 * Cottage View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class CotresViewCottage extends JView
{
	/**
	 * display method of Cottage view
	 * @return void
	 **/
	function display($tpl = null)
	{
		//get the cottage
		$cottage	=& $this->get('Data');
		$prices		=& $this->get('Prices');

		$isNew		= ($cottage->id < 1);

		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'Cottage' ).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		$this->assignRef('cottage',		$cottage);
        $this->assignRef('prices',      $prices);
        
		parent::display($tpl);
	}
}
