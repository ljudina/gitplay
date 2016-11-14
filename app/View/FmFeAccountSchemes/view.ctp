<div class="breadcrumbs_holder">
    <ul class="breadcrumbs">
        <li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
        <li><?php echo $this->Html->link(__('Finansijsko knjigovodstvo'), array('controller' => 'ErpModules', 'action' => 'start', 'financial')); ?></li>
        <li><?php echo $this->Html->link(__('Devizni izvodi banke'), array('controller' => 'FmFeBasics', 'action' => 'index')); ?></li>
        <li><?php echo $this->Html->link(__('Šeme knjiženja za automatske naloge'), array('controller' => 'FmFeAccountSchemes', 'action' => 'index')); ?></li>
        <li class="last"><a href="" onclick="return false"><?php echo __('Pregled šeme'); ?></a></li>
    </ul>
</div>
<div class="content_data" style="margin-top:0;">    
    <h4><i class="icon-book"></i> <?php echo __('Pregled šeme br.'); ?> <?php echo $account_scheme['FmFeAccountScheme']['code']; ?></h4>
    <table>
        <thead>
            <tr>
                <th colspan="4">&nbsp;</th>
            </tr>        
            <tr>
                <th colspan="4" class="center"><?php echo __('Osnovni podaci o šemi'); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="bold"><?php echo __('Broj šeme za knjizenje'); ?></td>
                <td><?php echo $account_scheme['FmFeAccountScheme']['code']; ?></td>
                <td class="bold"><?php echo __('Datum formiranja'); ?></td>
                <td><?php echo date('d.m.Y', strtotime($account_scheme['FmFeAccountScheme']['created'])); ?></td>                
            </tr>
            <tr>
                <td class="bold"><?php echo __('Opis šeme knjiženja'); ?></td>
                <td><?php echo $account_scheme['FmFeAccountScheme']['scheme_desc']; ?></td>
                <td colspan="2">&nbsp;</td>
            </tr>
            <?php if(!empty($account_scheme['FmFeAccountScheme']['valid_from']) && !empty($account_scheme['FmFeAccountScheme']['user_id_start'])){ ?>
            <tr>
                <td class="bold"><?php echo __('Datum od kada važi'); ?></td>
                <td><?php echo date('d.m.Y', strtotime($account_scheme['FmFeAccountScheme']['valid_from'])); ?></td>
                <td class="bold"><?php echo __('Operater pokrenuo period važenja'); ?></td>
                <td><?php echo $account_scheme['UserStart']['first_name']; ?> <?php echo $account_scheme['UserStart']['last_name']; ?></td> 
            </tr>
            <?php } ?>
            <?php if(!empty($account_scheme['FmFeAccountScheme']['valid_to']) && !empty($account_scheme['FmFeAccountScheme']['user_id_end'])){ ?>
            <tr>
                <td class="bold"><?php echo __('Datum do kada važi'); ?></td>
                <td><?php echo date('d.m.Y', strtotime($account_scheme['FmFeAccountScheme']['valid_to'])); ?></td>
                <td class="bold"><?php echo __('Operater završio period važenja'); ?></td>
                <td><?php echo $account_scheme['UserEnd']['first_name']; ?> <?php echo $account_scheme['UserEnd']['last_name']; ?></td>
            </tr>
            <?php } ?>            
        </tbody>
    </table>
    <div id='alert'><?php echo $this->Session->flash(); ?></div>
    <div id="rows">
        <?php if(empty($account_scheme['FmFeAccountScheme']['valid_from'])){ ?>
        <div style="float:right; margin:0;">
            <?php echo $this->Html->link('<i class="icon-plus-sign"></i> '.__('Dodaj red'), array('controller' => 'FmFeAccountSchemeRows', 'action' => 'save', $account_scheme['FmFeAccountScheme']['id']), array('escape' => false, 'class' => 'button green small')); ?>
        </div>
        <div class="clear"></div>    
        <?php } ?>
        <?php if(empty($account_scheme_rows)){ ?>
            <div class="notice warning">
                <i class="icon-warning-sign icon-large"></i>
                <?php echo __("Za ovu šemu knjiženja ne postoje definisani redovi!"); ?>
            </div>
        <?php }else{ ?>
            <div style="width:100%; overflow-x: scroll;">
                <table>
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <?php foreach ($account_scheme_rows as $row): ?>
                                <th class="left">
                                    <?php echo $row['FmFeAccountSchemeRow']['ordinal']; ?>. <?php echo __("Red"); ?>
                                </th>
                            <?php endforeach; ?>
                        </tr>
                        <?php if(empty($account_scheme['FmFeAccountScheme']['valid_from'])){ ?>
                        <tr>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <?php foreach ($account_scheme_rows as $row): ?>
                                <th class="left">
                                    <?php echo $this->Html->link('<i class="icon-edit" style="color:purple;"></i>', array('controller' => 'FmFeAccountSchemeRows', 'action' => 'save', $account_scheme['FmFeAccountScheme']['id'], $row['FmFeAccountSchemeRow']['id']), array('escape' => false, 'class' => 'button small', 'title' => __("Izmena ".$row['FmFeAccountSchemeRow']['ordinal'].". reda"))); ?>
                                    <?php echo $this->Html->link('<i class="icon-remove" style="color:red;"></i>', array('controller' => 'FmFeAccountSchemeRows', 'action' => 'delete', $row['FmFeAccountSchemeRow']['id']), array('escape' => false, 'class' => 'button small', 'title' => __("Brisanje ".$row['FmFeAccountSchemeRow']['ordinal'].". reda")), __("Da li ste sigurni da želite da obrišete red ".$row['FmFeAccountSchemeRow']['ordinal']."?")); ?>
                                </th>
                            <?php endforeach; ?>
                        </tr>
                        <?php } ?>
                        <tr>
                            <th>&nbsp;</th>
                            <th><?php echo __("Uslov"); ?></th>
                            <?php foreach ($account_scheme_rows as $row): ?>
                                <th class="left"><?php echo $conditions[$row['FmFeAccountSchemeRow']['conditions']]; ?></th>
                            <?php endforeach; ?>
                        </tr>
                        <tr>
                            <th>&nbsp;</th>
                            <th style="white-space: nowrap;"><?php echo __("Polja u obrascu za knjiženje"); ?></th>
                            <th class="left" colspan="<?php echo count($account_scheme_rows); ?>">&nbsp;</th>
                        </tr>                                        
                    </thead>
                    <tbody>
                        <?php foreach ($document_field_no as $no => $document_field): ?>
                            <tr>
                                <td class="center"><?php echo $no; ?>.</td>
                                <td><?php echo $document_fields[$document_field]; ?></td>
                                <?php foreach ($account_scheme_rows as $row): ?>
                                    <?php 
                                        //Map acccount scheme records
                                        $records = array();
                                        foreach ($row['FmFeAccountSchemeRecord'] as $record) {
                                            $records[$record['document_field']] = $record;
                                        }
                                    ?>                                
                                    <td class="left">
                                        <?php if(in_array($records[$document_field]['operation_used'], array('equal_codebook','equal_document_link'))){ ?>
                                            <?php echo $records[$document_field]['record_title']; ?>
                                        <?php } ?>
                                        <?php if(in_array($records[$document_field]['operation_used'], array('fixed_value','equals_col'))){ ?>
                                            <?php echo $records[$document_field]['record_value']; ?>
                                        <?php } ?>         
                                        <?php if($records[$document_field]['operation_used'] == 'divide_fields'){ ?>
                                            <?php echo __("Podeli kolone"); ?> <?php echo $records[$document_field]['arithmetic_first_col']; ?> <?php echo __('i'); ?> <?php echo $records[$document_field]['arithmetic_second_col']; ?>
                                        <?php } ?>                                             
                                        <?php if(in_array($records[$document_field]['operation_used'], array('equal_prev_row','no_data'))){ ?>
                                            <?php echo $used_operations[$records[$document_field]['operation_used']]; ?>
                                        <?php } ?>                                    
                                        <?php if(!empty($records[$document_field]['absolute_value'])){ ?>
                                            <br><span style="font-size: 80%; font-style: italic; color: blue;">* apsolutna vrednost</span>
                                        <?php } ?>
                                        <?php if(!empty($records[$document_field]['negative_value'])){ ?>
                                            <br><span style="font-size: 80%; font-style: italic; color: purple;">* u minusu</span>
                                        <?php } ?>                                        
                                    </td>
                                <?php endforeach; ?>                            
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
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