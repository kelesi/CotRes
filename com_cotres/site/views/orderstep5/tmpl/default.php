<?php // no direct access
    defined('_JEXEC') or die('Restricted access');
?>
<?php
CotResHelper::showTimeline(5);
?>
<div class="separator"></div>

<form action="index.php" method="post" name="adminForm" id="adminForm">
    <div class="cotres_box" id="step2top" style="text-align: center;">
    <b><?php echo JText::_('Ďakujeme za Vašu objednávku'); ?></b>
    </div>
    <br />
    <div class="cotres_box" id="step2middle"><div id="step5middle">
        <?php echo JText::_('Na Váš email bola odoslaná správa so všetkými údajmi. Tú prosím dobre uschovajte a zároveň slúži ako ubytovací preukaz'); ?>
    </div></div>

    <div class="cleaner"></div>
    <div class="separator"></div>

    <input type="hidden" name="option" value="com_cotres" />
    <input type="hidden" name="boxchecked" value="0" />

    <div class="submitbuttons">
        <button type="submit" name="task2" value="" class="button right"    onclick="javascript: return myValidateStep3(adminForm, 'step3next');"><?php echo JText::_("Dokončiť"); ?>  </button>
    </div>
</form>
