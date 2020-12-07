<?php // no direct access
    defined('_JEXEC') or die('Restricted access');
?>
<script type="text/javascript">
function myValidateStep3(f, task)
{
    if (confirm("<?php echo JText::_('Naozaj chcete potvrdiť objednávku ?'); ?>") == false)
    {
        return false;
    }

    if (document.formvalidator.isValid(f)) {
        f.task.value=task;
        f.submit();
    }
    else
    {
        alert("<?php echo JText::_('Some values are not acceptable. Please retry.'); ?>");
        return false;
    }
    return false;
}
</script>

<?php
$date_from  = new DateTime($this->order->date_from);
$date_to    = new DateTime($this->order->date_to);
CotResHelper::showTimeline(3);
/*
if ($this->config->add_fee_nights && $this->config->add_fee_perc)
{
    echo JText::_('Pri zvolenom počte nocí menšom ako')." ".$this->config->add_fee_nights." ".JText::_(', výsledná cena obsahuje príplatok')." ".$this->config->add_fee_perc." %.<br />";
}
*/
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
    </tr>
    <?php
        $i = 0;
        $total = 0;
        $reserv_total = 0;
        foreach ($this->price_arr["cottages"] as $cot_id => $cot)
        {
            $price = "";
            foreach ($cot["prices"] as $pr)
            {
                $price .= $pr["price"]." / ";
            }
            $prices = substr($price,0,-2).JText::_('EUR');

            echo '<tr>';
            echo '<td class="col1">'.$cot["name"].'</td>';
            echo '<td class="col2">'.$prices.'</td>';
            echo '<td class="col3">'.sprintf("%.02f", $cot["reservation_fee"])." ".JText::_('EUR').'</td>';
            echo '<td class="col4">'.sprintf("%.02f", $cot["cot_total"])." ".JText::_('EUR').'</td>';
            echo '</tr>';
            $i++;
            $total += $cot["cot_total"];
            $reserv_total += $cot["reservation_fee"];
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
            <td class="colc3"><span id="add_rfee"><?php echo sprintf("%.02f", $reserv_total*$this->price_arr["add_fee_perc"]/100)." ".JText::_('EUR'); ?></span></td>
            <td class="colc4"><span id="add_total"><?php echo sprintf("%.02f", $total*$this->price_arr["add_fee_perc"]/100)." ".JText::_('EUR'); ?></span></td>
        </tr>
    <?php
        }
    ?>
    </table>
    <div class="separator"></div>
    <div class="box1 greenbox">
        <?php echo JText::_('Rezervačný poplatok spolu'); ?>
        <span class="right">
        <?php
            echo $this->price_arr["reservation_fee"];
            echo " ".JText::_('EUR');
            if ($this->config->conversion)
            {
                echo " ( ".sprintf("%.02f", (round($this->price_arr["reservation_fee"]*$this->config->conversion*100)/100)).' '.JText::_('Sk').")";
            }
        ?>
        </span>
    </div>
    <div class="separator"></div>

    <div class="box1 pinkbox">
        <?php echo JText::_('Celková cena ubytovania'); ?>
        <span class="right">
        <?php
            echo $this->price_arr["total"];
            echo " ".JText::_('EUR');
            if ($this->config->conversion)
            {
                echo " ( ".sprintf("%.02f", (round($this->price_arr["total"]*$this->config->conversion*100)/100)).' '.JText::_('Sk').")";
            }
        ?>

        </span>
    </div>
    <div class="separator"></div>

    <div class="cotres_box" id="step2top">
    <?php echo JText::_('Ubytovanie rezervujem')." "; ?>
    <b><?php echo ($this->order->user_type ? JText::_('na firmu') : JText::_('súkromne')); ?></b>
    </div>

    <div class="cotres_box" id="step2middle"><div id="step3middle">
		<table class="admintable">
    <?php
        if ($this->order->user_type)
        {
    ?>
        <thead name="company_rows" id="company_rows">
		<tr>
			<td align="right" class="key">
				<label for="company_name">
                <?php echo JText::_( 'Company Name' ); ?>
				</label>
			</td>
			<td><?php echo $this->order->company_name;?>"</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="ico">
                <?php echo JText::_( 'Company ID' ); ?>
				</label>
			</td>
			<td><?php echo $this->order->ico; ?>"</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="dic">
                <?php echo JText::_( 'VAT ID' ); ?>
				</label>
			</td>
			<td><?php echo $this->order->dic; ?></td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="contact_person">
                <?php echo JText::_( 'Contact person' ); ?>
				</label>
			</td>
			<td><?php echo $this->order->contact_person; ?></td>
		</tr>
		</thead>
    <?php
        }
        else
        {
    ?>
        <tbody name="person_rows" id="person_rows" <?php echo $tbody_pers; ?>>
		<tr>
			<td align="right" class="key">
				<label for="fname">
                <?php echo JText::_( 'First name' ); ?>
				</label>
			</td>
			<td><?php echo $this->order->fname; ?></td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="lname">
                <?php echo JText::_( 'Last name' ); ?>
				</label>
			</td>
			<td><?php echo $this->order->lname; ?></td>
		</tr>
        </tbody>
    <?php
        }
    ?>
		<tr>
			<td align="right" class="key">
				<label for="street">
                <?php echo JText::_( 'Street' ); ?>
				</label>
			</td>
			<td><?php echo $this->order->street; ?></td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="city">
                <?php echo JText::_( 'City' ); ?>
				</label>
			</td>
			<td><?php echo $this->order->city; ?></td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="zip">
                <?php echo JText::_( 'ZIP' ); ?>
				</label>
			</td>
			<td><?php echo $this->order->zip; ?></td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="country">
                <?php echo JText::_( 'Country' ); ?>
				</label>
			</td>
			<td><?php echo $this->order->country; ?></td>
		</tr>
	   </table>
        <table >
    		<tr>
    			<td width="1%" align="right" class="key">
    				<label for="phone">
                    <?php echo JText::_( 'Phone' ); ?>
    				</label>
    			</td>
    			<td><?php echo $this->order->phone; ?></td>
    		</tr>
    		<tr>
    			<td align="right" class="key">
    				<label for="fax">
                    <?php echo JText::_( 'Fax' ); ?>
    				</label>
    			</td>
    			<td><?php echo $this->order->fax; ?></td>
    		</tr>
    		<tr>
    			<td align="right" class="key">
    				<label for="email">
                    <?php echo JText::_( 'Email' ); ?>
    				</label>
    			</td>
    			<td><?php echo $this->order->email; ?></td>
    		</tr>
    	</table>
    <div class="cleaner"></div>
    </div></div>

    <div class="separator"></div>

    <div class="cotres_box" id="step2bottom">
        <b>
        <?php echo JText::_('Vybraný typ spôsobu platby: '); ?>
        <?php echo JText::_($this->order->payment_type); ?>
        </b>
        <br />
        <span style="font-weight: normal;">
        <?php
        if ($this->order->payment_type != "bank_transfer")
        {
            echo JText::_('Pri tejto platbe dochádza k online záväznému zarezervovaniu ubytovania');
        ?>
        <br /><br />
        <span class="red"><?php echo JText::_('Po odoslaní platby kliknite prosím na návrat na stránku ubytovateľa!'); ?></span>
        <?php
        }
        else
        {
            echo JText::_('Detail objednávky spolu s údajmi o platbe budú zaslané na Váš email po potvrdení objednávky.');
        }
        ?>
        </span>
    </div>
    
    <div class="separator"></div>

    <input type="hidden" name="option" value="com_cotres" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="controller" value="orderfe" />
    <input type="hidden" name="task" value="step3next" />


    <div class="submitbuttons">
        <button type="submit" name="task1" value="step3back" class="button left"     onclick="javascript: adminForm.task.value='step3back';"><?php echo JText::_("Naspäť"); ?> </button>
        <button type="submit" name="task2" value="step3next" class="button right"    onclick="javascript: return myValidateStep3(adminForm, 'step3next');"><?php echo JText::_("Ďalej"); ?>  </button>
    </div>
</form>

<div class="separator"></div>

<?php
echo JText::_('V hore uvedenej tabulke sú zruby volné na rezerváciu vo vami zadanom termíne.');
echo JText::_('Zakliknutím si prosím vyberte zrub na rezerovavanie a v nasledujúcom kroku vyplníte formulár na záveznú rezerváciu.');
echo JText::_('Rezervačný poplatok je zaroveň strono poplatkom a tento je nutné uhradit v procese spôsobom podľa vybranej platby.');
?>
