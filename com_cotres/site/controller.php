<?php
/**
 * Hello World default controller
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license		GNU/GPL
 */

jimport('joomla.application.component.controller');

/**
 * Hello World Component Controller
 *
 * @package		HelloWorld
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
        $cont = JRequest::getVar('controller');
        //Check if online
        $configModel = $this->getModel('config');
        $config = $configModel->getData();
        if (!$config->online)
        {
            echo JText::_("Rezevačný systém je momentálne offline z dôvodu údržby");
            return;
        }

        if ($config->testing && $cont != "payments")
        {
            echo '<span class="red">';
            echo JText::_("Upozornenie: Systém je momentálne v testovacej prevádzke. Vykonané rezervácie online nebudú platné.");
            echo '</span><br /><br />';
        }

        //Assign the Config model to the actual view
	    $view = JRequest::getVar('view');
        $viewObj = &$this->getView($view, 'html');
        /* assign to the view another model */
        $viewObj->setModel($configModel,'false');
        $viewObj->setModel($this->getModel('orderfe'),'false');

		parent::display();
	}

}
?>
