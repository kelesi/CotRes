<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php
    $company_fields = array('company_name', 'ico', 'dic', 'contact_person');
    $person_fields = array('fname','lname');
    $dis_comp = !$this->order->user_type ? 'disabled="disabled" class="text_area"' : 'class="text_area required"';
    $dis_pers = $this->order->user_type ? 'disabled="disabled" class="text_area"' : 'class="text_area required"';
	$isNew		= ($this->order->id < 1);
?>
<script type="text/javascript">
function myValidate(f) {
        if (document.formvalidator.isValid(f)) {
            f.task.value='save';
            f.submit();
        }
        else {
                alert("<?php echo JText::_('Some values are not acceptable. Please retry.'); ?>");
        return false;
      }
        return false;
}

Window.onDomReady(function() {
        document.formvalidator.setHandler('date', function(value) {
                regex=/^\d{1,2}.\d{1,2}.\d{4}$/;
                return regex.test(value);
        })
})

function myUserType(f)
{
    if (f.user_type[0].checked)
    {
    <?php
        foreach ($company_fields as $fld)
        {
            echo "f.$fld.disabled = true;";
            //echo "f.$fld.value = '';";
            echo "f.$fld.className = 'text_area';";
        }
        foreach ($person_fields as $fld)
        {
            echo "f.$fld.disabled = false;";
            echo "f.$fld.className = 'text_area required';";
        }
    ?>
    }
    
    if (f.user_type[1].checked)
    {
    <?php
        foreach ($company_fields as $fld)
        {
            echo "f.$fld.disabled = false;";
            echo "f.$fld.className = 'text_area required';";
        }
        foreach ($person_fields as $fld)
        {
            echo "f.$fld.disabled = true;";
            //echo "f.$fld.value = '';";
            echo "f.$fld.className = 'text_area';";
        }
    ?>
    }
}

function myStatus(f)
{
    if (f.status[0].checked)
    {
        f.payment_date.className = 'text_area validate-date';
        f.payment_date.disabled = true;
    }

    if (f.status[1].checked)
    {
        f.payment_date.className = 'text_area required validate-date';
        f.payment_date.disabled = false;
    }
}
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">
<div class="col">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>

		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="user_type">
					<?php echo JText::_( 'Order Number' ); ?>:
				</label>
			</td>
			<td>
            <?php
                if (!$isNew)
                {
                    echo $this->order->order_number;
                    if ($this->order->status == 0)
                    { ?>
                        <img src="components/com_cotres/images/hours_left.png" width="21" height="20" title="" alt="" border="1" />
            <?php
                        echo $this->modelConfig->getHoursLeft($this->order->created) ." ". JText::_('hrs');
                    }
                }
                else
                {
                    echo JText::_('New Order');
                }
            ?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="user_type">
					<?php echo JText::_( 'Created' ); ?>:
				</label>
			</td>
			<td>
                <?php
                echo $this->order->created;
                ?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="user_type">
					<?php echo JText::_( 'User type' ); ?>:
				</label>
			</td>
			<td>
                <?php
                    echo JHTML::_('select.booleanlist', 'user_type', 'onclick="javascript:myUserType(adminForm);"', $this->order->user_type, JText::_('Company'), JText::_('Person'));
                ?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
                <?php echo JHTML::tooltip(JText::_("Format DD-MM-YYYY"), JText::_('Date from')); ?>
                <?php echo JText::_( 'Date from' ); ?>
			</td>
			<td>
            <?php
                if ($isNew)
                {
            ?>
                <input class="text_area required validate-date" type="text" name="date_from" id="date_from" size="32" maxlength="250" value="<?php echo CotResHelper::formatDate($this->order->date_from); ?>" />
                <img class="calendar" src="templates/system/images/calendar.png" alt="calendar" onclick="return showCalendar('date_from', '%d.%m.%Y');" />
            <?php
                }
                else
                {
                    echo CotResHelper::formatDate($this->order->date_from);
                }
            ?>
  			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="date_to">
                <?php echo JHTML::tooltip(JText::_("Format DD-MM-YYYY"), JText::_('Date to')); ?>
                <?php echo JText::_( 'Date to' ); ?>
				</label>
			</td>
			<td>
            <?php
                if ($isNew)
                {
            ?>
				<input class="text_area required validate-date" type="text" name="date_to" id="date_to" size="32" maxlength="250" value="<?php echo CotResHelper::formatDate($this->order->date_to); ?>" />
                <img class="calendar" src="templates/system/images/calendar.png" alt="calendar" onclick="return showCalendar('date_to', '%d.%m.%Y');" />
            <?php
                }
                else
                {
                    echo CotResHelper::formatDate($this->order->date_to);
                }
            ?>
  			</td>
		</tr>
	<?php
        if (!$isNew)
        {
	?>
		<tr>
			<td width="100" align="right" class="key">
				<label for="payment_type">
                <?php echo JText::_( 'Payment type' ); ?>
				</label>
			</td>
			<td>
                <?php echo JText::_($this->order->payment_type); ?>
  			</td>
		</tr>

		<tr>
			<td width="100" align="right" class="key">
				<label for="total_price">
                <?php echo JText::_('Total price'); ?>
				</label>
			</td>
			<td>
                <?php echo $this->order->price_total." ".JText::_('EUR'); ?>
  			</td>
		</tr>
		
		<tr>
			<td width="100" align="right" class="key">
				<label for="status">
                <?php echo JHTML::tooltip(JText::_("Status of order"), JText::_('Status')); ?>
                <?php echo JText::_( 'Status' ); ?>
				</label>
			</td>
			<td>
            <?php
                if ($this->order->status == 0)
                {
                    echo JHTML::_('select.booleanlist', 'status', 'onclick="javascript:myStatus(adminForm);"', $this->order->status, CotResHelper::translateStatus(1), CotResHelper::translateStatus(0));
                }
                else
                {
                    echo CotResHelper::translateStatus($this->order->status);
                }
            ?>
  			</td>
		</tr>
		
		<tr>
			<td width="100" align="right" class="key">
				<label for="payment_date">
                <?php echo JHTML::tooltip(JText::_("Format DD-MM-YYYY"), JText::_('Payment date')); ?>
                <?php echo JText::_( 'Payment date' ); ?>
				</label>
			</td>
			<td>
            <?php
            if ($this->order->status == 0 || !$this->order->payment_date)
            {
            ?>
                
				<input class="text_area validate-date" type="text" name="payment_date" id="payment_date" size="32" maxlength="250" value="" disabled="disabled" />
                <img class="calendar" src="templates/system/images/calendar.png" alt="calendar" onclick="return showCalendar('payment_date', '%d-%m-%Y');" />
            <?php
            }
            else
            {
                echo CotResHelper::formatDate($this->order->payment_date);
            }
            ?>
  			</td>
		</tr>
	<?php
        }
	?>
		<tr>
			<td width="100" align="right" class="key">
				<label for="date_to">
                <?php echo JText::_( 'Company Name' ); ?>
				</label>
			</td>
			<td>
				<input <?php echo $dis_comp; ?> type="text" name="company_name" id="company_name" size="32" maxlength="250" value="<?php echo $this->order->company_name;?>" />
  			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="ico">
                <?php echo JText::_( 'Company ID' ); ?>
				</label>
			</td>
			<td>
				<input <?php echo $dis_comp; ?> type="text" name="ico" id="ico" size="32" maxlength="250" value="<?php echo $this->order->ico; ?>" />
  			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="dic">
                <?php echo JText::_( 'VAT ID' ); ?>
				</label>
			</td>
			<td>
				<input <?php echo $dis_comp; ?> type="text" name="dic" id="dic" size="32" maxlength="250" value="<?php echo $this->order->dic; ?>" />
  			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="contact_person">
                <?php echo JText::_( 'Contact person' ); ?>
				</label>
			</td>
			<td>
				<input <?php echo $dis_comp; ?> type="text" name="contact_person" id="contact_person" size="32" maxlength="250" value="<?php echo $this->order->contact_person; ?>" />
  			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="fname">
                <?php echo JText::_( 'First name' ); ?>
				</label>
			</td>
			<td>
				<input <?php echo $dis_pers; ?> type="text" name="fname" id="fname" size="32" maxlength="250" value="<?php echo $this->order->fname; ?>" />
  			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="lname">
                <?php echo JText::_( 'Last name' ); ?>
				</label>
			</td>
			<td>
				<input <?php echo $dis_pers; ?> type="text" name="lname" id="lname" size="32" maxlength="250" value="<?php echo $this->order->lname; ?>" />
  			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="street">
                <?php echo JText::_( 'Street' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="street" id="street" size="32" maxlength="250" value="<?php echo $this->order->street; ?>" />
  			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="city">
                <?php echo JText::_( 'City' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="city" id="city" size="32" maxlength="250" value="<?php echo $this->order->city; ?>" />
  			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="zip">
                <?php echo JText::_( 'ZIP' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="zip" id="zip" size="32" maxlength="250" value="<?php echo $this->order->zip; ?>" />
  			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="country">
                <?php echo JText::_( 'Country' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="country" id="country" size="32" maxlength="250" value="<?php echo $this->order->country; ?>" />
  			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="phone">
                <?php echo JText::_( 'Phone' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="phone" id="phone" size="32" maxlength="250" value="<?php echo $this->order->phone; ?>" />
  			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="fax">
                <?php echo JText::_( 'Fax' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="fax" id="fax" size="32" maxlength="250" value="<?php echo $this->order->fax; ?>" />
  			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="email">
                <?php echo JText::_( 'Email' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area required validate-email" type="text" name="email" id="email" size="32" maxlength="250" value="<?php echo $this->order->email; ?>" />
  			</td>
		</tr>
	</table>

	</fieldset>
</div>

<div class="col">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Cottages' ); ?></legend>
		<table class="admintable">
		<tr><td>
        <?php
//        string   genericlist  (array $arr, string $name, [string $attribs = null], [string $key = 'value'], [string $text = 'text'], [mixed $selected = NULL], [ $idtag = false], [ $translate = false])
            if ($isNew)
            {
                echo JHTML::_('select.genericlist', $this->cottages, "cottages[]", 'multiple="multiple" class="required" style="width:100px;"', "id_cottage", "name", array());
            }
            else
            {
                foreach ($this->cottages as $cot)
                {
//                    if ($cot->selected)
                    {
                        //print_r($cot);
                    	$link = JRoute::_( 'index.php?option=com_cotres&controller=cottage&task=edit&cid[]='. $cot->id_cottage );
                        echo '<a href="'.$link.'" title="'.JText::_("Edit").'">'.$cot->name."</a><br />";
                    }
                }
            }
        ?>

		</td></tr>
		</table>
    </fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_cotres" />
<input type="hidden" name="id" value="<?php echo $this->order->id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="order" />
</form>
