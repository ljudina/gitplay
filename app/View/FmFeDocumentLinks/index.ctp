<div class="breadcrumbs_holder">
    <ul class="breadcrumbs">
        <li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>        
        <li><?php echo $this->Html->link(__('Finansijsko knjigovodstvo'), array('controller' => 'ErpModules', 'action' => 'start', 'financial')); ?></li>
        <li><?php echo $this->Html->link(__('Devizni izvodi banke'), array('controller' => 'FmFeBasics', 'action' => 'index')); ?></li>
        <li class="last"><a href="" onclick="return false"><?php echo __('Povezivanje dokumenata'); ?></a></li>
    </ul>
</div>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
    <div class="name_of_page">
        <h3><i class="icon-exchange"></i> <?php echo __('Povezivanje dokumenata'); ?></h3>
    </div>
    <div style="float:right; margin:20px 24px 0 0;">
        <ul class="button-bar">
            <li class="last">
                <?php echo $this->Html->link('<i class="icon-plus-sign"></i> '.__('Dodaj dokument'), array('controller' => 'FmFeDocumentLinks', 'action' => 'save'), array('escape' => false)); ?>
            </li>
        </ul>
    </div>
    <div class="clear"></div>
</div>

<div class="content_data meni">
    <fieldset style="margin-top:0;">
        <legend>Filter</legend>
        <?php echo $this->Form->create('FmFeDocumentLink', array('type' => 'get', 'action' => 'index')); ?>
        <?php echo $this->Form->label('keywords', __('Pretraga').':', array('style' => 'margin-right:5px;')); ?>
        <?php echo $this->Form->input('keywords', array('label' => false, 'div' => false, 'style' => 'width:820px;', 'placeholder' => __('Unesite reči pretrage'), 'required' => false)); ?>
        <?php echo $this->Form->checkbox('show_all'); ?>
        <?php echo $this->Form->label('show_all', __('Prikazi i neaktivne')); ?>
        <?php echo $this->Form->button('Prikaži', array('type' => 'submit', 'class' => 'small green right', 'style' => 'margin-left:10px;')); ?>
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>
<div class="content_data" style="margin-top:0;">
     <?php if(empty($document_links)){ ?>
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
                    <th><?php echo __('Naziv dokumenta'); ?></th>
                    <th><?php echo __('Polje dokumenta'); ?></th>
                    <th><?php echo __('Naziv modela'); ?></th>
                    <th><?php echo __('Polje modela'); ?></th>
                    <th class="center"><?php echo __('Aktivan'); ?></th>
                    <th class="right"><?php echo __('Opcije'); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($document_links as $document_link): ?>
                <tr>                    
                    <td><?php echo $document_link['FmFeDocumentLink']['document_name']; ?></td>
                    <td><?php echo $document_link['FmFeDocumentLink']['field_name']; ?></td>
                    <td><?php echo $document_link['FmFeDocumentLink']['model_name']; ?></td>
                    <td><?php echo $document_link['FmFeDocumentLink']['model_field']; ?></td>
                    <td class="center">
                        <?php if(!empty($document_link['FmFeDocumentLink']['active'])){ ?>
                            <span style="color:green"><?php echo __('DA'); ?></span>
                        <?php }else{ ?>
                            <span style="color:red"><?php echo __('NE'); ?></span>
                        <?php } ?>
                    </td>
                    <td class="right">
                        <ul class="button-bar">
                            <li class="first last">
                                <?php echo $this->Html->link('<i class="icon-edit"></i>', array('controller' => 'FmFeDocumentLinks', 'action' => 'save', $document_link['FmFeDocumentLink']['id']), array('title' => __('Izmena dokumenta'), 'escape' => false)); ?>
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
});
</script>