<?php echo $this->Html->script('jquery.numeric.js'); ?>
<?php echo $this->Html->script('Script/Helpers/numbers.js'); ?>
<?php echo $this->element('../FmFeTransactions/index'); ?>
<div class="content_data" id="transaction_form" style="width:100%; margin:0; background-color: #f2f2f2; border:1px solid #ccc; padding:0 20px 14px 20px;">
	<div style="float:left;">
		<?php if(empty($this->request->data['FmFeTransaction']['id'])){ ?>
			<h6><i class="icon-plus-sign"></i> <?php echo __("Kreiranje transakcije"); ?></h6>
		<?php }else{ ?>
			<h6><i class="icon-edit"></i> <?php echo __("Izmena transakcija br."); ?> <?php echo $this->request->data['FmFeTransaction']['ordinal']; ?></h6>
		<?php } ?>
	</div>
	<div style="float:right; margin-top:14px;">
        <?php 
        	echo $this->Js->link('<i class="icon-remove" style="color:red;"></i>',
                array('controller' => 'FmFeTransactions', 'action' => 'index', $fe_basic['FmFeBasic']['id']),
                array('update' => '#transactions', 'buffer' => false, 'htmlAttributes' => array('escape' => false))
            ); 
        ?>            
	</div>
	<div class="clear"></div>
	<?php echo $this->Form->create('FmFeTransaction'); ?>
	<div class="content_text_input" style="float:left; width:100px; margin-right: 5px;">
		<?php echo $this->Form->label('flow_type', __('Priliv/Odliv').' <span class="red">*</span>'); ?>
		<?php echo $this->Form->input('flow_type', array('label' => false, 'div' => false, 'error' => false, 'options' => $flow_types, 'class' => 'dropdown', 'required' => false, 'style' => 'width:100%;')); ?>
	</div>	
	<div class="content_text_input" style="float:left; width:180px; margin-right: 5px;">
		<?php echo $this->Form->label('payer_recipient', __('Vrsta isplatioca/Primaoca').' <span class="red">*</span>'); ?>
		<?php echo $this->Form->input('payer_recipient', array('label' => false, 'div' => false, 'error' => false, 'options' => $payer_recipients, 'class' => 'dropdown', 'required' => false, 'style' => 'width:100%;')); ?>
	</div>	
	<div class="content_text_input" style="float:left; width:332px; margin-right: 5px;">
		<?php echo $this->Form->label('client_id', __('Komitent').' <span class="red">*</span>'); ?>
		<?php echo $this->Form->input('client_id', array('type' => 'text', 'label' => false, 'div' => false, 'error' => false, 'required' => false, 'style' => 'width:100%;')); ?>
		<?php echo $this->Form->input('client_title', array('type' => 'hidden')); ?>
	</div>		
	<div class="content_text_input" style="float:left; width:280px; margin-right: 5px;">
		<?php $transaction_types = $transaction_links[$this->request->data['FmFeTransaction']['payer_recipient']]; ?>
		<?php echo $this->Form->label('transaction_type', __('Vrsta transakcije').' <span class="red">*</span>'); ?>
		<?php echo $this->Form->input('transaction_type', array('label' => false, 'div' => false, 'error' => false, 'options' => $transaction_types, 'class' => 'dropdown', 'style' => 'width:100%;', 'required' => false)); ?>
	</div>			
	<div class="content_text_input" style="float:left; width:100px; margin-right: 5px;">
		<?php echo $this->Form->label('transaction_value', __('Devizna vr.').' <span class="red">*</span>'); ?>
		<?php echo $this->Form->input('transaction_value', array('type' => 'text', 'label' => false, 'div' => false, 'error' => false, 'class' => 'inputborder positive-decimal', 'style' => 'width:100%;', 'required' => false, 'autocomplete'=> 'off', 'placeholder' => __('Unesite saldo'))); ?>
	</div>	
	<div class="content_text_input" style="float:left; width:100px;">
		<?php echo $this->Form->label('transaction_value_rsd', __('Dinarska vr.').' <span class="red">*</span>'); ?>
		<?php echo $this->Form->input('transaction_value_rsd', array('type' => 'text', 'label' => false, 'div' => false, 'error' => false, 'class' => 'inputborder positive-decimal', 'style' => 'width:100%;', 'required' => false, 'autocomplete'=> 'off', 'readonly' => true)); ?>
	</div>
	<div id='alert' style="float:left; width:960px;">
	    <?php echo $this->Session->flash(); ?>  
	</div>		
	<div style="float:right; margin: 14px 0 0 0;">
        <?php echo $this->Js->submit('Snimi transakciju', array(
                'update' => '#transactions',
                'div' => false,
                'class' => "button green",
                'buffer' => false,
            ));
        ?>
	</div>
	<div class="clear"></div>
</div> 
<div class="clear"></div>
<script type="text/javascript">
<?php echo "var transaction_links = ". json_encode($transaction_links) . ";\n"; ?>
<?php echo "var fe_basic = ". json_encode($fe_basic) . ";\n"; ?>
var payer_recipient = '';

/* Load transaction types based on payer recipient */
function loadTransactionTypes(payer_recipient){	
	if(payer_recipient){
		if(transaction_links.hasOwnProperty(payer_recipient)){
			//Set type list
		    var type_list = transaction_links[payer_recipient];

		    //Clear current select list
	        $('#FmFeTransactionTransactionType option').remove();
			$("#FmFeTransactionTransactionType").off("select2-selecting");
			$("#FmFeTransactionTransactionType").select2();

		    //Bulid new select based on links
		    var first_link = null;
			for (var link in type_list) {
				if(!first_link){
					first_link = link;
				}
				if (type_list.hasOwnProperty(link)) {
					var link_title = type_list[link];					
					$('#FmFeTransactionTransactionType').append($("<option></option>").attr("value",link).text(link_title));
				}							    
			}
			//Set first link
			$("#FmFeTransactionTransactionType").select2("val", first_link);
		}
	}
}//~!

/* Calculate domestic transaction value based on exchange rate*/
function calculateDomestic(transaction_value){
	if(transaction_value){
		var rsd_value = parseFloat(fe_basic.FmFeBasic.exchange_rate) * transaction_value;
		$("#FmFeTransactionTransactionValueRsd").val(roundNumber(rsd_value, 2));
	}else{
		$("#FmFeTransactionTransactionValueRsd").val("");
	}
}//~!

//Init app on div load
$('#transactions').ready(function(){
	//Init libraries
    $(".dropdown").select2({ allowClear: true });
	$(".positive-decimal").numeric({ decimal: '.', negative: false, decimalPlaces: 2 }, function() { this.value = ""; this.focus(); });

	// Client search
	$('#FmFeTransactionClientId').select2({
	    minimumInputLength: 2,
	    placeholder: "<?php echo __('(Odaberite komitenta)'); ?>",
	    allowClear: true,
	    query: function(query){
	        var process = {results: []};
	        $.ajax({
	            dataType: 'json',
	            type: 'POST',
	            evalScripts: true,
	            data: ({term: query.term}),
	            url: '/Clients/getClientsBySearch',
	            success: function(data){
	                var index;
	                for (var index = 0; index < data.length; ++index) {
	                    process.results.push({id: data[index].Client.id, text: data[index].Client.code + ' - ' + data[index].Client.title });
	                };
	                query.callback(process);
	            },
	            error:function(xhr){
	            	//Show message
	                var error_msg = 'Error: ' + xhr.status + ' ' + xhr.statusText;		            
					$.ambiance({
						timeout: 10,
					    message: error_msg,
					    type: "error"
					});
	            }
	        });
	    },
		initSelection : function (element, callback) {
			var data = {id: $("#FmFeTransactionClientId").val(), text: $("#FmFeTransactionClientTitle").val()};
	        callback(data);
	    }
	});

    //Fill-up form based on client selection
    $("#FmFeTransactionClientId").on("select2-selecting", function(e) {
        //Set client title value
        $("#FmFeTransactionClientTitle").val(e.object.text);
    });

	//Set selected payer recipient
	selected_payer_recipient = $("#FmFeTransactionPayerRecipient").val();

	//On payer recipient change set new transaction types
	$("#FmFeTransactionPayerRecipient").on("select2-selecting", function(e) { 
		var selected_payer_recipient = e.val;
		
		//If payer/recipient different load new new transaction types
		if(selected_payer_recipient != payer_recipient){
			loadTransactionTypes(selected_payer_recipient);
		}
	});

	//On transaction value entering calculate domestic value
	$("#FmFeTransactionTransactionValue").on("keyup", function(e) { 
		var transaction_value = parseFloat($(this).val());
		calculateDomestic(transaction_value);
	});

	//On transaction value change calculate domestic value
	$("#FmFeTransactionTransactionValue").on("change", function(e) { 
		var transaction_value = parseFloat($(this).val());
		calculateDomestic(transaction_value);
	});		
});
</script>