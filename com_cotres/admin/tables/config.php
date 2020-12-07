<?php
/**
 * Orders table class
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
class TableConfig extends JTable
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
	var $email = null;

	/**
	 * @var text
	 */
	var $payment_info = null;

	/**
	 * @var text
	 */
	var $paypal = null;

	/**
	 * @var int
	 */
	var $reserv_perc = null;

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
	 * @var enum('0','1')
	 */
	var $online = null;

	/**
	 * @var enum('0','1')
	 */
	var $testing = null;

	/**
	 * @var int
	 */
	var $reserved_hours = null;

	/**
	 * @var int
	 */
	var $policy_article_id = null;

    /* CARDPAY SETTINGS */
	/**
	 * @var string
	 */
	var $cardpay_mid = null;
	/**
	 * @var string
	 */
	var $cardpay_key = null;
	/**
	 * @var string
	 */
	var $cardpay_cs = null;
	/**
	 * @var string
	 */
	var $cardpay_rem = null;
	/**
	 * @var string
	 */
	var $cardpay_rsms = null;
	/**
	 * @var string
	 */
	var $cardpay_name = null;
	/**
	 * @var string
	 */
	var $cardpay_ipc = null;
	/**
	 * @var string
	 */
	var $conversion = null;
	/**
	 * @var int
	 */
	var $pricelist_module_id = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableConfig(& $db) {
		parent::__construct('#__cotres_config', 'id', $db);
	}
	
	function load($id = 1)
	{
        return parent::load($id);
    }
    
    function store()
    {
        $ret = parent::store();
        // Set id to 1 if not so
        if ($this->id != 1)
        {
            $query = "UPDATE #__cotres_config SET id=1 WHERE id=".$this->id;
    		$this->_db->setQuery($query);
    		$this->_db->query();
        }

        return $ret;
    }

}
