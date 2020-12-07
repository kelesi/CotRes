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
class TableOrders extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;

	/**
	 * @var float
	 */
	var $price_total = null;

	/**
	 * @var datetime
	 */
	var $created = null;

	/**
	 * @var enum('-1','0','1')
	 */
	var $status = null;

	/**
	 * @var string
	 */
	var $payment_type = null;

	/**
	 * @var enum('0','1')
	 * 0 = person; 1= company
	 */
	var $user_type = null;

	/**
	 * @var string
	 */
	var $company_name = null;

	/**
	 * @var string
	 */
	var $ico = null;

	/**
	 * @var string
	 */
	var $dic = null;

	/**
	 * @var string
	 */
	var $contact_person = null;

	/**
	 * @var string
	 */
	var $fname = null;

	/**
	 * @var string
	 */
	var $lname = null;

	/**
	 * @var string
	 */
	var $street = null;

	/**
	 * @var string
	 */
	var $city = null;

	/**
	 * @var string
	 */
	var $zip = null;

	/**
	 * @var string
	 */
	var $country = null;

	/**
	 * @var string
	 */
	var $phone = null;

	/**
	 * @var string
	 */
	var $fax = null;

	/**
	 * @var string
	 */
	var $email = null;

	/**
	 * @var datetime
	 */
	var $date_from = null;

	/**
	 * @var datetime
	 */
	var $date_to = null;

	/**
	 * @var int
	 */
	var $reservation_fee = null;

	/**
	 * @var datetime
	 */
	var $payment_date = null;

	/**
	 * @var string - serialized arrray
	 */
    var $price_array = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableOrders(& $db) {
		parent::__construct('#__cotres_orders', 'id', $db);
	}
	
	function delete($id = null)
    {
        if ($id)
        {
            $this->load($id);
        }

        //Delete only if status == -3 (archived); if status <0 set status to -3; otherwise put status to -2
        if ($this->status == -3)
        {
            if (!$this->deleteByOrderId($id))
            {
                return false;
            }
            return parent::delete($id);
        }
        elseif ($this->status < 0)
        {
            $this->status = -3;
        }
        else
        {
            $this->status = -2;
        }

        if (!$this->store())
        {
            return false;
        }

        return true;
    }


	/**
	 * Function that removes all cottages from #__cotres_order_detail for specified order
	 *
	 * @param int - cottage_id
	 */
	function deleteByOrderId($id)
	{
        $query = "DELETE FROM #__cotres_order_details WHERE id_order = ".$id;
        $this->_db->setQuery( $query );
		if (!$this->_db->query())
        {
            return false;
        }
        
        return true;
    }
}
