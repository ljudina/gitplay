<style type="text/css">
	tr.error_row td { background-color: #fff2f2; }
</style> 
<ul class="breadcrumbs">
	<li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
	<li><?php echo $this->Html->link(__('Finansijsko knjigovodstvo'), array('controller' => 'ErpModules', 'action' => 'start', 'financial')); ?></li>
	<li><?php echo $this->Html->link(__('Devizni izvodi banke'), array('controller' => 'FmFeBasics', 'action' => 'index')); ?></li>
	<li><?php echo $this->Html->link(__('Šeme knjiženja za automatske naloge'), array('controller' => 'FmFeAccountSchemes', 'action' => 'view', $account_scheme['FmFeAccountScheme']['id'])); ?></li>
	<li class="last"><a href="" onclick="return false"><?php echo __('Snimanje reda'); ?></a></li>
</ul>

<div class="name_add_search">
	<div class="name_of_page">
		<?php if(empty($account_scheme_row)){ ?>
			<h3><i class="icon-plus-sign"></i> <?php echo __('Novi red u šemi knjiženja'); ?></h3>
		<?php }else{ ?>
			<h3><i class="icon-edit"></i> <?php echo __("Red br. ".$account_scheme_row['FmFeAccountSchemeRow']['ordinal']." u šemi knjiženja"); ?></h3>
		<?php } ?>
	</div>
</div>
<div id='alert'><?php echo $this->Session->flash(); ?></div>
<div style="width:1200px; margin:0 0 0 54px;">	
	<?php echo $this->Form->create('FmFeAccountSchemeRow'); ?>
	<div class="content_text_input">
		<?php echo $this->Form->label('conditions', __('Uslov kreiranja reda').' <span class="red">*</span>'); ?>
		<?php echo $this->Form->input('conditions', array('label' => false, 'div' => false, 'options' => $conditions, 'class' => 'dropdown col_12', 'required' => false, 'empty' => __('Odaberite uslov'))); ?>
	</div>
	<div class="clear"></div>
	<div class="content_text_input">
	    <table style="width:100%;">
	        <thead>
	            <tr>
	                <th>&nbsp;</th>
	                <th style="white-space: nowrap;"><?php echo __("Polja u obrascu za knjiženje"); ?> <span class="red">*</span></th>
	                <th><?php echo __("Operacija"); ?> <span class="red">*</span></th>
	                <th><?php echo __("Veza"); ?></th>
	                <th><?php echo __("Vrednost"); ?></th>
	                <th class="center"><?php echo __("Kolona 1"); ?></th>
	                <th class="center"><?php echo __("Kolona 2"); ?></th>
	                <th class="center"><?php echo __("Apsolutna vrednost"); ?></th>
	                <th class="center"><?php echo __("U minusu"); ?></th>
	            </tr>                                        
	        </thead>
	        <tbody>
	            <?php foreach ($document_field_no as $no => $document_field): ?>
	            	<?php if(empty($current_records)){ ?>
	            		<?php 
	            			$disable_connection = true;
	            			$disable_arithmetic_cols = true;
	            		?>
	            	<?php } ?>
	            	<?php 
	            		$row_class = 'standard';
	            		if(!empty($error_rows[$no])){
	            			$row_class = 'error_row';
	            		}
	            	?>
	                <tr id="<?php echo $no; ?>" class="<?php echo $row_class; ?>">
	                    <td class="center"><?php echo $no; ?>.</td>
	                    <td><?php echo $document_fields[$document_field]; ?></td>
	                    <td>
	                    	<?php echo $this->Form->input($document_field.'_operation_used', array('label' => false, 'div' => false, 'options' => $used_operations, 'class' => 'dropdown operation_used', 'style' => 'width:230px;', 'id' => 'operation_used_'.$no)); ?>	                    	
	                    </td>
	                    <td>
	                    	<?php echo $this->Form->input($document_field.'_connection', array('type' => 'hidden', 'label' => false, 'required' => false, 'class' => 'connection', 'style' => 'width:200px;', 'id' => 'connection_'.$no, 'disabled' => $disable_connection)); ?>
							<?php echo $this->Form->input($document_field.'_connection_title', array('type' => 'hidden', 'label' => false, 'required' => false, 'id' => 'connection_title_'.$no)); ?>	                    	
	                    </td>
	                    <td>
	                    	<?php echo $this->Form->input($document_field.'_record_value', array('type' => 'text', 'label' => false, 'div' => false, 'class' => 'record_value', 'style' => 'width:200px;', 'id' => 'record_value_'.$no, 'autocomplete' => 'off')); ?>
	                    	<?php echo $this->Form->input($document_field.'_record_title', array('type' => 'hidden', 'label' => false, 'required' => false, 'id' => 'record_title_'.$no)); ?>	                    	
	                    </td>
	                    <td>
	                    	<?php echo $this->Form->input($document_field.'_arithmetic_col_1', array('type' => 'text', 'label' => false, 'div' => false, 'class' => 'arithmetic_col_1', 'style' => 'width:50px;', 'disabled' => $disable_arithmetic_cols, 'id' => 'arithmetic_col_1_'.$no, 'autocomplete' => 'off')); ?>	                    	
	                    </td>
	                    <td>
	                    	<?php echo $this->Form->input($document_field.'_arithmetic_col_2', array('type' => 'text', 'label' => false, 'div' => false, 'class' => 'arithmetic_col_2', 'style' => 'width:50px;', 'disabled' => $disable_arithmetic_cols, 'id' => 'arithmetic_col_2_'.$no, 'autocomplete' => 'off')); ?>	                    		
	                    </td>
	                    <td class="center">
	                    	<?php echo $this->Form->checkbox($document_field.'_absolute_value', array('type' => 'text', 'label' => false, 'div' => false, 'class' => 'absolute_value', 'style' => 'width:50px; box-shadow: none;', 'id' => 'absolute_value_'.$no, 'autocomplete' => 'off')); ?>
	                    </td>
	                    <td class="center">
	                    	<?php echo $this->Form->checkbox($document_field.'_negative_value', array('type' => 'text', 'label' => false, 'div' => false, 'class' => 'negative_value', 'style' => 'width:50px; box-shadow: none;', 'id' => 'negative_value_'.$no, 'autocomplete' => 'off')); ?>
	                    </td>
	                </tr>
	            <?php endforeach; ?>
	        </tbody>
	    </table>		
	</div>	
	<div class="clear"></div>
	<div class="content_text_input">
		<div class="button_box">
		<?php echo $this->Form->submit(__('Snimi'), array(
				'div' => false,
				'class' => "button blue",
				'style' => "margin:0;"
			));?>
		</div>
		<div class="button_box">
			<?php echo $this->Html->link(__('Nazad'), array('controller' => 'FmFeAccountSchemes', 'action' => 'view', $account_scheme['FmFeAccountScheme']['id']), array('class' => 'button', 'style' => 'margin:0;')); ?>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
	<div class="clear"></div>
</div>
<script>
/* Init default variables */
<?php echo "var total_fields = ". count($document_fields) . ";\n"; ?>

/* Javascript implementation of PHP function in_array */
function in_array(needle, haystack, argStrict){
  var key = '';
  var strict = !!argStrict;

  if (strict) {
    for (key in haystack) {
      if (haystack[key] === needle) {
        return true;
      }
    }
  } else {
    for (key in haystack) {
      if (haystack[key] == needle) { // eslint-disable-line eqeqeq
        return true;
      }
    }
  }

  return false;
}//~!

/* Load record value selector based on codebook connection */
function loadRecordValueSelector(parent_id, query_codebook_connection_id){
	if(parent_id && query_codebook_connection_id){
        //codebook connection ajax loader
        $('#record_value_'+parent_id).select2({
            minimumInputLength: 0,
            placeholder: "(Odaberite šifru veze)",
            allowClear: true,
            query: function (query) {
                //Set init search data
                var process = {results: []};

                //Call search
                $.ajax({
                    dataType: "json",
                    type: "POST",
                    evalScripts: true,
                    data: ({ term: query.term, codebook_connection_id: query_codebook_connection_id }),
                    url: '/CodebookConnections/getConnectionData/',
                    success: function (data){                            
                        if(data){
                            for (var key in data) {
                              if (data.hasOwnProperty(key)) {
                                process.results.push({id: key, text: data[key] });
                              }
                            }
                        }
                        query.callback(process);

                        //Check data selection
                        $('#record_value_'+parent_id).on("select2-selecting", function(e) {
                            $('#record_title_'+parent_id).val(e.object.text);
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
                var data = {id: $('#record_value_'+parent_id).val(), text: $('#record_title_'+parent_id).val() };
                callback(data);
            }
        });
	}
}//~!

/* Load connection column based on connection type */
function loadConnectionColumn(parent_id, connection_type){
	if(connection_type){
		//Enable dropdown
		$('#connection_'+parent_id).off("select2-selecting");
        $('#connection_'+parent_id).attr("disabled", false);

        //codebook connection ajax loader
        $('#connection_'+parent_id).select2({
            minimumInputLength: 0,
            placeholder: "(Odaberite vezu)",
            allowClear: true,
            query: function (query) {
                //Set init search data
                var process = {results: []};
				if(connection_type == 'codebook_connection'){
	                //Call search
	                $.ajax({
	                    dataType: "json",
	                    type: "POST",
	                    evalScripts: true,
	                    data: ({ term: query.term }),
	                    url: '/CodebookConnections/searchCodebookConnections/',
	                    success: function (data){
	                        if(data){
	                            for (var key in data) {
	                              if (data.hasOwnProperty(key)) {
	                                process.results.push({id: key, text: data[key] });
	                              }
	                            }
	                        }
	                        query.callback(process);

	                        //Check data selection
	                        $('#connection_'+parent_id).on("select2-selecting", function(e) {
	                            $('#connection_title_'+parent_id).val(e.object.text);

	                            //Load record value loader	                            
	                            var codebook_connection_id = e.object.id;
	                            setValueColumn(parent_id, true);
	                            loadRecordValueSelector(parent_id, codebook_connection_id);
	                            $('#record_value_'+parent_id).select2("val", "");
	                        });                        
	                    },
	                    error:function(xhr){
	                        var error_msg = "An error occured: " + xhr.status + " " + xhr.statusText;
	                        alert(error_msg);
	                        $(".submit_loader").hide();
	                    }
	                });
				}
				if(connection_type == 'document_link'){
	                //Call search
	                $.ajax({
	                    dataType: "json",
	                    type: "POST",
	                    evalScripts: true,
	                    data: ({ term: query.term }),
	                    url: '/FmFeDocumentLinks/searchDocumentLinks/',
	                    success: function (data){                            
	                        if(data){
	                            for (var key in data) {
	                              if (data.hasOwnProperty(key)) {
	                                process.results.push({id: key, text: data[key] });
	                              }
	                            }
	                        }
	                        query.callback(process);

	                        //Check data selection
	                        $('#connection_'+parent_id).on("select2-selecting", function(e) {
	                            $('#connection_title_'+parent_id).val(e.object.text);
	                        });                        
	                    },
	                    error:function(xhr){
	                        var error_msg = "An error occured: " + xhr.status + " " + xhr.statusText;
	                        alert(error_msg);
	                        $(".submit_loader").hide();
	                    }
	                });
				}				
            },
            initSelection : function (element, callback) {
                var data = {id: $('#connection_'+parent_id).val(), text: $('#connection_title_'+parent_id).val() };
                callback(data);
            }
        });        
        if(connection_type == 'document_link'){
			//Destroy select2 on record field if exists
			$('#record_value_'+parent_id).select2('destroy');
			$('#record_value_'+parent_id).val("");        	
        }
	}
}//~!

/* Disable or enable connection column */
function setConnectionColumn(parent_id, enable){
	if(parent_id){
		//Clear selection
		$('#connection_'+parent_id).select2("val", "");
		if(enable){
			//Enable connection selection			
			$('#connection_'+parent_id).prop('disabled', false);
			if($('#connection_'+parent_id).hasClass('disabled')){
				$('#connection_'+parent_id).removeClass('disabled');
			}			
		}else{			
			//Disable connection selection
			$('#connection_'+parent_id).prop('disabled', true);
			if(!$('#connection_'+parent_id).hasClass('disabled')){
				$('#connection'+parent_id).addClass('disabled');
			}

			//Destroy select2 on record field if exists
			if($('#record_value_'+parent_id).data('select2')){
				$('#record_value_'+parent_id).select2('destroy');
				$('#record_value_'+parent_id).val("");
			}
		}		
	}
}//~!

/* Disable or enable record value column */
function setValueColumn(parent_id, enable){
	if(parent_id){
		if(enable){
			//Enable record value
			$('#record_value_'+parent_id).prop('disabled', false);
			if($('#record_value_'+parent_id).hasClass('disabled')){
				$('#record_value_'+parent_id).removeClass('disabled');
			}
		}else{
			//Disable record value
			$('#record_value_'+parent_id).prop('disabled', true);
			if(!$('#record_value_'+parent_id).hasClass('disabled')){
				$('#record_value_'+parent_id).addClass('disabled');
			}
		}
	}
}//~!

/* Disable or enable arithmetic columns */
function setArithmeticColumn(parent_id, enable){	
	if(parent_id){
		if(enable){
			//Enable arithmetic col 1
			$('#arithmetic_col_1_'+parent_id).prop('disabled', false);
			if($('#arithmetic_col_1_'+parent_id).hasClass('disabled')){
				$('#arithmetic_col_1_'+parent_id).removeClass('disabled');
			}
			//Enable arithmetic col 2
			$('#arithmetic_col_2_'+parent_id).prop('disabled', false);
			if($('#arithmetic_col_2_'+parent_id).hasClass('disabled')){
				$('#arithmetic_col_2_'+parent_id).removeClass('disabled');
			}			
		}else{
			//Disable arithmetic col 1
			$('#arithmetic_col_1_'+parent_id).prop('disabled', true);
			if(!$('#arithmetic_col_1_'+parent_id).hasClass('disabled')){
				$('#arithmetic_col_1_'+parent_id).addClass('disabled');
			}
			//Disable arithmetic col 2
			$('#arithmetic_col_2_'+parent_id).prop('disabled', true);
			if(!$('#arithmetic_col_2_'+parent_id).hasClass('disabled')){
				$('#arithmetic_col_2_'+parent_id).addClass('disabled');
			}			
		}
	}
}//~!

/* Update fields based on used operation */
function updateFields(operation_used, parent_id){
	if(operation_used && parent_id){
		//Check for operation used		
		if(in_array(operation_used, ['fixed_value', 'equals_col'])){
			//Enable record value
			setValueColumn(parent_id, true);

			//Disable arithmetic rows
			setArithmeticColumn(parent_id, false);

			//Disable connection
			setConnectionColumn(parent_id, false);
		}
		if(in_array(operation_used, ['no_data', 'equal_prev_row'])){
			//Disable record value
			setValueColumn(parent_id, false);			
			
			//Disable arithmetic rows
			setArithmeticColumn(parent_id, false);

			//Disable connection
			setConnectionColumn(parent_id, false);			
		}
		if(in_array(operation_used, ['divide_fields', 'multiply_fields'])){
			//Disable record value
			setValueColumn(parent_id, false);

			//Enable arithmetic rows
			setArithmeticColumn(parent_id, true);

			//Disable connection
			setConnectionColumn(parent_id, false);
		}
		if(operation_used == 'equal_codebook'){
			//Disable record value
			setValueColumn(parent_id, false);

			//Enable arithmetic rows
			setArithmeticColumn(parent_id, false);

			//Enable connection
			setConnectionColumn(parent_id, true);

			//Load connection
			loadConnectionColumn(parent_id, 'codebook_connection');
		}
		if(operation_used == 'equal_document_link'){
			//Disable record value
			setValueColumn(parent_id, false);

			//Enable arithmetic rows
			setArithmeticColumn(parent_id, false);

			//Enable connection
			setConnectionColumn(parent_id, true);

			//Load connection
			loadConnectionColumn(parent_id, 'document_link');
		}				
	}
}//~!

/* If connection selected update select2 lists */
function updateLists(){
	for (var parent_id = 1; parent_id <= total_fields; parent_id++) {
		var operation_used = $("#operation_used_"+parent_id).val();
		updateFields(operation_used, parent_id);
		if(in_array(operation_used, ['equal_codebook', 'equal_document_link'])){			
			if(operation_used == 'equal_codebook'){
				var codebook_connection_id = $('#connection_'+parent_id).val();
				setValueColumn(parent_id, true);
				loadRecordValueSelector(parent_id, codebook_connection_id);
			}
		}
	}
}//~!

/* Init app */
$('#container').ready(function(){
	//Init libraries
	$(".submit_loader").hide();
	$(".dropdown").select2();
	
    //Check for operation used change
    $(".operation_used").on("select2-selecting", function(e) {
        var operation_used = e.object.id;
        var parent_id = $(this).closest('tr').attr('id');

        //Update fields
        updateFields(operation_used, parent_id);
    });

    //document link ajax loader     
    $('.connection').select2({
        minimumInputLength: 0,
        placeholder: "(Odaberite vezu)",
        allowClear: true,
        query: function (query) {}
    });

	//Clear error styles if something on row changed
	$(".error_row input").on("keyup", function(e) {
		var parent_id = $(this).closest('tr').attr('id');
		if($("#"+parent_id).hasClass('error_row')){
			$("#"+parent_id).removeClass('error_row');
		}
	});
	$(".error_row input").on("change", function(e) { 
		var parent_id = $(this).closest('tr').attr('id');
		if($("#"+parent_id).hasClass('error_row')){
			$("#"+parent_id).removeClass('error_row');
		}
	});  
	$(".error_row select").on("change", function(e) { 
		var parent_id = $(this).closest('tr').attr('id');
		if($("#"+parent_id).hasClass('error_row')){
			$("#"+parent_id).removeClass('error_row');
		}
	});

	//Update list after form submission
	updateLists();
});
</script>