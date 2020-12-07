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
class CotresControllerOrder extends CotresController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( ''  , 	'emptyTask' );
		$this->registerTask( 'edit'  , 	'add' );
	}


	/**
	 * display the new form
	 * @return void
	 */
	function add()
	{
		JRequest::setVar( 'view', 'order' );
		JRequest::setVar( 'layout', 'edit_order'  );
		JRequest::setVar( 'hidemainmenu', 1 );

		parent::display();
	}


	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
		$model = $this->getModel('order');
        $post = JRequest::get( 'post' );

        $isNew = JRequest::getVar('id') > 0 ? false : true;

        if ($isNew && !$post["cottages"] )
        {
            JError::raiseWarning( 'COTRES_NO_COTTAGES_SELECTED', JText::_( 'No cottages have been selected.' ) );
            JRequest::setVar( 'cid', JRequest::getVar('id') );
            JRequest::setVar( 'reposted', 1 );
    		$this->add();
    		return;
        }
        
		if ($model->store())
        {
			$msg = JText::_( 'Order Saved!' );
		}
        else
        {
            switch($model->getError())
            {
                case "overlap":
                    JError::raiseWarning( 'COTRES_ORDERS_OVERLAP', JText::_( 'Order overlaps with existing order.' ) );
                    break;

                case "min_nights":
                    JError::raiseWarning( 'COTRES_ORDERS_MINNIGHTS', JText::_( 'Minium nights condition not met.' ) );
                    break;

                default:
                    JError::raiseWarning('COTRES_ERR_SAVE_SEASON', JText::_( 'Error Saving Season.' ) );
                    return;
            }
            JRequest::setVar( 'cid', JRequest::getVar('id') );
            JRequest::setVar( 'reposted', 1 );
    		$this->add();
            return;
        }
    	$link = 'index.php?option=com_cotres&type=orders';
		// Check the table in so it can be edited.... we are done with it anyway
		$this->setRedirect($link);
	}

	/**
	 * remove record(s)
	 * @return void
	 */
	function remove()
	{
		$model = $this->getModel('order');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Seasons Could not be Deleted' );
		} else {
			$msg = JText::_( 'Season(s) Deleted' );
		}

		$this->setRedirect( 'index.php?option=com_cotres&type=orders', $msg );
	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_cotres&type=orders', $msg );
	}
	
    function emptyTask()
    {

        $model = $this->getModel('orders');
		$this->setRedirect( 'index.php?option=com_cotres&type=orders' );
    }
}
