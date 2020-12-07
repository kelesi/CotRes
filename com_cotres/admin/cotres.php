<?php
/**
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license    GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

JHTML::stylesheet("cotres.css", "administrator/components/com_cotres/css/");

$task = JRequest::getCmd( 'task' );
// Authorize
$user 	= & JFactory::getUser();
$acl	= & JFactory::getACL();
// Fudge ACL for Administrators
$acl->addACL( 'com_cotres', $task, 'users', 'super administrator' );
$acl->addACL( 'com_cotres', $task, 'users', 'administrator' );
// Allow Manager access only for no task, cancel, view and edit (Read only access)
$acl->addACL( 'com_cotres', "view",     'users', 'manager' );
$acl->addACL( 'com_cotres', "",         'users', 'manager' );
$acl->addACL( 'com_cotres', "edit",     'users', 'manager' );
$acl->addACL( 'com_cotres', "cancel",   'users', 'manager' );


if (!$user->authorize( 'com_cotres', $task )) {
	$mainframe->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
}

//echo "<pre>";
//print_r($_REQUEST);
//echo "</pre>";

// Require the base controller
require_once( JPATH_COMPONENT.DS.'controller.php' );
// Require the CotRes helper
require_once( dirname( __FILE__ ) .DS. 'helper.php' );

//Change the format of the date_from and date_to vars
$post = JRequest::get('post');
$post["date_from_show"]  = JRequest::getVar('date_from');
$post["date_to_show"]    = JRequest::getVar('date_to');
$df  = strtotime(JRequest::getVar('date_from'));
$dt  = strtotime(JRequest::getVar('date_to'));
$pdt  = strtotime(JRequest::getVar('payment_date'));
if ($pdt) {
    $post["payment_date"]    = date("Y-m-d", $pdt);
}
if ($df) {
    $post["date_from"]  = date("Y-m-d", $df);
}
if ($dt) {
    $post["date_to"]    = date("Y-m-d", $dt);
}
JRequest::set($post, "POST", true);


// Require specific controller if requested
if($controller = JRequest::getWord('controller')) {
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
}

// Create the controller
$classname	= 'CotresController'.$controller;
$controller	= new $classname( );

$type = JRequest::getVar('type');
switch($type)
{
    case "config":
        JRequest::setVar('view', 'config');
        break;

    case "calendar":
        JRequest::setVar('view', 'calendar');
        break;

    case "orders":
        JRequest::setVar('view', 'orders');
        break;

    case "seasons":
        JRequest::setVar('view', 'seasons');
        break;

    case "config":
        JRequest::setVar('view', 'config');
        break;

    case "cottages":
        JRequest::setVar('view', 'cottages');
        break;

    default:
        JRequest::setVar('view', 'orders');
}
// Perform the Request task
$controller->execute( JRequest::getVar( 'task' ) );

// Redirect if set by the controller
$controller->redirect();
