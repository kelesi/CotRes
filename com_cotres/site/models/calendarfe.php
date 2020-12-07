<?php
/**
 * Cotres model
 *
 * @package    CotRes - Cottage Reservation system
 * @subpackage Components
 * @link http://www.sigil.sk
 * @license
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );
jimport( 'joomla.error.error' );

require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'calendar.php');

/**
 * Hello Model
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class CotresModelCalendarFE extends CotresModelCalendar
{
    function __construct()
    {
        parent::__construct();

        $month  = $this->getState('month');
        $year   = $this->getState('year');

        if ($year - date("Y") < 0)
        {
            $this->setState('year', date("Y"));
            $year   = $this->getState('year');
        }

        if (($year - date("Y") == 0) && ($month - date("m") < 0))
        {
            $this->setState('month', date("m"));
        }
    }
}
