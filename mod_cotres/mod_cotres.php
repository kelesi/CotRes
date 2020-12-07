<?php
/**
* @version		$Id: mod_search.php 10855 2008-08-29 22:47:34Z willebil $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once( dirname(__FILE__).DS.'helper.php' );

$set_Itemid		 = intval($params->get('set_itemid', 0));
$moduleclass_sfx = $params->get('moduleclass_sfx', '');
$itemid = $params->get('itemid', 0);

// includes for mini-calendar
$document = &JFactory::getDocument();
$document->addScript("includes/js/joomla.javascript.js");

$lang =& JFactory::getLanguage();
JHTML::script( 'calendar_mini.js', 'includes/js/calendar/' );
JHTML::script( "calendar-".$lang->_lang.".js", 'includes/js/calendar/lang/' );
JHTML::_('stylesheet', 'calendar-mos.css', 'includes/js/calendar/');

JHTML::_('behavior.formvalidation');

$cal_img    = JURI::base().'components/com_cotres/images/cal_button.png';
$pay_img    = JURI::base().'components/com_cotres/images/payments.png';
$cal_link   = JURI::base().'index.php?option=com_cotres&type=calendar&Itemid='.$itemid;
$pol_link   = JURI::base()."index.php?option=com_content&view=article&Itemid=$itemid&id=".modCotresHelper::getArticleId();
$count      = modCotresHelper::getCottageCount();

//Get data from session
$session = &JSession::getInstance('none', array());

$date_from  = $session->get("date_from",    JRequest::getVar("date_from_show"),  "mod_cotres");
$date_to    = $session->get("date_to",      JRequest::getVar("date_to_show"),    "mod_cotres");
$sel_count  = $session->get("count",        JRequest::getVar("count"),      "mod_cotres");

require(JModuleHelper::getLayoutPath('mod_cotres'));
