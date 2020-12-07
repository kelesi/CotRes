<?php
/**
 * Cotres model
 * 
 * @package    CotRes - Cottage Reservation system
 * @subpackage Components
 * @link http://www.sigil.sk
 * @license		
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
 * Hello Model
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class CotresModelCotres extends JModel
{
	/**
	 * Gets the greeting
	 * @return string The greeting to be displayed to the user
	 */
	function getData()
	{

        return;
		$db =& JFactory::getDBO();

		$query = 'SELECT greeting FROM #__';
		$db->setQuery( $query );
		$greeting = $db->loadResult();

		return $greeting;
	}
}
