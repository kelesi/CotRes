<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php
global $option;
//Are we in frontend or backend?
$isBackend = (JURI::root() != JURI::base()) ? true : false;
?>

<h1><?php echo JText::_('Kalendár obsadenosti'); ?></h1>
<div id="calendar">
    <?php
        $curr_date = date("Ym");
        $m = $this->list["month"];
        $y = $this->list["year"];
		$date = new DateTime("$y-$m-01");
        $posted_date = $date->format("Ym");
        $date->modify("-1 year");
        $link_prev = JRoute::_("index.php?option=com_cotres&type=calendar&month=01&year=".$date->format("Y"));
        $date->modify("+2 year");
        $link_next = JRoute::_("index.php?option=com_cotres&type=calendar&month=01&year=".$date->format("Y"));
        $img_dir = JURI::root()."/administrator/components/com_cotres/images/";
    ?>
    <div id="cal_years">
    <?php
        if ($isBackend || ($posted_date > $curr_date))
        {
    ?>
            <a href="<?php echo $link_prev;?>"><?php echo JText::_('<<'); ?></a>
    <?php
        }
        else
        {
            echo JText::_('<<');
        }
    ?>
        <?php echo $y; ?>
        <a href="<?php echo $link_next;?>"><?php echo JText::_('>>'); ?></a>
    </div>
    
    <div id="cal_months">
    <?php echo JText::_('Mesiac'); ?>:
    <?php
        $date = new DateTime("$y-01-01");
        for ($i=1; $i<=12; $i++)
        {
            $link = JRoute::_("index.php?option=com_cotres&type=calendar&month=$i&year=$y");
            echo "<span>";
            if ($isBackend || $date->format("Ym") >= $curr_date)
            {
                echo '<a href="'.$link.'" class="'.(($m-$i==0) ? "selected" : "").'">' . JText::_($date->format("F")."_short") . "</a>";
            }
            else
            {
                echo JText::_($date->format("F")."_short");
            }
            echo "</span>";
            $date->modify("+1 month");
        }
    ?>
    </div>

	<table class="adminlist calendarlist">
	<thead>
		<tr>
            <th class="title" style="width: 1%;"><?php echo JText::_('Day'); ?></th>

   		<?php
            $arr = current($this->items);
            foreach ($arr as $obj)
            {
                echo '<th class="title">'."$obj->name</th>";
            }

   		?>
		</tr>
	</thead>
    <tbody>
	<?php
        $k=1;
        foreach ($this->items as $date => $arr)
        {
            $k = 1 - $k;
            echo '<tr class="row'.$k.'"><td>'.substr($date,0,2)."</td>";
            foreach ($arr as $obj)
            {
                $st = "$date - $obj->name: ";
                switch ($obj->status)
                {
                    case '0':
                        $img="hours_left.png";
                        $st .= JText::_("Obsadený - Čaká sa na platbu");
                        break;
                    case '1':
                        $img="cross_icon.png";
                        $st .= JText::_("Obsadený");
                        break;
                    case '-3':
                        $st .= CotResHelper::translateStatus($obj->status);
                        $img="cross_icon.png";
                        break;
                    case '-1':
                        $st .= CotResHelper::translateStatus($obj->status);
                        $img="check_icon.png";
                        break;
                    default:
                        $st .= JText::_("Voľný");
                        $img="check_icon.png";
                        break;
                }
                echo '<td title="'.$st.'">';

                echo '<img src="'.$img_dir.$img.'" />';
                if ($obj->status == '0')
                {
                    echo $this->modelConfig->getHoursLeft($obj->created) ." ". JText::_('hrs');
                }
                
                if ($isBackend && $obj->id_order)
                {
                    $link = JRoute::_("index.php?option=com_cotres&controller=order&task=edit&cid[]=$obj->id_order");
                    echo '<a href="'.$link.'">'.JText::_('Order Number').": $obj->id_order</a>";
                }
                echo "</td>";
            }
            echo "</tr>\n";
        }

	?>
    </tbody>
	</table>
    <div id="cal_footer">
        <span><img src="<?php echo $img_dir.'check_icon.png'?>" valign="bottom" /><?php echo JText::_("voľný"); ?></span>
        <span><img src="<?php echo $img_dir.'hours_left.png'?>" /><?php echo JText::_("čaká sa na platbu"); ?>*</span>
        <span><img src="<?php echo $img_dir.'cross_icon.png'?>" /><?php echo JText::_("rezervovaný"); ?></span>
        <br />
    </div>
    <div id="cal_descr">
        *<?php echo JText::_("objednávka ubytovania je platná daný počet hodín"); ?><br />
        <?php echo JText::_("pri nezaplatení v danom limite, bude automaticky zrušená"); ?>
    </div>
</div>
