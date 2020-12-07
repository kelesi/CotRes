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
class CotresModelSeasons extends JModel
{
	/**
	 * Cottages data array
	 *
	 * @var array
	 */
	var $_data;


    function __construct()
    {
        parent::__construct();

        global $mainframe, $option;

		$filter_year        = $mainframe->getUserStateFromRequest($option.'articleelement.filter_year',	    'filter_year',	      NULL,	        'string');
        $this->setState('filter_year',        $filter_year);
    }


	/**
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */
	function _buildQuery()
	{
	    $filter_year = $this->getState('filter_year');

		$query = ' SELECT * '
			. ' FROM #__cotres_seasons where 1 '
			. ( $filter_year > 0 ? " and year = $filter_year" : "")
			. ' order by year desc '
		;

		return $query;
	}

	/**
	 * Retrieves the cottage data
	 * @return array Array of objects containing the data from the database
	 */
	function getData()
	{
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList( $query );
		}

		return $this->_data;
	}

    /**
	 * Method to get the years from DB
	 * @return object with data
	 */
    function &getYears()
	{
		// Load the data
        if ( empty( $this->_years )) {
			$query = ' SELECT DISTINCT year as value, year as text FROM #__cotres_seasons ORDER BY year ASC';
			$this->_db->setQuery( $query );
			$this->_years = $this->_db->loadObjectList();
            $this->_years[-1]->value = 0;
            $this->_years[-1]->text = JText::_("- Choose year -");
		}
		if ($this->_years[0]->text == "1") $this->_years[0]->text = JText::_("Default");
        //CotResHelper::p_r($this->_years);
        //Use form submitted data if it has been posted
		return $this->_years;
	}
	
    /**
	 * The function will create and return a new Filter array that can be accessed by the View.
	 * @return pagination object
	 */
    function getList()
    {
        $list["year"]          = $this->getState("filter_year");
        return $list;
    }
}
