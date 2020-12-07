<?php
/**
 * Cottages table class
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Hello Table class
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class TablePrices extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;

	/**
	 * @var int
	 */
	var $id_cottage = null;

	/**
	 * @var int
	 */
	var $id_season = null;

	/**
	 * @var string/float
	 */
	var $price = null;


	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TablePrices(& $db) {
		parent::__construct('#__cotres_prices', 'id', $db);
	}
    
}
