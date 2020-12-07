<?php defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Cottage' ); ?></legend>

		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'Name' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="name" id="name" size="32" maxlength="250" value="<?php echo $this->cottage->name;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'Description' ); ?>:
				</label>
			</td>
			<td>
            <?php
			    $editor = &JFactory::getEditor();
                echo $editor->display('desc', $this->cottage->desc, '550', '400', '60', '20');
            ?>
			</td>
		</tr>

	</table>

	</fieldset>
</div>
<div class="col">
<?php
    foreach ($this->prices as $year => $prices)
    {
?>
	<fieldset class="adminform">
        <legend><?php echo JText::_( 'Prices for year' )." ".($year == 1 ? JText::_("Default") : $year); ?></legend>
		<table class="admintable">
<?php

        foreach ($prices as $pid => $price)
        {
        //print_r($price);
?>
		<tr>
			<td align="right" class="key">
				<label for="name">
					<?php echo JText::_('Season ').$price->name." <br /> ($price->date_from - $price->date_to)"; ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="prices[<?php echo $price->id_season; ?>]" id="price<?php echo $pid; ?>" size="32" maxlength="250" value="<?php echo $price->price;?>" />
			</td>
		</tr>
<?php
        }
?>

	</table>
    </fieldset>
<?php
    }
?>

</div>

<div class="clr"></div>

<input type="hidden" name="option" value="com_cotres" />
<input type="hidden" name="id" value="<?php echo $this->cottage->id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="cottage" />
</form>
