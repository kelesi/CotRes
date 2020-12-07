<?php
/**
 * CotRes default controller
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * CotRes Component Controller
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class CotresController extends JController
{

	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	function display()
	{
        //Assign the Config model to the actual view
	    $view = JRequest::getVar('view');
        $viewObj = &$this->getView($view, 'html');
        /* assign to the view another model */
        $viewObj->setModel($this->getModel('config'),'false');

        switch ($view)
        {
            case "orders":
                /* assign to the view another model */
                $viewObj->setModel($this->getModel('order'),'false');
                break;
            default:
        }
		parent::display();
	}
}
