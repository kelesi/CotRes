<?php //defined('_JEXEC') or die('Restricted access'); ?>

<?php
require_once (JPATH_COMPONENT_SITE.DS.'payments'.DS.'cardpay.class.php');
//CARDPAY

// Vytvorime novy objekt triedy TatraPay
// Prvy parameter je MID -- merchant ID, druhy parameter je osemznakove
// heslo od tatrabanky
if (0) //$config->testing)
{
    $tatra = new CardPay ($config->cardpay_mid, $config->cardpay_key, "", $config->cardpay_testurl);
}
else
{
    $tatra = new CardPay ($config->cardpay_mid, $config->cardpay_key);
}

//Set currency to EUR
$tatra->set_curr("978");
// Nastavime konstantny symbol
$tatra->set_cs ($config->cardpay_cs);
// Variabilny symbol
$tatra->set_vs ($data->id);
// Hodnota na zaplatenie
$tatra->set_amt(sprintf("%.02d", $data->reservation_fee));
// Reply stranka
$tatra->set_rurl (JURI::base()."/components/com_cotres/payments/cardpay.return.php");
// Popis transakcie (nesmie obsahovat medzery), nepovinne
$tatra->set_desc ('Platba za rezeváciu zrubov na. Objednávka č.'.$data->id);
// Reply SMS
$tatra->set_rsms ($config->cardpay_rsms);
// Reply e-mail
$tatra->set_rem ($config->cardpay_rem);
//IPC
$tatra->set_ipc ($config->cardpay_ipc);
//NAME
$tatra->set_name ($config->cardpay_name);
//LANG
$lang =& JFactory::getLanguage();
$lg = strtolower(substr($lang->_lang, 0, 2));
if ($lg != "sk" || $lg != "en" || $lg != "de" || $lg != "hu") $lg = "en";
$tatra->set_lang ($lg);

$payment_link = $tatra->pay_link("tatra");
  
//    $linka = $tatra->pay_link("tatra"); // priamo na tatrabanku
//    mosRedirect($linka);
  // Funkcia zaroven vypocita aj podpis!
  // Moze mat parametre
  // eliot -> pouzije sa eliotpay
  // tatra -> pouzije sa tatrapay
  // hocico ine, pripadne nic -> uzivatel si moze vybrat na serveri tatrabanky
//  $formular = $tatra->image_pay_form("tatra"); // priamo na eliot
//  echo $formular;
