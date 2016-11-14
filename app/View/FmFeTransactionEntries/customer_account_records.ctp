<?php echo $this->Html->css('Script/FmFeTransactionEntries/records'); ?>
<ul class="breadcrumbs">
	<li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
	<li><?php echo $this->Html->link(__('Finansijsko knjigovodstvo'), array('controller' => 'ErpModules', 'action' => 'start', 'financial')); ?></li>
	<li><?php echo $this->Html->link(__('Devizni izvodi banke'), array('controller' => 'FmFeBasics', 'action' => 'index')); ?></li>
	<li><?php echo $this->Html->link(__('Pregled izvoda'), array('controller' => 'FmFeBasics', 'action' => 'view', $fe_transaction['FmFeTransaction']['fm_fe_basic_id'])); ?></li>
	<li class="last"><a href="" onclick="return false"><?php echo __('PREGLED OTVORENIH OBAVEZA PREMA INO-KUPCU'); ?></a></li>
</ul>

<div class="name_add_search">
	<div class="name_of_page">
		<h5><i class="icon-book"></i> <?php echo __('Pregled otvorenih obaveza prema ino-kupcu na dan '); ?><?php echo date('d.m.Y'); ?></h5>
	</div>
</div>
<div style="width:1500px; overflow-x: scroll; margin-left: 50px;">
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
				<th rowspan="2" class="center">&nbsp;</th>
				<th rowspan="2" class="center"><?php echo __("Konto"); ?></th>
				<th rowspan="2" class="center"><?php echo __("Šifra valute"); ?></th>
				<th rowspan="2" class="center"><?php echo __("Vrsta dokumenta"); ?></th>
				<th rowspan="2" class="center"><?php echo __("Br. dokumenta"); ?></th>				
				<th rowspan="2" class="center"><?php echo __("Šifra statusa"); ?></th>
				<th rowspan="2" class="center"><?php echo __("Devizni kurs"); ?></th>
				<th colspan="2" class="center"><?php echo __("Knjižena obaveza"); ?></th>
				<th colspan="2" class="center"><?php echo __("Rasknjiženje obaveze"); ?></th>
				<th colspan="3" class="center"><?php echo __("Ostatak obaveze"); ?></th>
				<th colspan="3" class="center"><?php echo __("Konverzija obaveze u valutu po izvodu"); ?></th>
			</tr>
			<tr>
				<th class="center"><?php echo __("devizni iznos"); ?></th>
				<th class="center"><?php echo __("RSD iznos"); ?></th>
				<th class="center"><?php echo __("devizni iznos"); ?></th>
				<th class="center"><?php echo __("RSD iznos"); ?></th>
				<th class="center"><?php echo __("devizni iznos"); ?></th>
				<th class="center"><?php echo __("RSD iznos"); ?></th>
				<th class="center"><?php echo __("devizni kurs"); ?></th>
				<th class="center"><?php echo __("koef.konv."); ?></th>
				<th class="center"><?php echo __("devizni iznos"); ?></th>
				<th class="center"><?php echo __("šifra valute"); ?></th>
			</tr>
			<tr class="column_number">
				<th>&nbsp;</th>
				<th>1</th>
				<th>2</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<?php if(empty($customer_account_records)){ ?>
				<tr>
					<td colspan="11">
				        <i class="icon-warning-sign icon-large"></i>
				        <?php echo __("Za ovog komitenta trenutno nema otvorenih stavki!"); ?>
					</td>
				</tr>
			<?php }else{ ?>
				<?php echo $this->Form->create('FmFeTransactionEntry'); ?>
				<?php 					
					$sum_domestic_credit = 0;
					$sum_domestic_debit = 0;
					$sum_domestic_diff = 0;
					$sum_conversion_diff = 0;

					$diff_foreign_sums = array();					
				?>
				<?php foreach ($customer_account_records as $record): ?>
				<tr class="nowrap">
					<td class="center">						
						<?php echo $this->Form->checkbox('select_record_'.$record['FmAccountOrderRecord']['id'], array('label' => false, 'div' => false)); ?>
						<?php echo $this->Form->input('id_'.$record['FmAccountOrderRecord']['id'], array('type' => 'hidden', 'label' => false, 'div' => false)); ?>
					</td>
					<td class="center"><?php echo $record['FmChartAccount']['code']; ?></td>
					<td class="center"><?php echo $record['Currency']['iso']; ?></td>
					<td class="center"><?php echo $record['PrimaryDocumentType']['code']; ?> - <?php echo $record['PrimaryDocumentType']['name']; ?></td>
					<td class="center"><?php echo $record['FmAccountOrderRecord']['primary_document_code']; ?></td>					
					<td class="center"><?php echo $record['FmTrafficStatus']['code']; ?></td>
					<td class="center"><?php echo number_format($record['FmAccountOrderRecord']['domestic_exchange_rate'], 4, '.', ','); ?></td>
					<td class="right">
						<?php echo number_format($record['FmAccountOrderRecord']['total_foreign_credit'], 2, '.', ','); ?>						
					</td>
					<td class="right">
						<?php $sum_domestic_credit += round($record['FmAccountOrderRecord']['total_domestic_credit'], 3); ?>
						<?php echo number_format($record['FmAccountOrderRecord']['total_domestic_credit'], 3, '.', ','); ?>						
					</td>
					<td class="right">
						<?php echo number_format($record['FmAccountOrderRecord']['total_foreign_debit'], 2, '.', ','); ?>						
					</td>
					<td class="right">
						<?php $sum_domestic_debit += round($record['FmAccountOrderRecord']['total_domestic_debit'], 3); ?>
						<?php echo number_format($record['FmAccountOrderRecord']['total_domestic_debit'], 3, '.', ','); ?>						
					</td>
					<td class="right">
						<?php 
							$foreign_diff = $record['FmAccountOrderRecord']['total_foreign_credit'] - $record['FmAccountOrderRecord']['total_foreign_debit']; 
							if(empty($diff_foreign_sums[$record['Currency']['iso']])){
								$diff_foreign_sums[$record['Currency']['iso']] = $foreign_diff;
							}else{
								$diff_foreign_sums[$record['Currency']['iso']] += $foreign_diff;
							}
						?>
						<?php if(!empty($foreign_diff)){ ?>
							<?php echo number_format($foreign_diff, 2, '.', ','); ?>
						<?php }else{ ?>
							&nbsp;
						<?php } ?>
					</td>
					<td class="right">
						<?php 
							$domestic_diff = $record['FmAccountOrderRecord']['total_domestic_credit'] - $record['FmAccountOrderRecord']['total_domestic_debit']; 
							$sum_domestic_diff += $domestic_diff;
						?>
						<?php if(!empty($domestic_diff)){ ?>
							<?php echo number_format($domestic_diff, 3, '.', ','); ?>
						<?php }else{ ?>
							&nbsp;
						<?php } ?>
					</td>
					<td class="right">
						<?php 
							$diff_exchange_rate = 0;
							if(!empty($foreign_diff)){
								$diff_exchange_rate = $domestic_diff / $foreign_diff;
							}
						?>
						<?php if(!empty($diff_exchange_rate)){ ?>
							<?php echo number_format($diff_exchange_rate, 4, '.', ','); ?>
						<?php }else{ ?>
							&nbsp;
						<?php } ?>
					</td>
					<td class="center">
						<?php $coeff_conv = round($exchange_rates[$record['Currency']['iso']] / $exchange_rates[$business_account['Currency']['iso']], 4); ?>
						<?php echo number_format($coeff_conv, 4, '.', ','); ?>
					</td>
					<td class="right">
						<?php $conversion_diff = round($coeff_conv * $foreign_diff, 2); ?>
						<?php $sum_conversion_diff += $conversion_diff; ?>
						<?php echo number_format($conversion_diff, 2, '.', ','); ?>
					</td>
					<td class="center">
						<?php echo $business_account['Currency']['iso']; ?>
					</td>													
				</tr>
				<?php endforeach; ?>
				<tr class="marker">
					<td colspan="8" class="bold right"><?php echo __("Ukupno RSD"); ?></td>
					<td class="right">
						<?php if(!empty($sum_domestic_credit)){ ?>
							<?php echo number_format($sum_domestic_credit, 3, '.', ','); ?>
						<?php }else{ ?>
							&nbsp;
						<?php } ?>
					</td>
					<td>&nbsp;</td>
					<td class="right">
						<?php if(!empty($sum_domestic_debit)){ ?>
							<?php echo number_format($sum_domestic_debit, 3, '.', ','); ?>
						<?php }else{ ?>
							&nbsp;
						<?php } ?>
					</td>					
					<td>&nbsp;</td>
					<td>
						<?php if(!empty($sum_domestic_diff)){ ?>
							<?php echo number_format($sum_domestic_diff, 3, '.', ','); ?>
						<?php }else{ ?>
							&nbsp;
						<?php } ?>					
					</td>
					<td colspan="4" class="right">&nbsp;</td>			
				</tr>
				<?php $diff_count = 1; ?>
				<?php foreach ($diff_foreign_sums as $currency_iso => $diff_foreign_sum): ?>
					<tr class="marker">
						<?php if($diff_count == 1){ ?>
							<td colspan="10" class="bold right"><?php echo __("Devizni iznos obaveze po valuti knjizenja"); ?></td>
						<?php }else{ ?>
							<td colspan="10">&nbsp;</td>
						<?php } ?>
						<td class="right">
							<?php if(!empty($diff_foreign_sum)){ ?>
								<?php echo number_format($diff_foreign_sum, 2, '.', ','); ?>
							<?php }else{ ?>
								&nbsp;
							<?php } ?>						
						</td>
						<td>
							<?php if(!empty($diff_foreign_sum)){ ?>
								<?php echo $currency_iso; ?>
							<?php }else{ ?>
								&nbsp;
							<?php } ?>							
						</td>						
						<?php if($diff_count == 1){ ?>
							<td colspan="3" class="right bold"><?php echo __("Devizni iznos obaveze po valuti izvoda"); ?></td>
							<td class="right">
								<?php if(!empty($sum_conversion_diff)){ ?>
									<?php echo number_format($sum_conversion_diff, 2, '.', ','); ?>
								<?php }else{ ?>
									&nbsp;
								<?php } ?>						
							</td>
							<td class="center">
								<?php echo $business_account['Currency']['iso']; ?>
							</td>					
						<?php }else{ ?>
							<td colspan="5">&nbsp;</td>
						<?php } ?>											
					</tr>
					<?php $diff_count++; ?>
				<?php endforeach; ?>				
				<tr>
					<td colspan="17" class="right">
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