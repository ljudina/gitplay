<div class="breadcrumbs_holder">
    <ul class="breadcrumbs">
        <li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>        
        <li><?php echo $this->Html->link(__('Finansijsko knjigovodstvo'), array('controller' => 'ErpModules', 'action' => 'start', 'financial')); ?></li>
        <li class="last"><a href="" onclick="return false"><?php echo __('Šifarnik deviznih transakcija'); ?></a></li>
    </ul>
</div>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
    <div class="name_of_page">
        <h3><i class="icon-exchange"></i> <?php echo __('Šifarnik deviznih transakcija'); ?></h3>
    </div>
    <div style="float:right; margin:20px 24px 0 0;">
        <ul class="button-bar">
            <li class="first last">
                <?php echo $this->Html->link('<i class="icon-plus-sign"></i> '.__('Dodaj deviznu transakciju'), array('controller' => 'FmFeTransactionTypes', 'action' => 'save'), array('escape' => false)); ?>
            </li>
        </ul>
    </div>
    <div class="clear"></div>
</div>

<div class="content_data meni">
    <fieldset style="margin-top:0;">
        <legend>Filter</legend>
        <?php echo $this->Form->create('FmFeTransactionType', array('type' => 'get', 'action' => 'index')); ?>
            <?php echo $this->Form->label('desc_data', __('Pretraga po opisu').':', array('style' => 'margin-right:5px;')); ?>
            <?php echo $this->Form->input('desc_data', array('type' => 'text', 'label' => false, 'div' => false, 'style' => 'width:630px;', 'placeholder' => __('Unesite ključne reči pretrage'), 'required' => false)); ?>
            <?php echo $this->Form->label('payer_recipients', __('Isplatilac/Primalac').':', array('style' => 'margin-right:5px;')); ?>
            <?php echo $this->Form->input('payer_recipients', array('label' => false, 'options' => $payer_recipients, 'div' => false, 'class' => 'dropdown', 'style' => 'width:145px; margin-right:5px;', 'empty' => __('Sve'), 'required' => false)); ?>
            <?php echo $this->Form->button('Prikaži', array('type' => 'submit', 'class' => 'small green right', 'style' => 'margin-left:10px;')); ?>
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>
<div class="content_data" style="margin-top:0;">
     <?php if(empty($types)){ ?>
        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            Nema podataka u bazi!
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
                    <th class="center"><?php echo __('Red Br.'); ?></th>
                    <th><?php echo __('Isplatilac/Primalac'); ?></th>
                    <th><?php echo __('Vrsta transakcije'); ?></th>
                    <th><?php echo __('Veza sa konto-karticom'); ?></th>
                    <th><?php echo __('Podatak za polje: Opis'); ?></th>
                    <th><?php echo __('Broj šeme za knjiženje'); ?></th>
                    <th class="left">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($types as $type): ?>
                <tr>                    
                    <td class="center"><?php echo $type['FmFeTransactionType']['ordinal']; ?></td>
                    <td><?php echo $payer_recipients[$type['FmFeTransactionType']['payer_recipient']]; ?></td>
                    <td><?php echo $transaction_types[$type['FmFeTransactionType']['transaction_type']]; ?></td>
                    <td>
                        <?php if(!empty($type['FmFeTransactionType']['fm_chart_account_links'])){ ?>
                            <?php echo $type['FmFeTransactionType']['fm_chart_account_links']; ?>
                        <?php }else{ ?>
                            &nbsp;
                        <?php } ?>
                    </td>
                    <td><?php echo $type['FmFeTransactionType']['desc_data']; ?></td>
                    <td>
                        <?php if(!empty($type['FmFeTransactionType']['fm_fe_account_scheme_id'])){ ?>
                            <?php echo $type['FmFeAccountScheme']['code']; ?>
                        <?php }else{ ?>
                            &nbsp;
                        <?php } ?>
                    </td>
                    <td class="right">
                        <ul class="button-bar">
                            <li class="first">
                                <?php echo $this->Html->link('<i class="icon-edit"></i>', array('controller' => 'FmFeTransactionTypes', 'action' => 'save', $type['FmFeTransactionType']['id']), array('title' => __('Izmena'), 'escape' => false)); ?>
                            </li>
                            <li class="last">
                                <?php echo $this->Html->link('<i class="icon-remove" style="color:red;"></i>', array('controller' => 'FmFeTransactionTypes', 'action' => 'delete', $type['FmFeTransactionType']['id']), array('title' => __('Brisanje'), 'escape' => false), __("Da li ste sigurni da želite da obrišete račun rednim brojem ".$type['FmFeTransactionType']['ordinal']."?")); ?>
                            </li>                                        
                        </ul>
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
    $(".dropdown").select2();
});
</script>