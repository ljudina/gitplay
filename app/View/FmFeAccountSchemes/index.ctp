<style type="text/css">
    tr.inactive td { color: #bababa; }
</style> 
<div class="breadcrumbs_holder">
    <ul class="breadcrumbs">
        <li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>        
        <li><?php echo $this->Html->link(__('Finansijsko knjigovodstvo'), array('controller' => 'ErpModules', 'action' => 'start', 'financial')); ?></li>
        <li><?php echo $this->Html->link(__('Devizni izvodi banke'), array('controller' => 'FmFeBasics', 'action' => 'index')); ?></li>
        <li class="last"><a href="" onclick="return false"><?php echo __('Šeme knjiženja za automatske naloge'); ?></a></li>
    </ul>
</div>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
    <div class="name_of_page">
        <h3><i class="icon-table"></i> <?php echo __('Šeme knjiženja za automatske naloge'); ?></h3>
    </div>
    <div style="float:right; margin:20px 24px 0 0;">
        <ul class="button-bar">
            <li class="last">
                <?php echo $this->Html->link('<i class="icon-plus-sign"></i> '.__('Dodaj šemu'), array('controller' => 'FmFeAccountSchemes', 'action' => 'save'), array('escape' => false)); ?>
            </li>
        </ul>
    </div>
    <div class="clear"></div>
</div>

<div class="content_data meni">
    <fieldset style="margin-top:0;">
        <legend>Filter</legend>
        <?php echo $this->Form->create('FmFeAccountScheme', array('type' => 'get', 'action' => 'index')); ?>

        <?php echo $this->Form->label('keywords', __('Pretraga').':', array('style' => 'margin-right:5px;')); ?>
        <?php echo $this->Form->input('keywords', array('label' => false, 'div' => false, 'style' => 'width:515px;', 'placeholder' => __('Unesite reči za pretragu'), 'required' => false)); ?>

        <?php echo $this->Form->label('valid_from', __('za period od').':', array('style' => 'margin-right:5px;')); ?>
        <?php echo $this->Form->input('valid_from', array('type' => 'text', 'label' => false, 'div' => false, 'style' => 'width:88px;', 'class' => 'date field', 'placeholder' => __('Datum od'))); ?>

        <?php echo $this->Form->label('valid_to', __('do').':', array('style' => 'margin-right:5px;')); ?>
        <?php echo $this->Form->input('valid_to', array('type' => 'text', 'label' => false, 'div' => false, 'style' => 'width:88px;', 'class' => 'date field', 'placeholder' => __('Datum do'))); ?>

        <?php echo $this->Form->label('show_all', __('Prikazi neaktivne'), array('style' => 'margin-right:5px;')); ?>
        <?php echo $this->Form->checkbox('show_all', array('label' => false, 'div' => false)); ?>

        <?php echo $this->Form->button('Prikaži', array('type' => 'submit', 'class' => 'small green right', 'style' => 'margin-left:10px;')); ?>
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>
<div class="content_data" style="margin-top:0;">
     <?php if(empty($account_schemes)){ ?>
        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            <?php echo __("Za ovaj upit nema podataka u bazi!"); ?>
        </div>
    <?php }else{ ?>
        <!-- log list starts -->
        <div class="center paginator">
            <?php echo $this->Paginator->first(__('prva strana'), array());?>
            <?php if($this->Paginator->hasPrev()){ ?>
            <?php echo $this->Paginator->prev('« '.__('prethodna'), array(), null, array('class' => 'disabled')); } ?>
            <?php echo $this->Paginator->numbers();?>
            <?php if($this->Paginator->hasNext()){ ?>
            <?php echo $this->Paginator->next(__('sledeća').' »', array(), null, array('class' => 'disabled')); } ?>
            <?php echo $this->Paginator->last(__('poslednja strana'), array()); ?>            
        </div>
        <table>
            <thead>
                <tr>                   
                    <th class="left">&nbsp;</th>
                    <th class="center"><?php echo __('Br. šeme'); ?></th>
                    <th class="center"><?php echo __('Datum formiranja'); ?></th>
                    <th><?php echo __('Operater pokrenuo period važenja'); ?></th>
                    <th><?php echo __('Operater završio period važenja'); ?></th>
                    <th class="center"><?php echo __('Datum od kada važi'); ?></th>
                    <th class="center"><?php echo __('Datum do kada važi'); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($account_schemes as $scheme): ?>
                <?php 
                    $row_class = 'standard';
                    if(!empty($scheme['FmFeAccountScheme']['valid_to'])){
                        $row_class = 'inactive';
                    }
                ?>
                <tr class="<?php echo $row_class; ?>">
                    <td style="white-space: nowrap;">
                        <ul class="dropit_menu filemenu">
                            <li>
                                <a href="#" class="center"><i class="icon-cog"></i> Opcije</a>
                                <ul>
                                  <li><?php echo $this->Html->link('<i class="icon-eye-open"></i> Pregled šeme', array('controller' => 'FmFeAccountSchemes', 'action' => 'view', $scheme['FmFeAccountScheme']['id']), array('escape' => false)); ?></li>      
                                  <?php if(empty($scheme['FmFeAccountScheme']['valid_from']) && empty($scheme['FmFeAccountScheme']['valid_to'])){ ?>
                                  <li><?php echo $this->Html->link('<i class="icon-edit"></i> Izmena osnovnih podataka', array('controller' => 'FmFeAccountSchemes', 'action' => 'save', $scheme['FmFeAccountScheme']['id']), array('escape' => false)); ?></li>                                  
                                  <li><?php echo $this->Html->link('<i class="icon-remove" style="color:red;"></i> Brisanje šeme', array('controller' => 'FmFeAccountSchemes', 'action' => 'delete', $scheme['FmFeAccountScheme']['id']), array('escape' => false), __("Da li ste sigurni da želite da obrišete šemu ".$scheme['FmFeAccountScheme']['code']."?")); ?></li>
                                  <?php } ?>
                                  <?php if(empty($scheme['FmFeAccountScheme']['valid_from']) && empty($scheme['FmFeAccountScheme']['valid_to'])){ ?>
                                  <li><?php echo $this->Html->link('<i class="icon-play" style="color:green;"></i> Startuj period važenja šeme knjiženja', array('controller' => 'FmFeAccountSchemes', 'action' => 'start', $scheme['FmFeAccountScheme']['id']), array('escape' => false), __("Da li ste sigurni da želite da startujete period važenja šeme ".$scheme['FmFeAccountScheme']['code']."?")); ?></li>
                                  <?php } ?>
                                  <?php if(!empty($scheme['FmFeAccountScheme']['valid_from']) && empty($scheme['FmFeAccountScheme']['valid_to'])){ ?>
                                  <li><?php echo $this->Html->link('<i class="icon-stop" style="color:orange;"></i> Završi period važenja šeme knjiženja', array('controller' => 'FmFeAccountSchemes', 'action' => 'end', $scheme['FmFeAccountScheme']['id']), array('escape' => false), __("Da li ste sigurni da želite da završite period važenja šeme ".$scheme['FmFeAccountScheme']['code']."?")); ?></li>
                                  <?php } ?>
                                </ul>
                            </li>
                        </ul>
                    </td>                           
                    <td class="center"><?php echo $scheme['FmFeAccountScheme']['code']; ?></td>
                    <td class="center"><?php echo date('d.m.Y', strtotime($scheme['FmFeAccountScheme']['created'])); ?></td>
                    <td class="center">
                        <?php if(!empty($scheme['FmFeAccountScheme']['user_id_start'])){ ?>
                            <?php echo $scheme['UserStart']['first_name']; ?> <?php echo $scheme['UserStart']['last_name']; ?>
                        <?php }else{ ?>
                            &nbsp;
                        <?php } ?>
                    </td>
                    <td class="center">
                        <?php if(!empty($scheme['FmFeAccountScheme']['user_id_end'])){ ?>
                            <?php echo $scheme['UserEnd']['first_name']; ?> <?php echo $scheme['UserEnd']['last_name']; ?>
                        <?php }else{ ?>
                            &nbsp;
                        <?php } ?>
                    </td>
                    <td class="center">
                        <?php if(!empty($scheme['FmFeAccountScheme']['valid_from'])){ ?>
                            <?php echo date('d.m.Y', strtotime($scheme['FmFeAccountScheme']['valid_from'])); ?>
                        <?php }else{ ?>
                            &nbsp;
                        <?php } ?>
                    </td>
                    <td class="center">
                        <?php if(!empty($scheme['FmFeAccountScheme']['valid_to'])){ ?>
                            <?php echo date('d.m.Y', strtotime($scheme['FmFeAccountScheme']['valid_to'])); ?>
                        <?php }else{ ?>
                            &nbsp;
                        <?php } ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="center paginator">
            <?php echo $this->Paginator->first(__('prva strana'), array());?>
            <?php if($this->Paginator->hasPrev()){ ?>
            <?php echo $this->Paginator->prev('« '.__('prethodna'), array(), null, array('class' => 'disabled')); } ?>
            <?php echo $this->Paginator->numbers();?>
            <?php if($this->Paginator->hasNext()){ ?>
            <?php echo $this->Paginator->next(__('sledeća').' »', array(), null, array('class' => 'disabled')); } ?>
            <?php echo $this->Paginator->last(__('poslednja strana'), array()); ?>            
        </div>        
    <?php } ?>
    <!-- log list ends -->
</div>
<div class="clear"></div>
<div class="submit_loader">
    <?php echo $this->Html->image('submit_loader.gif', array('alt' => 'Loader')); ?>
    <h2>Molimo sačekajte...</h2>
</div> 
<script type="text/javascript">
$('#container').ready(function(){
    $(".submit_loader").hide();
    $(".dropit_menu").dropit();
    $(".date").datepicker({ dateFormat: "yy-mm-dd" });
});
</script>