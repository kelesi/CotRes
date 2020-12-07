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

/**
 * CotRes Cottage Controller
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class CotresControllerCottage extends CotresController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
	}


    /**
	 * display the edit form
	 * @return void
	 */
	function publish($publish = 1)
	{
		JRequest::setVar( 'view', 'cottages' );
		JRequest::setVar( 'hidemainmenu', 1 );
        $model = $this->getModel('cottage');

        $cid = JRequest::getVar('cid');
        foreach ($cid as $id)
        {
            echo $id."<br />";
            JRequest::setVar( 'id', $id );
            JRequest::setVar( 'published', $publish );
    		if (!$model->store())
    		{
                $publ_bad++;
    		}
        }

        $msg = JText::_( $publ_bad ? "Could not (un)publish $publ_bad cottage(s)." : "Cottage(s) ".($publish ? "" : "un")."published succesfully.");

		$link = 'index.php?option=com_cotres&type=cottages';
		$this->setRedirect($link, $msg);
	}


    function unpublish()
    {
        $this->publish(0);
    }


	/**
	 * display the edit form
	 * @return void
	 */
	function edit()
	{
		JRequest::setVar( 'view', 'cottage' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar( 'hidemainmenu', 1);

		parent::display();
	}


	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
		$model = $this->getModel('cottage');
		if ($model->store($post)) {
			$msg = JText::_( 'Cottage Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Cottage' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_cotres&type=cottages';
		$this->setRedirect($link, $msg);
	}
	

	/**
	 * remove record(s)
	 * @return void
	 */
	function remove()
	{
		$model = $this->getModel('cottage');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Cottages Could not be Deleted' );
		} else {
			$msg = JText::_( 'Cottages(s) Deleted' );
		}

		$this->setRedirect( 'index.php?option=com_cotres&type=cottages', $msg );
	}
	

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_cotres&type=cottages', $msg );
	}
}
