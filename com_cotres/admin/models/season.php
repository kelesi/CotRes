<?php
/**
 * Cottage Model for CotRes Component
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');


/**
 * CotRes Cottage Model
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class CotresModelSeason extends JModel
{
	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}

	/**
	 * Method to set the Cottage identifier
	 *
	 * @access	public
	 * @param	int Cottage identifier
	 * @return	void
	 */
	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}

	/**
	 * Method to get a cottage
	 * @return object with data
	 */
	function &getData()
	{
		// Load the data
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__cotres_seasons '.
					'  WHERE id = '.$this->_id;
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->name = null;
            $this->_data->date_from = null;
            $this->_data->date_to = null;
            $this->_data->published = null;
            $this->_data->reserv_min_nights = null;
            $this->_data->add_fee_nights = null;
            $this->_data->add_fee_perc = null;
            $this->_data->year = null;
		}

        $name       = JRequest::getVar('name');
        $date_from  = JRequest::getVar('date_from');
        $date_to    = JRequest::getVar('date_to');
        $year       = JRequest::getVar('year');
        $reserv_min_nights  = JRequest::getVar('reserv_min_nights');
        $add_fee_nights     = JRequest::getVar('add_fee_nights');
        $add_fee_perc       = JRequest::getVar('add_fee_perc');

        //Use form submitted data if it has been posted
        if ($name || $date_from || $date_to || $year || $add_fee_nights || $reserv_min_nights || $add_fee_perc)
        {
            $this->_data->name          = $name;
            $this->_data->date_from     = $date_from;
            $this->_data->date_to       = $date_to;
            $this->_data->year          = $date_to;
            $this->_data->reserv_min_nights = $reserv_min_nights;
            $this->_data->add_fee_nights    = $add_fee_nights;
            $this->_data->add_fee_perc      = $add_fee_perc;
        }

		return $this->_data;
	}

    /**
	 * Method to get the years
	 * @return object with data
	 */
    function getYears()
	{
        $arr = array();
        $arr[] = array("value" => "1", "text" => JText::_("Default"));
        for($i=0; $i<=2; $i++)
        {
            $arr[] = array("value" => date('Y')+$i, "text" => date('Y')+$i);
        }
		return $arr;
	}


    function storeSelf()
    {
        return $this->store(true);
    }

    /**
	 * Method to store a record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function store($self = false)
	{
		$row =& $this->getTable('seasons');

        if ($self)
        {
            $data = $this->_data;
            //CotResHelper::p_r($data);
        }
        else
        {
            $data = JRequest::get( 'post' );
        }

		// Bind the form fields to the cotres_cottages table
		if (!$row->bind($data)) { 
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Make sure the cottage record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
        if ($this->checkSeasonOverlap($row))
        {
            $this->setError("overlap");
            return false;
        }

		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}

		return true;
	}

	/**
	 * Method to delete record(s)
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row =& $this->getTable('seasons');

		if (count( $cids )) {
			foreach($cids as $cid) {
				if (!$row->delete( $cid )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}
		}
		return true;
	}
	
	function checkSeasonOverlap(&$row)
	{
        $overlaps = false;
        $id = $row->id ? $row->id : '-1';
		$query = ' SELECT * '
			. " FROM #__cotres_seasons where id <> $id and year = $row->year"
		;		// Lets load the data if it doesn't already exist
		echo $query;
        $seasons = $this->_getList( $query );
        $df     = substr($row->date_from,3,2).substr($row->date_from,0,2);
        $dt     = substr($row->date_to,3,2).substr($row->date_to,0,2);

        if ($df > $dt) //Switch the dates
        {
            $tmp = $row->date_from;
            $row->date_from = $row->date_to;
            $row->date_to = $tmp;
            $tmp = $df;
            $df = $dt;
            $dt = $tmp;
        }

        foreach ($seasons as $season)
        {
            $df_old = substr($season->date_from,3,2).substr($season->date_from,0,2);
            $dt_old = substr($season->date_to,3,2).substr($season->date_to,0,2);
            if (($df >= $df_old && $df <= $dt_old) || ($dt >= $df_old && $dt <= $dt_old) || ($df <= $df_old && $dt >= $dt_old))
            {
                $overlaps = true;
            }
         }
        return $overlaps;

    }
}
