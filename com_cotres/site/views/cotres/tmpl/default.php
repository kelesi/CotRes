<?php // no direct access
    defined('_JEXEC') or die('Restricted access');
?>

<div class="cotres_box" id="cotres_intro">
    <h1><?php echo JText::_('Online rezervácia'); ?></h1>

    <?php CotResHelper::showTimeline(); ?>
    <?php
        echo JText::_("Preverte si online Váš požadovaný termín ubytovania v ");
        $link = JRoute::_("index.php?option=com_cotres&type=calendar");
    ?>
        <a href="<?php echo $link; ?>"><?php echo JText::_("kalendári obsadenosti"); ?></a>.
    <?php echo JText::_("Do formulára vľavo dolu zadajte požadovaný termín ubytovania. Kliknite Vyhľadaj."); ?>

    <ul>
    <li>V kroku rezervácia vyberte požadovaný volny zrub pre vami zadaný termín</li>
    <li>V kroku formulár vyplnte vaše fakturačné udaje a vyberte si sposob platby, alebo bankovy prevod.</li>
    <li>Povrdte vami zadané údaje.</li>
    <li>Realizujte platbu cez PayPal, CardPay</li>
    <li>Ukončenie rezervácie</li>
    </ul>
    <span class="red">* Krok len pri zvolenej platbe PayPal, CardPay</span>
</div>

<div class="cotres_box" id="cr_phone_res">
    <h1><?php echo JText::_('Telefonická rezervácia'); ?></h1>
    <?php
        echo JText::_("Preverte si online Váš požadovaný termín ubytovania v ");
        $link = JRoute::_("index.php?option=com_cotres&type=calendar");
    ?>
        <a href="<?php echo $link; ?>"><?php echo JText::_("kalendári obsadenosti"); ?></a>.
    <?php echo JText::_("Kontaktujte nás telefonicky, zarezervujte si ubytovanie v požadovanom termíne."); ?>

</div>
