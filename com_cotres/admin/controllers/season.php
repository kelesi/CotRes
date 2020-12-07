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
class CotresControllerSeason extends CotresController
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
		$this->registerTask( 'add'  , 	'edit' );
	}	/**
	 * display the edit form
	 * @return void
	 */

	function publish($publish = 1)
	{
		JRequest::setVar( 'view', 'seasons' );
		JRequest::setVar( 'hidemainmenu', 1 );
        $model = $this->getModel('season');

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

        $msg = JText::_( $publ_bad ? "Could not (un)publish $publ_bad season(s)." : "Season(s) ".($publish ? "" : "un")."published succesfully.");

		$link = 'index.php?option=com_cotres&type=seasons';
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
		JRequest::setVar( 'view', 'season' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}


	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
	
		$model = $this->getModel('season');
		if ($model->store($post))
        {
			$msg = JText::_( 'Season Saved!' );
		}
        elseif ($model->getError() == "overlap")
        {
            JError::raiseWarning( 'COTRES_SEASONS_OVERLAP', JText::_( 'Season overlaps with existing season.' ) );
            JRequest::setVar( 'cid', JRequest::getVar('id') );
    		$this->edit();
    		return;
        }
        else
        {
            JError::raiseWarning('COTRES_ERR_SAVE_SEASON', JText::_( 'Error Saving Season.' ) );
		}

    	$link = 'index.php?option=com_cotres&type=seasons';
		// Check the table in so it can be edited.... we are done with it anyway
		$this->setRedirect($link);
	}

	/**
	 * remove record(s)
	 * @return void
	 */
	function remove()
	{
		$model = $this->getModel('season');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Seasons Could not be Deleted' );
		} else {
			$msg = JText::_( 'Season(s) Deleted' );
		}

		$this->setRedirect( 'index.php?option=com_cotres&type=seasons', $msg );
	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_cotres&type=seasons', $msg );
	}

    function emptyTask()
    {

        $model = $this->getModel('seasons');
		$this->setRedirect( 'index.php?option=com_cotres&type=seasons' );
    }
    
    
    function copy2next()
    {
        return $this->copy(date('Y')+1);
    }
    
    function copy($toY = 0)
    {
        $model = $this->getModel('season');
        if (!$toY) $toY = date('Y');
        
        $cid = JRequest::getVar('cid');

        $bad = 0;
        foreach ($cid as $id)
        {
            echo $id."<br />";
            $model->setId($id);
            $model->getData();
            $model->_data->id = null;
            $model->_data->year = $toY;
            
    		if (!$model->storeSelf())
    		{
                $bad++;
    		}
        }
        echo "'".$bad."'";
        $msg = JText::_( $bad ? "Could not copy $bad season(s) out of ".count($cid).". ".JText::_("Reason:")." ".JText::_($model->getError()) : "All seasons copied succesfully.");

		$link = 'index.php?option=com_cotres&type=seasons';
		$this->setRedirect($link, $msg);
    }
}
