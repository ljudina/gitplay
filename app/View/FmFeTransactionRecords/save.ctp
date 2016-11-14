<?php echo $this->Html->script('jquery.numeric.js'); ?>
<?php echo $this->Html->script('Script/Helpers/numbers.js'); ?>
<?php echo $this->element('../FmFeTransactionRecords/records'); ?>
<div class="content_data" id="record_form" style="width:1180px; margin:0; background-color: #f2f2f2; border:1px solid #ccc; padding:0 20px 14px 20px;">
	<div style="float:left;">
		<?php if(empty($this->request->data['FmFeTransactionRecord']['id'])){ ?>
			<h6><i class="icon-plus-sign"></i> <?php echo __("Kreiranje stavke"); ?></h6>
		<?php }else{ ?>
			<h6><i class="icon-edit"></i> <?php echo __("Izmena stavke br."); ?> <?php echo $this->request->data['FmFeTransactionRecord']['ordinal']; ?></h6>
		<?php } ?>
	</div>
	<div style="float:right; margin-top:14px;">
        <?php 
        	echo $this->Js->link('<i class="icon-remove" style="color:red;"></i>',
                array('controller' => 'FmFeTransactionRecords', 'action' => 'index', $transaction['FmFeTransaction']['id']),
                array('update' => '#records', 'buffer' => false, 'htmlAttributes' => array('escape' => false))
            ); 
        ?>            
	</div>
	<div class="clear"></div>
	<?php echo $this->Form->create('FmFeTransactionRecord'); ?>
	<div style="margin-top:15px;">
		<div class="content_text_input" style="float:left; width:100px; margin-right: 5px;">
			<?php echo $this->Form->label('fm_chart_account_id', __('Šifra konta').' <span class="red">*</span>'); ?>
			<?php echo $this->Form->input('fm_chart_account_id', array('label' => false, 'div' => false, 'error' => false, 'options' => $chart_accounts, 'empty' => array('Odaberite'), 'class' => 'dropdown', 'required' => false, 'style' => 'width:100%;')); ?>
		</div>
		<div class="content_text_input" style="float:left; width:190px; margin-right: 5px;">
			<?php echo $this->Form->label('data_id', __('Šifra analitike').' <span class="red">*</span>'); ?>
			<?php echo $this->Form->input('data_id', array('type' => 'hidden', 'label' => false, 'div' => false, 'error' => false, 'required' => false, 'style' => 'width:100%;')); ?>
			<?php echo $this->Form->input('data_title', array('type' => 'hidden', 'label' => false, 'div' => false, 'error' => false, 'required' => false, 'style' => 'width:100%;')); ?>
		</div>
		<div class="content_text_input" style="float:left; width:120px; margin-right: 5px;">
			<?php echo $this->Form->label('codebook_document_type_id', __('Vrsta dokumenta').' <span class="red">*</span>'); ?>
			<?php echo $this->Form->input('codebook_document_type_id', array('label' => false, 'div' => false, 'error' => false, 'options' => $codebook_document_types, 'empty' => array('Odaberite'), 'class' => 'dropdown', 'required' => false, 'style' => 'width:100%;')); ?>
		</div>
		<div class="content_text_input" style="float:left; width:80px; margin-right: 5px;">
			<?php echo $this->Form->label('codebook_document_code', __('Broj dok.').' <span class="red">*</span>'); ?>
			<?php echo $this->Form->input('codebook_document_code', array('label' => false, 'div' => false, 'error' => false, 'required' => false, 'style' => 'width:100%;')); ?>
		</div>
		<div class="content_text_input" style="float:left; width:120px; margin-right: 5px;">
			<?php echo $this->Form->label('transaction_desc', __('Opis transakcije').' <span class="red">*</span>'); ?>
			<?php echo $this->Form->input('transaction_desc', array('type' => 'text', 'label' => false, 'div' => false, 'error' => false, 'required' => false, 'style' => 'width:100%;', 'class' => 'disabled','readonly' => $transaction_desc_read_only)); ?>
		</div>
		<div class="content_text_input" style="float:left; width:140px; margin-right: 5px;">
			<?php echo $this->Form->label('primary_document_type_id', __('Vrsta primarne veze').' <span class="red">*</span>'); ?>
			<?php echo $this->Form->input('primary_document_type_id', array('label' => false, 'div' => false, 'error' => false, 'options' => $codebook_document_types, 'empty' => array('Odaberite'), 'class' => 'dropdown', 'required' => false, 'style' => 'width:100%;')); ?>
		</div>
		<div class="content_text_input" style="float:left; width:90px; margin-right: 5px;">
			<?php echo $this->Form->label('primary_document_code', __('Broj veze').' <span class="red">*</span>'); ?>
			<?php echo $this->Form->input('primary_document_code', array('label' => false, 'div' => false, 'error' => false, 'required' => false, 'style' => 'width:100%;')); ?>
		</div>
		<div class="content_text_input" style="float:left; width:140px; margin-right: 5px;">
			<?php echo $this->Form->label('secondary_document_type_id', __('Vrsta sek. veze').' <span class="red">*</span>'); ?>
			<?php echo $this->Form->input('secondary_document_type_id', array('label' => false, 'div' => false, 'error' => false, 'options' => $codebook_document_types, 'empty' => array('Odaberite'), 'class' => 'dropdown', 'required' => false, 'style' => 'width:100%;')); ?>
		</div>
		<div class="content_text_input" style="float:left; width:90px; margin-right: 5px;">
			<?php echo $this->Form->label('secondary_document_code', __('Broj veze').' <span class="red">*</span>'); ?>
			<?php echo $this->Form->input('secondary_document_code', array('label' => false, 'div' => false, 'error' => false, 'required' => false, 'style' => 'width:100%;')); ?>
		</div>
		<div class="clear"></div>
	</div>
	<div style="margin-top:15px;">
		<div class="content_text_input" style="float:left; width:180px; margin-right: 5px;">
			<?php echo $this->Form->label('fm_traffic_status_id', __('Šifra klasifikacije')); ?>
			<?php echo $this->Form->input('fm_traffic_status_id', array('label' => false, 'div' => false, 'error' => false, 'options' => $traffic_statuses, 'empty' => array('Odaberite klasifikaciju'), 'class' => 'dropdown', 'required' => false, 'style' => 'width:100%;')); ?>
		</div>	
		<div class="content_text_input" style="float:left; width:130px; margin-right: 5px;">
		<?php echo $this->Form->label('foreign_debit', __('Devizna duguje')); ?>
		<?php echo $this->Form->input('foreign_debit', array('type' => 'text', 'label' => false, 'div' => false, 'error' => false, 'class' => 'positive-decimal', 'style' => 'width:100%;', 'required' => false, 'autocomplete'=> 'off')); ?>
		</div>
		<div class="content_text_input" style="float:left; width:130px; margin-right: 5px;">
			<?php echo $this->Form->label('foreign_credit', __('Devizna potražuje')); ?>
			<?php echo $this->Form->input('foreign_credit', array('type' => 'text', 'label' => false, 'div' => false, 'error' => false, 'class' => 'positive-decimal', 'style' => 'width:100%;', 'required' => false, 'autocomplete'=> 'off')); ?>
		</div>
		<div class="content_text_input" style="float:left; width:130px; margin-right: 5px;">
			<?php echo $this->Form->label('exchange_rate_date', __('Datum kursa')); ?>
			<?php echo $this->Form->input('exchange_rate_date', array('type' => 'text', 'label' => false, 'div' => false, 'error' => false, 'class' => 'date', 'style' => 'width:100%;', 'required' => false, 'autocomplete'=> 'off')); ?>
		</div>
		<div class="content_text_input" style="float:left; width:130px; margin-right: 5px;">
			<?php echo $this->Form->label('exchange_rate', __('Devizni kurs')); ?>
			<?php echo $this->Form->input('exchange_rate', array('type' => 'text', 'label' => false, 'div' => false, 'error' => false, 'class' => 'positive-decimal', 'style' => 'width:100%;', 'required' => false, 'autocomplete'=> 'off')); ?>
		</div>
		<div class="content_text_input" style="float:left; width:130px; margin-right: 5px;">
			<?php echo $this->Form->label('domestic_debit', __('RSD duguje')); ?>
			<?php echo $this->Form->input('domestic_debit', array('type' => 'text', 'label' => false, 'div' => false, 'error' => false, 'class' => 'positive-decimal', 'style' => 'width:100%;', 'required' => false, 'autocomplete'=> 'off')); ?>
		</div>
		<div class="content_text_input" style="float:left; width:130px; margin-right: 5px;">
			<?php echo $this->Form->label('domestic_credit', __('RSD potražuje')); ?>
			<?php echo $this->Form->input('domestic_credit', array('type' => 'text', 'label' => false, 'div' => false, 'error' => false, 'class' => 'positive-decimal', 'style' => 'width:100%;', 'required' => false, 'autocomplete'=> 'off')); ?>
		</div>
		<div style="float:right; margin: 24px 0 0 0;">
	        <?php echo $this->Js->submit('Snimi stavku', array(
	                'update' => '#records',
	                'div' => false,
	                'class' => "button green small",
	                'buffer' => false,
	            ));
	        ?>
		</div>		
		<div class="clear"></div>
	</div>	
	<div id='alert' style="float:left; width:960px;">
	    <?php echo $this->Session->flash(); ?>  
	</div>		
	<div class="clear"></div>
</div> 
<div class="clear"></div>
<script type="text/javascript">
<?php echo "var transaction = ". json_encode($transaction) . ";\n"; ?>
<?php echo "var basic = ". json_encode($basic) . ";\n"; ?>
/* Load exchange rate based on date */
function loadExchangeRate(){
	var query_exchange_date = $("#FmFeTransactionRecordExchangeRateDate").val() || "";
	var query_currency_id = basic.FmBusinessAccount.currency_id || 0;

	if(query_exchange_date && query_currency_id){
	    //Get exchange rate information
	    $.ajax({
	        dataType: "json",
	        type: "POST",
	        evalScripts: true,
	        data: ({ currency_id: query_currency_id, exhange_date: query_exchange_date }),
	        url: "/ExchangeRates/getExchangeRateById/",
	        success: function (data){          
	            //Update exchange rate
	            if(data['exchange_rate']){
	            	$("#FmFeTransactionRecordExchangeRate").val(data['exchange_rate']);
	            	calculateDomestic();
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
/* Recalculate domestic values */
function calculateDomestic(){
	var foreign_debit = parseFloat($("#FmFeTransactionRecordForeignDebit").val()) || 0;
	var foreign_credit = parseFloat($("#FmFeTransactionRecordForeignCredit").val()) || 0;
	var exchange_rate = parseFloat($("#FmFeTransactionRecordExchangeRate").val()) || 0;

	//Check for foreign debit
	if(foreign_debit && exchange_rate){
		domestic_debit = roundNumber(foreign_debit * exchange_rate, 3);
		$("#FmFeTransactionRecordDomesticDebit").val(domestic_debit);
	}

	//Check for foreign credit
	if(foreign_credit && exchange_rate){
		domestic_credit = roundNumber(foreign_credit * exchange_rate, 3);
		$("#FmFeTransactionRecordDomesticCredit").val(domestic_credit);
	}
}//~!
/* Enable/Disable domestic/credit fields */
function disableFields(field){
	if(field){		
		if(field == 'debit' || field == 'none'){
			//Enable credit
			$('#FmFeTransactionRecordForeignCredit').prop('readonly', false);
			if($('#FmFeTransactionRecordForeignCredit').hasClass('disabled')){
				$('#FmFeTransactionRecordForeignCredit').removeClass('disabled');
			}
			$('#FmFeTransactionRecordDomesticCredit').prop('readonly', false);
			if($('#FmFeTransactionRecordDomesticCredit').hasClass('disabled')){
				$('#FmFeTransactionRecordDomesticCredit').removeClass('disabled');
			}
			if(field == 'debit'){
				//Reset debit
				$('#FmFeTransactionRecordForeignDebit').val("");
				$('#FmFeTransactionRecordDomesticDebit').val("");

				//Disable debit
				$('#FmFeTransactionRecordForeignDebit').prop('readonly', true);
				if(!$('#FmFeTransactionRecordForeignDebit').hasClass('disabled')){
					$('#FmFeTransactionRecordForeignDebit').addClass('disabled');
				}
				$('#FmFeTransactionRecordDomesticDebit').prop('readonly', true);
				if(!$('#FmFeTransactionRecordDomesticDebit').hasClass('disabled')){
					$('#FmFeTransactionRecordDomesticDebit').addClass('disabled');
				}
			}
		}
		if(field == 'credit' || field == 'none'){
			//Enable debit
			$('#FmFeTransactionRecordForeignDebit').prop('readonly', false);
			if($('#FmFeTransactionRecordForeignDebit').hasClass('disabled')){
				$('#FmFeTransactionRecordForeignDebit').removeClass('disabled');
			}
			$('#FmFeTransactionRecordDomesticDebit').prop('readonly', false);
			if($('#FmFeTransactionRecordDomesticDebit').hasClass('disabled')){
				$('#FmFeTransactionRecordDomesticDebit').removeClass('disabled');
			}
			if(field == 'credit'){
				//Reset credit
				$('#FmFeTransactionRecordForeignCredit').val("");
				$('#FmFeTransactionRecordDomesticCredit').val("");

				//Disable credit
				$('#FmFeTransactionRecordForeignCredit').prop('readonly', true);
				if(!$('#FmFeTransactionRecordForeignCredit').hasClass('disabled')){
					$('#FmFeTransactionRecordForeignCredit').addClass('disabled');
				}
				$('#FmFeTransactionRecordDomesticCredit').prop('readonly', true);
				if(!$('#FmFeTransactionRecordDomesticCredit').hasClass('disabled')){
					$('#FmFeTransactionRecordDomesticCredit').addClass('disabled');
				}	
			}		
		}		
	}
}//~!

/* Check debit/credit fields for enabling/disabling */
function checkFields(){
	var foreign_debit = $("#FmFeTransactionRecordForeignDebit").val();
	if(foreign_debit){
		disableFields('credit');
	}
	var foreign_credit = $("#FmFeTransactionRecordForeignCredit").val();
	if(foreign_credit){
		disableFields('debit');
	}	
}//~!

//Init app on div load
$('#records').ready(function(){
	//Init libraries
    $(".dropdown").select2({ allowClear: true });
	$(".positive-decimal").numeric({ decimal: '.', negative: false, decimalPlaces: 2 }, function() { this.value = ""; this.focus(); });
	$(".date").datepicker({ dateFormat: "yy-mm-dd" });

	//Init data loader
    $("#FmFeTransactionRecordDataId").select2({
        minimumInputLength: 0,
        placeholder: "(Odaberite analitiku)",
        allowClear: true,
        query: function (query) {
            //Set init search data
            var process = {results: []};
            var query_chart_account_id = $("#FmFeTransactionRecordFmChartAccountId").val();                    
            //Call search
            $.ajax({
                dataType: "json",
                type: "POST",
                evalScripts: true,
                data: ({ term: query.term, chart_account_id: query_chart_account_id }),
                url: '/FmChartAccounts/searchConnectionData/',
                success: function (data){
                    if(data){
                    	if(data[0]){
                        	for (var key in data) {
                        	  var bank = data[key];
	                          process.results.push({id: bank.Data.id, text: bank.Data.code + ' - ' + bank.Data.name });
	                        }
                    	}else{
	                        for (var key in data) {
	                          if (data.hasOwnProperty(key)) {
	                            process.results.push({id: key, text: data[key] });
	                          }
	                        }
                    	}
                    }
                    query.callback(process);

                    //Check data selection
                    $("#FmFeTransactionRecordDataId").on("select2-selecting", function(e) {
                        $("#FmFeTransactionRecordDataTitle").val(e.object.text);
                    });                        
                },
                error:function(xhr){
                    var error_msg = "An error occured: " + xhr.status + " " + xhr.statusText;
                    alert(error_msg);
                    $(".submit_loader").hide();
                }
            });
        },
        initSelection : function (element, callback) {
            var data = { id: $("#FmFeTransactionRecordDataId").val(), text: $("#FmFeTransactionRecordDataTitle").val() };
            callback(data);
        }
    });
	
	//Set events for monitoring changes on value fields
	$("#FmFeTransactionRecordForeignDebit").on("keyup", function(e) {		
		calculateDomestic();
		var value = $("#FmFeTransactionRecordForeignDebit").val();
		if(value){
			disableFields('credit');
		}else{
			disableFields('none');
		}
	});
	$("#FmFeTransactionRecordForeignDebit").on("change", function(e) {
		calculateDomestic();
		var value = $("#FmFeTransactionRecordForeignDebit").val();
		if(value){
			disableFields('credit');
		}else{
			disableFields('none');
		}		
	});
	$("#FmFeTransactionRecordForeignCredit").on("keyup", function(e) {
		calculateDomestic();
		var value = $("#FmFeTransactionRecordForeignCredit").val();
		if(value){
			disableFields('debit');
		}else{
			disableFields('none');
		}
	});
	$("#FmFeTransactionRecordForeignCredit").on("change", function(e) {
		calculateDomestic();
		var value = $("#FmFeTransactionRecordForeignCredit").val();
		if(value){
			disableFields('debit');
		}else{
			disableFields('none');
		}
	});
	$("#FmFeTransactionRecordExchangeRateDate").on("change", function(e) {
		loadExchangeRate();		
	});
	$("#FmFeTransactionRecordExchangeRate").on("keyup", function(e) {
		calculateDomestic();
	});
	$("#FmFeTransactionRecordExchangeRate").on("change", function(e) {
		calculateDomestic();
	});

	//Reset analytics on chart account change
	$("#FmFeTransactionRecordFmChartAccountId").on("change", function(e) {
		$("#FmFeTransactionRecordDataId").select2('val', null);
	});

	//Check fields on load
	checkFields();
});
</script>