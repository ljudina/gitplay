<ul class="breadcrumbs">
	<li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
	<li><?php echo $this->Html->link(__('Finansijsko knjigovodstvo'), array('controller' => 'ErpModules', 'action' => 'start', 'financial')); ?></li>
	<li><?php echo $this->Html->link(__('Devizni izvodi banke'), array('controller' => 'FmFeBasics', 'action' => 'index')); ?></li>
    <li><?php echo $this->Html->link(__('Povezivanje dokumenata'), array('controller' => 'FmFeDocumentLinks', 'action' => 'index')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Snimanje'); ?></a></li>
</ul>
<div class="name_add_search">
	<div class="name_of_page">
		<?php if(empty($this->request->data['FmFeDocumentLink']['id'])){ ?>
			<h3><i class="icon-plus-sign"></i> <?php echo __('Dokument za povezivanje'); ?></h3>
		<?php }else{ ?>
			<h3><i class="icon-edit"></i> <?php echo __('Dokument za povezivanje'); ?></h3>
		<?php } ?>
	</div>
</div>
<div id='alert'><?php echo $this->Session->flash(); ?></div>
<div class="content_data" style="width:370px; margin-top:0;">	
	<?php echo $this->Form->create('FmFeDocumentLink'); ?>
	<div class="content_text_input">
		<?php echo $this->Form->label('document_name', __('Naziv dokumenta').' <span class="red">*</span>'); ?>
		<?php echo $this->Form->input('document_name', array('type' => 'text', 'label' => false, 'class' => 'col_12 inputborder', 'required' => false, 'placeholder' => __('Unesite naziv dokumenta'))); ?>
	</div>
	<div class="clear"></div>
	<div class="content_text_input">
		<?php echo $this->Form->label('field_name', __('Polje dokumenta').' <span class="red">*</span>'); ?>
		<?php echo $this->Form->input('field_name', array('type' => 'text', 'label' => false, 'class' => 'col_12 inputborder', 'required' => false, 'placeholder' => __('Unesite polje dokumenta'))); ?>
	</div>
	<div class="clear"></div>	
	<div class="content_text_input">
		<?php echo $this->Form->label('model_name', __('Naziv modela dokumenta').' <span class="red">*</span>'); ?>
		<?php echo $this->Form->input('model_name', array('type' => 'text', 'label' => false, 'class' => 'col_12 inputborder', 'required' => false, 'placeholder' => __('Unesite naziv modela dokumenta'))); ?>
	</div>
	<div class="clear"></div>
	<div class="content_text_input">
		<?php echo $this->Form->label('model_field', __('Polje modela dokumenta').' <span class="red">*</span>'); ?>
		<?php echo $this->Form->input('model_field', array('type' => 'text', 'label' => false, 'class' => 'col_12 inputborder', 'required' => false, 'placeholder' => __('Unesite polje modela dokumenta'))); ?>
	</div>
	<div class="clear"></div>	
	<div class="content_text_input holder">
		<?php echo $this->Form->label('active', __('Aktivan')); ?>
		<?php echo $this->Form->checkbox('active'); ?>        
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
				<?php echo $this->Html->link(__('Nazad'), array('controller' => 'FmFeDocumentLinks', 'action' => 'index'), array('class' => 'button', 'style' => 'margin:20px 0 20px 0;')); ?>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>
<script>
/* Init app */
$('#container').ready(function(){	
	$(".submit_loader").hide(); //Hide loader
});
</script>