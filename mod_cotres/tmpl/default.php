<?php // no direct access
defined('_JEXEC') or die('Restricted access');
?>
<script type="text/javascript">
function myModCotresValidate(f) {
    if (document.formvalidator.isValid(f))
    {
        f.submit();
    }
    else
    {
        alert("<?php echo JText::_('Some values are not acceptable. Please retry.'); ?>");
    }
    return false;
}

Window.onDomReady(function() {
        document.formvalidator.setHandler('date', function(value) {
                regex=/^\d{1,2}.\d{1,2}.\d{4}$/;
                return regex.test(value);
        })
})
</script>

<div class="margin">
    <form action="index.php" method="post" name="cotresSearchForm" id="cotresSearchForm" class="form-validate">
        <div>
            <span class="w60"><?php echo JText::_('Príchod:'); ?></span>
            <input type="text" name="date_from" id="date_from" class="text required validate-date" value="<?php echo $date_from; ?>" onfocus="return showCalendar('date_from', 'dd.mm.y');" />
            <img class="cal_button" src="<?php echo $cal_img; ?>" style="margin:0;" width="16" height="15" onclick="return showCalendar('date_from', 'dd.mm.y');" />
        </div>

        <div>
            <span class="w60"><?php echo JText::_('Odchod:'); ?></span>
            <input type="text" name="date_to" id="date_to" class="text required validate-date" value="<?php echo $date_to; ?>" onfocus="return showCalendar('date_to', 'dd-mm-y');" />
            <img class="cal_button" src="<?php echo $cal_img; ?>" style="margin:0;" width="16" height="15" onclick="return showCalendar('date_to', 'dd.mm.y');" />
        </div>

        <?php echo JText::_('Počet zrubov'); ?>
        &nbsp;
        <?
         echo JHTML::_('select.integerlist', 1, $count, 1, 'count', '', $sel_count);
        ?>
        &nbsp;
        <input type="submit" value="<?php echo JText::_("Vyhľadaj"); ?>" class="button" onclick="javascript: return myModCotresValidate(cotresSearchForm);" />
    	<input type="hidden" name="option" value="com_cotres" />
        <input type="hidden" name="Itemid" value="<?php echo $itemid; ?>" />
    	<input type="hidden" name="task"   value="search" />
    	<input type="hidden" name="controller" value="orderfe" />
    </form>
    <br />
    <img class="cal_payments" src="<?php echo $pay_img; ?>" alt="" title="" />
    <br /><br />
    <a href="<?php echo $cal_link; ?>"><?php echo JText::_('Kalendár'); ?></a>
    &nbsp;|&nbsp;
    <a href="<?php echo $pol_link; ?>"><?php echo JText::_('Obchodné podmienky'); ?></a>

</div>
