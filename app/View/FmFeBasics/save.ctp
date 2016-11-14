<?php echo $this->Html->script('jquery.numeric.js'); ?>
<ul class="breadcrumbs">
	<li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
	<li><?php echo $this->Html->link(__('Finansijsko knjigovodstvo'), array('controller' => 'ErpModules', 'action' => 'start', 'financial')); ?></li>
	<li><?php echo $this->Html->link(__('Devizni izvodi banke'), array('controller' => 'FmFeBasics', 'action' => 'index')); ?></li>
	<li class="last"><a href="" onclick="return false"><?php echo __('Snimanje'); ?></a></li>
</ul>

<div class="name_add_search">
	<div class="name_of_page">
		<?php if(empty($this->request->data['FmFeBasic']['id'])){ ?>
			<h3><i class="icon-plus-sign"></i> <?php echo __('Osnovni podaci o izvodu'); ?></h3>
		<?php }else{ ?>
			<h3><i class="icon-edit"></i> <?php echo __('Osnovni podaci o izvodu'); ?></h3>
		<?php } ?>
	</div>
</div>
<div id='alert'><?php echo $this->Session->flash(); ?></div>
<div class="content_data" style="width:370px; margin-top:0;">	
	<?php echo $this->Form->create('FmFeBasic'); ?>
	<div class="content_text_input">
		<?php echo $this->Form->label('fm_business_account_id', __('Poslovni račun').' <span class="red">*</span>'); ?>
		<?php echo $this->Form->input('fm_business_account_id', array('label' => false, 'div' => false, 'options' => $accounts, 'class' => 'dropdown col_12', 'required' => false, 'empty' => __('Odaberite banku'))); ?>
	</div>
	<div class="clear"></div>
	<div class="content_text_input">
		<?php echo $this->Form->label('fe_date', __('Datum izvoda').' <span class="red">*</span>'); ?>
		<?php echo $this->Form->input('fe_date', array('type' => 'text', 'label' => false, 'class' => 'col_12 inputborder date', 'required' => false, 'placeholder' => __('Unesite datum izvoda'))); ?>
	</div>
	<div class="clear"></div>
	<div class="content_text_input">
		<?php echo $this->Form->label('exchange_rate', __('Kurs devizne valute').' <span class="red">*</span>'); ?>
		<?php echo $this->Form->input('exchange_rate', array('type' => 'text', 'label' => false, 'class' => 'col_12 inputborder', 'required' => false, 'placeholder' => __('Unesite kurs devizne valute'))); ?>
	</div>
	<div class="clear"></div>	
	<div class="content_text_input">
		<?php echo $this->Form->label('previous_balance_currency', __('Prethodni devizni saldo').' <span class="red">*</span>'); ?>
		<?php echo $this->Form->input('previous_balance_currency', array('type' => 'text', 'label' => false, 'class' => 'col_12 inputborder positive-decimal', 'required' => false, 'placeholder' => __('Unesite devizni saldo'))); ?>
	</div>
	<div class="clear"></div>
	<div class="content_text_input">
		<?php echo $this->Form->label('previous_balance_rsd', __('Prethodni dinarski saldo').' <span class="red">*</span>'); ?>
		<?php echo $this->Form->input('previous_balance_rsd', array('type' => 'text', 'label' => false, 'class' => 'col_12 inputborder positive-decimal', 'required' => false, 'placeholder' => __('Unesite dinarski saldo'))); ?>
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
				<?php echo $this->Html->link(__('Nazad'), array('controller' => 'FmFeBasics', 'action' => 'index'), array('class' => 'button', 'style' => 'margin:20px 0 20px 0;')); ?>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>
<script>
/* Load exchange rate based on business account and exchange date*/
function loadExchangeRate(query_account_id, query_exchange_date){	
	if(query_account_id && query_exchange_date != ''){
	    //Get exchange rate information
	    $.ajax({
	        dataType: "json",
	        type: "POST",
	        evalScripts: true,
	        data: ({ fm_business_account_id: query_account_id, exchange_date: query_exchange_date }),
	        url: "/FmBusinessAccounts/getExchangeRate/",
	        success: function (data){	            
	            //Update exchange rate
	            if(data['exchange_rate']){
	            	$("#FmFeBasicExchangeRate").val(data['exchange_rate']);
	            }
	        },
	        error:function(xhr){
	        	//Set error message
	            var error_msg = "An error occured: " + xhr.status + " " + xhr.statusText;
	            //Show message
				$.ambiance({
					timeout: 10,
				    message: error_msg,
				    type: "error"
				});
	        }
	    });		
	}
}//~!
/* Init app */
$('#container').ready(function(){
	//Init libraries
	$(".submit_loader").hide();
	$(".dropdown").select2();
	$(".date").datepicker({ dateFormat: "yy-mm-dd" });
	$(".positive-decimal").numeric({ decimal: '.', negative: false, decimalPlaces: 2 }, function() { this.value = ""; this.focus(); });

	//On account select load exchange rate
	$("#FmFeBasicFmBusinessAccountId").on("select2-selecting", function(e) { 
		var account_id = e.val;
		var exchange_date = $("#FmFeBasicFeDate").val();

		if(account_id && exchange_date != ''){
			loadExchangeRate(account_id, exchange_date);
		}
	});

	//On date change load exchange rate
	$("#FmFeBasicFeDate").on("change", function(e) { 
		var account_id = $("#FmFeBasicFmBusinessAccountId").val();
		var exchange_date = $("#FmFeBasicFeDate").val();

		if(account_id && exchange_date != ''){
			loadExchangeRate(account_id, exchange_date);
		}
	});	
});
</script>