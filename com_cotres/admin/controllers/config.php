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
class CotresControllerConfig extends CotresController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
	}	/**

	/**
	 * save the configuration (and redirect to main page/config)
	 * @return void
	 */
	function save($type = "calendar")
	{
		$model = $this->getModel('config');
		if ($model->store($post))
        {
			$msg = JText::_( 'Configuration Saved!' );
		}
        else
        {
            JError::raiseWarning('COTRES_ERR_SAVE_CONFIG', JText::_( 'Error saving configuration.' ) );
		}

    	$link = "index.php?option=com_cotres&type=$type";
		// Check the table in so it can be edited.... we are done with it anyway
		$this->setRedirect($link);
	}

	/**
	 * save the configuration (and redirect to config)
	 * @return void
	 */
	function apply()
	{
        $this->save("config");
    }


	/**
	 * cancel editing the configuration
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_cotres', $msg );
	}
}
