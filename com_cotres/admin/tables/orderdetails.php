<?php
/**
 * Order Details table class
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
class TableOrderDetails extends JTable
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
	var $id_order = null;

	/**
	 * @var int
	 */
	var $id_cottage = null;

	/**
	 * @var string
	 */
	var $cottage_name = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableOrderDetails(& $db) {
		parent::__construct('#__cotres_order_details', 'id', $db);
	}
    
}
