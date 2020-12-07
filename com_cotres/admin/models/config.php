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
class CotresModelConfig extends JModel
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

		//$array = JRequest::getVar('cid',  0, '', 'array');
		//$this->setId((int)$array[0]);
		$this->setId(1);
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
		$this->_id		  = $id;
		$this->_data	  = null;
		$this->_articles  = null;
		$this->_modules   = null;
	}

	/**
	 * Method to get the configuration
	 * @return object with data
	 */
	function &getData()
	{
		// Load the data
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__cotres_config '.
					'  WHERE id = '.$this->_id;
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_id = 0;
			$this->_data->policy_content = "";
			$this->_data->pricelist_content = "";
		}
		else
		{
		    $this->_data->policy_content = $this->getArticleContent();
		    $this->_data->pricelist_content = $this->getModuleContent($this->_data->pricelist_module_id);
        }
        //Use form submitted data if it has been posted
		return $this->_data;
	}

	/**
	 * Method to get the articles
	 * @return object with data
	 */
	function &getArticles()
	{
		// Load the data
		if ( empty( $this->_articles )) {
			$query = ' SELECT id as value, CONCAT(id," | ",title) as text FROM #__content ';
			$this->_db->setQuery( $query );
			$this->_articles = $this->_db->loadObjectList();
		}

        //Use form submitted data if it has been posted
		return $this->_articles;
	}

	/**
	 * Method to get the modules
	 * @return object with data
	 */
	function &getModules()
	{
		// Load the data
		if ( empty( $this->_modules )) {
			$query = ' SELECT id as value, CONCAT(id," | ",title) as text FROM #__modules ';
			$this->_db->setQuery( $query );
            $this->_modules[-1] = array("value" => "", "text" => JText::_("None"));
            $this->_modules = array_merge($this->_modules, $this->_db->loadObjectList());
            //CotResHelper::p_r($this->_modules);
		}

        //Use form submitted data if it has been posted
		return $this->_modules;
	}
	
	/**
	 * Method to get the articles
	 * @return object with data
	 */
	function getArticleContent($id = false)
	{
        $id = $id ? $id : $this->_data->policy_article_id;
		$query = ' SELECT introtext FROM #__content WHERE id='.$id;
		//$this->_db->setQuery( $query );
		return $this->_db->getOne($query);
    }

	/**
	 * Method to get the content of a module
	 * @return object with data
	 */
	function getModuleContent($id)
	{
		$query = ' SELECT content FROM #__modules WHERE id='.$id;
		//$this->_db->setQuery( $query );
		return $this->_db->getOne($query);
    }
    
	/**
	 * Method to store the configuration
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function store()
	{
		$row =& $this->getTable('config');

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

		return true;
	}

	/**
	 * Method to get the hours left
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function getHoursLeft($created)
	{
	    $data = $this->getData();
        return $data->reserved_hours - round((time() - strtotime($created)) / 3600);
    }
}
