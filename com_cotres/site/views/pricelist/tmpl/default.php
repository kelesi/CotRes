<?php // no direct access
    defined('_JEXEC') or die('Restricted access');
    //CotResHelper::p_r($this->items);
?>

    <table class="step1list calendarlist">
    <tr>
        <th><?php echo JText::_('Zrub'); ?></th>
    <?php
        foreach (current($this->items) as $s)
        {
    ?>
        <th><?php echo $s->season_name." ".JText::_('od')." ".$s->date_from." ".JText::_('do')." ".$s->date_to; ?></th>
    <?php
        }
    ?>
    </tr>
<?php
    foreach ($this->items as &$cot)
    {
        $first = current($cot);

    ?>
        <tr>
        <td class="col1" width="<?php echo floor(100/(count($cot)+1)); ?>%"><?php echo $first->cottage_name; ?></td>
    <?php
        $i = 1;
        foreach ($cot as $p)
        {
            $i++;
            if ($i > 4) $i=2;
    ?>
        <td class="col<?php echo $i; ?>" width="<?php echo floor(100/(count($cot)+1)); ?>%">
            <div style="width:<?php echo floor(681/(count($cot)+1)); ?>px">
            <?php
                echo sprintf("%.02f", $p->price)." ".JText::_('EUR');
                if ($this->config->conversion)
                {
                    echo " (".sprintf("%.00f", $p->price*$this->config->conversion)." ".JText::_('Sk').")";
                }
                echo " / ".JText::_("Noc");
            ?>
            </div>
        </td>
<?php
        }
    }
?>

<?php
    $arr = array();
    $arr["reserv_min_nights"]   = JText::_('Min. nocí')." ".JHTML::tooltip(JText::_("Minimum nights alowed for reservation."), JText::_('Minimum nights'));
    $arr["add_fee_nights"]      = JText::_( 'Nocí s prípl.').JHTML::tooltip(JText::_("When the order is for less nights that this value an additional fee (set bellow) will be added to final price."), JText::_('Number of nights with additional fee'));
    $arr["add_fee_perc"]        = JText::_( 'Additional fee percentage' );

    foreach ($arr as $fld => $txt)
    {
        $cot = $this->items;
        $cot = current($cot);
        $first = current($cot);

    ?>
        <tr>
        <td class="col1" width="<?php echo floor(100/(count($cot)+1)); ?>%"><?php echo $txt; ?></td>
    <?php
        $i = 1;
        foreach ($cot as $p)
        {
            $i++;
            if ($i > 4) $i=2;
    ?>
        <td class="col<?php echo $i; ?>" width="<?php echo floor(100/(count($cot)+1)); ?>%">
            <div style="width:<?php echo floor(681/(count($cot)+1)); ?>px">
            <?php
                echo $p->$fld;
            ?>
            </div>
        </td>
<?php
        }
    }
?>
    </table>
