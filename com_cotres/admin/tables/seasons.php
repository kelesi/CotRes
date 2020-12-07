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
class TableSeasons extends JTable
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
	var $date_from = null;

	/**
	 * @var string
	 */
	var $date_to = null;

	/**
	 * @var int
	 */
	var $published = null;

	/**
	 * @var int
	 */
	var $year = null;

	/**
	 * @var int
	 */
	var $add_fee_nights = null;

	/**
	 * @var int
	 */
	var $add_fee_perc = null;

	/**
	 * @var int
	 */
	var $reserv_min_nights = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableSeasons(& $db) {
		parent::__construct('#__cotres_seasons', 'id', $db);
	}

}
