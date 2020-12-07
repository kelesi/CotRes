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
class CotresControllerPayments extends CotresController
{

    /**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
	}

    /**
	 * Function, which triggers removal of expired orders
	 * @return void
	 */
    function remove_expired()
    {
        $order  =  $this->getModel('orderfe');
        echo "REMOVE EXPIRED ORDERS: ".($order->removeExpired() ? "TRUE" : "FALSE");
        echo "<br />";
        echo "ARCHIVE_OLD_ORDERS: ". ($order->archiveOldOrders() ? "TRUE" : "FALSE");
        die;
    }


    function cardpay_return()
    {
        $vs     = JRequest::getVar('VS');
        $res    = JRequest::getVar('RES');
        $ac     = JRequest::getVar('AC');
        $sign   = JRequest::getVar('SIGN');

        if (!$vs)
        {
            $msg = urlencode(JText::_( 'Platba CardPay neprebehla úspešne.' ).JText::_('Chýba variabilný symbol.'));
            JError::raiseWarning( 'COTRES_CARDPAY_NOID', $msg );
            $this->setRedirect(JRoute::_("index.php?option=com_cotres&msg=$msg"));
            return;
        }
        JRequest::setVar('cid', $vs);
        $order  =  $this->getModel('orderfe');
        $data = $order->getData();
        $configModel  =  $this->getModel('config');
        $config       = $configModel->getData();

        require_once (JPATH_COMPONENT_SITE.DS.'payments'.DS.'cardpay.php');

        $sign_calc = $tatra->calculate_send_sign();
//echo $sign_calc."<br />";
//echo $sign;
        if ($sign != $sign_calc)
        {
            $msg = urlencode(JText::_( 'Platba CardPay neprebehla úspešne.' ).JText::_('Nesúhlasia kľúče.'));
            JError::raiseWarning( 'COTRES_CARDPAY_WRONGSIGN',  $msg);
            $this->setRedirect(JRoute::_("index.php?option=com_cotres&msg=$msg"));
            return;
        }
        
        if ($res != "OK")
        {
            $msg = urlencode(JText::_( 'Platba CardPay neprebehla úspešne.'));
            $this->setRedirect(JRoute::_("index.php?option=com_cotres&msg=$msg"));
            JError::raiseWarning( 'COTRES_MIN_NIGHTS', $msg );
            return;
        }

        //Set as paid
        $order->updateStatusPaid();
        
        //Go to "thank you" page
        $msg = urlencode(JText::_('Platba CardPay prebehla úspešne.'));
        $this->setRedirect(JRoute::_("index.php?option=com_cotres&type=orderstep5&msg=$msg"));
    }
    
    function paypal_success()
    {
      // Order was successful...

      // This is where you would probably want to thank the user for their order
      // or what have you.  The order information at this point is in POST
      // variables.  However, you don't want to "process" the order until you
      // get validation from the IPN.  That's where you would have the code to
      // email an admin, update the database with payment status, activate a
      // membership, etc.

        $msg = urlencode(JText::_('Platba PayPal prebehla úspešne.'));
        $this->setRedirect(JRoute::_("index.php?option=com_cotres&type=orderstep5&msg=$msg"));


      // You could also simply re-direct them to another page, or your own
      // order status page which presents the user with the status of their
      // order based on a database (which can be modified with the IPN code
      // below).

    }

    function paypal_cancel()
    {
        // Order was canceled...
        // The order was canceled before being completed.
        $msg = urlencode(JText::_('Platba PayPal bolo zrušená. Objednávka nie je zaplatená.'));
        $this->setRedirect(JRoute::_("index.php?option=com_cotres&type=orderstep5&msg=$msg"));
    }

    function paypal_ipn()
    {
        $configModel  =  $this->getModel('config');
        $config       = $configModel->getData();

        $order  =   $this->getModel('orderfe');
        $data   =   $order->getData();

        // Paypal is calling page for IPN validation...

        // It's important to remember that paypal calling this script.  There
        // is no output here.  This is where you validate the IPN data and if it's
        // valid, update your database to signify that the user has payed.  If
        // you try and use an echo or printf function here it's not going to do you
        // a bit of good.  This is on the "backend".  That is why, by default, the
        // class logs all IPN data to a text file.

        require_once (JPATH_COMPONENT_SITE.DS.'payments'.DS.'paypal.class.php');
        // include the class file
        $p = new paypal_class;             // initiate an instance of the class
        
        if ($config->testing)
        {
            $p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url
        }
        else
        {
            $p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';     // paypal url
        }

        if ($p->validate_ipn())
        {
            // Payment has been recieved and IPN is verified.  This is where you
            // update your database to activate or process the order, or setup
            // the database with the user's order details, email an administrator,
            // etc.  You can access a slew of information via the ipn_data() array.

            // Check the paypal documentation for specifics on what information
            // is available in the IPN POST variables.  Basically, all the POST vars
            // which paypal sends, which we send back for validation, are now stored
            // in the ipn_data() array.

            //Set as paid
            $order->updateStatusPaid();
            die;
        }

    }

}
