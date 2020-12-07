<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" name="adminForm">
<div id="editcell">
    <table>
    	<tr>
    		<td width="50%">
    			<?php echo JText::_( 'Filter' ); ?>:

                <?php echo JHTML::_('select.genericlist', $this->years, "filter_year", 'onchange="document.adminForm.submit();"', "value", "text", $this->list['year']); ?>

    			<!--button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
    			<button onclick="document.getElementById('filter_year').value=''; document.adminForm.submit();"><?php echo JText::_( 'Reset' ); ?></button-->
    		</td>
        </tr>
    </table>

	<table class="adminlist">
	<thead>
		<tr>
			<th width="5">
				<?php echo JText::_( 'ID' ); ?>
			</th>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
			</th>			
			<th>
				<?php echo JText::_( 'Name' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Date from'); ?>
			</th>
			<th>
				<?php echo JText::_( 'Date to' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Year' ); ?>
			</th>
			<th>
                <?php echo JText::_( 'Minimum nights' ); ?>
			</th>
			<th>
                <?php echo JText::_( 'Number of nights with additional fee' ); ?>
			</th>
			<th>
                <?php echo JText::_( 'Additional fee percentage' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Published' ); ?>
			</th>
		</tr>
	</thead>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)	{
		$row       = &$this->items[$i];
		$checked   = JHTML::_( 'grid.id',   $i, $row->id );
		$published = JHTML::_('grid.state', $row->published, 'Published', 'Unpublished');
		$link 	   = JRoute::_( 'index.php?option=com_cotres&controller=season&task=edit&cid[]='. $row->id );
		$link_publish = JRoute::_( 'index.php?option=com_cotres&controller=season&task='.($row->published ? "unpublished" : "published" ).'&cid[]='. $row->id );
		$img       = ($row->published) ? "publish_g.png" : "publish_x.png";
		$alt       = ($row->published) ? JText::_("Published") : JText::_("Unpublished");

        
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $row->id; ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td>
				<a href="<?php echo $link; ?>"><?php echo $row->name; ?></a>
			</td>
			<td style="width: 80px;" >
				<?php echo $row->date_from; ?>
			</td>
			<td style="width: 80px;">
				<?php echo $row->date_to; ?>
			</td>
			<td style="width: 1%">
				<?php echo $row->year != 1 ? $row->year : JText::_("Default"); ?>
			</td>
			<td style="width: 1%">
				<?php echo $row->reserv_min_nights; ?>
			</td>
			<td style="width: 1%">
				<?php echo $row->add_fee_nights; ?>
			</td>
			<td style="width: 1%">
				<?php echo $row->add_fee_perc; ?>
			</td>

			<td style="width: 1%; text-align: center;">
		        <span class="editlinktip hasTip" title="<?php echo JText::_( 'Click to '.$row->publish ? 'unpublish' : 'publish' ); ?>">
                <a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $row->published ? 'unpublish' : 'publish' ?>')">
				    <img src="images/<?php echo $img;?>" width="16" height="16" border="0" alt="<?php echo $alt; ?>" />
                </a></span>
			</td>

		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	</table>
</div>

<input type="hidden" name="option" value="com_cotres" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="season" />

</form>
