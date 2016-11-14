<?php echo $this->Html->script('Script/Helpers/numbers.js'); ?>
<?php echo $this->Html->script('jquery.numeric.js'); ?>
<?php echo $this->Html->css('Script/FmFeTransactionEntries/records'); ?>
<ul class="breadcrumbs">
	<li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
	<li><?php echo $this->Html->link(__('Finansijsko knjigovodstvo'), array('controller' => 'ErpModules', 'action' => 'start', 'financial')); ?></li>
	<li><?php echo $this->Html->link(__('Devizni izvodi banke'), array('controller' => 'FmFeBasics', 'action' => 'index')); ?></li>
	<li><?php echo $this->Html->link(__('Pregled izvoda'), array('controller' => 'FmFeBasics', 'action' => 'view', $fe_transaction['FmFeTransaction']['fm_fe_basic_id'])); ?></li>
	<li class="last"><a href="" onclick="return false"><?php echo __('Obr.br.1-OBRAZAC ZA EVIDENTIRANJE DEVIZNE TRANSAKCIJE PO IZVODU T.R.'); ?></a></li>
</ul>
<div style="width:1400px; overflow-x: scroll; margin-left: 50px;">
	<div class="name_add_search">
		<div class="name_of_page">
			<h5><i class="icon-book"></i> <?php echo __('Obr.br.1-OBRAZAC ZA EVIDENTIRANJE DEVIZNE TRANSAKCIJE PO IZVODU T.R.'); ?></h5>
		</div>
	</div>
	<?php echo $this->element('../FmFeBasics/mini_basic'); ?>
	<table class="records">
		<thead>
			<tr>
				<th class="center"><?php echo __("Konto"); ?></th>
				<th class="center"><?php echo __("Šifra statusa"); ?></th>
				<th class="center"><?php echo __("Br.Rn"); ?></th>
				<th class="center"><?php echo __("Br.PF"); ?></th>
				<th class="center"><?php echo __("Devizni iznos nenaplaćenih sredstava"); ?></th>
				<th class="center"><?php echo __("Devizni iznos doznacenih sredstava"); ?></th>
				<th class="center"><?php echo __("Devizni iznos bankarske provizije"); ?></th>
				<th class="center"><?php echo __("Devizni iznos priliva/odliva na t.r."); ?></th>
				<th class="center"><?php echo __("Dinarski iznos doznacenih sredstava"); ?></th>
				<th class="center"><?php echo __("Dinarski iznos zaduženih sredstava"); ?></th>
				<th class="center"><?php echo __("Kursna razlika"); ?></th>
				<th class="center"><?php echo __("Opis transakcije"); ?></th>
				<th class="center"><?php echo __("Provizija banke u RSD"); ?></th>
				<th class="center"><?php echo __("Razlika nenaplacenog iznosa i iznosa naplate"); ?></th>
			</tr>
			<tr class="column_number">
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
			<?php if(empty($fe_transaction_entries)){ ?>
				<tr>
					<td colspan="14" class="notice warning center">
				        <i class="icon-warning-sign icon-large"></i>
				        <?php echo __("Za ovu transakciju trenutno nema definisanih stavki!"); ?>
					</td>
				</tr>
			<?php }else{ ?>
				<?php echo $this->Form->create('FmFeTransactionEntry'); ?>
				<?php foreach ($fe_transaction_entries as $entry): ?>
				<tr class="nowrap" id="entry_<?php echo $entry['FmFeTransactionEntry']['id']; ?>">
					<td class="center"><?php echo $entry['FmChartAccount']['code']; ?></td>
					<td class="center"><?php echo $entry['FmTrafficStatus']['code']; ?></td>
					<td><?php echo $entry['FmFeTransactionEntry']['primary_document_code']; ?></td>
					<td><?php echo $entry['FmFeTransactionEntry']['secondary_document_code']; ?></td>
					<td class="right">
						<?php $unpaid = $entry['FmFeTransactionEntry']['foreign_total'] - $entry['FmFeTransactionEntry']['foreign_paid']; ?>
						<?php echo number_format($unpaid, 2, '.', ','); ?>
					</td>
					<td>
						<?php echo $this->Form->input('foreign_transaction_value_'.$entry['FmFeTransactionEntry']['id'], array('label' => false, 'div' => false, 'class' => 'positive-decimal', 'id' => 'foreign_transaction_value_'.$entry['FmFeTransactionEntry']['id'], 'autocomplete' => 'off')); ?>
					</td>
					<td><?php echo $this->Form->input('foreign_bank_costs_'.$entry['FmFeTransactionEntry']['id'], array('label' => false, 'div' => false, 'class' => 'positive-decimal', 'id' => 'foreign_bank_costs_'.$entry['FmFeTransactionEntry']['id'], 'autocomplete' => 'off')); ?></td>
					<td class="foreign_diff right">
						<?php echo $this->Form->input('foreign_diff_'.$entry['FmFeTransactionEntry']['id'], array('label' => false, 'div' => false, 'class' => 'right number_input', 'readonly' => true, 'id' => 'foreign_diff_'.$entry['FmFeTransactionEntry']['id'])); ?>
					</td>
					<td class="domestic_value_exchange right">
						<?php echo $this->Form->input('domestic_value_exchange_'.$entry['FmFeTransactionEntry']['id'], array('label' => false, 'div' => false, 'class' => 'right number_input', 'readonly' => true, 'id' => 'domestic_value_exchange_'.$entry['FmFeTransactionEntry']['id'])); ?>
					</td>
					<td class="domestic_value_invoice right">
						<?php echo $this->Form->input('domestic_value_invoice_'.$entry['FmFeTransactionEntry']['id'], array('label' => false, 'div' => false, 'class' => 'right number_input', 'readonly' => true, 'id' => 'domestic_value_invoice_'.$entry['FmFeTransactionEntry']['id'])); ?>
					</td>
					<td class="exchange_diff right">
						<?php echo $this->Form->input('exchange_diff_'.$entry['FmFeTransactionEntry']['id'], array('label' => false, 'div' => false, 'class' => 'right number_input', 'readonly' => true, 'id' => 'exchange_diff_'.$entry['FmFeTransactionEntry']['id'])); ?>
					</td>
					<td class="center"><?php echo $fe_transaction['FmFeTransactionType']['desc_data']; ?></td>
					<td class="bank_commision right">
						<?php echo $this->Form->input('bank_commision_'.$entry['FmFeTransactionEntry']['id'], array('label' => false, 'div' => false, 'class' => 'right number_input', 'readonly' => true, 'id' => 'bank_commision_'.$entry['FmFeTransactionEntry']['id'])); ?>
					</td>
					<td class="invoice_exchange_diff right">
						<?php echo $this->Form->input('invoice_exchange_diff_'.$entry['FmFeTransactionEntry']['id'], array('label' => false, 'div' => false, 'class' => 'right number_input', 'readonly' => true, 'id' => 'invoice_exchange_diff_'.$entry['FmFeTransactionEntry']['id'])); ?>
					</td>
				</tr>
				<?php endforeach; ?>
				<tr class="nowrap marker">
					<td colspan="5" class="right bold"><?php echo __("Ukupno"); ?></td>
					<td class="right sum_foreign_transaction_value">&nbsp;</td>
					<td class="right sum_foreign_bank_costs">&nbsp;</td>					
					<td class="right sum_foreign_diff">&nbsp;</td>
					<td class="right sum_domestic_value_exchange">&nbsp;</td>
					<td class="right sum_domestic_value_invoice">&nbsp;</td>
					<td class="right sum_exchange_diff">&nbsp;</td>
					<td class="center">&nbsp;</td>
					<td class="right sum_bank_commision">&nbsp;</td>
					<td class="right sum_invoice_exchange_diff">&nbsp;</td>
				</tr>				
				<tr>
					<td colspan="14" class="right">
						<?php 
							echo $this->Form->submit(__('Učitaj izveštaj'), array(
								'div' => false,
								'class' => "button green"
							));
						?>
						<?php echo $this->Form->end(); ?>
						<?php echo $this->Html->link(__('Nazad na pregled potraživanja'), array('controller' => 'FmFeTransactionEntries', 'action' => 'load_entries', $fe_transaction['FmFeTransaction']['id']), array('class' => 'button')); ?>								
					</td>
				</tr>
			<?php } ?>
		</tbody>		
	</table>		
</div>
<script>
/* Init default variables */
<?php echo "var fe_transaction = ". json_encode($fe_transaction) . ";\n"; ?>
<?php echo "var fe_transaction_entries = ". json_encode($fe_transaction_entries) . ";\n"; ?>
<?php if($form_submitted){ ?>
	<?php echo "var form_submitted = true;\n"; ?>
<?php }else{ ?>
	<?php echo "var form_submitted = false;\n"; ?>
<?php }?>

/* Function for calculating column values */
function getEntry(entry_id){
	result = null;
	if(entry_id){
		for (var i = 0, l = fe_transaction_entries.length; i < l; i++) {
		    var entry = fe_transaction_entries[i];
		    if(entry.FmFeTransactionEntry.id == entry_id){
		    	result = entry;
		    	break;
		    }
		}
	}
	return result;
}//~!

/* Function for calculating column totals */
function updateTotals(){
	var sum_foreign_transaction_value = 0;
	var sum_foreign_bank_costs = 0;
	var sum_foreign_diff = 0;
	var sum_domestic_value_exchange = 0;
	var sum_domestic_value_invoice = 0;
	var sum_exchange_diff = 0;
	var sum_bank_commision = 0;
	var sum_invoice_exchange_diff = 0;

	for (var i = 0, l = fe_transaction_entries.length; i < l; i++) {
		//Set entry
	    var entry = fe_transaction_entries[i];

	    //Calculate sums
	    sum_foreign_transaction_value += entry.foreign_transaction_value || 0;
	    sum_foreign_bank_costs += entry.foreign_bank_costs || 0;
	    sum_foreign_diff += entry.foreign_diff || 0;
	    sum_domestic_value_exchange += entry.domestic_value_exchange || 0;
	    sum_domestic_value_invoice += entry.domestic_value_invoice || 0;
	    sum_exchange_diff += entry.exchange_diff || 0;
	    sum_bank_commision += entry.bank_commision || 0;
	    sum_invoice_exchange_diff += entry.invoice_exchange_diff || 0;
	}

	//Set sum foreign transaction value
	if(sum_foreign_transaction_value != 0){
		$("td.sum_foreign_transaction_value").text(number_format(sum_foreign_transaction_value, 2, '.', ','));
	}else{
		$("td.sum_foreign_transaction_value").text('');
	}

	//Set sum foreign bank costs
	if(sum_foreign_bank_costs != 0){
		$("td.sum_foreign_bank_costs").text(number_format(sum_foreign_bank_costs, 2, '.', ','));
	}else{
		$("td.sum_foreign_bank_costs").text('');
	}

	//Set sum foreign diff
	if(sum_foreign_diff != 0){
		$("td.sum_foreign_diff").text(number_format(sum_foreign_diff, 2, '.', ','));
	}else{
		$("td.sum_foreign_diff").text('');
	}

	//Set sum domestic value exchange
	if(sum_domestic_value_exchange != 0){
		$("td.sum_domestic_value_exchange").text(number_format(sum_domestic_value_exchange, 3, '.', ','));
	}else{
		$("td.sum_domestic_value_exchange").text('');
	}

	//Set sum domestic value invoice
	if(sum_domestic_value_invoice != 0){
		$("td.sum_domestic_value_invoice").text(number_format(sum_domestic_value_invoice, 3, '.', ','));
	}else{
		$("td.sum_domestic_value_invoice").text('');
	}

	//Set sum exchange diff
	if(sum_exchange_diff != 0){
		$("td.sum_exchange_diff").text(number_format(sum_exchange_diff, 3, '.', ','));
	}else{
		$("td.sum_exchange_diff").text('');
	}

	//Set sum domestic costs
	if(sum_bank_commision != 0){
		$("td.sum_bank_commision").text(number_format(sum_bank_commision, 3, '.', ','));
	}else{
		$("td.sum_bank_commision").text('');
	}

	//Set sum total diff
	if(sum_invoice_exchange_diff != 0){
		$("td.sum_invoice_exchange_diff").text(number_format(sum_invoice_exchange_diff, 2, '.', ','));
	}else{
		$("td.sum_invoice_exchange_diff").text('');
	}
}//~!

/* Function for calculating column values */
function calculateColumns(entry_id){
	if(entry_id){
		//Check if entry exists
		var entry = getEntry(entry_id);		
		if(!entry){
			return false;
		}

		//Init data
		entry.foreign_transaction_value = parseFloat($("#foreign_transaction_value_"+entry_id).val()) || 0;
		entry.foreign_bank_costs = parseFloat($("#foreign_bank_costs_"+entry_id).val()) || 0;
		entry.foreign_exchange_rate = parseFloat(fe_transaction.FmFeBasic.exchange_rate) || 0;
		entry.invoice_exchange_rate = parseFloat(entry.FmFeTransactionEntry.foreign_exchange_rate) || 0;
		entry.record_total_foreign = parseFloat(entry.FmFeTransactionEntry.foreign_total) || 0;

		//Calculate data
		entry.foreign_diff = roundNumber(entry.foreign_transaction_value - entry.foreign_bank_costs, 2);
		entry.domestic_value_exchange = roundNumber(entry.foreign_transaction_value * entry.foreign_exchange_rate, 3);
		entry.domestic_value_invoice = roundNumber(entry.foreign_transaction_value * entry.invoice_exchange_rate, 3);
		entry.exchange_diff = roundNumber(entry.domestic_value_exchange - entry.domestic_value_invoice, 3);
		entry.bank_commision = roundNumber(entry.foreign_bank_costs * entry.foreign_exchange_rate, 3);
		entry.invoice_exchange_diff = roundNumber(entry.record_total_foreign - entry.foreign_transaction_value, 2);

		//Set data
		if(entry.foreign_diff != 0){
			$("#foreign_diff_"+entry_id).val(entry.foreign_diff);
		}else{
			$("#foreign_diff_"+entry_id).val('');
		}
		if(entry.domestic_value_exchange != 0){
			$("#domestic_value_exchange_"+entry_id).val(entry.domestic_value_exchange);
		}else{
			$("#domestic_value_exchange_"+entry_id).val('');
		}
		if(entry.domestic_value_invoice != 0){
			$("#domestic_value_invoice_"+entry_id).val(entry.domestic_value_invoice);
		}else{
			$("#domestic_value_invoice_"+entry_id).val('');
		}
		if(entry.exchange_diff != 0){
			$("#exchange_diff_"+entry_id).val(entry.exchange_diff);
		}else{
			$("#exchange_diff_"+entry_id).val('');
		}		
		if(entry.bank_commision != 0){
			$("#bank_commision_"+entry_id).val(entry.bank_commision);
		}else{
			$("#bank_commision_"+entry_id).val('');
		}
		if(entry.invoice_exchange_diff != 0){
			$("#invoice_exchange_diff_"+entry_id).val(entry.invoice_exchange_diff);
		}else{
			$("#invoice_exchange_diff_"+entry_id).val('');
		}
	}
}//~!

/* Function for calculating column values */
function refreshColumns(){
	//Refresh all columns
	for (var i = 0, l = fe_transaction_entries.length; i < l; i++) {
	    var entry = fe_transaction_entries[i];

	    //Check if form submitted
	    if(!form_submitted){
		    //Set transaction to unpaid value
		    var unpaid = entry.FmFeTransactionEntry.foreign_total - entry.FmFeTransactionEntry.foreign_paid;
		    $("#foreign_transaction_value_"+entry.FmFeTransactionEntry.id).val(unpaid);

		    //Set bank costs
		    $("#foreign_bank_costs_"+entry.FmFeTransactionEntry.id).val(entry.FmFeTransactionEntry.foreign_bank_costs);
		}

	    //Calculate columns
	   	calculateColumns(entry.FmFeTransactionEntry.id);
	}
	//Recalculate sums
	updateTotals();
}//~!

/* Init app */
$('#container').ready(function(){
	//Init libraries
	$(".submit_loader").hide();
	$(".dropdown").select2();
	$(".positive-decimal").numeric({ decimal: '.', negative: false, decimalPlaces: 2 }, function() { this.value = ""; this.focus(); });
	
	//On value or cost entering calculate other values
	$(".positive-decimal").on("keyup", function(e) {
		var id = null;
		var id_text = $(this).attr('id');

		if(id_text.indexOf('foreign_transaction_value_') !== -1){
			id = parseInt(id_text.replace("foreign_transaction_value_", ""));
		}
		if(id_text.indexOf('foreign_bank_costs_') !== -1){
			id = parseInt(id_text.replace("foreign_bank_costs_", ""));	
		}
		calculateColumns(id);
		updateTotals();
	});

	//On value or cost change calculate other values
	$(".positive-decimal").on("change", function(e) { 
		var id = null;
		var id_text = $(this).attr('id');

		if(id_text.indexOf('foreign_transaction_value_') !== -1){
			id = parseInt(id_text.replace("foreign_transaction_value_", ""));
		}
		if(id_text.indexOf('foreign_bank_costs_') !== -1){
			id = parseInt(id_text.replace("foreign_bank_costs_", ""));
		}
		calculateColumns(id);
		updateTotals();
	});

	//Refresh columns based on db data
	refreshColumns();
});
</script>