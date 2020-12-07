<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php
JHTML::_('behavior.tooltip');
JHTML::_('behavior.calendar');
JHTML::_('behavior.formvalidation');
?>
<script language="javascript">
function myValidate(f) {
        if (document.formvalidator.isValid(f))
        {
            if (f.reserv_min_nights.value-f.add_fee_nights.value > 0)
            {
                alert("<?php echo JText::_('Minimum nights').' '.JText::_('cannot be higher than').' '.JText::_('Number of nights with additional fee'); ?>");
                return false;
            }
            f.task.value='save';
            f.submit();
        }
        else
        {
            alert("<?php echo JText::_('Some values are not acceptable. Please retry.'); ?>");
            return false;
        }
        return false;
}

Window.onDomReady(function() {
        document.formvalidator.setHandler('season', function(value) {
                regex=/^\d{2}(-\d{2})$/;
                return regex.test(value);
        })
})

</script>

<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>

		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="year">
					<?php echo JText::_( 'Year' ); ?>:
				</label>
			</td>
			
			<td>
                <?php
                    if ($this->season->id)
                    {
                        echo $row->year ? $row->year : JText::_("Default");
                    }
                    else
                    {
                        echo JHTML::_('select.genericlist', $this->years, "year", "", "value", "text", date('Y'));
                    }
                ?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'Season name' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area required" type="text" name="name" id="name" size="32" maxlength="250" value="<?php echo $this->season->name;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
                <?php echo JHTML::tooltip(JText::_("Format dd-mm <br />dd=day<br />mm=month"), JText::_('Season start')); ?>
                <?php echo JText::_( 'Date from' ); ?>
			</td>
			<td>
                <input class="text_area validate-season required" type="text" name="date_from" id="date_from" size="32" maxlength="250" value="<?php echo $this->season->date_from;?>" />
                <img class="calendar" src="templates/system/images/calendar.png" alt="calendar" onclick="return showCalendar('date_from', '%d-%m');" />
  			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="date_to">
                <?php echo JHTML::tooltip(JText::_("Format dd-mm <br />dd=day<br />mm=month"), JText::_('Season end')); ?>
                <?php echo JText::_( 'Date to' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area validate-season required" type="text" name="date_to" id="date_to" size="32" maxlength="250" value="<?php echo $this->season->date_to;?>" />
                <img class="calendar" src="templates/system/images/calendar.png" alt="calendar" onclick="return showCalendar('date_to', '%d-%m');" />
  			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="reserv_min_nights">
                <?php echo JText::_( 'Minimum nights' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area validate-numeric" type="text" name="reserv_min_nights" id="reserv_min_nights" size="32" maxlength="250" value="<?php echo $this->season->reserv_min_nights;?>" />
                <?php echo JHTML::tooltip(JText::_("Minimum nights alowed for reservation."), JText::_('Minimum nights')); ?>
  			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="add_fee_nights">
                <?php echo JText::_( 'Number of nights with additional fee' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area validate-numeric" type="text" name="add_fee_nights" id="add_fee_nights" size="32" maxlength="250" value="<?php echo $this->season->add_fee_nights;?>" />
                <?php echo JHTML::tooltip(JText::_("When the order is for less nights that this value an additional fee (set bellow) will be added to final price."), JText::_('Number of nights with additional fee')); ?>
  			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="add_fee_nights">
                <?php echo JText::_( 'Additional fee percentage' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area validate-numeric" type="text" name="add_fee_perc" id="add_fee_perc" size="32" maxlength="250" value="<?php echo $this->season->add_fee_perc;?>" />
                <?php echo JHTML::tooltip(JText::_("This fee is in % from the order's total and is added to the final price, if the number of nights is less than defined above."), JText::_('Additional fee percentage')); ?>
  			</td>
		</tr>
	</table>

	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_cotres" />
<input type="hidden" name="id" value="<?php echo $this->season->id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="season" />
</form>
