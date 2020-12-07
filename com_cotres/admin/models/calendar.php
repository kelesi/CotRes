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
class CotresModelCalendar extends JModel
{
	/**
	 * Calendar data array
	 *
	 * @var array
	 */
	var $_data;

    function __construct()
    {
        parent::__construct();

        global $mainframe, $option;

        // Get month and year
		$month    = $mainframe->getUserStateFromRequest($option.'month',  'month',    '', 'string');
		$year     = $mainframe->getUserStateFromRequest($option.'year',   'year',     '', 'string');

        $this->setState('month', $month);
        $this->setState('year',  $year);


    }

    /**
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */
	function _buildQuery($date)
	{
		$query = ""
            ." SELECT sel.id, sel.id_order, ord.status, sel.name, ord.created"
            ." FROM "
            ." ("
            ."  SELECT c.id as id, MAX(o.id) as id_order, c.name as name"
            ."  FROM #__cotres_cottages c "
            ."  LEFT JOIN #__cotres_order_details od ON c.id=od.id_cottage"
            ."  LEFT JOIN #__cotres_orders o ON o.id=od.id_order AND o.status <> -2"
            ."  AND o.date_from <= '$date' AND o.date_to > '$date'"
            ."  WHERE c.published > 0"
            ."  GROUP BY c.id"
            ."  ORDER BY c.id ASC"
            ." ) sel"
            ." LEFT JOIN #__cotres_orders ord ON ord.id = sel.id_order"
		;
       // echo $query."<br />";
		return $query;
	}

	/**
	 * Retrieves the Order data
	 * @return array Array of objects containing the data from the database
	 */
	function getData()
	{
        $month = $this->getState('month');
        $year  = $this->getState('year');
        if (!$month)
        {
            $month = date('m');
            $this->setState('month', $month);
        }

        if (!$year)
        {
            $year = date('Y');
            $this->setState('year', $year);
        }


		// Lets load the data if it doesn't already exist
		if (empty( $this->items ))
		{
		    $date = new DateTime("$year-$month-01");

            while ($date->format('m') == $month)
            {
                //echo $date->format('Y-m-d')."<br />";
    			$query = $this->_buildQuery($date->format("Y-m-d"));
                $this->_data[$date->format("d.m.Y")] = $this->_getList($query);
//                CotResHelper::p_r($this->_data[$date->format("d.m.Y")]);
                //Advance 1 day
                $date->modify("+1 day");
            }
           // echo "<pre>"; print_r($this->_data); echo "</pre>";
		}

		return $this->_data;
	}

    /**
	 * The function will create and return month and year
	 * @return pagination object
	 */
    function getList()
    {
        $list["month"]  = $this->getState("month");
        $list["year"]   = $this->getState("year");

        return $list;
    }
}
