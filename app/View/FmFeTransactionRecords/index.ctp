<div class="breadcrumbs_holder">
    <ul class="breadcrumbs">
        <li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
        <li><?php echo $this->Html->link(__('Finansijsko knjigovodstvo'), array('controller' => 'ErpModules', 'action' => 'start', 'financial')); ?></li>
        <li><?php echo $this->Html->link(__('Devizni izvodi banke'), array('controller' => 'FmFeBasics', 'action' => 'index')); ?></li>
        <li><?php echo $this->Html->link(__('Pregled izvoda'), array('controller' => 'FmFeBasics', 'action' => 'view', $fe_transaction['FmFeBasic']['id'])); ?></li>
        <li class="last"><a href="" onclick="return false"><?php echo __('Obrazac za knjizenje deviznih transakcija'); ?></a></li>
    </ul>
</div>
<div class="content_data" style="margin-top:0; margin-left: 50px;">        
    <h4><i class="icon-key"></i> <?php echo __('Obrazac za knjizenje deviznih transakcija'); ?></h4>
    <?php echo $this->element('../FmFeBasics/mini_basic'); ?>
    <div id="records">
        <?php echo $this->element('../FmFeTransactionRecords/records'); ?>
    </div>
</div>
<div class="submit_loader">
    <?php echo $this->Html->image('submit_loader.gif', array('alt' => 'Loader')); ?>
    <h2>Molimo sačekajte...</h2>
</div> 
<script type="text/javascript">
$('#container').ready(function(){
    //Hide ajax loader
    $(".submit_loader").hide();
});
</script>