<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php
JHTML::_('behavior.tooltip');
JHTML::_('behavior.formvalidation');
?>

<script language="javascript">
function myValidate(f, task) {
        if (document.formvalidator.isValid(f)) {
            f.task.value=task;
            f.submit();
        }
        else {
                alert("<?php echo JText::_('Some values are not acceptable. Please retry.'); ?>");
        return false;
      }
        return false;
}

Window.onDomReady(function() {
        document.formvalidator.setHandler('int', function(value) {
                regex=/^\d*$/;
                return regex.test(value);
        })
})

</script>

<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">
<div class="col">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>

		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="email">
			        <?php echo JText::_( 'Email of the owner' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area validate-email" type="text" name="email" id="email" size="32" maxlength="250" value="<?php echo $this->config->email;?>" />
				<?php echo JHTML::tooltip(JText::_("Order confirmations will be sent here."), JText::_('Email of the owner')); ?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="payment_info">
                <?php echo JText::_( 'Payment information' ); ?>
                </label>
			</td>
			<td>
                <textarea class="text_area" type="text" name="payment_info" id="payment_info" rows="3" cols="45" class="inputbox"><?php echo $this->config->payment_info;?></textarea>
                <?php echo JHTML::tooltip(JText::_("Address. Contacts. Bank accout information."), JText::_('Payment information')); ?>
  			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="reserv_perc">
                <?php echo JText::_( 'Reservation fee percentage (%)' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area validate-numeric" type="text" name="reserv_perc" id="reserv_perc" size="32" maxlength="250" value="<?php echo $this->config->reserv_perc;?>" />
                <?php echo JHTML::tooltip(JText::_("The percent of the oder price needed to pay as reservation fee."), JText::_('Reservation fee')); ?>
  			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="online">
                <?php echo JHTML::tooltip(JText::_("Whether system is online or offline. If offline a message on frontend will be shown to the users."), JText::_('System online')); ?>
                <?php echo JText::_( 'System online' ); ?>
				</label>
			</td>
			<td>
                <input class="radio" type="radio" name="online" id="online" <?php echo $this->config->online ? '' : 'checked="checked"'; ?> value="0" /><?php echo JText::_('No'); ?>
				<input class="radio" type="radio" name="online" id="online" <?php echo $this->config->online ? 'checked="checked"' : ""; ?> value="1" /><?php echo JText::_('Yes'); ?>
  			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="testing">
                <?php echo JHTML::tooltip(JText::_("Whether system is in testing mode."), JText::_('Testing mode')); ?>
                <?php echo JText::_( 'Testing mode' ); ?>
				</label>
			</td>
			<td>
                <input class="radio" type="radio" name="testing" id="testing" <?php echo $this->config->testing ? '' : 'checked="checked"'; ?> value="0" /><?php echo JText::_('No'); ?>
				<input class="radio" type="radio" name="testing" id="testing" <?php echo $this->config->testing ? 'checked="checked"' : ""; ?> value="1" /><?php echo JText::_('Yes'); ?>
  			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="reserved_hours">
                <?php echo JText::_( 'Reserved time' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area validate-int" type="text" name="reserved_hours" id="reserved_hours" size="32" maxlength="250" value="<?php echo $this->config->reserved_hours;?>" />
                <?php echo JHTML::tooltip(JText::_("Time in hours since order creation after which the reservation fee has to be paid, otherwise the order will be deleted."), JText::_('Reserved time')); ?>
  			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="policy_article_id">
                <?php echo JText::_( 'Policy article' ); ?>
				</label>
			</td>
			<td>
                <?php echo JHTML::_('select.genericlist', $this->articles, "policy_article_id", "", "value", "text", $this->config->policy_article_id); ?>
                <?php echo JHTML::tooltip(JText::_(""), JText::_('Policy article')); ?>
  			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="policy_article_id">
                <?php echo JText::_( 'Modul emailom' ); ?>
				</label>
			</td>
			<td>
                <?php echo JHTML::_('select.genericlist', $this->modules, "pricelist_module_id", "", "value", "text", $this->config->pricelist_module_id); ?>
                <?php echo JHTML::tooltip(JText::_(""), JText::_('Modul zasielaný emailom')); ?>
  			</td>
		</tr>

		<tr>
			<td width="100" align="right" class="key">
				<label for="conversion">
                <?php echo JText::_( 'Konverzný kurz' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area validate-float" type="text" name="conversion" id="conversion" size="32" maxlength="250" value="<?php echo $this->config->conversion; ?>" />
  			</td>
		</tr>

	</table>

	</fieldset>
</div>
<div class="col">
	<fieldset class="cardpayform">
		<legend><?php echo JText::_( 'CardPay' ); ?></legend>
		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="cardpay_key">
			        <?php echo JText::_( 'Bezpečnostný kľúč' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="cardpay_key" id="cardpay_key" size="32" maxlength="250" value="<?php echo $this->config->cardpay_key; ?>" />
				<?php echo JHTML::tooltip(JText::_("Bezpečnostný kľúč z TB"), JText::_('Bezpečnostný kľúč')); ?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="cardpay_mid">
			        <?php echo JText::_( 'Merchant Identification (MID)' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="cardpay_mid" id="cardpay_mid" size="32" maxlength="250" value="<?php echo $this->config->cardpay_mid; ?>" />
				<?php echo JHTML::tooltip(JText::_("Jedinečné identifikačné číslo obchodníka, ku ktorému je priradený účet obchodníka a bezpečnostný kľúč, určený na zabezpečenie správ. "), JText::_('Merchant Identification')); ?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="cardpay_cs">
			        <?php echo JText::_( 'Konštantný symbol' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="cardpay_cs" id="cardpay_cs" size="32" maxlength="250" value="<?php echo $this->config->cardpay_cs;?>" />
				<?php echo JHTML::tooltip(JText::_("Konštantný symbol"), JText::_('Konštantný symbol')); ?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="cardpay_rem">
			        <?php echo JText::_( 'Return e-mail' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area validate-email" type="text" name="cardpay_rem" id="cardpay_rem" size="32" maxlength="250" value="<?php echo $this->config->cardpay_rem;?>" />
				<?php echo JHTML::tooltip(JText::_("Notifikácia pre obchodníka o realizácii platby  vo forme e-mailu. "), JText::_('Return e-mail')); ?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="cardpay_rsms">
			        <?php echo JText::_( 'Return Short Message System' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="cardpay_rsms" id="cardpay_rsms" size="32" maxlength="250" value="<?php echo $this->config->cardpay_rsms;?>" />
				<?php echo JHTML::tooltip(JText::_("Notifikácia pre obchodníka o realizácii platby vo forme SMS. Zadané MT číslo musí byť v tvare:<br/>9XX NNN NNN<br/>09XX NNN NNN<br/>+4219XX NNN NNN"), JText::_('Return Short Message System')); ?>
			</td>
		<tr>
			<td width="100" align="right" class="key">
				<label for="cardpay_name">
			        <?php echo JText::_( 'Meno klienta ' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="cardpay_name" id="cardpay_name" size="32" maxlength="250" value="<?php echo $this->config->cardpay_name;?>" />
				<?php echo JHTML::tooltip(JText::_("Meno musí byť očistené od diakritiky s dĺžkou max. 30 znakov"), JText::_('Meno klienta')); ?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="cardpay_ipc">
			        <?php echo JText::_( 'IP adresa webu' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="cardpay_ipc" id="cardpay_ipc" size="32" maxlength="250" value="<?php echo $this->config->cardpay_ipc;?>" />
				<?php echo JHTML::tooltip(JText::_("Ak nie je k dispozícii, tak IP adresa proxy servera."), JText::_('IP adresa klienta')); ?>
			</td>
		</tr>

        </table>
    </fieldset>
	<fieldset class="paypalform">
		<legend><?php echo JText::_( 'PayPal' ); ?></legend>
		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="paypal">
                <?php echo JText::_( 'Paypal' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="paypal" id="paypal" size="32" maxlength="250" value="<?php echo $this->config->paypal;?>" />
                <?php echo JHTML::tooltip(JText::_("Paypal email of owner."), JText::_('Paypal ID')); ?>
  			</td>
		</tr>
		</table>
    </fieldset>

</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_cotres" />
<input type="hidden" name="id" value="<?php echo $this->config->id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="config" />
</form>


<?php return; ?>
<!--
		<tr>
			<td width="100" align="right" class="key">
				<label for="reserv_perc">
                <?php echo JText::_( 'Reservation fee percentage (%)' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area validate-numeric" type="text" name="reserv_perc" id="reserv_perc" size="32" maxlength="250" value="<?php echo $this->config->reserv_perc;?>" />
                <?php echo JHTML::tooltip(JText::_("The percent of the oder price needed to pay as reservation fee."), JText::_('Reservation fee')); ?>
  			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="add_fee_nights">
                <?php echo JText::_( 'Number of nights with additional fee' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area validate-int" type="text" name="add_fee_nights" id="add_fee_nights" size="32" maxlength="250" value="<?php echo $this->config->add_fee_nights;?>" />
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
				<input class="text_area validate-numeric" type="text" name="add_fee_perc" id="add_fee_perc" size="32" maxlength="250" value="<?php echo $this->config->add_fee_perc;?>" />
                <?php echo JHTML::tooltip(JText::_("This fee is in % from the order's total and is added to the final price, if the number of nights is less than defined above."), JText::_('Additional fee percentage')); ?>
  			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="reserv_min_nights">
                <?php echo JText::_( 'Minimum nights' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area validate-int" type="text" name="reserv_min_nights" id="reserv_min_nights" size="32" maxlength="250" value="<?php echo $this->config->reserv_min_nights;?>" />
                <?php echo JHTML::tooltip(JText::_("Minimum nights alowed for reservation."), JText::_('Minimum nights')); ?>
  			</td>
		</tr>
-->
