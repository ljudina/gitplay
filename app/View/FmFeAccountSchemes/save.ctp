<ul class="breadcrumbs">
	<li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
	<li><?php echo $this->Html->link(__('Finansijsko knjigovodstvo'), array('controller' => 'ErpModules', 'action' => 'start', 'financial')); ?></li>
	<li><?php echo $this->Html->link(__('Devizni izvodi banke'), array('controller' => 'FmFeBasics', 'action' => 'index')); ?></li>
	<li><?php echo $this->Html->link(__('Šeme knjiženja za automatske naloge'), array('controller' => 'FmFeAccountSchemes', 'action' => 'index')); ?></li>
	<li class="last"><a href="" onclick="return false"><?php echo __('Snimanje'); ?></a></li>
</ul>

<div class="name_add_search">
	<div class="name_of_page">
		<?php if(empty($this->request->data['FmFeAccountScheme']['id'])){ ?>
			<h3><i class="icon-plus-sign"></i> <?php echo __('Osnovni podaci o šemi'); ?></h3>
		<?php }else{ ?>
			<h3><i class="icon-edit"></i> <?php echo __('Osnovni podaci o šemi'); ?></h3>
		<?php } ?>
	</div>
</div>
<div id='alert'><?php echo $this->Session->flash(); ?></div>
<div class="content_data" style="width:370px; margin-top:0;">	
	<?php echo $this->Form->create('FmFeAccountScheme'); ?>
	<div class="content_text_input">
		<?php echo $this->Form->label('code', __('Broj šeme za knjiženje').' <span class="red">*</span>'); ?>
		<?php echo $this->Form->input('code', array('type' => 'text', 'label' => false, 'class' => 'col_12 inputborder date', 'required' => false, 'placeholder' => __('Unesite br. šeme'))); ?>
	</div>
	<div class="clear"></div>
	<div class="content_text_input">
		<?php echo $this->Form->label('scheme_desc', __('Opis šeme').' <span class="red">*</span>'); ?>
		<?php echo $this->Form->input('scheme_desc', array('type' => 'textarea', 'label' => false, 'class' => 'col_12 desc', 'required' => false)); ?>
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
				<?php echo $this->Html->link(__('Nazad'), array('controller' => 'FmFeAccountSchemes', 'action' => 'index'), array('class' => 'button', 'style' => 'margin:20px 0 20px 0;')); ?>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>
<script>
/* Init libs */
$('#container').ready(function(){
	//Init libraries
	$(".submit_loader").hide();
	$(".desc").ckeditor( function() { /* callback code */ }, { height : '200px' } );
});
</script>