<?php echo $before_widget;?>
<?php echo $title; ?>

<?php foreach ( $mini_cart as $cart ) { ?>
		
<h3><strong><?php echo $cart['title'];?></strong></h3>
		
<table border="0" cellspacing="0" cellpadding="0" style="width:100%;">
<?php
	if ( $cart['has_items'] ) {
		foreach ( $cart['items'] as $item ) {
?>
	<tr>
		<td colspan="3"><h5><strong><?php echo $item['name'];?></strong></h5></td>
	</tr>
	<tr>
		<td><?php echo __('Price', 'events');?></td>
		<td><?php echo __('Qty', 'events');?></td>
		<td><?php echo __('Total', 'events');?></td>
	</tr>
		<td><?php echo $currency_symbol . $item['price'];?></td>
		<td><?php echo $item['qty'];?></td>
		<td><?php echo $currency_symbol . $item['line_total'];?></td>
	</tr>
<?php } ?>
	<tr>
		<td colspan="3"><strong><?php echo __('Total', 'events');?></strong></td>
	</tr>
	<tr>
		<td colspan="3">
	<h4><strong><?php echo __('Total', 'events') . ' ' . $cart['title'];?></strong></h4>
		<?php
	printf(  _n( '%s item,  ', '%s items, ', $cart['total_items'], 'events' ), $cart['total_items'] );
	echo $currency_symbol . $cart['sub_total'];
		?></td>
	</tr>

<?php } else { ?>
	<tr>
		<td colspan="3"><?php echo __($cart['empty_msg'], 'events');?></td>
	</tr>
<?php } ?>
</table>
<br />

<?php } ?>

<h3><strong><?php echo __('Grand Total: ', 'events');?></strong></h3>
<h4><?php printf(  _n( '%s item, ', '%s items, ', $total_items, 'events' ), $total_items );
echo $currency_symbol . $grand_total;
?></h4>

<?php echo $after_widget;?>
