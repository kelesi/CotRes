<?php
/**
 * Order Model for CotRes Component
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');
jimport('joomla.filesystem.file');

/**
 * CotRes Cottage Model
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class CotresModelOrder extends JModel
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
		$this->_id		  = $id;
		$this->_data	  = null;
        $this->_cottages  = null;
        $this->_prices    = null;

        $this->_config = $this->getTable('config');
        $this->_config->load();
	}

	/**
	 * Method to get a cottage
	 * @return object with data
	 */
	function &getData()
	{

        // Load the data
        if (JRequest::getVar('reposted'))
        {
     		$row =& $this->getTable('orders');
    		$data = JRequest::get( 'post' );

            // Bind the form fields to the cotres_cottages table
    		if ($row->bind($data))
            {
    			$this->setError($this->_db->getErrorMsg());
                $this->setId($row->id);
                $this->_data = $row;
    		}
        }

        if (empty( $this->_data ))
        {
			$query = ' SELECT LPAD(id, 6, "0") as order_number, o.* FROM #__cotres_orders o'.
					'  WHERE id = '.$this->_id;
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}

		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
        	$this->_data->price_total = null;
        	$this->_data->created = null;
        	$this->_data->status = null;
        	$this->_data->payment_type = null;
        	$this->_data->user_type = null;
        	$this->_data->company_name = null;
        	$this->_data->ico = null;
        	$this->_data->dic = null;
        	$this->_data->contact_person = null;
        	$this->_data->fname = null;
        	$this->_data->lname = null;
        	$this->_data->street = null;
        	$this->_data->city = null;
        	$this->_data->zip = null;
        	$this->_data->country = null;
        	$this->_data->phone = null;
        	$this->_data->fax = null;
        	$this->_data->email = null;
        	$this->_data->date_from = null;
        	$this->_data->date_to = null;
        	$this->_data->reservation_fee = null;
        	$this->_data->payment_date = null;
        	$this->_data->order_number = null;
            $this->_data->price_array = null;
		}

        //Unserialize price data if needed
		if ($this->_data->price_array && !is_array($this->_data->price_array))
        {
            $this->_data->price_array = unserialize($this->_data->price_array);
        }
        $this->_prices = $this->_data->price_array;
//CotResHelper::p_r($this->_data);

		return $this->_data;
	}

	/**
	 * Method to get cottages for actual order
	 * @return object with cottages
	 */
	function &getCottages($cottages_arr = false)
	{
        if (is_array($cottages_arr))
        {
            //get the specified cottages only
            $id = -1;
            $cot_id = implode(",", $cottages_arr);
        }
        else
        {
    		// Load the cottages for actual order
            $id = ($this->_id > 0) ? $this->_id : -1;
        }

		if (empty( $this->_cottages ))
        {
            if ($id < 1)
            {
    			$query = ' SELECT distinct c.name as name, c.id as id_cottage, c.id as selected'.
                         " FROM #__cotres_cottages c".
    					 ' WHERE c.published > 0 '.
    					 ( $cot_id ? " AND c.id in ($cot_id)": '' ).
                         ' ORDER BY c.name, c.id'
                         ;

            }
            else
            {
    			$query =  " SELECT od.cottage_name as name, od.id_cottage as id_cottage, od.id_cottage as selected"
                         ." FROM #__cotres_order_details od"
    					 ." WHERE od.id_order = $id"
                         .' ORDER BY od.id_cottage'
                         ;
            }
//echo $query;
//echo $query;
			$this->_cottages = $this->_getList( $query );
//print_r($this->_cottages);
//echo $this->getError();
		}
		if (!$this->_cottages) {
			$this->_cottages = array();
		}
		return $this->_cottages;
	}


    function storeSelf()
    {
        return $this->store(false);
    }
	/**
	 * Method to store a record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function store($fromPost = true)
	{
		$row =& $this->getTable('orders');

        //Get from Post or this
        if ($fromPost === true)
        {
		  $data = JRequest::get( 'post' );
        }
        else
        {
            $data = (array) $this->_data;
            foreach ($this->_cottages as $cot)
            {
                $data["cottages"][] = $cot->id_cottage;
            }
        }
		$data["created"] = date("Y-m-d H:i:s");
		//set the $this->_cottages variable
        $this->getCottages($data["cottages"]);

        //If Order ID is not 0, do not allow to edit some values
        $isNew = ($data["id"] <= 0);
        if (!$isNew)
        {
        	unset($data["price_total"]);
        	unset($data["created"]);
        	unset($data["payment_type"]);
        	unset($data["date_from"]);
        	unset($data["date_to"]);
        	unset($data["reservation_fee"]);
        	unset($data["price_arraty"]);
            if ($data["status"] != 1) unset($data["status"]);
        }
        else if ($fromPost == true)
        {
            $data["payment_type"] = "bank_transfer";
        }

        //delete fields for user_types
        if ($data["user_type"] == 0)
        {
            foreach (array('company_name', 'ico', 'dic', 'contact_person') as $f)
            {
                $data[$f] = "";
            }
        }
        else
        {
            foreach (array('fname','lname') as $f)
            {
                $data[$f] = "";
            }
        }
        
        // Bind the form fields to the cotres_cottages table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

        if ($isNew)
        {
            //Check if the minimum nights condition is met
            if ($this->isMinNights($row) < 0)
            {
                $this->setError("min_nights");
                return false;
            }

    		//Check if order for selected cottages does not interfere with other orders
            if ($this->checkOrderOverlap($row, $data["cottages"]))
            {
                $this->setError("overlap");
                return false;
            }

            //Get the price array
            $price_arr = $this->getPrice($row);
            $data["price_total"] = $price_arr["total"];
            $data["reservation_fee"] = $price_arr["total"] * $this->_config->reserv_perc / 100;
            $data["price_array"] = serialize($price_arr);
//CotResHelper::p_r($data); die;
//            echo $data["price_array"];
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

		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
        //Get the saved order from DB
        if ($this->_data->id != $row->id)
        {
            $this->setId($row->id);
            $this->getData();
        }
//CotResHelper::p_r($this->getData());
		//Store the cottages; Only when the order is a new one
        //==================
        if ($isNew)
        {
            $cottages = array();
            $cottages["id_order"] = $row->id;
    	    $row =& $this->getTable('orderdetails');
    	    $cot_row =& $this->getTable('cottages');


            foreach ($data["cottages"] as $cottages["id_cottage"])
    	    {
        	    $row->id = null;
        	    //Get the cottage name
                $cot_row->load($cottages["id_cottage"]);
                $cottages["cottage_name"] = $cot_row->name;

        		if (!$row->bind($cottages)) {
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
        }

        //Send email
        if (!$this->sendEmail())
        {
            $this->setError(JText::_( 'Chyba pri odosielaní emailov.' ));
            JError::raiseWarning( 'COTRES_EMAIL_FAIL', JText::_( 'Chyba pri odosielaní emailov.' ));
        }

		return $this->_data->id;
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
		$row =& $this->getTable('orders');

		if (count( $cids )) {
			foreach($cids as $cid) {
				if (!$row->delete( $cid ))
                {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}
		}
		return true;
	}

	/**
	 * Method to delete record(s)
	 *
	 * @access	public
	 * @return	boolean	True if overlaps
	 */
	function checkOrderOverlap(&$row, $cottages_arr)
	{
	    if (!$cottages_arr) return false;
        $id = $row->id ? $row->id : '-1';

        //check for proper dates
        if ((date("Y-m-d", strtotime($row->date_from))  !== $row->date_from) || (date("Y-m-d", strtotime($row->date_to)) !== $row->date_to))
        {
            return true;
        }

        if (str_replace("-", "", $row->date_from) > str_replace("-", "", $row->date_to)) //Switch the dates
        {
            $tmp = $row->date_from;
            $row->date_from = $row->date_to;
            $row->date_to = $tmp;
        }
        $df = $row->date_from;
        $dt = $row->date_to;

        $cot_ids = implode(",", $cottages_arr);

//        echo "df: $df<br>";
//        echo "dt: $dt<br>";
//        echo "cot_ids: $cot_ids<br>";
		$query = ' SELECT * '
			. " FROM #__cotres_orders o, #__cotres_order_details od"
            . " WHERE o.id = od.id_order AND od.id_cottage in ($cot_ids) AND o.id<>$id AND o.status >= 0"
			. " AND (('$df' > o.date_from AND '$df' < o.date_to) OR ('$dt' > o.date_from AND '$dt' < o.date_to) OR ('$df' <= o.date_from AND '$dt' >= o.date_to))"
		;

        $orders = $this->_getList( $query );
//CotResHelper::p_r($orders);
        return (count($orders) > 0 ? true : false);
    }

	/**
	 * Method to get the price record(s)
	 *
	 * @access	public
	 * @return	boolean	True if overlaps
	 */
    function getPrice(&$data, &$cottages = false)
    {
        if ($this->_prices)
        {
            return $this->_prices;
        }
        
        $db = $this->getDBO();

        if (!$cottages) $cottages = $this->getCottages();
//CotResHelper::p_r($cottages);
//die;
        $date_from = $data->date_from;
        $date_to = $data->date_to;

        $date = new DateTime($date_to);
        $dt = $date->format("Ymd");
        $date = new DateTime($date_from);
        $df = $date->format("Ymd");

        $total = 0;
        $price_arr = array();
        $price_arr["nights"] = $this->getNightsCount($date_from, $date_to);
        
//print_r($cottages); die;
        foreach ($cottages as $cot)
        {
            $date = new DateTime($date_from);
            while ($date->format('Y-m-d') < $date_to)
            {
                $dt = $date->format("md");

            //echo $date->format('Y-m-d')."<br />";
            //For every cottage assigned to order
                // "1" is the default year
                if (!$cot->selected) continue;
                foreach(array($date->format("Y"), "1" ) as $year)
                {
                   /* $query =
                         " SELECT p.price, s.date_from, s.date_to, s.name, s.year, s.reserv_min_nights, s.add_fee_nights, s.add_fee_perc  FROM #__cotres_seasons s, #__cotres_prices p"
                        ." WHERE CONCAT(RIGHT(s.date_from,2),LEFT(s.date_from,2)) <= '$dt' AND CONCAT(RIGHT(s.date_to,2),LEFT(s.date_to,2)) >= '$dt'"
                        ." AND p.id_season = s.id AND id_cottage = $cot->id_cottage AND s.published > 0 AND s.year = $year";
                    */
                    $query =
                         " SELECT ss.id as sid, ss.*,p.* FROM ("
                        ." SELECT * FROM #__cotres_seasons s"
                        ." WHERE CONCAT(RIGHT(s.date_from,2),LEFT(s.date_from,2)) <= '$dt' AND CONCAT(RIGHT(s.date_to,2),LEFT(s.date_to,2)) >= '$dt'"
                        ." AND s.published > 0 AND s.year = $year"
                        .") ss"
                        ." LEFT JOIN #__cotres_prices p ON p.id_season = ss.id AND p.id_cottage = $cot->id_cottage";

//echo $query ."<br><br>";
                    $res = $this->_getList($query);
                    if ($res || $year == "1") break;
                }
//CotResHelper::p_r($res);
                //SeasonId
                $sid = $res[0]->sid;
                if (!$res)
                {
                    $pr = 0;
                }
                else
                {
                    $pr = $res[0]->price;
                    $price_arr["cottages"][$cot->id_cottage]["prices"][$sid]["season_from"]         = $res[0]->date_from;
                    $price_arr["cottages"][$cot->id_cottage]["prices"][$sid]["season_to"]           = $res[0]->date_to;
                    $price_arr["cottages"][$cot->id_cottage]["prices"][$sid]["season_name"]         = $res[0]->name;
                    $price_arr["cottages"][$cot->id_cottage]["prices"][$sid]["year"]                = $res[0]->year;
                    $price_arr["cottages"][$cot->id_cottage]["prices"][$sid]["reserv_min_nights"]   = $res[0]->reserv_min_nights;
                    $price_arr["cottages"][$cot->id_cottage]["prices"][$sid]["add_fee_nights"]      = $res[0]->add_fee_nights;
                    $price_arr["cottages"][$cot->id_cottage]["prices"][$sid]["add_fee_perc"]        = $res[0]->add_fee_perc;

                }
                $price_arr["cottages"][$cot->id_cottage]["prices"][$sid]["nights"]++;
                $price_arr["cottages"][$cot->id_cottage]["prices"][$sid]["price"] = $pr;
                $price_arr["cottages"][$cot->id_cottage]["name"] = $cot->name;
                $price_arr["cottages"][$cot->id_cottage]["price"] += $pr;

                $total += $pr;

                /*
                if ($res[0]->reserv_min_nights > $price_arr["reserv_min_nights"])
                {
                    $price_arr["reserv_min_nights"] = $res[0]->reserv_min_nights;
                }
                */

                //Advance 1 day
                $date->modify("+1 day");
            }
            $price_arr["cottages"][$cot->id_cottage]["cot_total"] = $price_arr["cottages"][$cot->id_cottage]["price"];
            if ($isMinNightsFee)
            {
                $price_arr["cottages"][$cot->id_cottage]["cot_total"] = sprintf("%.02f",$price_arr["cottages"][$cot->id_cottage]["price"] * (1 + $this->_config->add_fee_perc/100));
            }
            $price_arr["cottages"][$cot->id_cottage]["reservation_fee"] =  sprintf("%.02f", $price_arr["cottages"][$cot->id_cottage]["cot_total"] * $this->_config->reserv_perc / 100);

        }

        //Determine the Additional Fee
        $price_arr["add_fee_perc"] = $this->additionalFee($price_arr);
        //Should we apply min.nights fee?
        if ($price_arr["add_fee_perc"])
        {
            $add_fee = $total * ($price_arr["add_fee_perc"]/100);
            $price_arr["is_add_fee"] = true;
        }

        //$price_arr["add_fee_nights"] = $this->_config->add_fee_nights;
        //$price_arr["add_fee_perc"] = $this->_config->add_fee_perc;
        $price_arr["add_fee_value"] = $price_arr["is_add_fee"] ? $add_fee : '0';

        $price_arr["total"] = sprintf("%.02f", $total + $add_fee);
        $price_arr["reserv_perc"] = $this->_config->reserv_perc;
        $price_arr["reservation_fee"] = sprintf("%.02f", $price_arr["total"] * $this->_config->reserv_perc / 100);
        $price_arr["total"] = sprintf("%.02f", $price_arr["total"]); //only informational
//CotResHelper::p_r($price_arr);

        $this->_prices = $price_arr;
        return $price_arr;
    }

	/**
	 * Method to get number of nights between two dates
	 *
	 * @access	public
	 * @return	boolean	True if minimum nights selected
	 */
    function getNightsCount($df, $dt)
    {
        return abs(strtotime($df) - strtotime($dt)) / 3600 / 24;
    }

    
	/**
	 * Method to check if Minimun nights condition to create order is met.
	 *
	 * @access	public
	 * @return	boolean	True if minimum nights selected
	 */
    function additionalFee(&$prices)
    {
        $first = current($prices["cottages"]);
        $add_fee_perc = 0;

        foreach ($first["prices"] as $ssn)
        {
            if (!$ssn["add_fee_nights"] || !$ssn["add_fee_perc"]) continue;
            
            if ($ssn["add_fee_nights"] > $prices["nights"])
            {
                $add_fee_perc = ($ssn["add_fee_perc"] > $add_fee_perc) ? $ssn["add_fee_perc"] : $add_fee_perc;
            }
        }
        return $add_fee_perc;
    }


	/**
	 * Method to get additional fee percentage.
	 *
	 * @access	public
	 * @return	boolean	True if minimum nights selected
	 */
    function isMinNights(&$data)
    {
        $prices = $this->getPrice($data);

        $first = current($prices["cottages"]);
//        CotResHelper::p_r($first);

        foreach ($first["prices"] as $ssn)
        {
            if (!$ssn["reserv_min_nights"]) continue;

            if ($ssn["reserv_min_nights"] > $ssn["nights"])
            {
                return -$ssn["reserv_min_nights"];
            }
        }
        return true;
    }
	/**  UNUSED **
	 * Method to check if Minimun nights condition to create order is met.
	 *
	 * @access	public
	 * @return	boolean	True if minimum nights selected
	 */
    function isMinNightsOld(&$data)
    {
        $reserv_min_nights = 0;
        $date = new DateTime($data->date_from);
        $df = $date->format("md");
        $dfy = $date->format("Y");
        
        $date = new DateTime($data->date_to);
        $dt = $date->format("md");
        $dty = $date->format("Y");
        
//CotResHelper::p_r($data); die;
        $diff = abs(strtotime($data->date_to) - strtotime($data->date_from)) / 3600 / 24;

        //If the timeframe is across more that 2 years -> false
        if ($dty1-$dfy1 > 1) return false;

        //Timeframe accross 2 years => split to two
        $df1 = $df;
        $dt1 = $dt;
        $dy1 = $dfy;
        if ($dty - $dfy == 1)
        {
            $dt2 = $dt;
            $dt1 = "1231";
            $df2 = "0101";
            $dy2 = $dty;
        }
        
        for($i=1; $i<=($dty-$dfy+1); $i++)
        {
            $df =  ${'df'.$i};
            $dt = ${'$dt'.$i};
            $y  = ${'dy'.$i};
            foreach (array($y,"1") as $yy)
            {
                $query =
                     " SELECT s.reserv_min_nights FROM #__cotres_seasons s"
                    ." WHERE (('$df' >= CONCAT(RIGHT(s.date_from,2),LEFT(s.date_from,2)) AND '$df' <= CONCAT(RIGHT(s.date_to,2),LEFT(s.date_to,2)))"
                    ." OR ('$dt' >= CONCAT(RIGHT(s.date_from,2),LEFT(s.date_from,2)) AND '$dt' <= CONCAT(RIGHT(s.date_to,2),LEFT(s.date_to,2)))"
                    ." OR ('$df' <= CONCAT(RIGHT(s.date_from,2),LEFT(s.date_from,2)) AND '$dt' >= CONCAT(RIGHT(s.date_to,2),LEFT(s.date_to,2))))"
                    ." AND s.published > 0 AND s.year = '$yy'"
                    ." ORDER BY s.reserv_min_nights DESC";
//echo $query."<br><br>";
                $res = $this->_getList($query);
                if ($res) break;
            }
            if ($res && $res[0]->reserv_min_nights > $reserv_min_nights) $reserv_min_nights = $res[0]->reserv_min_nights;
            //CotResHelper::p_r($res); die;
        }

//echo $reserv_min_nights; die;

        if (!$reserv_min_nights) return true;
        
        $ret = ($diff >= $reserv_min_nights) ? $reserv_min_nights : -$reserv_min_nights;

        return $ret;
    }

	/**
	 * Method to check if additional fee for not enough nights has been met.
	 *
	 * @access	public
	 * @return	boolean True if the additional fee should be added
	 */
    function isMinNightsFee($data)
    {
        $diff = abs(strtotime($data->date_to) - strtotime($data->date_from)) / 3600 / 24 - 1;

        $ret = ($diff <= $this->_config->add_fee_nights) ? true : false;
        return $ret;
    }

    function getRequiredFields()
    {
        $ret = array();
        $ret[0] = array('fname', 'lname');
        $ret[1] = array('company_name', 'ico', 'dic');
        $ret[2] = array('date_from', 'date_to', 'street', 'city', 'zip', 'country', 'phone', 'email');
        return $ret;
    }

    function setStatus($status)
    {
        $this->_data->status = $status;
    }

    function updateStatusPaid($id = false)
    {
        $id = $id ? $id : $this->_id;
        $query = "UPDATE #__cotres_orders SET status = 1 WHERE id = $id";
        $this->_db->setQuery( $query );
		if (!$this->_db->query())
        {
            return false;
        }
        $this->setId($id);
        $this->getData();
        $this->sendEmail(true);
        return true;
    }
    
    function sendEmail($statusChange = false)
    {
        $pricelist_content = $this->getModuleContent($this->_config->pricelist_module_id);

        $eur = " ".JText::_('EUR');
        $lang =& JFactory::getLanguage();
        $data = $this->_data;
        $data->date_from = date("d.m.Y", strtotime($data->date_from));
        $data->date_to = date("d.m.Y", strtotime($data->date_to));
        $data->created = date("d.m.Y H:i:s", strtotime($data->created));
        $topay = $this->_prices["total"] - $this->_prices["reservation_fee"];

//        cotResHelper::p_r($lang);
 //       echo $data->email;
 //       echo $this->_config->email;

        //Open email template file and parse it
        $path = JPATH_COMPONENT_SITE.DS."emailing".DS;
        $file = $path."email.".$lang->_lang.".txt";
        $file_btrf = $path."banktransfer.".$lang->_lang.".txt";
        if (!file_exists($file) || !file_exists($file_btrf))
        {
            $file = $path."email.sk-SK.txt";
            $file_btrf = $path."banktransfer.sk-SK.txt";
        }

        $body = JFile::read($file);
        if ($data->payment_type == "bank_transfer" && $data->status == 0)
        {
            $banktransfer_text = JFile::read($file_btrf);
        }
        else
        {
            $banktransfer_text = "";
        }
        $body = str_replace("<banktransfer_text>", $banktransfer_text, $body);


        //get the longest season name
        $ssn_max_len = strlen(JText::_("Sezóna"));
        $frst = current($this->_prices["cottages"]);
        foreach ($frst["prices"] as $pr)
        {
            if (strlen($pr["season_name"]) > $ssn_max_len) $ssn_max_len = strlen($pr["season_name"]);
        }

        $order_number = sprintf("%06d", $data->id);
        $body = str_replace("<order_number>", $order_number, $body);
        $fields = array('date_from', 'date_to', 'company_name', 'ico', 'dic', 'contact_person', 'fname', 'lname', 'street', 'city', 'zip', 'phone', 'email', 'fax', 'country', 'created');
        foreach ($fields as $fld)
        {
            $body = str_replace("<$fld>", $data->$fld, $body);
        }

        $prices_text = JText::_("Zrub")."\t\t".JText::_("Sezóna").(str_repeat(' ', $ssn_max_len-strlen(JText::_("Sezóna"))))."\t".JText::_("Nocí")."\t".JText::_("Cena")."\n";
        $prices_text .= str_repeat('=', strlen($prices_text)+20)."\n";


        foreach ($this->_prices["cottages"] as $cot)
        {
            $cot_str .=  $cot["name"].", ";
            $nights = 0;
            foreach ($cot["prices"] as $pr)
            {
                //pad the season name with spaces
                $pr["season_name"] .= str_repeat(' ', $ssn_max_len-strlen($pr["season_name"]));
                $prices_text .= $cot["name"]."\t".$pr["season_name"]."\t".$pr["nights"]."\t".$pr["price"]." ".JText::_('EUR')."\n";
                $nights += $pr["nights"];
            }
        }
        $cot_str = substr($cot_str, 0, -2);
        $body = str_replace("<cottages>", $cot_str, $body);

        //Additional fee?
        if ($this->_prices["add_fee_value"])
        {
            $prices_text .= $this->_prices["add_fee_perc"]."% ".JText::_("príplatok za nízky počet nocí ");
            $prices_text .= "\t\t".$this->_prices["add_fee_value"]." $eur\n";
        }
        $prices_text .= "Spolu\t\t\t\t\t$nights\t".$this->_prices["total"].$eur;
        $topay_str = sprintf("%.02f", $topay).$eur;
        $resfee_conv = "";
        if ($this->_config->conversion)
        {
            $topay_str .= " (".sprintf("%.02f", $topay*$this->_config->conversion)." ".JText::_('Sk').")";
            $prices_text .= " (".sprintf("%.02f", $this->_prices["total"]*$this->_config->conversion)." ".JText::_('Sk').")";
            $resfee_conv = " (".sprintf("%.02f", $this->_prices["reservation_fee"]*$this->_config->conversion)." ".JText::_('Sk').")";
        }
        $prices_text .= "\n";
        
        $body = str_replace("<prices>", $prices_text, $body);

        $body = str_replace("<payment_type>", JText::_($data->payment_type), $body);
        $body = str_replace("<status>", $data->status ? JText::_('Uhradená') : JText::_('Neuhradená'), $body);

        $body = str_replace("<topay>",  $topay_str, $body);

        //reservation fee
        if ($data->status)
        {
            $resfee_text = JText::_("Uhradená výška rezervačného poplatku: ").sprintf("%.02f", $this->_prices["reservation_fee"])."$eur".$resfee_conv;
        }
        else
        {
            $resfee_text = JText::_("Treba uhradiť rezervačný poplatok vo výške: ").sprintf("%.02f", $this->_prices["reservation_fee"])."$eur".$resfee_conv;
            $hours_left = $this->_config->reserved_hours - round((time() - strtotime($data->created)) / 3600);
            $hours_left .= " ".JText::_("hodín");
            $body = str_replace("<hours_left>", $hours_left, $body);
            $body = str_replace('<payment_info>', $this->_config->payment_info, $body);

        }
        $body = str_replace("<reservation_fee>", $resfee_text, $body);

        $body = "<pre>".$body;
        $body = str_replace("<module_by_email>", "</pre>".$pricelist_content, $body);
        
//        CotResHelper::p_r($body); die; return true;
//       CotResHelper::p_r($this->_prices);

        //Send the message
        foreach (array($data->email, $this->_config->email) as $recipient)
        {
            $message =& JFactory::getMailer();
            $message->addRecipient($recipient);
            $subject = $statusChange ? JText::_("Zmena stavu Vašej objednávky č.") : JText::_('Vaša objednávka č.');
            $subject .= $data->id;
            $message->setSubject($subject);
            $message->setBody($body);
            $sender = array( $this->_config->email, JText::_('Zruby Bystrá') );
            $message->setSender($sender);
            $message->IsHTML(1);
            //CotResHelper::p_r($body);
            $sent = $message->send();

            if ($sent->code)
            {
      			$this->setError('Error sending email');
                return false;
            }
        }

        return true;
    }
    

	/**
	 * Method to remove expired orders.
	 *
	 * @access	public
	 * @return	boolean True if OK, false if failed
	 */
    function removeExpired()
    {
        $config = $this->getTable('config');
        $config->load();
        
        $query =
             "UPDATE #__cotres_orders SET status = -1 "
            ."WHERE status = 0 AND ((UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(created)) / 3600) >= $config->reserved_hours";

        $this->_db->setQuery($query);
        $ret = $this->_db->query();
        return $ret;
    }

	/**
	 * Method to archive orders which have been in the past
	 *
	 * @access	public
	 * @return	boolean True if OK, false if failed
	 */
    function archiveOldOrders()
    {
        $query =
             " UPDATE #__cotres_orders SET status = -3 "
            ." WHERE status in (0,1) AND UNIX_TIMESTAMP(date_to) < UNIX_TIMESTAMP(NOW())-24*3600 ";

        $this->_db->setQuery($query);
        $ret = $this->_db->query();
        return $ret;
    }
    
	/**
	 * Method to get the content of a module
	 * @return object with data
	 */
	function getModuleContent($id)
	{
        if (!$id) return "";
		$query = ' SELECT content FROM #__modules WHERE id='.$id;
		$content = $this->_db->getOne($query);
		return $content;
    }

}
