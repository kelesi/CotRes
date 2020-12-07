<?php // no direct access
    defined('_JEXEC') or die('Restricted access');

    $company_fields = array('company_name', 'ico', 'dic');
    $person_fields = array('fname','lname');
    $dis_comp = !$this->order->user_type ? 'disabled="disabled" class="text_area"' : 'class="text_area required"';
    $dis_pers = $this->order->user_type ? 'disabled="disabled" class="text_area"' :'class="text_area required"';
    $thead_comp = !$this->order->user_type ? 'style="display:none;"' : "";
    $tbody_pers = $this->order->user_type ? 'style="display:none;"' : "";
?>
<script type="text/javascript">
function myValidateStep2(f, task)
{
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

Window.onDomReady(function() {
        document.formvalidator.setHandler('date', function(value) {
                regex=/^\d{4}(-\d{2}){2}$/;
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
        //Disable the company fields and enable the person fields
        c = document.getElementById('company_rows');
        c.style.display = 'none';
        p = document.getElementById('person_rows');
        p.style.display = '';
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
        //Disable the person fields and enable the company fields
        c = document.getElementById('company_rows');
        c.style.display = '';
        p = document.getElementById('person_rows');
        p.style.display = 'none';
    }
}
</script>

<?php
$date_from  = new DateTime($this->order->date_from);
$date_to    = new DateTime($this->order->date_to);
CotResHelper::showTimeline(2);
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
    <div class="cotres_box" id="step2top">
    <b><?php echo JText::_('Ubytovanie rezervujem'); ?></b>
    <?php
        echo JHTML::_('select.booleanlist', 'user_type', 'onclick="javascript:myUserType(adminForm);"', $this->order->user_type, JText::_('na firmu'), JText::_('súkromne'));
    ?>

    </div>
    <div class="separator"></div>
    <div class="cotres_box" id="step2middle">
        <div class="cleaner"></div>
		<table class="admintable">
        <thead name="company_rows" id="company_rows" <?php echo $thead_comp; ?>>
		<tr>
			<td align="right" class="key">
				<label for="company_name">
                <?php echo JText::_( 'Company Name' ); ?>
				</label>
			</td>
			<td>
				<input <?php echo $dis_comp; ?> type="text" name="company_name" id="company_name" size="30" maxlength="250" value="<?php echo $this->order->company_name;?>" />
  			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="ico">
                <?php echo JText::_( 'Company ID' ); ?>
				</label>
			</td>
			<td>
				<input <?php echo $dis_comp; ?> type="text" name="ico" id="ico" size="30" maxlength="250" value="<?php echo $this->order->ico; ?>" />
  			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="dic">
                <?php echo JText::_( 'VAT ID' ); ?>
				</label>
			</td>
			<td>
				<input <?php echo $dis_comp; ?> type="text" name="dic" id="dic" size="30" maxlength="250" value="<?php echo $this->order->dic; ?>" />
  			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="contact_person">
                <?php echo JText::_( 'Contact person' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="contact_person" id="contact_person" size="30" maxlength="250" value="<?php echo $this->order->contact_person; ?>" />
  			</td>
		</tr>
		</thead>
        <tbody name="person_rows" id="person_rows" <?php echo $tbody_pers; ?>>
		<tr>
			<td align="right" class="key">
				<label for="fname">
                <?php echo JText::_( 'First name' ); ?>
				</label>
			</td>
			<td>
				<input <?php echo $dis_pers; ?> type="text" name="fname" id="fname" size="30" maxlength="250" value="<?php echo $this->order->fname; ?>" />
  			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="lname">
                <?php echo JText::_( 'Last name' ); ?>
				</label>
			</td>
			<td>
				<input <?php echo $dis_pers; ?> type="text" name="lname" id="lname" size="30" maxlength="250" value="<?php echo $this->order->lname; ?>" />
  			</td>
		</tr>
        </tbody>
		<tr>
			<td align="right" class="key">
				<label for="street">
                <?php echo JText::_( 'Street' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area required" type="text" name="street" id="street" size="30" maxlength="250" value="<?php echo $this->order->street; ?>" />
  			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="city">
                <?php echo JText::_( 'City' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area required" type="text" name="city" id="city" size="30" maxlength="250" value="<?php echo $this->order->city; ?>" />
  			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="zip">
                <?php echo JText::_( 'ZIP' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area required validate-numeric" type="text" name="zip" id="zip" size="30" maxlength="250" value="<?php echo $this->order->zip; ?>" />
  			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="country">
                <?php echo JText::_( 'Country' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area required" type="text" name="country" id="country" size="30" maxlength="250" value="<?php echo $this->order->country; ?>" />
  			</td>
		</tr>
	   </table>
       <table class="admintable">
    		<tr>
    			<td width="1%" align="right" class="key">
    				<label for="phone">
                    <?php echo JText::_( 'Phone' ); ?>
    				</label>
    			</td>
    			<td>
    				<input class="text_area required" type="text" name="phone" id="phone" size="30" maxlength="250" value="<?php echo $this->order->phone; ?>" />
      			</td>
    		</tr>
    		<tr>
    			<td align="right" class="key">
    				<label for="fax">
                    <?php echo JText::_( 'Fax' ); ?>
    				</label>
    			</td>
    			<td>
    				<input class="text_area" type="text" name="fax" id="fax" size="30" maxlength="250" value="<?php echo $this->order->fax; ?>" />
      			</td>
    		</tr>
    		<tr>
    			<td align="right" class="key">
    				<label for="email">
                    <?php echo JText::_( 'Email' ); ?>
    				</label>
    			</td>
    			<td>
    				<input class="text_area required validate-email" type="text" name="email" id="email" size="30" maxlength="250" value="<?php echo $this->order->email; ?>" />
      			</td>
    		</tr>
        </table>
        <table>
    		<tr>
    			<td align="left" class="key">
                    <?php echo JText::_('* Povinné údaje pre vyplnenie'); ?>
                </td>
            </tr>
    		<tr>
    			<td align="left" class="key">
    				<div class="policy" name="policy" id="policy"><?php echo $this->config->policy_content; ?></div>
      			</td>
    		</tr>
    		<tr>
    			<td align="left" class="key">
    				<input type="checkbox" class="required" name="agreement" id="agreement" value="1" />
                    <label for="agreement"><?php echo JText::_('Súhlasím s obchodnými podmienkami*'); ?></label>
      			</td>
    		</tr>

    	</table>
    <div class="cleaner"></div>
    </div>
    
    <div class="cleaner"></div>
    <div class="separator"></div>

    <div class="cotres_box" id="step2bottom">
    <?php
        foreach ($this->payments as $p)
        {
    ?>
            <div class="radio">
                <input <?php echo ($p["value"] == $this->order->payment_type) ? 'checked="checked"' : ""; ?> type="radio" name="payment_type" id="payment_type" value="<?php echo $p['value']; ?>" /><?php echo $p["text"]; ?>
            </div>
    <?php
            if (end($this->payments) !== $p)
            {
    ?>
            <div class="separator"></div>
    <?
            }
        }
    ?>
    </div>
    
    <input type="hidden" name="option" value="com_cotres" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="controller" value="orderfe" />
    <input type="hidden" name="task" value="step2next" />


    <div class="separator"></div>
    <div class="submitbuttons">
        <button type="submit" name="submit"  class="button left"     onclick="javascript: adminForm.task.value='step2back';"><?php echo JText::_("Naspäť"); ?> </button>
        <button type="submit" name="submit2" class="button right"    onclick="javascript: return myValidateStep2(adminForm, 'step2next');"><?php echo JText::_("Ďalej"); ?>  </button>
    </div>

</form>

<div class="separator"></div>

<?php
echo JText::_('V hore uvedenej tabulke sú zruby volné na rezerváciu vo vami zadanom termíne.');
echo JText::_('Zakliknutím si prosím vyberte zrub na rezerovavanie a v nasledujúcom kroku vyplníte formulár na záveznú rezerváciu.');
echo JText::_('Rezervačný poplatok je zaroveň strono poplatkom a tento je nutné uhradit v procese spôsobom podľa vybranej platby.');
?>
