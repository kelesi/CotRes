<?php
/**
 * Cottage Controller for CotRes Component
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.error.error' );

/**
 * CotRes Cottage Controller
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class CotresControllerOrderFE extends CotresController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

        $task = JRequest::getVar('task');
        //Check sessions
        $order =  $this->getModel('orderfe');
        if ($task !="search" && !$order->checkSessions())
        {
        	JRequest::setVar( 'task',          '' );
        	JRequest::setVar( 'controller',    '' );
        	JRequest::setVar( 'type',          'calendar' );
        	return;
        }

		// Register Extra tasks
//		$this->registerTask( ''  , 	'emptyTask' );
//	    $this->registerTask( 'edit'  , 	'add' );
	}


	/**
	 * Task submited from the cotres search module - shows step 1
	 * @return void
	 */
    function search($post = false)
    {
		//Create session
        $session = &JSession::getInstance('none', array());

        $link = 'index.php?option=com_cotres&type=calendarfe';
        $order          =  $this->getModel('orderfe');
        $config         =& $order->getTable('config');
        $config->load();

        if ($post)
        {
            $count = $post["count"] = $session->get("count",      1,             "mod_cotres");
        }
        else
        {
            $post   = JRequest::get( 'post' );
            $count  = $post["count"];
        }

		if (!$order->setData($post))
        {
			return false;
		}
        $row = $order->getData();

        //CotResHelper::p_r($session->get("order_data",  "default", "com_cotres"));
        //Save session data for the module
        if ($post["date_from_show"]) $session->set("date_from",  $post["date_from_show"],    "mod_cotres");
        if ($post["date_to_show"]) $session->set("date_to",    $post["date_to_show"],      "mod_cotres");
        $session->set("count",      $count,             "mod_cotres");

        //Check if cottage count is correct
        if ($count < 1)
        {
            JError::raiseWarning( 'COTRES_COTTAGE_COUNT', JText::_( 'Počet zrubov je menší ako 1.' ) );
    		JRequest::setVar( 'view', 'calendarfe' );
            parent::display();
    		return;
        }
        //Check if the date is not in the past and if the dates are withing current and next year
        $dFrom = new DateTime($post["date_from_show"]);
        $dTo   = new DateTime($post["date_to_show"]);
        if ($dFrom->format("Ymd") < date("Ymd") || $dTo->format("Ymd") < date("Ymd") || $dFrom->format("Y") > date('Y')+1 || $dTo->format("Y") > date('Y')+1)
        {
            JError::raiseWarning( 'COTRES_DATE_IN_PAST', JText::_( 'Zruby možno rezervovať iba pre aktuálny a budúci rok.' ) );
    		JRequest::setVar( 'view', 'calendarfe' );
            parent::display();
    		return;
        }
        
        
        $reserv_min_nights = Abs($order->isMinNights($row));
        if ($order->isMinNights($row) < 0)
        {
            $pricelist_link = ' <a href="'.JRoute::_("index.php?option=com_cotres&type=pricelist").'">'.JText::_("in pricelist").'</a>';
            JError::raiseWarning( 'COTRES_MIN_NIGHTS', JText::_("Minimum nights condition for a season has not been met. Please see the minimum nights in the").$pricelist_link."." );
    		JRequest::setVar( 'view', 'calendarfe' );
            parent::display();
    		return;
        }

        //Check if the amount of cottages selected is available at the selected date
        $cottages = array();
        foreach ($order->getCottages() as $cot)
        {
            if (!$order->checkOrderOverlap($row, array($cot->id_cottage)))
            {
                $cottages[] = $cot;
            }
            //if (count($cottages) >= $count) break;
        }
//CotResHelper::p_r($cottages);
        if (count($cottages) < $count)
        {
            JError::raiseWarning( 'COTRES_RESERVED', JText::_( 'Na vybraný termín nebolo možné nájsť daný počet voľných zrubov. Overte si obsadenosť v dole uvedenej tabuľke a podľa toho upravte váš termín.' ));
    		JRequest::setVar( 'view', 'calendarfe' );
            parent::display();
    		return;
        }

        //If everything is all right show first stage
    	JRequest::setVar( 'view', 'orderstep1' );
        //Set the Data to the model
        $order->setDataSession($row);
        //Save the free cottages to session
        $order->setCottagesSession($cottages);
        //** COUNT THE PRICE **
        $order->setPriceSession($order->getPrice($row, $cottages));

        parent::display();
    }

	/**
	 * Task submited from Step 2 - Back button
	 * @return void
	 */
    function step2back()
    {
        $order          =  $this->getModel('orderfe');
        $this->search((array) $order->getDataSession());

        return;
//       	JRequest::setVar( 'view', 'orderstep1' );
//          parent::display();
    }

	/**
	 * Task submited from Step 1 - Next button
	 * @return void
	 */
    function step1next()
    {

        $order          =  $this->getModel('orderfe');

        $post = JRequest::get( 'post' );
        if (!$post["cid"])
        {
            JError::raiseWarning( 'COTRES_NOCOTTAGE', JText::_( 'Nebol zvolený žiaden zrub.' ));
            $this->step2back();
            return;
        }

        //Find out what cottages has been selected in Step 1
        $cottages = array();
        foreach ($order->getCottagesSession() as $cot)
        {
            if (in_array($cot->id_cottage, $post["cid"]))
            {
                $cottages[] = $cot;
            }
        }
        //Save the chosen cottages to session
        $order->setCottagesSession($cottages);

        JRequest::setVar( 'view', 'orderstep2' );
        parent::display();
    }

	/**
	 * Task submited from Step 3 - Back button or on error at Step 2
	 * @return void
	 */
    function step3back()
    {
       	JRequest::setVar( 'view', 'orderstep2' );
        parent::display();
    }


	/**
	 * Task submited from Step 2 - Next button
	 * @return void
	 */
    function step2next()
    {
        $order          =  $this->getModel('orderfe');
        $ordersTable    = $order->getTable('orders');

        $post = JRequest::get( 'post' );
        //set user type
        $post["user_type"] = $post["user_type"] ? 1 : 0;

        //security measures - NOT finnished yet
        unset($post["date_from"]);
        unset($post["date_to"]);

        //Bind the session data
		if (!$ordersTable->bind($order->getDataSession()) || !$ordersTable->bind($post))
        {
            $this->setError($ordersTable->_db->getErrorMsg());
			return false;
		}

        //check the values
        if (!$post["agreement"])
        {
            JError::raiseWarning( 'COTRES_AGREEMENT_UNCHECKED', JText::_( 'Nebol zaškrtnutý súhlas s obchodnými podmienkami.' ));
            $this->step3back();
            return;
        }

        //TODO: Check required fields (if JS fails)
        $reqfields = $order->getRequiredFields();
        $ut = $ordersTable->user_type ? 1 : 0;

        //check general required fields
        foreach (array($ut,2) as $idx)
        {
            foreach ($reqfields[$idx] as $fld)
            {
                if (!($ordersTable->$fld))
                {
                    echo $fld;
                    JError::raiseWarning( 'COTRES_REQ_FLD_EMPTY', JText::_( 'Nebolo vyplnené políčko ').JText::_($fld));
                    $this->step3back();
                    return;
                }
            }
        }

        $order->setDataSession($ordersTable);
        $cottages = $order->getCottagesSession();
        //CotResHelper::p_r($cottages);
        //CotResHelper::p_r($order->getCottages());

        $order->setPriceSession($order->getPrice($ordersTable, $cottages));

        JRequest::setVar( 'view', 'orderstep3' );
        parent::display();
    }

	/**
	 * Task submited from Step 3 - Next button - Order confirmation
	 * @return void
	 */
    function step3next()
    {
        $order          =  $this->getModel('orderfe');
        $ordersTable    = $order->getTable('orders');
        $config         =& $order->getTable('config');
        $config->load();

        //set the Data from session to the order
        $order->setData($order->getDataSession());
        $order->setCottages($order->getCottagesSession());

        //Save the order
        if (!$order->storeSelf())
        {
            JError::raiseWarning( 'COTRES_STORE_FAIL', JText::_( 'Vytvorenie objednávky zlyhalo.' )." ". JText::_( 'Pravdepodobne si jeden zo zvolených zrubov niekto medičasom zarezervoval.' ));
            JRequest::setVar( 'view', 'orderstep3' );
            parent::display();
            return;
        }

        $data = $order->getData();

        JRequest::setVar( 'id',     $data->id );

        if ($data->payment_type == "bank_transfer")
        {
            JRequest::setVar( 'view',   'orderstep5' );
            //$order->deleteSessionVars();
        }
        else if ($data->payment_type)
        {
//cotResHelper::p_r($order->getData());
            //Go to the online payment site chosen
            require_once (JPATH_COMPONENT.DS.'payments'.DS.$data->payment_type.'.php');
            //echo $payment_link;
            $order->deleteSessionVars();
            if ($payment_link) $this->setRedirect($payment_link);
            return;
        }
        parent::display();
    }

    function display()
    {
	    $view = JRequest::getVar('view');
        $viewObj = &$this->getView($view, 'html');
        /* assign to the view another model */
        $viewObj->setModel($this->getModel('orderfe'),'false');

        parent::display();
    }
}
