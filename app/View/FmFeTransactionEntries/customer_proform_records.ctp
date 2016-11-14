<?php echo $this->Html->css('Script/FmFeTransactionEntries/records'); ?>
<ul class="breadcrumbs">
	<li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
	<li><?php echo $this->Html->link(__('Finansijsko knjigovodstvo'), array('controller' => 'ErpModules', 'action' => 'start', 'financial')); ?></li>
	<li><?php echo $this->Html->link(__('Devizni izvodi banke'), array('controller' => 'FmFeBasics', 'action' => 'index')); ?></li>
	<li><?php echo $this->Html->link(__('Pregled izvoda'), array('controller' => 'FmFeBasics', 'action' => 'view', $fe_transaction['FmFeTransaction']['fm_fe_basic_id'])); ?></li>
	<li class="last"><a href="" onclick="return false"><?php echo __('PREGLED IZDATIIH DEVIZNIH PROFAKTURA'); ?></a></li>
</ul>
<div style="width:1500px; margin-left:44px;">
	<div class="name_add_search">
		<div class="name_of_page">
			<h5><i class="icon-book"></i> <?php echo __('PREGLED IZDATIIH DEVIZNIH PROFAKTURA'); ?></h5>
		</div>
	</div>
	<?php echo $this->element('../FmFeBasics/mini_basic'); ?>
	<table>
		<thead>
			<tr>
				<th class="center" colspan="<?php echo count($exchange_rates) * 2;?>">
					<?php echo __("Devizni kurs na dan izvoda"); ?> <?php echo date('d.m.Y', strtotime($fe_transaction['FmFeBasic']['fe_date'])); ?>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
			<?php foreach ($exchange_rates as $currency_iso => $intermediate_rate): ?>
				<td class="center bold"><?php echo $currency_iso; ?></td>
				<td class="right"><?php echo number_format($intermediate_rate, 4, '.', ','); ?></td>				
			<?php endforeach; ?>				
			</tr>		
		</tbody>
	</table>
	<table class="records">
		<thead>
			<tr>
				<th rowspan="3" class="center">&nbsp;</th>				
				<th rowspan="3" class="center"><?php echo __("Br.PF"); ?></th>				
				<th rowspan="3" class="center"><?php echo __("Šifra statusa"); ?></th>
				<th rowspan="2" colspan="2" class="center"><?php echo __("Ukupan iznos izdate PF"); ?></th>
				<th colspan="5" class="center"><?php echo __("Naplaćeno"); ?></th>
				<th colspan="5" class="center"><?php echo __("Nenaplaćeno"); ?></th>
			</tr>
			<tr>
				<th class="center" colspan="2"><?php echo __("u valuti naplate"); ?></th>
				<th class="center" colspan="3"><?php echo __("konverzija u valutu po PF"); ?></th>
				<th class="center" colspan="2"><?php echo __("u valuti po PF"); ?></th>
				<th class="center" colspan="3"><?php echo __("konverzija u valutu naplate"); ?></th>				
			</tr>
			<tr>
				<th class="center"><?php echo __("devizni iznos"); ?></th>
				<th class="center"><?php echo __("valuta"); ?></th>				
				<th class="center"><?php echo __("devizni iznos"); ?></th>
				<th class="center"><?php echo __("valuta"); ?></th>
				<th class="center"><?php echo __("koef.konv."); ?></th>				
				<th class="center"><?php echo __("devizni iznos"); ?></th>
				<th class="center"><?php echo __("valuta"); ?></th>
				<th class="center"><?php echo __("devizni iznos"); ?></th>
				<th class="center"><?php echo __("valuta"); ?></th>				
				<th class="center"><?php echo __("koef.konv."); ?></th>				
				<th class="center"><?php echo __("devizni iznos"); ?></th>
				<th class="center"><?php echo __("valuta"); ?></th>								
			</tr>
			<tr class="column_number">
				<th>&nbsp;</th>
				<th>1</th>
				<th>2</th>
				<th>3</th>
				<th>4</th>
				<th>5</th>
				<th>6</th>
				<th>7</th>
				<th>8</th>
				<th>9</th>
				<th>10</th>
				<th>11</th>
				<th>12</th>
				<th>13</th>
				<th>14</th>
			</tr>
		</thead>
		<tbody>
			<?php if(empty($customer_proform_records)){ ?>
				<tr>
					<td colspan="15">
				        <i class="icon-warning-sign icon-large"></i>
				        <?php echo __("Za ovog komitenta trenutno nema izdatih deviznih profaktura!"); ?>
					</td>
				</tr>
			<?php }else{ ?>
				<?php echo $this->Form->create('FmFeTransactionEntry'); ?>
				
				<?php $total_currencies = array(); ?>
				<?php $total_proform = array(); ?>
				<?php $total_unpaid = array(); ?>
				<?php $total = 0; ?>

				<?php foreach ($customer_proform_records as $record): ?>
				<?php $rowspan = 1; ?>
				<?php if(count($record['OrderPayment']) >= 2){ ?>
					<?php $rowspan = count($record['OrderPayment']); ?>
				<?php } ?>
				<?php $payment_sum = 0; ?>
				<?php foreach ($record['OrderPayment'] as $payment): ?>
					<?php if($payment['currency_id'] == $record['Order']['currency_id']){ ?>
						<?php $payment_sum += $payment['approved']; ?>
					<?php }else{ ?>
						<?php 
							//Recalculate approved
							$conversion_coeff = round($payment['payment_currency_rate'] / $payment['order_currency_rate'], 4);
							$payment_sum += $payment['approved'] * $conversion_coeff;
						?>
					<?php } ?>
				<?php endforeach; ?>
				<tr class="nowrap">		
					<td class="center" rowspan="<?php echo $rowspan; ?>">
						<?php echo $this->Form->checkbox('select_order_'.$record['Order']['id'], array('label' => false, 'div' => false)); ?>
						<?php echo $this->Form->input('id_'.$record['Order']['id'], array('type' => 'hidden', 'label' => false, 'div' => false)); ?>
					</td>
					<td class="center" rowspan="<?php echo $rowspan; ?>">
						<?php echo $this->Html->link($record['Order']['order_number'], array('controller' => 'Orders', 'action' => 'view', $record['Order']['id'])); ?>							
					</td>
					<td class="center" rowspan="<?php echo $rowspan; ?>">
						<?php echo $record['FmTrafficStatus']['code']; ?>							
					</td>
					<td class="right" rowspan="<?php echo $rowspan; ?>">
						<?php echo number_format($record['Order']['total'], 2, '.', ','); ?>
					</td>
					<?php if(empty($total_proform[$record['Currency']['iso']])){ ?>
						<?php $total_proform[$record['Currency']['iso']] = $record['Order']['total']; ?>
						<?php $total_currencies[] = $record['Currency']['iso']; ?>
					<?php }else{ ?>
						<?php $total_proform[$record['Currency']['iso']] += $record['Order']['total']; ?>
					<?php } ?>
					<td class="center" rowspan="<?php echo $rowspan; ?>">
						<?php echo $record['Currency']['iso']; ?>
					</td>
					<?php if(!empty($record['OrderPayment'])){ ?>
						<?php $payment = $record['OrderPayment'][0]; ?>
						<td class="right"><?php echo number_format($payment['approved'], 2, '.', ','); ?></td>						
						<?php if($payment['currency_id'] == $record['Order']['currency_id']){ ?>
							<td class="center"><?php echo $record['Currency']['iso']; ?></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						<?php }else{ ?>
							<td class="center"><?php echo $payment['currency_iso']; ?></td>
							<?php $conversion_coeff = round($payment['payment_currency_rate'] / $payment['order_currency_rate'], 4); ?>
							<td class="center"><?php echo number_format($conversion_coeff, 4, '.', ','); ?></td>
							<td class="right"><?php echo number_format($conversion_coeff * $payment['approved'], 2, '.', ','); ?></td>
							<td class="center"><?php echo $record['Currency']['iso']; ?></td>
						<?php } ?>
					<?php }else{ ?>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					<?php } ?>
					<?php $payment_diff = $record['Order']['total'] - $payment_sum; ?>
					<td rowspan="<?php echo $rowspan; ?>" class="right">
						<?php echo $this->Form->input('payment_diff_'.$record['Order']['id'], array('label' => false, 'div' => false, 'value' => $payment_diff, 'class' => 'right number_input', 'readonly' => true)); ?>
					</td>
					<td rowspan="<?php echo $rowspan; ?>" class="center"><?php echo $record['Currency']['iso']; ?></td>					
					<?php if(empty($total_unpaid[$record['Currency']['iso']])){ ?>
						<?php $total_unpaid[$record['Currency']['iso']] = $payment_diff; ?>
					<?php }else{ ?>
						<?php $total_unpaid[$record['Currency']['iso']] += $payment_diff; ?>
					<?php } ?>
					<?php if($business_account['FmBusinessAccount']['currency_id'] == $record['Order']['currency_id']){ ?>
						<td rowspan="<?php echo $rowspan; ?>">&nbsp;</td>
						<td rowspan="<?php echo $rowspan; ?>" class="right">
							<?php echo $this->Form->input('converted_diff_'.$record['Order']['id'], array('label' => false, 'div' => false, 'value' => $payment_diff, 'class' => 'right number_input', 'readonly' => true)); ?>	
						</td>
						<td rowspan="<?php echo $rowspan; ?>" class="center"><?php echo $record['Currency']['iso']; ?></td>
						<?php $total += $payment_diff; ?>
					<?php }else{ ?>						
						<td rowspan="<?php echo $rowspan; ?>" class="center">
							<?php $exchange_conversion_coeff = round($exchange_rates[$record['Currency']['iso']] / $exchange_rates[$business_account['Currency']['iso']], 4); ?>
							<?php echo number_format($exchange_conversion_coeff, 4, '.', ','); ?>
						</td>
						<?php 
							$payment_diff_converted = round($payment_diff * $exchange_conversion_coeff, 2);
							$total += $payment_diff_converted; 
						?>
						<td rowspan="<?php echo $rowspan; ?>" class="right">
							<?php echo $this->Form->input('converted_diff_'.$record['Order']['id'], array('label' => false, 'div' => false, 'value' => $payment_diff_converted, 'class' => 'right number_input', 'readonly' => true)); ?>		
						</td>
						<td rowspan="<?php echo $rowspan; ?>" class="center"><?php echo $business_account['Currency']['iso']; ?></td>
					<?php } ?>
				</tr>
				<?php if(count($record['OrderPayment']) >= 2){ ?>
					<?php for ($payment_count = 1; $payment_count < count($record['OrderPayment']); $payment_count++) { ?>
						<?php $payment = $record['OrderPayment'][$payment_count]; ?>
						<tr>
							<td class="right"><?php echo number_format($payment['approved'], 2, '.', ','); ?></td>
							<?php if($payment['currency_id'] == $record['Order']['currency_id']){ ?>
								<td class="center"><?php echo $record['Currency']['iso']; ?></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							<?php }else{ ?>
								<td class="center"><?php echo $payment['currency_iso']; ?></td>
								<?php $conversion_coeff = round($payment['payment_currency_rate'] / $payment['order_currency_rate'], 4); ?>
								<td class="center"><?php echo number_format($conversion_coeff, 4, '.', ','); ?></td>
								<td class="right"><?php echo number_format($conversion_coeff * $payment['approved'], 2, '.', ','); ?></td>
								<td class="center"><?php echo $record['Currency']['iso']; ?></td>
							<?php } ?>
						</tr>
					<?php } ?>
				<?php } ?>				
				<?php endforeach; ?>
				<?php $total_count = 1; ?>
				<?php foreach ($total_currencies as $currency_iso): ?>				
				<tr class="marker">
					<?php if($total_count == 1){ ?>
						<td colspan="3" class="right bold"><?php echo __("Ukupno"); ?></td>
					<?php }else{ ?>
						<td colspan="3">&nbsp;</td>
					<?php } ?>
					<td class="right"><?php echo number_format($total_proform[$currency_iso], 2, '.', ','); ?></td>
					<td class="center"><?php echo $currency_iso; ?></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td class="right"><?php echo number_format($total_unpaid[$currency_iso], 2, '.', ','); ?></td>
					<td class="center"><?php echo $currency_iso; ?></td>
					<td>&nbsp;</td>
					<?php if($total_count == 1){ ?>
						<td colspan="2" class="bold center"><?php echo __("Ukupno nenaplaćeno"); ?></td>
					<?php }else{ ?>
						<?php if($total_count == 2){ ?>
							<td class="right"><?php echo number_format($total, 2, '.', ','); ?></td>
							<td class="center"><?php echo $business_account['Currency']['iso']; ?></td>												
						<?php }else{ ?>
							<td>&nbsp;</td>
							<td>&nbsp;</td>						
						<?php } ?>
					<?php } ?>
				</tr>
				<?php $total_count++; ?>
				<?php endforeach; ?>
				<tr>
					<td colspan="15" class="right">
						<?php
							echo $this->Form->submit(__('Učitaj obrazac'), array(
								'div' => false,
								'class' => "button green"
							));
						?>
						<?php echo $this->Form->end(); ?>						
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<script>
/* Init app */
$('#container').ready(function(){
	//Init libraries
	$(".submit_loader").hide();
});
</script>