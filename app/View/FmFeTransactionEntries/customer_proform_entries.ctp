<?php echo $this->Html->script('Script/Helpers/numbers.js'); ?>
<?php echo $this->Html->script('jquery.numeric.js'); ?>
<?php echo $this->Html->css('Script/FmFeTransactionEntries/records'); ?>
<ul class="breadcrumbs">
	<li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
	<li><?php echo $this->Html->link(__('Finansijsko knjigovodstvo'), array('controller' => 'ErpModules', 'action' => 'start', 'financial')); ?></li>
	<li><?php echo $this->Html->link(__('Devizni izvodi banke'), array('controller' => 'FmFeBasics', 'action' => 'index')); ?></li>
	<li><?php echo $this->Html->link(__('Pregled izvoda'), array('controller' => 'FmFeBasics', 'action' => 'view', $fe_transaction['FmFeTransaction']['fm_fe_basic_id'])); ?></li>
	<li class="last"><a href="" onclick="return false"><?php echo __('Obr.br.2 - OBRAZAC ZA EVIDENTIRANJE DEVIZNE TRANSAKCIJE PO IZVODU T.R.'); ?></a></li>
</ul>
<div style="width:1800px; overflow-x: scroll; margin-left: 50px;">
	<div class="name_add_search">
		<div class="name_of_page">
			<h5><i class="icon-book"></i> <?php echo __('Obr.br.2 - OBRAZAC ZA EVIDENTIRANJE DEVIZNE TRANSAKCIJE PO IZVODU T.R.'); ?></h5>
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
				<td class="bold"><?php echo $currency_iso; ?></td>
				<td class="left"><?php echo number_format($intermediate_rate, 4, '.', ','); ?></td>				
			<?php endforeach; ?>				
			</tr>		
		</tbody>
	</table>	
	<table class="records">
		<thead>
			<tr>
				<th class="center" rowspan="3"><?php echo __("Br.PF"); ?></th>
				<th class="center" rowspan="3"><?php echo __("Šifra statusa"); ?></th>
				<th class="center" colspan="4"><?php echo __("Nenaplaćeno"); ?></th>
				<th class="center" rowspan="3"><?php echo __("Devizni iznos doznacenih sredstava"); ?></th>
				<th class="center" rowspan="3"><?php echo __("Devizni iznos bankarske provizije"); ?></th>
				<th class="center" rowspan="3"><?php echo __("Devizni iznos priliva/odliva na t.r."); ?></th>
				<th class="center" rowspan="3"><?php echo __("Dinarski iznos doznacenih sredstava na dan izvoda"); ?></th>
				<th class="center" rowspan="3"><?php echo __("Provizija banke u RSD"); ?></th>
				<th class="center" rowspan="2" colspan="2"><?php echo __("Preracun doznacenih sredstava u valutu po PF"); ?></th>
				<th class="center" rowspan="2" colspan="3"><?php echo __("Rasknjižеnje"); ?></th>
				<th class="center" rowspan="3"><?php echo __("Vrsta transakcije"); ?></th>
				<th class="center" rowspan="3"><?php echo __("Razlika izmedju iznosa izdate PF i iznosa naplate"); ?></th>
			</tr>
			<tr>
				<th class="center" colspan="2"><?php echo __("u valuti po PF"); ?></th>
				<th class="center" colspan="2"><?php echo __("u valuti naplate"); ?></th>
			</tr>
			<tr>
				<th class="center"><?php echo __("devizni iznos"); ?></th>
				<th class="center"><?php echo __("valuta"); ?></th>
				<th class="center"><?php echo __("devizni iznos"); ?></th>
				<th class="center"><?php echo __("valuta"); ?></th>
				<th class="center"><?php echo __("devizni iznos"); ?></th>
				<th class="center"><?php echo __("valuta"); ?></th>								
				<th class="center"><?php echo __("način rasknjiženja"); ?></th>
				<th class="center"><?php echo __("devizni iznos"); ?></th>
				<th class="center"><?php echo __("valuta"); ?></th>				
			</tr>			
			<tr class="column_number">
				<th>1</th>
				<th>2</th>
				<th colspan="2">3</th>
				<th colspan="2">4</th>
				<th>5</th>
				<th>6</th>
				<th>7</th>
				<th>8</th>
				<th>9</th>
				<th colspan="2">10</th>
				<th colspan="3">11</th>
				<th>12</th>
				<th>13</th>
			</tr>			
		</thead>
		<tbody>
			<?php if(empty($fe_transaction_entries)){ ?>
				<tr>
					<td colspan="16" class="notice warning center">
				        <i class="icon-warning-sign icon-large"></i>
				        <?php echo __("Za ovu transakciju trenutno nema definisanih stavki!"); ?>
					</td>
				</tr>
			<?php }else{ ?>
				<?php echo $this->Form->create('FmFeTransactionEntry'); ?>
				<?php 
					$currencies = array();
					$order_totals = array();
					$conversion_totals = array();
					$foreign_converted_sum = 0;
				?>
				<?php foreach ($fe_transaction_entries as $entry): ?>
				<tr class="nowrap" id="entry_<?php echo $entry['FmFeTransactionEntry']['id']; ?>">
					<td class="center"><?php echo $entry['Order']['order_number']; ?></td>
					<td class="center"><?php echo $entry['FmTrafficStatus']['code']; ?></td>
					<td class="right">
						<?php echo number_format($entry['FmFeTransactionEntry']['foreign_total'], 2, '.', ','); ?>
						<?php if(empty($order_totals[$entry['Currency']['iso']])){ ?>
							<?php $order_totals[$entry['Currency']['iso']] = $entry['FmFeTransactionEntry']['foreign_total']; ?>
						<?php }else{ ?>
							<?php $order_totals[$entry['Currency']['iso']] += $entry['FmFeTransactionEntry']['foreign_total']; ?>
						<?php } ?>
					</td>
					<td class="center">
						<?php echo $entry['Currency']['iso']; ?>
						<?php if(!in_array($entry['Currency']['iso'], $currencies)){ ?>
							<?php $currencies[] = $entry['Currency']['iso']; ?>
						<?php } ?>
					</td>
					<td class="right">
						<?php echo number_format($entry['FmFeTransactionEntry']['foreign_total_converted'], 2, '.', ','); ?>
						<?php $foreign_converted_sum += $entry['FmFeTransactionEntry']['foreign_total_converted']; ?>		
					</td>
					<td class="center"><?php echo $business_account['Currency']['iso']; ?></td>
					<td class="right">
						<?php echo $this->Form->input('foreign_transaction_value_'.$entry['FmFeTransactionEntry']['id'], array('label' => false, 'div' => false, 'class' => 'positive-decimal', 'id' => 'foreign_transaction_value_'.$entry['FmFeTransactionEntry']['id'], 'autocomplete' => 'off')); ?>
					</td>
					<td class="right">
						<?php echo $this->Form->input('foreign_bank_costs_'.$entry['FmFeTransactionEntry']['id'], array('label' => false, 'div' => false, 'class' => 'positive-decimal', 'id' => 'foreign_bank_costs_'.$entry['FmFeTransactionEntry']['id'], 'autocomplete' => 'off')); ?>							
					</td>
					<td class="foreign_diff right">
						<?php echo $this->Form->input('foreign_diff_'.$entry['FmFeTransactionEntry']['id'], array('label' => false, 'div' => false, 'class' => 'right number_input', 'readonly' => true, 'id' => 'foreign_diff_'.$entry['FmFeTransactionEntry']['id'])); ?>
					</td>
					<td class="domestic_value_exchange right">
						<?php echo $this->Form->input('domestic_value_exchange_'.$entry['FmFeTransactionEntry']['id'], array('label' => false, 'div' => false, 'class' => 'right number_input', 'readonly' => true, 'id' => 'domestic_value_exchange_'.$entry['FmFeTransactionEntry']['id'])); ?>
					</td>
					<td class="bank_commision right">
						<?php echo $this->Form->input('bank_commision_'.$entry['FmFeTransactionEntry']['id'], array('label' => false, 'div' => false, 'class' => 'right number_input', 'readonly' => true, 'id' => 'bank_commision_'.$entry['FmFeTransactionEntry']['id'])); ?>
					</td>
					<td class="domestic_foreign_conversion right">
						<?php echo $this->Form->input('domestic_foreign_conversion_'.$entry['FmFeTransactionEntry']['id'], array('label' => false, 'div' => false, 'class' => 'right number_input', 'readonly' => true, 'id' => 'domestic_foreign_conversion_'.$entry['FmFeTransactionEntry']['id'])); ?>
					</td>
					<td class="center"><?php echo $entry['Currency']['iso']; ?></td>
					<td class="account_manner">
						<?php echo $this->Form->input('account_manner_'.$entry['FmFeTransactionEntry']['id'], array('label' => false, 'div' => false, 'options' => $account_manners, 'class' => 'dropdown manner_select', 'style' => 'width:120px;','id' => 'account_manner_'.$entry['FmFeTransactionEntry']['id'])); ?>
					</td>					
					<td class="final_foreign_value right">
						<?php echo $this->Form->input('final_foreign_value_'.$entry['FmFeTransactionEntry']['id'], array('label' => false, 'div' => false, 'class' => 'right number_input', 'readonly' => true, 'id' => 'final_foreign_value_'.$entry['FmFeTransactionEntry']['id'])); ?>
					</td>
					<td id="currency_id_<?php echo $entry['FmFeTransactionEntry']['id']; ?>">
						<?php echo $entry['Currency']['iso']; ?>
					</td>
					<td class="center"><?php echo $fe_transaction['FmFeTransactionType']['desc_data']; ?></td>
					<td class="invoice_exchange_diff right">
						<?php echo $this->Form->input('invoice_exchange_diff_'.$entry['FmFeTransactionEntry']['id'], array('label' => false, 'div' => false, 'class' => 'right number_input', 'readonly' => true, 'id' => 'invoice_exchange_diff_'.$entry['FmFeTransactionEntry']['id'])); ?>
					</td>
				</tr>
				<?php endforeach; ?>
				<?php $currency_count = 1; ?>
				<?php foreach ($currencies as $currency): ?>
					<tr class="nowrap marker">
						<?php if($currency_count == 1){ ?>
							<td colspan="2" class="bold right"><?php echo __("Ukupno"); ?></td>
						<?php }else{ ?>
							<td colspan="2">&nbsp;</td>
						<?php } ?>
						<td class="right"><?php echo number_format($order_totals[$currency], 2, '.', ','); ?></td>
						<td class="center"><?php echo $currency; ?></td>
						<?php if($currency_count == 1){ ?>
							<td class="right"><?php echo number_format($foreign_converted_sum, 2, '.', ','); ?></td>
							<td class="center"><?php echo $business_account['Currency']['iso']; ?></td>
							<td class="right" id="sum_foreign_transaction_value">&nbsp;</td>
							<td class="right" id="sum_foreign_bank_costs">&nbsp;</td>
							<td class="right" id="sum_foreign_diff">&nbsp;</td>
							<td class="right" id="sum_domestic_value_exchange">&nbsp;</td>
							<td class="right" id="sum_bank_commision">&nbsp;</td>
						<?php }else{ ?>
							<td colspan="7">&nbsp;</td>							
						<?php } ?>
						<td class="right" id="<?php echo $currency; ?>_domestic_foreign_conversion">&nbsp;</td>
						<td class="center"><?php echo $currency; ?></td>						
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>						
					</tr>
					<?php $currency_count++; ?>
				<?php endforeach; ?>								
				<tr>
					<td colspan="18" class="right">
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
<?php echo "var exchange_rates = ". json_encode($exchange_rates) . ";\n"; ?>
<?php echo "var business_account = ". json_encode($business_account) . ";\n"; ?>
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
	//Init data
	var sum_foreign_transaction_value = 0;
	var sum_foreign_bank_costs = 0;
	var sum_foreign_diff = 0;
	var sum_domestic_value_exchange = 0;
	var sum_bank_commision = 0;
	var currency_sums = {};

	//Calculate data
	for (var i = 0, l = fe_transaction_entries.length; i < l; i++) {
		//Set entry
	    var entry = fe_transaction_entries[i];

	    //Calculate sums
	    sum_foreign_transaction_value += entry.foreign_transaction_value || 0;
	    sum_foreign_bank_costs += entry.foreign_bank_costs || 0;
	    sum_foreign_diff += entry.foreign_diff || 0;
	    sum_domestic_value_exchange += entry.domestic_value_exchange || 0;
	    sum_bank_commision += entry.bank_commision || 0;

	    //Calculate currency sums
	    if(currency_sums[entry.Currency.iso]){
	    	currency_sums[entry.Currency.iso] += entry.domestic_foreign_conversion || 0;
	    }else{
	    	currency_sums[entry.Currency.iso] = entry.domestic_foreign_conversion || 0;
	    }	    
	}

	//Set data
	if(sum_foreign_transaction_value != 0){
		$("td#sum_foreign_transaction_value").text(number_format(sum_foreign_transaction_value, 2, '.', ','));
	}else{
		$("td#sum_foreign_transaction_value").text('');
	}
	if(sum_foreign_bank_costs != 0){
		$("td#sum_foreign_bank_costs").text(number_format(sum_foreign_bank_costs, 2, '.', ','));
	}else{
		$("td#sum_foreign_bank_costs").text('');
	}
	if(sum_foreign_diff != 0){
		$("td#sum_foreign_diff").text(number_format(sum_foreign_diff, 2, '.', ','));
	}else{
		$("td#sum_foreign_diff").text('');
	}
	if(sum_domestic_value_exchange != 0){
		$("td#sum_domestic_value_exchange").text(number_format(sum_domestic_value_exchange, 3, '.', ','));
	}else{
		$("td#sum_domestic_value_exchange").text('');
	}
	if(sum_bank_commision != 0){
		$("td#sum_bank_commision").text(number_format(sum_bank_commision, 3, '.', ','));
	}else{
		$("td#sum_bank_commision").text('');
	}

	//Calculate currency data
	for (var currency_iso in currency_sums) {
	  if (currency_sums.hasOwnProperty(currency_iso)) {
	    var currency_sum = currency_sums[currency_iso];
		if(currency_sum != 0){
			$("td#"+currency_iso+"_domestic_foreign_conversion").text(number_format(currency_sum, 2, '.', ','));
		}else{
			$("td#"+currency_iso+"_domestic_foreign_conversion").text('');
		}
	  }
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
		entry.account_manner = $("#account_manner_"+entry_id).val();

		//Celculate data
		entry.foreign_diff = roundNumber(entry.foreign_transaction_value - entry.foreign_bank_costs, 2);
		entry.domestic_value_exchange = roundNumber(entry.foreign_transaction_value * fe_transaction.FmFeBasic.exchange_rate, 3);
		entry.bank_commision = roundNumber(entry.foreign_bank_costs * fe_transaction.FmFeBasic.exchange_rate, 3);
		entry.domestic_foreign_conversion = roundNumber(entry.domestic_value_exchange / exchange_rates[entry.Currency.iso], 2);
		entry.invoice_exchange_diff = roundNumber(entry.FmFeTransactionEntry.foreign_total - entry.domestic_foreign_conversion, 2);

		entry.final_foreign_value = 0;
		entry.currency_iso = '';
		if(entry.account_manner == 'proform'){			
			entry.final_foreign_value = entry.domestic_foreign_conversion;
			entry.currency_iso = entry.Currency.iso;
		}
		if(entry.account_manner == 'foreign_exchange'){
			entry.final_foreign_value = entry.foreign_transaction_value;			
			entry.currency_iso = business_account.Currency.iso;			
		}

		//Set input fields
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
		if(entry.bank_commision != 0){
			$("#bank_commision_"+entry_id).val(entry.bank_commision);
		}else{
			$("#bank_commision_"+entry_id).val('');
		}
		if(entry.domestic_foreign_conversion != 0){
			$("#domestic_foreign_conversion_"+entry_id).val(entry.domestic_foreign_conversion);
		}else{
			$("#domestic_foreign_conversion_"+entry_id).val('');
		}		
		if(entry.invoice_exchange_diff != 0){
			$("#invoice_exchange_diff_"+entry_id).val(entry.invoice_exchange_diff);
		}else{
			$("#invoice_exchange_diff_"+entry_id).val('');
		}
		if(entry.final_foreign_value != 0){
			$("#final_foreign_value_"+entry_id).val(entry.final_foreign_value);
		}else{
			$("#final_foreign_value_"+entry_id).val('');
		}
		$("#currency_id_"+entry_id).text(entry.currency_iso);
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
		    var unpaid = entry.FmFeTransactionEntry.foreign_total_converted;
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

    //Check for account manner change
    $(".manner_select").on("select2-selecting", function(e) {
    	//Init variables
    	var id_text = $(this).attr('id');
    	var entry_id = parseInt(id_text.replace("account_manner_", ""));

    	//Set new currency
    	$("#account_manner_"+entry_id).val(e.object.id)

    	//Update fields
		calculateColumns(entry_id);
		updateTotals();
    });

	//Refresh columns based on db data
	refreshColumns();
});
</script>