<?php defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php" method="post" name="adminForm">
<table>
	<tr>
		<td width="50%">
			<?php echo JText::_( 'Filter' ); ?>:
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->list['search']; ?>" class="text_area" onchange="document.adminForm.submit();" />
			<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
			<button onclick="document.getElementById('filter_search').value=''; this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
		</td>
		
		<td width="40%" nowrap="nowrap">
            <?php echo JText::_( 'Status filter' ); ?>:
        	<button onclick="e=document.getElementById('filter_removed'); if (e.value==0) e.value=1; else e.value=0; this.form.submit();">

                <?php echo JText::_( $this->list['removed'] ? 'Show proper orders' : 'Show removed/archived orders' ); ?>
            </button>
        </td>

		<td nowrap="nowrap">
            <table><tr>
                <td><?php echo JText::_( 'Filter by created date' ); ?></td>
                <td>
                    <?php
                        echo JHTML::tooltip(JText::_("Format YYYY-MM-DD. Click on calendar icon to choose date interactively."), JText::_('Created date from'), '', JText::_('from'));
                    ?>
                    <br />
                    <?php
                        echo JHTML::tooltip(JText::_("Format YYYY-MM-DD. Click on calendar icon to choose date interactively."), JText::_('Created date '), '', JText::_('till'));
                    ?>
                </td>
                <td>
                <input type="text" name="filter_created" id="filter_created" value="<?php echo $this->list['created']; ?>" class="text_area" onchange="document.adminForm.submit();" />
                <img class="calendar" src="templates/system/images/calendar.png" alt="calendar" onclick="return showCalendar('filter_created', '%d-%m-%Y');" />
                <br />

                 <input type="text" name="filter_created_till" id="filter_created_till" value="<?php echo $this->list['created_till']; ?>" class="text_area" onchange="document.adminForm.submit();" />
                <img class="calendar" src="templates/system/images/calendar.png" alt="calendar" onclick="return showCalendar('filter_created_till', '%d-%m-%Y');" />
                </td>
                <td>
        			<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
        			<button onclick="document.getElementById('filter_created').value=''; document.getElementById('filter_created_till').value=''; this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
                </td>
            </table>
			<?php
                //echo JHTML::_('select.genericlist', $this->articles, "filter_by_", "", "value", "text", $this->config->policy_article_id);
			?>
		</td>
	</tr>
</table>
<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="5" class="title">
                <?php echo JHTML::_('grid.sort',   'id', 'id', $this->list['order_Dir'], $this->list['order'] ); ?>
			</th>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
			</th>
			<th width="5" class="title">
                <?php echo JHTML::_('grid.sort',   JText::_('Order Number'), 'id', $this->list['order_Dir'], $this->list['order'] ); ?>
			</th>
			<th class="title">
                <?php echo JHTML::_('grid.sort',   JText::_('Created'), 'created', $this->list['order_Dir'], $this->list['order'] ); ?>
			</th>
			<th class="title">
                <?php echo JHTML::_('grid.sort',   JText::_('User type'), 'user_type', $this->list['order_Dir'], $this->list['order'] ); ?>
			</th>
			<th class="title">
                <?php echo JHTML::_('grid.sort',   JText::_('Name'), 'lname', $this->list['order_Dir'], $this->list['order'] ); ?>
			</th>
			<th class="title">
                <?php echo JHTML::_('grid.sort',   JText::_('Company'), 'company_name', $this->list['order_Dir'], $this->list['order'] ); ?>
			</th>
			<th class="title">
                <?php echo JHTML::_('grid.sort',   JText::_('Status'), 'status', $this->list['order_Dir'], $this->list['order'] ); ?>
			</th>
			<th class="title">
                <?php echo JHTML::_('grid.sort',   JText::_('Payment type'), 'payment_type', $this->list['order_Dir'], $this->list['order'] ); ?>
			</th>
			<th class="title">
                <?php echo JHTML::_('grid.sort',   JText::_('Cottages'), 'cottages', $this->list['order_Dir'], $this->list['order'] ); ?>
			</th>
			<th class="title">
                <?php echo JHTML::_('grid.sort',   JText::_('Date from'), 'date_from', $this->list['order_Dir'], $this->list['order'] ); ?>
			</th>
			<th class="title">
                <?php echo JHTML::_('grid.sort',   JText::_('Date to'), 'date_to', $this->list['order_Dir'], $this->list['order'] ); ?>
			</th>
			<th class="title">
                <?php echo JHTML::_('grid.sort',   JText::_('Total price'), 'price_total', $this->list['order_Dir'], $this->list['order'] ); ?>
			</th>

		</tr>
	</thead>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)	{
		$row       = &$this->items[$i];
		$checked   = JHTML::_( 'grid.id',   $i, $row->id );
		$published = JHTML::_( 'grid.state', $row->published, 'Published', 'Unpublished');
		$link 	   = JRoute::_( 'index.php?option=com_cotres&controller=order&task=edit&cid[]='. $row->id );

		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $row->id; ?>
			</td>

			<td>
				<?php echo $checked; ?>
			</td>
			<td>
				<a href="<?php echo $link; ?>"><?php echo $row->order_number; ?></a>
			</td>
			<td style="width: 80px;">
				<?php
                    echo JHTML::_('date',  $row->created, JText::_("DATE_FORMAT_LC2") );
                ?>
			</td>
			<td style="width: 80px;">
				<?php echo CotResHelper::getUserType($row->user_type); ?>
			</td>
			<td>
				<?php echo $row->lname.($row->lname ? ", " : "").$row->fname; ?>
			</td>
			<td>
				<?php echo $row->company_name; ?>
			</td>
			<td>
				<?php
                    echo CotResHelper::translateStatus($row->status);
                ?>
                <?php
                    if ($row->status == 0)
                    { ?>
                        <img src="components/com_cotres/images/hours_left.png" width="21" height="20" title="" alt="" border="1" />
                <?php
                        # Subtract and divide by 3600 seconds/hour
                        echo $this->modelConfig->getHoursLeft($row->created) ." ". JText::_('hrs');
                    }
                 ?>
			</td>
			<td>
				<?php echo JText::_($row->payment_type); ?>
			</td>
			<td>
				<?php echo JText::_($row->cottages); ?>
			</td>
			<td style="width: 80px;" >
				<?php echo JHTML::_('date',  $row->date_from, JText::_('DATE_FORMAT_LC3') ); ?>
			</td>
			<td style="width: 80px;">
				<?php echo JHTML::_('date',  $row->date_to, JText::_('DATE_FORMAT_LC3') ); ?>
			</td>
			<td style="width: 80px;">
				<?php echo $row->price_total." ".JText::_('EUR'); ?>
			</td>


		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
      <tfoot>
        <tr>
          <td colspan="13"><?php echo $this->pagination->getListFooter(); ?></td>
        </tr>
      </tfoot>

	</table>
</div>

<input type="hidden" name="option" value="com_cotres" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" id="boxchecked" value="0" />
<input type="hidden" name="controller" id="controller" value="order" />
<input type="hidden" name="filter_order" id="filter_order" value="<?php echo $this->list['order']; ?>" />
<input type="hidden" name="filter_order_Dir" id="filter_order_Dir" value="<?php echo $this->list['order_Dir']; ?>" />
<input type="hidden" name="filter_removed" id="filter_removed" value="<?php echo $this->list['removed']; ?>" />
</form>
