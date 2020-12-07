<?php
/**
 * Cottages Model for CotRes Component
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

/**
 * Cottage Model
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class CotresModelOrders extends JModel
{
	/**
	 * Cottages data array
	 *
	 * @var array
	 */
	var $_data;
    var $_total;
    var $_pagination;

    function __construct()
    {
        parent::__construct();
        
        global $mainframe, $option;
        
        // Get pagination request variables
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = $mainframe->getUserStateFromRequest($option.'.limitstart', 'limitstart', 0, 'int');
        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$filter_order		= $mainframe->getUserStateFromRequest($option.'filter_order',		                'filter_order',		      'created',	'cmd');
		$filter_order_Dir	= $mainframe->getUserStateFromRequest($option.'articleelement.filter_order_Dir',	'filter_order_Dir',	      'asc',	    'word');
		$filter_search      = $mainframe->getUserStateFromRequest($option.'articleelement.filter_search',	    'filter_search',	      '',	        'string');
		$filter_created     = $mainframe->getUserStateFromRequest($option.'articleelement.filter_created',	    'filter_created',         '',	        'string');
		$filter_created     = $filter_created ? date("Y-m-d", strtotime($filter_created)) : "";
		$filter_created_till= $mainframe->getUserStateFromRequest($option.'articleelement.filter_created_till',	'filter_created_till',    '',	        'string');
        $filter_created_till= $filter_created_till ? date("Y-m-d", strtotime($filter_created_till)) : "";
        $filter_removed     = $mainframe->getUserStateFromRequest($option.'articleelement.filter_removed',	    'filter_removed',         '0',	        'string');

        $this->setState('limit',                $limit);
        $this->setState('limitstart',           $limitstart);
        $this->setState('filter_order',         $filter_order);
        $this->setState('filter_order_Dir',     $filter_order_Dir);
        $this->setState('filter_search',        $filter_search);
        $this->setState('filter_created',       $filter_created);
        $this->setState('filter_created_till',  $filter_created_till);
        $this->setState('filter_removed',       $filter_removed);
    }

    /**
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */
	function _buildQuery()
	{
        $order          = $this->getState('filter_order');
        $order_Dir      = $this->getState('filter_order_Dir');
        $search         = JString::strtolower($this->getState('filter_search'));
        $created        = $this->getState('filter_created');
        $created_till   = $this->getState('filter_created_till');
        $removed        = $this->getState('filter_removed');

        //Construct the search sql
        $filter_q = "";
        if ($search)
        {
            $tbl_flds = $this->_db->getTableFields("#__cotres_orders");
            foreach ( $tbl_flds["#__cotres_orders"] as $field => $type)
            {
                if ($type == "int" || $type == "varchar")
                {
                    $filter_q .= "o.$field like '%$search%' OR ";
                }
            }
            $filter_q = " AND ($filter_q LPAD(id, 6, '0') like '%$search%' )";
        }

        
        //Construct the created date filter
        if ($created && $created_till)
        {
            $filter_q .= " AND ('$created' <= DATE_FORMAT(created, '%Y-%m-%d') AND '$created_till' >= DATE_FORMAT(created, '%Y-%m-%d'))";
        }
        elseif ($created)
        {
            $filter_q = " AND ('$created' = DATE_FORMAT(created, '%Y-%m-%d'))";
        }
        //echo "search query:".$search_q;

        //Determine wheter to show the removed orders
        if ($removed)
        {
            $filter_q .= " AND (o.status < 0)";
        }
        else
        {
            $filter_q .= " AND (o.status >= 0)";
        }
        
		$query = ' SELECT LPAD(id, 6, "0") as order_number, o.* '
			. ' FROM #__cotres_orders o'
			. " WHERE 1 "
            . $filter_q
            . " ORDER BY $order $order_Dir"
		;
        //echo $query;
		return $query;
	}

	/**
	 * Retrieves the Order data
	 * @return array Array of objects containing the data from the database
	 */
	function getData()
	{
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery();
            $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_data;
	}
	
	/**
	 * Retrieves the total count of orders
	 * @return array Array of objects containing the data from the database
	 */
    function getTotal()
    {
        // Load the content if it doesn't already exist
        if (empty($this->_total)) {
            $query = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);
        }
        return $this->_total;
    }

    /**
	 * The function will create and return a new Pagination object that can be accessed by the View.
	 * @return pagination object
	 */
    function getPagination()
    {
        // Load the content if it doesn't already exist
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
        }
        return $this->_pagination;
    }

    /**
	 * The function will create and return a new Filter array that can be accessed by the View.
	 * @return pagination object
	 */
    function getList()
    {
        $list["order"]          = $this->getState("filter_order");
        $list["order_Dir"]      = $this->getState("filter_order_Dir");
        $list["search"]         = $this->getState("filter_search");
        $list["created"]        = $this->getState("filter_created") ? date("d-m-Y", strtotime($this->getState("filter_created"))) : "";
        $list["created_till"]   = $this->getState("filter_created_till") ? date("d-m-Y", strtotime($this->getState("filter_created_till"))) : "";
        $list["removed"]        = $this->getState("filter_removed");

        return $list;
    }
}
