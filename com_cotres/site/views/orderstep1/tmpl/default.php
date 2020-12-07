<?php // no direct access
    defined('_JEXEC') or die('Restricted access');
?>
<script type="text/javascript">
function myValidateStep1(f)
{
    if (f.boxchecked.value == 0)
    {
        alert('<?php echo JText::_("Musíte zvoliť minimálne jeden zrub."); ?>');
        return false;
    }
    return true;
}

function recalculate()
{
    var i=0;
    var rfee=0;
    var total=0;
    var add_rfee=0;
    var add_total=0;
    var e;
    var conv=<?php echo $this->config->conversion+1 ?>-1;
    var add_fee_perc=<?php echo $this->price_arr["add_fee_perc"]+0; ?>;
    var is_add_fee=<?php echo $this->price_arr["is_add_fee"]+0; ?>;

    while (1)
    {
        var cid = document.getElementById('cb'+i);

        if (!cid) break;
        if (cid.checked)
        {
            e = document.getElementById('rfee'+i);
            rfee += parseFloat(e.innerHTML);
            e = document.getElementById('cot_total'+i);
            total += parseFloat(e.innerHTML);

        }
        i++;
    }
    if (is_add_fee)
    {
        add_rfee  = rfee*add_fee_perc/100;
        add_total = total*add_fee_perc/100;
        e = document.getElementById('add_rfee');
        e.innerHTML = add_rfee.toFixed(2);
        e = document.getElementById('add_total');
        e.innerHTML = add_total.toFixed(2);
        
        rfee  += add_rfee;
        total += add_total;
    }
    e = document.getElementById('reservation_fee');
    e.innerHTML = rfee.toFixed(2);
    e = document.getElementById('total');
    e.innerHTML = total.toFixed(2);


    if (conv)
    {
        e = document.getElementById('reservation_fee_conv');
        i = rfee*conv;
        e.innerHTML = i.toFixed(2);
        e = document.getElementById('total_conv');
        i = total*conv;
        e.innerHTML = i.toFixed(2);
    }
    

}
</script>

<?php
$date_from  = new DateTime($this->order->date_from);
$date_to    = new DateTime($this->order->date_to);
CotResHelper::showTimeline(1);

//if ($this->config->add_fee_nights && $this->config->add_fee_perc)
if ($this->price_arr["add_fee_nights"] && $this->price_arr["add_fee_perc"])
{
    echo JText::_('Pri zvolenom počte nocí menšom ako')." ".$this->price_arr["add_fee_nights"]." ".JText::_(', výsledná cena obsahuje príplatok')." ".$this->price_arr["add_fee_perc"]." %.<br />";
}

?>
<div class="separator"></div>
<div class="box1 bluebox">
    <b><?php echo JText::_("Termín ubytovania").":"; ?></b>
    <span class="right">
    <?php
        echo JText::_("od")." <b>".$date_from->format("d.m.Y"). "</b> &nbsp;&nbsp;";
        echo JText::_("do")." <b>".$date_to->format("d.m.Y"). "</b> ";
        $nc = $this->price_arr["nights"];
        echo "<b>&nbsp;&nbsp; (".$nc.($nc < 5 ? JText::_(" Noci") : JText::_(" Nocí")).") </b>";
    ?>
    </span>
</div>
<div class="separator"></div>
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">
    <table class="step1list calendarlist">
    <tr>
        <th><?php echo JText::_('Zrub'); ?></th>
        <th><?php echo JText::_('Cena zrub/noc'); ?></th>
        <th><?php echo JText::_('Rezervačný poplatok'); ?></th>
        <th><?php echo JText::_('Celková cena'); ?></th>
        <th><?php echo JText::_('Rezervovať'); ?></th>
    </tr>
    <?php
        $i = 0;
        foreach ($this->price_arr["cottages"] as $cot_id => $cot)
        {
            $checked   = JHTML::_( 'grid.id',   $i, $cot_id );
            $price = "";
            foreach ($cot["prices"] as $pr)
            {
                $price .= $pr["price"]." / ";
            }
            $prices = substr($price,0,-2).JText::_('EUR');

            echo '<tr>';
            echo '<td class="col1">'.$cot["name"].'</td>';
            echo '<td class="col2">'.$prices.'</td>';
            echo '<td class="col3"><span id="rfee'.$i.'">'.$cot["reservation_fee"]."</span> ".JText::_('EUR').'</td>';
            echo '<td class="col4"><span id="cot_total'.$i.'">'.$cot["cot_total"]."</span> ".JText::_('EUR').'</td>';
            echo '<td class="col5" onclick="javascript: recalculate();">'.$checked.'</td>';

            echo '</tr>';
            $i++;
        }
    ?>
    <?php
        //If there is additional fee
        if ($this->price_arr["is_add_fee"])
        {
    ?>
        <tr>
            <td class="col1"><?php echo JText::_('Additional fee'); ?></td>
            <td class="colc2"><?php echo  $this->price_arr["add_fee_perc"]; ?> %</td>
            <td class="colc3"><span id="add_rfee">0</span></td>
            <td class="colc4"><span id="add_total">0</span></td>
            <td class="colc5"></td>
        </tr>
    <?php
        }
    ?>
    </table>
    <div class="separator"></div>
    <div class="box1 greenbox">
        <?php echo JText::_('Rezervačný poplatok spolu'); ?>
        <span class="right">
            <span id="reservation_fee" name="reservation_fee">0</span>
        <?php
            echo JText::_('EUR');
            if ($this->config->conversion)
            {
                echo ' (<span id="reservation_fee_conv" name="reservation_fee_conv">0</span> '.JText::_('Sk').")";
            }
        ?>
        </span>
    </div>
    <div class="separator"></div>

    <div class="box1 pinkbox">
        <?php echo JText::_('Celková cena ubytovania'); ?>
        <span class="right">
            <span id="total" name="total">0</span>
            <?php
            echo JText::_('EUR');
            if ($this->config->conversion)
            {
                echo ' (<span id="total_conv" name="total_conv">0</span> '.JText::_('Sk').")";
            }
        ?>

        </span>
    </div>
    <div class="separator"></div>

    <input type="hidden" name="option" value="com_cotres" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="controller" value="orderfe" />
    <input type="hidden" name="task" value="step1next" />

    <div class="submitbuttons">
        <button type="submit" name="submit"  class="button right" onclick="javascript: return myValidateStep1(adminForm);"><?php echo JText::_("Ďalej"); ?></button>
    </div>
</form>

<div class="separator"></div>

<?php
echo JText::_('V hore uvedenej tabulke sú zruby volné na rezerváciu vo vami zadanom termíne.');
echo JText::_('Zakliknutím si prosím vyberte zrub na rezerovavanie a v nasledujúcom kroku vyplníte formulár na záveznú rezerváciu.');
echo JText::_('Rezervačný poplatok je zaroveň strono poplatkom a tento je nutné uhradit v procese spôsobom podľa vybranej platby.');
?>
