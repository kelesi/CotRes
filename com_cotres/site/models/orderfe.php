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
require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'order.php');

/**
 * Hello Model
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class CotresModelOrderFE extends CotresModelOrder
{
    function __construct()
    {
        CotResHelper::showWarning();
        parent::__construct();
    }

    function setData($data)
    {
        if (!$data) return false;

        if (is_array($data))
        {
            $row = $this->getTable('orders');

    		if (!$row->bind($data)) {
    			$this->setError($this->_db->getErrorMsg());
    			return false;
    		}
    		$this->_data = $row;
        }
        else
        {
            $this->_data = $data;
        }

        return true;
    }

    function setCottages($row)
    {
        $this->_cottages = $row;
        return true;
    }

    function setDataSession($row)
    {
        if (!is_array($row)) $row = (array) $row;
        $session = &JSession::getInstance('none', array());
        $session->set("order_data", $row,     "com_cotres");
    }

    function setCottagesSession($row)
    {
        if (!is_array($row)) $row = (array) $row;
        $session = &JSession::getInstance('none', array());
        $session->set("order_cottages", $row,     "com_cotres");
    }

    function setPriceSession($row)
    {
        $session = &JSession::getInstance('none', array());
        $session->set("order_price", $row,     "com_cotres");
    }

/*
    function setDataFromSession()
    {
        $session = &JSession::getInstance('none', array());
        $this->_data = $session->get("order_data", array(),       "com_cotres");
    }

    function setCottagesFromSession()
    {
        $session = &JSession::getInstance('none', array());
        $this->_cottages = $session->get("order_cottages", array(),       "com_cotres");
    }
*/
    function getDataSession()
    {
        $session = &JSession::getInstance('none', array());
        //CotResHelper::p_r($session->get("order_data",  "default", "com_cotres"));
        $this->setData($session->get("order_data", array(), "com_cotres"));
        return $this->_data;
    }

    function getCottagesSession()
    {
        $session = &JSession::getInstance('none', array());
        //CotResHelper::p_r($session->get("order_data",  "default", "com_cotres"));
        return $session->get("order_cottages", array(), "com_cotres");
    }

    function getPriceSession()
    {
        $session = &JSession::getInstance('none', array());
        //CotResHelper::p_r($session->get("order_data",  "default", "com_cotres"));
        return $session->get("order_price", array(), "com_cotres");
    }

    function checkSessions()
    {
        $o = $this->getDataSession();
        $c = $this->getCottagesSession();
        $p = $this->getPriceSession();

        if (!$o || !$c || !$p)
        {
            JError::raiseWarning( 'COTRES_SESSION_EXP', JText::_( 'Vaše sedenie vypršalo, pravdepodobne z dôvodu nečinnosti.' ));
            return false;
        }

        return true;
    }

    function deleteSessionVars()
    {
        $session = &JSession::getInstance('none', array());
        $session->set("order_data", '',     "com_cotres");
        $session->set("order_cottages", '', "com_cotres");
        $session->set("order_price", '',    "com_cotres");
    }

}
