<ul class="breadcrumbs">
	<li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
	<li><?php echo $this->Html->link(__('Finansijsko knjigovodstvo'), array('controller' => 'ErpModules', 'action' => 'start', 'financial')); ?></li>
	<li><?php echo $this->Html->link(__('Šifarnik deviznih transakcija'), array('controller' => 'FmFeTransactionTypes', 'action' => 'index')); ?></li>
	<li class="last"><a href="" onclick="return false"><?php echo __('Snimanje'); ?></a></li>
</ul>

<div class="name_add_search">
	<div class="name_of_page">
		<?php if(empty($this->request->data['FmFeTransactionType']['id'])){ ?>
			<h3><i class="icon-plus-sign"></i> <?php echo __('Dodavanje devizne transakcije'); ?></h3>
		<?php }else{ ?>
			<h3><i class="icon-edit"></i> <?php echo __('Izmena devizne transakcije'); ?></h3>
		<?php } ?>
	</div>
</div>
<div id='alert' style="width:600px;"><?php echo $this->Session->flash(); ?></div>
<div class="content_data" style="width:540px; margin-top:0;">	
	<?php echo $this->Form->create('FmFeTransactionType'); ?>
	<div class="content_text_input">
		<?php echo $this->Form->label('payer_recipient', __('Isplatilac/Primalac').' <span class="red">*</span>'); ?>
		<?php echo $this->Form->input('payer_recipient', array('label' => false, 'div' => false, 'options' => $payer_recipients, 'class' => 'dropdown col_12', 'required' => false)); ?>
	</div>
	<div class="clear"></div>
	<div class="content_text_input">
		<?php $transaction_types = $transaction_links[$this->request->data['FmFeTransactionType']['payer_recipient']]; ?>
		<?php echo $this->Form->label('transaction_type', __('Vrsta transakcije').' <span class="red">*</span>'); ?>
		<?php echo $this->Form->input('transaction_type', array('label' => false, 'div' => false, 'options' => $transaction_types, 'class' => 'dropdown col_12', 'required' => false)); ?>
	</div>
	<div class="clear"></div>
	<div class="content_text_input">
		<?php echo $this->Form->label('fm_chart_account_links', __('Veza sa konto-karticom')); ?>
		<?php echo $this->Form->input('fm_chart_account_links', array('label' => false, 'div' => false, 'multiple' => true, 'options' => $chart_accounts, 'class' => 'dropdown col_12', 'required' => false, 'placeholder' => __('Odaberite vezu sa konto-karticom'))); ?>
	</div>
	<div class="clear"></div>	
	<div class="content_text_input">
		<?php echo $this->Form->label('desc_data', __('Podatak za polje: Opis')); ?>
		<?php echo $this->Form->input('desc_data', array('type' => 'text', 'label' => false, 'class' => 'col_12', 'required' => false, 'placeholder' => __('Unesite podatak za polje opis'))); ?>
	</div>
	<div class="clear"></div>
	<div class="content_text_input">
		<?php echo $this->Form->label('fm_fe_account_scheme_id', __('Broj šeme za knjiženje')); ?>
		<?php echo $this->Form->input('fm_fe_account_scheme_id', array('label' => false, 'div' => false, 'options' => $account_schemes, 'class' => 'dropdown col_12', 'required' => false, 'empty' => __('Odaberite broj šeme za knjiženje'))); ?>
	</div>
	<div class="clear"></div>
	<div class="content_text_input">
		<div class="buttons_box">
			<div class="button_box">
			<?php echo $this->Form->submit(__('Snimi'), array(
					'div' => false,
					'class' => "button blue",
					'style' => "margin:20px 0 20px 0;"
				));?>
			</div>
			<div class="button_box">
				<?php echo $this->Html->link(__('Nazad'), array('controller' => 'FmBusinessAccounts', 'action' => 'index'), array('class' => 'button', 'style' => 'margin:20px 0 20px 0;')); ?>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>
<script>
<?php echo "var transaction_links = ". json_encode($transaction_links) . ";\n"; ?>
var payer_recipient = '';

/* Load transaction types based on payer recipient */
function loadTransactionTypes(payer_recipient){	
	if(payer_recipient){
		if(transaction_links.hasOwnProperty(payer_recipient)){
			//Set type list
		    var type_list = transaction_links[payer_recipient];

		    //Clear current select list
	        $('#FmFeTransactionTypeTransactionType option').remove();
			$("#FmFeTransactionTypeTransactionType").off("select2-selecting");
			$("#FmFeTransactionTypeTransactionType").select2();

		    //Bulid new select based on links
		    var first_link = null;
			for (var link in type_list) {
				if(!first_link){
					first_link = link;
				}
				if (type_list.hasOwnProperty(link)) {
					var link_title = type_list[link];					
					$('#FmFeTransactionTypeTransactionType').append($("<option></option>").attr("value",link).text(link_title));
				}							    
			}
			//Set first link
			$("#FmFeTransactionTypeTransactionType").select2("val", first_link);
		}
	}
}//~!

//Init app on page load
$('#container').ready(function(){
	$(".submit_loader").hide();
	$(".dropdown").select2();

	//Set selected payer recipient
	selected_payer_recipient = $("#FmFeTransactionTypePayerRecipient").val();	

	//On payer recipient change set new transaction types
	$("#FmFeTransactionTypePayerRecipient").on("select2-selecting", function(e) { 
		var selected_payer_recipient = e.val;
		
		//If payer/recipient different load new new transaction types
		if(selected_payer_recipient != payer_recipient){
			loadTransactionTypes(selected_payer_recipient);
		}
	});	
});
</script>