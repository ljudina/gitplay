<div class="breadcrumbs_holder">
    <ul class="breadcrumbs">
        <li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>        
        <li><?php echo $this->Html->link(__('Finansijsko knjigovodstvo'), array('controller' => 'ErpModules', 'action' => 'start', 'financial')); ?></li>
        <li class="last"><a href="" onclick="return false"><?php echo __('Devizni izvodi banke'); ?></a></li>
    </ul>
</div>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
    <div class="name_of_page">
        <h3><i class="icon-home"></i> <?php echo __('Devizni izvodi banke'); ?></h3>
    </div>
    <div style="float:right; margin:20px 24px 0 0;">
        <ul class="button-bar">
            <li class="last">
                <?php echo $this->Html->link('<i class="icon-plus-sign"></i> '.__('Dodaj izvod'), array('controller' => 'FmFeBasics', 'action' => 'save'), array('escape' => false)); ?>
            </li>
        </ul>
    </div>
    <div class="clear"></div>
</div>

<div class="content_data meni">
    <fieldset style="margin-top:0;">
        <legend>Filter</legend>
        <?php echo $this->Form->create('FmFeBasic', array('type' => 'get', 'action' => 'index')); ?>
        <?php echo $this->Form->label('fm_business_account_id', __('Poslovni račun').':', array('style' => 'margin-right:5px;')); ?>
        <?php echo $this->Form->input('fm_business_account_id', array('label' => false, 'options' => $accounts, 'div' => false, 'class' => 'dropdown', 'style' => 'width:360px; margin-right:5px;', 'empty' => __('Svi'), 'required' => false)); ?>        

        <?php echo $this->Form->label('fe_number', __('Broj izvoda').':', array('style' => 'margin-right:5px;')); ?>
        <?php echo $this->Form->input('fe_number', array('label' => false, 'div' => false, 'style' => 'width:145px;', 'placeholder' => __('Unesite broj izvoda'), 'required' => false)); ?>

        <?php echo $this->Form->label('fe_date_from', __('za period od').':', array('style' => 'margin-right:5px;')); ?>
        <?php echo $this->Form->input('fe_date_from', array('label' => false, 'div' => false, 'style' => 'width:100px;', 'class' => 'date field', 'placeholder' => __('Datum od'))); ?>

        <?php echo $this->Form->label('fe_date_to', __('do').':', array('style' => 'margin-right:5px;')); ?>
        <?php echo $this->Form->input('fe_date_to', array('label' => false, 'div' => false, 'style' => 'width:100px;', 'class' => 'date field', 'placeholder' => __('Datum do'))); ?>

        <?php echo $this->Form->button('Prikaži', array('type' => 'submit', 'class' => 'small green right', 'style' => 'margin-left:10px;')); ?>
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>
<div class="content_data" style="margin-top:0;">
     <?php if(empty($fe_basics)){ ?>
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
                    <th class="center"><?php echo __('Red Br.'); ?></th>
                    <th><?php echo __('Šifra Banke'); ?></th>
                    <th class="center"><?php echo __('Devizna valuta'); ?></th>
                    <th><?php echo __('Broj t.r.'); ?></th>
                    <th class="center"><?php echo __('Broj izvoda'); ?></th>
                    <th class="center"><?php echo __('Datum izvoda'); ?></th>
                    <th class="right"><?php echo __('Kurs dev.valute'); ?></th>
                    <th class="right"><?php echo __('Pret. dev. saldo'); ?></th>
                    <th class="right"><?php echo __('Pret. din. saldo'); ?></th>
                    <th class="left">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($fe_basics as $fe_basic): ?>
                <tr>                    
                    <td class="center"><?php echo $fe_basic['FmFeBasic']['ordinal']; ?></td>
                    <td><?php echo $fe_basic['FmBusinessAccount']['CbBank']['code']; ?></td>
                    <td class="center"><?php echo $fe_basic['FmBusinessAccount']['Currency']['iso']; ?></td>
                    <td><?php echo $fe_basic['FmBusinessAccount']['account_number']; ?></td>
                    <td class="center"><?php echo $fe_basic['FmFeBasic']['fe_number']; ?></td>
                    <td class="center"><?php echo date('d.m.Y', strtotime($fe_basic['FmFeBasic']['fe_date'])); ?></td>
                    <td class="right"><?php echo $fe_basic['FmFeBasic']['exchange_rate']; ?></td>
                    <td class="right"><?php echo $fe_basic['FmFeBasic']['previous_balance_currency']; ?></td>
                    <td class="right"><?php echo $fe_basic['FmFeBasic']['previous_balance_rsd']; ?></td>
                    <td class="right">
                        <ul class="button-bar">
                            <li class="first">
                                <?php echo $this->Html->link('<i class="icon-book" style="color:green;"></i>', array('controller' => 'FmFeBasics', 'action' => 'view', $fe_basic['FmFeBasic']['id']), array('title' => __('Pregled izvoda'), 'escape' => false)); ?>
                            </li>
                            <?php if(empty($fe_basic['FmFeBasic']['user_id_verified'])){ ?>
                            <li>
                                <?php echo $this->Html->link('<i class="icon-edit"></i>', array('controller' => 'FmFeBasics', 'action' => 'save', $fe_basic['FmFeBasic']['id']), array('title' => __('Izmena'), 'escape' => false)); ?>
                            </li>
                            <li class="last">
                                <?php echo $this->Html->link('<i class="icon-remove" style="color:red;"></i>', array('controller' => 'FmFeBasics', 'action' => 'delete', $fe_basic['FmFeBasic']['id']), array('title' => __('Brisanje'), 'escape' => false), __("Da li ste sigurni da želite da obrišete izvod pod rednim brojem ".$fe_basic['FmFeBasic']['ordinal']."?")); ?>
                            </li>
                            <?php } ?>
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
    $(".date").datepicker({ dateFormat: "yy-mm-dd" });
});
</script>