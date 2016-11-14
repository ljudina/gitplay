<?php echo $this->Html->script('Script/Helpers/numbers.js'); ?>
<?php echo $this->Html->script('jquery.numeric.js'); ?>
<?php echo $this->Html->css('Script/FmFeTransactionEntries/records'); ?>
<ul class="breadcrumbs">
	<li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
	<li><?php echo $this->Html->link(__('Finansijsko knjigovodstvo'), array('controller' => 'ErpModules', 'action' => 'start', 'financial')); ?></li>
	<li><?php echo $this->Html->link(__('Devizni izvodi banke'), array('controller' => 'FmFeBasics', 'action' => 'index')); ?></li>
	<li><?php echo $this->Html->link(__('Pregled izvoda'), array('controller' => 'FmFeBasics', 'action' => 'view', $fe_transaction['FmFeTransaction']['fm_fe_basic_id'])); ?></li>
	<li class="last"><a href="" onclick="return false"><?php echo __('Obr.br.3 - OBRAZAC ZA EVIDENTIRANJE DEVIZNE TRANSAKCIJE PO IZVODU T.R.'); ?></a></li>
</ul>
<div style="width:1700px; overflow-x: scroll; margin-left: 50px;">
	<div class="name_add_search">
		<div class="name_of_page">
			<h5><i class="icon-book"></i> <?php echo __('Obr.br.3 - OBRAZAC ZA EVIDENTIRANJE DEVIZNE TRANSAKCIJE PO IZVODU T.R.'); ?></h5>
		</div>
	</div>
	<?php echo $this->element('../FmFeBasics/mini_basic'); ?>
	<table class="records">
		<thead>
			<tr class="top">
				<th class="center" colspan="11"><?php echo __("Izabrane otvorene stavke"); ?></th>
				<th class="center" colspan="6"><?php echo __("Podaci po izvodu t.r."); ?></th>
				<th class="center" colspan="5"><?php echo __("Podaci za knjiženje"); ?></th>
				<th class="center nobgcolor" rowspan="4"><?php echo __("Razlika izmedju iznosa knjizene obaveze i iznosa povracaja"); ?></th>
			</tr>
			<tr>
				<th class="center" rowspan="3"><?php echo __("Konto"); ?></th>
				<th class="center" rowspan="3"><?php echo __("Šifra valute"); ?></th>
				<th class="center" rowspan="3"><?php echo __("Vrsta dokumenta"); ?></th>
				<th class="center" rowspan="3"><?php echo __("Broj dokumenta"); ?></th>
				<th class="center" rowspan="3"><?php echo __("Šifra statusa"); ?></th>
				<th class="center" colspan="6"><?php echo __("OBAVEZA PREMA INO-KOMITENTU"); ?></th>
				<th class="center" colspan="2"><?php echo __("Iznos isplate prema ino-komitentu"); ?></th>
				<th class="center" colspan="2"><?php echo __("Iznos bankarske provizije"); ?></th>
				<th class="center" colspan="2"><?php echo __("Ukupan odliv sa t.r."); ?></th>
				<th class="center" colspan="3"><?php echo __("Za rasknjizenje obaveze"); ?></th>
				<th class="center" rowspan="3"><?php echo __("Kursna razlika"); ?></th>
				<th class="center" rowspan="3"><?php echo __("Opis transakcije"); ?></th>				
			</tr>
			<tr>
				<th class="center" colspan="4"><?php echo __("Knjižena obaveza"); ?></th>
				<th class="center" colspan="2"><?php echo __("Konverzija u valutu izvoda"); ?></th>
				<th class="center" rowspan="2"><?php echo __("Devizni iznos (valuta izvoda)"); ?></th>
				<th class="center" rowspan="2"><?php echo __("Iznos u RSD"); ?></th>
				<th class="center" rowspan="2"><?php echo __("Devizni iznos (valuta izvoda)"); ?></th>
				<th class="center" rowspan="2"><?php echo __("Iznos u RSD"); ?></th>				
				<th class="center" rowspan="2"><?php echo __("Devizni iznos (valuta izvoda)"); ?></th>
				<th class="center" rowspan="2"><?php echo __("Iznos u RSD"); ?></th>				
				<th class="center" colspan="2"><?php echo __("Devizni iznos"); ?></th>
				<th class="center" rowspan="2"><?php echo __("Iznos u RSD"); ?></th>
			</tr>
			<tr>
				<th class="center"><?php echo __("devizni iznos"); ?></th>
				<th class="center"><?php echo __("valuta"); ?></th>
				<th class="center"><?php echo __("iznos u RSD"); ?></th>
				<th class="center"><?php echo __("devizni kurs"); ?></th>
				<th class="center"><?php echo __("devizni iznos"); ?></th>
				<th class="center"><?php echo __("valuta"); ?></th>				
				<th class="center"><?php echo __("iznos"); ?></th>
				<th class="center"><?php echo __("valuta"); ?></th>				
			</tr>
			<tr class="column_number">
				<th class="center">1</th>
				<th class="center">2</th>
				<th class="center">3</th>
				<th class="center">4</th>
				<th class="center">5</th>
				<th class="center" colspan="2">6</th>
				<th class="center" colspan="2">7</th>
				<th class="center" colspan="2">8</th>
				<th class="center">9</th>
				<th class="center">10</th>
				<th class="center">11</th>
				<th class="center">12</th>
				<th class="center">13</th>
				<th class="center">14</th>
				<th class="center" colspan="2">15</th>
				<th class="center">16</th>
				<th class="center">17</th>
				<th class="center">18</th>
				<th class="center">19</th>
			</tr>			
		</thead>	
		<tbody>
			<?php if(empty($fe_transaction_entries)){ ?>
				<tr>
					<td colspan="23" class="notice warning center">
				        <i class="icon-warning-sign icon-large"></i>
				        <?php echo __("Za ovu transakciju trenutno nema definisanih stavki!"); ?>
					</td>
				</tr>
			<?php }else{ ?>
				<?php echo $this->Form->create('FmFeTransactionEntry'); ?>
				<?php foreach ($fe_transaction_entries as $entry): ?>
					<tr class="nowrap" id="entry_<?php echo $entry['FmFeTransactionEntry']['id']; ?>">
						<td class="center"><?php echo $entry['FmChartAccount']['code']; ?></td>
						<td class="center"><?php echo $entry['Currency']['iso']; ?></td>
						<td class="center"><?php echo $entry['PrimaryDocumentType']['code']; ?> - <?php echo $entry['PrimaryDocumentType']['name']; ?></td>
						<td class="center"><?php echo $entry['FmAccountOrderRecord']['primary_document_code']; ?></td>
						<td class="center"><?php echo $entry['FmTrafficStatus']['code']; ?></td>
						<td colspan="18">&nbsp;</td>
					</tr>
				<?php endforeach; ?>
				<tr>
					<td colspan="14" class="right">
						<?php 
							echo $this->Form->submit(__('Učitaj izveštaj'), array(
								'div' => false,
								'class' => "button green"
							));
						?>
						<?php echo $this->Form->end(); ?>
						<?php echo $this->Html->link(__('Nazad na pregled'), array('controller' => 'FmFeTransactionEntries', 'action' => 'load_entries', $fe_transaction['FmFeTransaction']['id']), array('class' => 'button')); ?>
					</td>
				</tr>		
			<?php } ?>
		</tbody>
	</table>
	<!-- 
	<table class="records">
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
					<td class="center"><?php echo $entry['PrimaryDocumentType']['code']; ?> - <?php echo $entry['PrimaryDocumentType']['name']; ?></td>
					<td class="center"><?php echo $entry['FmAccountOrderRecord']['primary_document_code']; ?></td>
					<td class="right">
						<?php echo $this->Form->input('foreign_transaction_value_'.$entry['FmFeTransactionEntry']['id'], array('label' => false, 'div' => false, 'class' => 'positive-decimal', 'id' => 'foreign_transaction_value_'.$entry['FmFeTransactionEntry']['id'], 'autocomplete' => 'off')); ?>
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
					<td class="invoice_exchange_diff right">
						<?php echo $this->Form->input('invoice_exchange_diff_'.$entry['FmFeTransactionEntry']['id'], array('label' => false, 'div' => false, 'class' => 'right number_input', 'readonly' => true, 'id' => 'invoice_exchange_diff_'.$entry['FmFeTransactionEntry']['id'])); ?>
					</td>
				</tr>
				<?php endforeach; ?>
				<tr class="nowrap marker">
					<td colspan="4" class="right bold"><?php echo __("Ukupno"); ?></td>
					<td class="right sum_foreign_transaction_value">&nbsp;</td>
					<td class="right sum_domestic_value_exchange">&nbsp;</td>
					<td class="right sum_domestic_value_invoice">&nbsp;</td>
					<td class="right sum_exchange_diff">&nbsp;</td>
					<td class="center">&nbsp;</td>
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
						<?php echo $this->Html->link(__('Nazad na pregled'), array('controller' => 'FmFeTransactionEntries', 'action' => 'load_entries', $fe_transaction['FmFeTransaction']['id']), array('class' => 'button')); ?>
					</td>
				</tr>
			<?php } ?>
		</tbody>		
	</table> -->		
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
	var sum_domestic_value_exchange = 0;
	var sum_domestic_value_invoice = 0;
	var sum_exchange_diff = 0;
	var sum_invoice_exchange_diff = 0;

	for (var i = 0, l = fe_transaction_entries.length; i < l; i++) {
		//Set entry
	    var entry = fe_transaction_entries[i];

	    //Calculate sums
	    sum_foreign_transaction_value += entry.foreign_transaction_value || 0;
	    sum_domestic_value_exchange += entry.domestic_value_exchange || 0;
	    sum_domestic_value_invoice += entry.domestic_value_invoice || 0;
	    sum_exchange_diff += entry.exchange_diff || 0;
	    sum_invoice_exchange_diff += entry.invoice_exchange_diff || 0;
	}

	//Set sum foreign transaction value
	if(sum_foreign_transaction_value != 0){
		$("td.sum_foreign_transaction_value").text(number_format(sum_foreign_transaction_value, 2, '.', ','));
	}else{
		$("td.sum_foreign_transaction_value").text('');
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
		entry.fe_basic_exchange_rate = parseFloat(fe_transaction.FmFeBasic.exchange_rate) || 0;
		entry.foreign_exchange_rate = parseFloat(entry.FmFeTransactionEntry.foreign_exchange_rate) || 0;		

		//Calculate domestic value exchange
		entry.domestic_value_exchange = roundNumber(entry.foreign_transaction_value * entry.fe_basic_exchange_rate, 3);
		if(entry.domestic_value_exchange != 0){
			$("#domestic_value_exchange_"+entry_id).val(entry.domestic_value_exchange);
		}else{
			$("#domestic_value_exchange_"+entry_id).val('');
		}

		//Calculate domestic value invoice
		entry.domestic_value_invoice = roundNumber(entry.foreign_transaction_value * entry.foreign_exchange_rate, 3);
		if(entry.domestic_value_invoice != 0){
			$("#domestic_value_invoice_"+entry_id).val(entry.domestic_value_invoice);
		}else{
			$("#domestic_value_invoice_"+entry_id).val('');
		}

		//Calculate exchange difference
		entry.exchange_diff = roundNumber(entry.domestic_value_exchange - entry.domestic_value_invoice, 3);
		if(entry.exchange_diff != 0){
			$("#exchange_diff_"+entry_id).val(entry.exchange_diff);
		}else{
			$("#exchange_diff_"+entry_id).val('');
		}		

		//Calculate total diff
		entry.invoice_exchange_diff = roundNumber(entry.FmFeTransactionEntry.foreign_paid - entry.foreign_transaction_value, 2);

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
		    var paid = entry.FmFeTransactionEntry.foreign_paid;
		    $("#foreign_transaction_value_"+entry.FmFeTransactionEntry.id).val(paid);
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
		calculateColumns(id);
		updateTotals();
	});

	//Refresh columns based on db data
	refreshColumns();
});
</script>