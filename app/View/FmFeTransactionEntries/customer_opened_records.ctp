<?php echo $this->Html->css('Script/FmFeTransactionEntries/records'); ?>
<ul class="breadcrumbs">
	<li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
	<li><?php echo $this->Html->link(__('Finansijsko knjigovodstvo'), array('controller' => 'ErpModules', 'action' => 'start', 'financial')); ?></li>
	<li><?php echo $this->Html->link(__('Devizni izvodi banke'), array('controller' => 'FmFeBasics', 'action' => 'index')); ?></li>
	<li><?php echo $this->Html->link(__('Pregled izvoda'), array('controller' => 'FmFeBasics', 'action' => 'view', $fe_transaction['FmFeTransaction']['fm_fe_basic_id'])); ?></li>
	<li class="last"><a href="" onclick="return false"><?php echo __('PREGLED POTRAZIVANJA (otvorene stavke)'); ?></a></li>
</ul>

<div class="name_add_search">
	<div class="name_of_page">
		<h5><i class="icon-book"></i> <?php echo __('Pregled potraživanja (otvorene stavke) na dan '); ?><?php echo date('d.m.Y'); ?></h5>
	</div>
</div>
<div class="content_data">
	<?php echo $this->element('../FmFeBasics/mini_basic'); ?>
	<table class="records">
		<thead>
			<tr>
				<th rowspan="2" class="center">&nbsp;</th>
				<th rowspan="2" class="center"><?php echo __("Konto"); ?></th>
				<th rowspan="2" class="center"><?php echo __("Šifra statusa"); ?></th>
				<th rowspan="2" class="center"><?php echo __("Br.Rn"); ?></th>
				<th rowspan="2" class="center"><?php echo __("Br.PF"); ?></th>
				<th rowspan="2" class="center"><?php echo __("Devizni kurs"); ?></th>
				<th colspan="2" class="center"><?php echo __("Ukupan iznos Rn"); ?></th>
				<th colspan="2" class="center"><?php echo __("Naplaćeno"); ?></th>
				<th colspan="2" class="center"><?php echo __("Nenaplaćeno"); ?></th>
			</tr>
			<tr>
				<th class="right"><?php echo __("devizni iznos"); ?></th>
				<th class="right"><?php echo __("RSD iznos"); ?></th>
				<th class="right"><?php echo __("devizni iznos"); ?></th>
				<th class="right"><?php echo __("RSD iznos"); ?></th>
				<th class="right"><?php echo __("devizni iznos"); ?></th>
				<th class="right"><?php echo __("RSD iznos"); ?></th>
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
			</tr>
		</thead>
		<tbody>
			<?php if(empty($customer_opened_records)){ ?>
				<tr>
					<td colspan="11">
				        <i class="icon-warning-sign icon-large"></i>
				        <?php echo __("Za ovog komitenta trenutno nema otvorenih stavki!"); ?>
					</td>
				</tr>
			<?php }else{ ?>
				<?php echo $this->Form->create('FmFeTransactionEntry'); ?>
				<?php 
					$sum_foreign_debit = 0;
					$sum_foreign_credit = 0;
					$sum_domestic_debit = 0;
					$sum_domestic_credit = 0;
				?>
				<?php foreach ($customer_opened_records as $record): ?>
				<tr>
					<td class="center">						
						<?php echo $this->Form->checkbox('select_record_'.$record['FmAccountOrderRecord']['id'], array('label' => false, 'div' => false)); ?>
						<?php echo $this->Form->input('id_'.$record['FmAccountOrderRecord']['id'], array('type' => 'hidden', 'label' => false, 'div' => false)); ?>
					</td>
					<td class="center"><?php echo $record['FmChartAccount']['code']; ?></td>
					<td class="center"><?php echo $record['FmTrafficStatus']['code']; ?></td>
					<td class="center"><?php echo $record['FmAccountOrderRecord']['primary_document_code']; ?></td>
					<td class="center"><?php echo $record['FmAccountOrderRecord']['secondary_document_code']; ?></td>
					<td class="center"><?php echo $record['FmAccountOrderRecord']['domestic_exchange_rate']; ?></td>
					<td class="right">
						<?php $sum_foreign_debit += round($record['FmAccountOrderRecord']['total_foreign_debit'], 2); ?>
						<?php echo number_format($record['FmAccountOrderRecord']['total_foreign_debit'], 2, '.', ','); ?>						
					</td>
					<td class="right">
						<?php $sum_domestic_debit += round($record['FmAccountOrderRecord']['total_domestic_debit'], 3); ?>
						<?php echo number_format($record['FmAccountOrderRecord']['total_domestic_debit'], 3, '.', ','); ?>						
					</td>
					<td class="right">
						<?php $sum_foreign_credit += round($record['FmAccountOrderRecord']['total_foreign_credit'], 2); ?>
						<?php echo number_format($record['FmAccountOrderRecord']['total_foreign_credit'], 2, '.', ','); ?>						
					</td>
					<td class="right">
						<?php $sum_domestic_credit += round($record['FmAccountOrderRecord']['total_domestic_credit'], 3); ?>
						<?php echo number_format($record['FmAccountOrderRecord']['total_domestic_credit'], 3, '.', ','); ?>						
					</td>
					<td class="right">
						<?php $foreign_diff = $record['FmAccountOrderRecord']['total_foreign_debit'] - $record['FmAccountOrderRecord']['total_foreign_credit']; ?>
						<?php if(!empty($foreign_diff)){ ?>
							<?php echo number_format($foreign_diff, 2, '.', ','); ?>
						<?php }else{ ?>
							&nbsp;
						<?php } ?>
					</td>
					<td class="right">
						<?php $domestic_diff = $record['FmAccountOrderRecord']['total_domestic_debit'] - $record['FmAccountOrderRecord']['total_domestic_credit']; ?>
						<?php if(!empty($domestic_diff)){ ?>
							<?php echo number_format($domestic_diff, 3, '.', ','); ?>
						<?php }else{ ?>
							&nbsp;
						<?php } ?>
					</td>
				</tr>
				<?php endforeach; ?>
				<tr class="marker">
					<td colspan="6" class="bold right"><?php echo __("Ukupno"); ?></td>
					<td class="right">
						<?php if(!empty($sum_foreign_debit)){ ?>
							<?php echo number_format($sum_foreign_debit, 2, '.', ','); ?>
						<?php }else{ ?>
							&nbsp;
						<?php } ?>
					</td>
					<td class="right">
						<?php if(!empty($sum_domestic_debit)){ ?>
							<?php echo number_format($sum_domestic_debit, 3, '.', ','); ?>
						<?php }else{ ?>
							&nbsp;
						<?php } ?>
					</td>
					<td class="right">
						<?php if(!empty($sum_foreign_credit)){ ?>
							<?php echo number_format($sum_foreign_credit, 2, '.', ','); ?>
						<?php }else{ ?>
							&nbsp;
						<?php } ?>
					</td>
					<td class="right">
						<?php if(!empty($sum_domestic_credit)){ ?>
							<?php echo number_format($sum_domestic_credit, 3, '.', ','); ?>
						<?php }else{ ?>
							&nbsp;
						<?php } ?>
					</td>													
					<td class="right">
						<?php $sum_foreign_diff = $sum_foreign_debit - $sum_foreign_credit; ?>
						<?php if(!empty($sum_foreign_diff)){ ?>
							<?php echo number_format($sum_foreign_diff, 2, '.', ','); ?>
						<?php }else{ ?>
							&nbsp;
						<?php } ?>
					</td>
					<td class="right">
						<?php $sum_domestic_diff = $sum_domestic_debit - $sum_domestic_credit; ?>
						<?php if(!empty($sum_domestic_diff)){ ?>
							<?php echo number_format($sum_domestic_diff, 3, '.', ','); ?>
						<?php }else{ ?>
							&nbsp;
						<?php } ?>
					</td>				
				</tr>				
				<tr>
					<td colspan="12" class="right">
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