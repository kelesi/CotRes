<?php
/**
 * Hello World entry point file for Hello World Component
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license		GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Require the base controller
require_once (JPATH_COMPONENT.DS.'controller.php');
// Require the CotRes helper
require_once( dirname( __FILE__ ) .DS. 'helper.php' );

JHTML::stylesheet("cotres.css", JURI::base()."/components/com_cotres/css/");
//Check for IE <= 6
if (CotResHelper::isIElte6())
{
    JHTML::stylesheet("cotres_ie6.css", JURI::base()."/components/com_cotres/css/");
}

//Change the format of the date_from and date_to vars
$post = JRequest::get('POST');
$df  = strtotime(JRequest::getVar('date_from'));
$dt  = strtotime(JRequest::getVar('date_to'));

//Exchage dates if needed
if ($df > $dt)
{
    $tmp = $df;
    $df = $dt;
    $dt = $tmp;
}
if ($df && ($dt-$df)/3600/24 < 1) $dt += 3600*24;

if (($dt-$df)/3600/24 > 356) $dt = $df + 3600*24*356;

if ($df) {
    $post["date_from"]  = date("Y-m-d", $df);
    $post["date_from_show"] = date("d-m-Y", $df);
//    CotResHelper::p_r($post);
}
if ($dt) {
    $post["date_to"]    = date("Y-m-d", $dt);
    $post["date_to_show"] = date("d-m-Y", $dt);
}
JRequest::set($post, "POST", true);


// Require specific controller if requested
if($controller = JRequest::getVar('controller')) {
	require_once (JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');
}

// Create the controller
$classname	= 'CotresController'.$controller;
$controller = new $classname();

$type = JRequest::getVar('type');
$view = JRequest::getVar('view');
switch($type)
{
    case "calendar":
        JRequest::setVar('view', 'calendarFE');
        break;

    case "pricelist":
        JRequest::setVar('view', 'pricelist');
        break;

    case "orderstep5":
        JRequest::setVar('view', 'orderstep5');
        break;

    default:
        if ($view != "calendar" && $view != "pricelist" && $view != "orderstep5")
        {
            JRequest::setVar('view', 'cotres');
        }
}

if (JRequest::getVar('msg'))
{
    JError::raiseWarning("COTRES_MSG", JRequest::getVar('msg'));
}

// Perform the Request task
$controller->execute( JRequest::getVar('task'));

// Redirect if set by the controller
$controller->redirect();

?>
