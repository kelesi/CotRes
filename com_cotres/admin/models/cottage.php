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
class CotresModelCottage extends JModel
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
        $this->_prices  = null;
	}

	/**
	 * Method to get a cottage
	 * @return object with data
	 */
	function &getData()
	{
		// Load the data
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__cotres_cottages '.
					'  WHERE id = '.$this->_id;
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->name = null;
			$this->_data->published = null;
			$this->_data->desc = null;
		}

		return $this->_data;
	}


	/**
	 * Method to get prices for actual cottage
	 * @return object with prices
	 */
	function &getPrices()
	{
		// Load the prices for actual cottage
		$id = ($this->_id > 0) ? $this->_id : -1;

		$query = ' SELECT distinct s.year '.
                 " FROM #__cotres_seasons s ".
				 " WHERE s.published > 0 ".
                 ' ORDER BY s.year ASC '
                 ;
                         
        $years = $this->_getList( $query );
        if (!$years) $years = array();
        
    	if (empty( $this->_prices ))
        {
            foreach ($years as $y)
            {
    			$query = ' SELECT distinct p.id_cottage, s.id as id_season, s.name, s.date_from, s.date_to, p.price '.
                         " FROM #__cotres_seasons s LEFT JOIN #__cotres_prices p ON s.id = p.id_season AND p.id_cottage = $id".
    					 " WHERE s.published > 0 AND s.year = $y->year".
                         ' ORDER BY s.year, s.date_from, s.date_to'
                         ;

    			$p = $this->_getList( $query );
        		if ($p)
        		{
                    $this->_prices[$y->year] = $p;
                }
    		}
    		
        }
		//CotResHelper::p_r($this->_prices);
		return $this->_prices;
	}


	/**
	 * Method to store a record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function store()
	{
		$row =& $this->getTable('cottages');

		$data = JRequest::get( 'post' );

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

		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		//Store the prices
        $prices = array();
        $prices["id_cottage"] = $row->id;

	    $row =& $this->getTable('prices');

        //delete all entries for current cottage
        $this->deleteByCottageId($prices["id_cottage"]);
        
        foreach ($data["prices"] as $prices["id_season"] => $prices["price"])
	    {
    	    $row->id = null;
            $prices["price"] = $prices["price"] ? $prices["price"] : 0;

    		if (!$row->bind($prices)) {
    			$this->setError($this->_db->getErrorMsg());
                echo $this->getError();
    			return false;
    		}

    		// Make sure the cottage record is valid
    		if (!$row->check()) {
    			$this->setError($this->_db->getErrorMsg());
                echo $this->getError();
    			return false;
    		}

    		// Store the web link table to the database
    		if (!$row->store()) {
    			$this->setError( $this->_db->getErrorMsg() );
                echo $this->getError();
    			return false;
    		}
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
		$cids = JRequest::getVar( 'cid', array(0), 'request', 'array' );

		$row =& $this->getTable('cottages');

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

	/**
	 * Function that removes all prices for specified cottage
	 *
	 * @param int - cottage_id
	 */
	function deleteByCottageId($id)
	{
        $query = "DELETE FROM #__cotres_prices WHERE id_cottage = ".$id;
        $this->_db->setQuery( $query );
		$this->_db->loadObject();
    }
}
