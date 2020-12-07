<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class CotResHelper
{

	function translateStatus($status)
	{
        if (!$status) $status = 0;
        $str = "";
        switch ($status)
        {
            case -3:
                $str = JText::_("ARCHIVED");
                break;

            case -2:
                $str = JText::_("MANUALLY REMOVED");
                break;

            case -1:
                $str = JText::_("EXPIRED");
                break;

            case 0:
                $str = JText::_("UNPAID");
                break;

            case 1:
                $str = JText::_("PAID");
                break;

            default:
                $str = JText::_("undefined");
        }

        return $str;
    }
    
    function getOrderNumber(&$row)
    {
        $year = JHTML::_('date',  $row->created, "%Y" );
        return $year.sprintf("%05d", $row->id);
    }
    
    function getUserType($user_type)
    {
        return $user_type ? JText::_("Company") : JText::_("Person");
    }

    function p_r($array)
    {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }

    function formatDate($d)
    {
        return strtotime($d) ? date("d-m-Y", strtotime($d)) : "";
    }
    
/* For FRONTEND */
    function showWarning($msg = false)
    {
        if (!$msg)
        {
            $error = JError::getError();
            //print_r($error);
            if ($error)
            {
               $msg = $error->message;
            }
        }
        if ($msg)
        {
            echo '<div class="warning">'.$msg."</div>";
        }
    }


    function showTimeLine($step=0)
    {
        $arr = array('Rezervácia', 'Formulár', 'Potvrdenie', 'Platba*', 'Ukončenie');
    ?>
        <div id="timeline" class="step<?php echo $step; ?>">
        <div class="cleaner"></div>
        <ul>
        <?php
            $i=0;
            foreach ($arr as $txt)
            {
                $i++;
                $class = ($i == $step) ? ' class="marked"' : '';
                echo "<li$class>".JText::_($txt)."</li>";
            }
        ?>
        </ul>
        </div>
    <?php
    }

    function isIElte6()
    {
        if ( $my->id ) {initEditor();}
        $browser = get_browser(null, true);
        $is_ie_lte6 = ($browser["browser"] == "IE" && $browser["majorver"] <= 6) ? true : false;
        return $is_ie_lte6;
    }

}
?>
