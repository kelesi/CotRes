<?php
/**
* @version		$Id: helper.php 10381 2008-06-01 03:35:53Z pasamio $
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

class modCotresHelper
{
    function getArticleId()
    {
        global $database;

        $query = "SELECT policy_article_id FROM #__cotres_config WHERE id = 1";
        $id = $database->getOne($query);
        return $id ? $id : false;
    }

    function getCottageCount()
    {
        global $database;

        $query = "SELECT count(*) FROM #__cotres_cottages WHERE published > 0";
        $count = $database->getOne($query);
        return ($count === false) ? false : $count ;
    }
}
