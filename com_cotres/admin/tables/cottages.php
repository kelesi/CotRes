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
class TableCottages extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;

	/**
	 * @var string
	 */
	var $name = null;

	/**
	 * @var int
	 */
	var $capacity = null;

	/**
	 * @var string
	 */
	var $desc = null;

	/**
	 * @var int
	 */
	var $published = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableCottages(& $db) {
		parent::__construct('#__cotres_cottages', 'id', $db);
	}
}
