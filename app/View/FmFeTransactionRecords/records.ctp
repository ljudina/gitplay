<?php echo $this->Html->css('Script/FmFeTransactionRecords/styles'); ?>
<?php $show_menu = empty($fe_transaction['FmFeTransactionType']['fm_fe_account_scheme_id']) && empty($fe_transaction['FmFeTransactionEntry']); ?>
<?php if(empty($records)){ ?>
    <div class="notice warning">
        <i class="icon-warning-sign icon-large"></i>
        <?php echo __("Za ovu obrazac trenutno nema definisanih stavki!"); ?>
    </div>
<?php }else{ ?>    
    <table class="records">
        <thead>
            <tr>
                <?php if($show_menu){ ?>
                    <th rowspan="2">&nbsp;</th>
                <?php } ?>
                <th class="center" rowspan="2"><?php echo __("Red. Br."); ?></th>
                <th class="center" rowspan="2"><?php echo __("Šifra konta"); ?></th>
                <th class="center" rowspan="2"><?php echo __("Šifra analitike"); ?></th>
                <th class="center" colspan="2"><?php echo __("Dokument"); ?></th>
                <th class="center" rowspan="2"><?php echo __("Opis transakcije"); ?></th>
                <th class="center" colspan="2"><?php echo __("Primarna veza"); ?></th>
                <th class="center" colspan="2"><?php echo __("Sekundarna veza"); ?></th>
                <th class="center" rowspan="2"><?php echo __("Šifra klasifikacije"); ?></th>
                <th class="center" rowspan="2"><?php echo __("Šifra valute"); ?></th>
                <th class="center" colspan="2"><?php echo __("Devizna stavka"); ?></th>
                <th class="center" rowspan="2"><?php echo __("Datum kursa"); ?></th>
                <th class="center" rowspan="2"><?php echo __("Devizni kurs"); ?></th>
                <th class="center" colspan="2"><?php echo __("Stavka u RSD"); ?></th>                
            </tr>
            <tr>
                <th class="center"><?php echo __("vrsta"); ?></th>
                <th class="center"><?php echo __("broj"); ?></th>
                <th class="center"><?php echo __("vrsta"); ?></th>
                <th class="center"><?php echo __("broj"); ?></th>  
                <th class="center"><?php echo __("vrsta"); ?></th>
                <th class="center"><?php echo __("broj"); ?></th>                                   
                <th class="center"><?php echo __("Duguje"); ?></th>
                <th class="center"><?php echo __("Potražuje"); ?></th>
                <th class="center"><?php echo __("Duguje"); ?></th>
                <th class="center"><?php echo __("Potražuje"); ?></th>                
            </tr>
            <tr class="column_numbers">
                <?php if($show_menu){ ?>
                    <th>&nbsp;</th>
                <?php } ?>            
                <th class="center">1</th>
                <th class="center">2</th>
                <th class="center">3</th>
                <th class="center">4</th>
                <th class="center">5</th>
                <th class="center">6</th>
                <th class="center">7</th>
                <th class="center">8</th>
                <th class="center">9</th>
                <th class="center">10</th>
                <th class="center">11</th>
                <th class="center">12</th>
                <th class="center">13</th>
                <th class="center">14</th>
                <th class="center">15</th>
                <th class="center">16</th>
                <th class="center">17</th>
                <th class="center">18</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($records as $record): ?>
            <tr class="nowrap">
                <?php if($show_menu){ ?>
                    <td>
                        <ul class="dropit_menu filemenu">
                            <li>
                                <a href="#"><i class="icon-cog"></i> Opcije</a>
                                <ul>
                                    <li><?php echo $this->Js->link('<i class="icon-edit"></i> Izmeni stavku br. '.$record['FmFeTransactionRecord']['ordinal'], array('controller' => 'FmFeTransactionRecords', 'action' => 'save', $record['FmFeTransactionRecord']['fm_fe_transaction_id'], $record['FmFeTransactionRecord']['id']), array('update' => '#records', 'buffer' => false, 'htmlAttributes' => array('escape' => false))); ?></li>
                                    <li><?php echo $this->Js->link('<i class="icon-remove" style="color:red;"></i> Obriši stavku br. '.$record['FmFeTransactionRecord']['ordinal'], array('controller' => 'FmFeTransactionRecords', 'action' => 'delete', $record['FmFeTransactionRecord']['id']), array('update' => '#records', 'buffer' => false, 'htmlAttributes' => array('escape' => false), 'confirm' => __("Da li ste sigurni da želite da obrišete stavku pod rednim brojem ".$record['FmFeTransactionRecord']['ordinal']."?"))); ?></li>
                                </ul>
                            </li>
                        </ul>                        
                    </td>
                <?php } ?>                            
                <td class="center"><?php echo $record['FmFeTransactionRecord']['ordinal']; ?></td>
                <td class="center"><?php echo $record['FmChartAccount']['code']; ?></td>
                <td class="left"><?php echo $record['CodebookConnectionData']['data_code']; ?> - <?php echo $record['CodebookConnectionData']['data_title']; ?></td>                
                <td class="center"><?php echo $record['CodebookDocumentType']['code']; ?></td>
                <td class="center"><?php echo $record['FmFeTransactionRecord']['codebook_document_code']; ?></td>
                <td class="center"><?php echo $record['FmFeTransactionRecord']['transaction_desc']; ?></td>
                <td class="center"><?php echo $record['PrimaryDocumentType']['code']; ?></td>
                <td class="center"><?php echo $record['FmFeTransactionRecord']['primary_document_code']; ?></td>
                <td class="center"><?php echo $record['SecondaryDocumentType']['code']; ?></td>
                <td class="center"><?php echo $record['FmFeTransactionRecord']['secondary_document_code']; ?></td>
                <td class="center">
                    <?php if(!empty($record['FmFeTransactionRecord']['fm_traffic_status_id'])){ ?>
                        <?php echo $record['FmTrafficStatus']['code']; ?>
                    <?php } ?>
                </td>
                <td class="center"><?php echo $record['Currency']['iso']; ?></td>
                <td class="right">
                    <?php 
                        if(!empty($record['FmFeTransactionRecord']['foreign_debit']) || $record['FmFeTransactionRecord']['foreign_debit'] === '0' || $record['FmFeTransactionRecord']['foreign_debit'] === 0){ ?>
                        <?php echo number_format($record['FmFeTransactionRecord']['foreign_debit'], 2, '.', ','); ?>
                    <?php }else{ ?>
                        &nbsp;
                    <?php } ?>                    
                </td>
                <td class="right">
                    <?php 
                        if(!empty($record['FmFeTransactionRecord']['foreign_credit']) || $record['FmFeTransactionRecord']['foreign_credit'] === '0' || $record['FmFeTransactionRecord']['foreign_credit'] === 0){ ?>
                        <?php echo number_format($record['FmFeTransactionRecord']['foreign_credit'], 2, '.', ','); ?>
                    <?php }else{ ?>
                        &nbsp;
                    <?php } ?>                    
                </td>
                <td class="center">
                    <?php if(!empty($record['FmFeTransactionRecord']['exchange_rate_date'])){ ?>
                        <?php echo date('d.m.Y', strtotime($record['FmFeTransactionRecord']['exchange_rate_date'])); ?>
                    <?php }else{ ?>
                        &nbsp;
                    <?php } ?>
                </td>
                <td class="center">
                    <?php if(!empty($record['FmFeTransactionRecord']['exchange_rate'])){ ?>
                        <?php echo number_format($record['FmFeTransactionRecord']['exchange_rate'], 4, '.', ','); ?>
                    <?php }else{ ?>
                        &nbsp;
                    <?php } ?>
                </td>
                <td class="right">
                    <?php 
                        if(!empty($record['FmFeTransactionRecord']['domestic_debit']) || $record['FmFeTransactionRecord']['domestic_debit'] === '0' || $record['FmFeTransactionRecord']['domestic_debit'] === 0){ ?>
                        <?php echo number_format($record['FmFeTransactionRecord']['domestic_debit'], 3, '.', ','); ?>
                    <?php }else{ ?>
                        &nbsp;
                    <?php } ?>                    
                </td>
                <td class="right">
                    <?php 
                        if(!empty($record['FmFeTransactionRecord']['domestic_credit']) || $record['FmFeTransactionRecord']['domestic_credit'] === '0' || $record['FmFeTransactionRecord']['domestic_credit'] === 0){ ?>
                        <?php echo number_format($record['FmFeTransactionRecord']['domestic_credit'], 3, '.', ','); ?>
                    <?php }else{ ?>
                        &nbsp;
                    <?php } ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php $sum_count = 1; ?>
            <?php $total_domestic_debit = 0; ?>
            <?php $total_domestic_credit = 0; ?>
            <?php 
                $colspan = 11;
                if($show_menu){
                    $colspan++;  
                }
            ?>            
            <?php foreach ($record_sum as $sum): ?>
            <tr class="highlight">
                <?php if($sum_count == 1){ ?>
                    <td colspan="<?php echo $colspan; ?>" class="bold right"><?php echo __("Ukupno"); ?></td>
                <?php }else{ ?>
                    <td colspan="<?php echo $colspan; ?>">&nbsp;</td>
                <?php } ?>
                <td class="center"><?php echo $sum['Currency']['iso']; ?></td>
                <td class="right"><?php echo number_format($sum['FmFeTransactionRecord']['sum_foreign_debit'], 2, '.', ','); ?></td>
                <td class="right"><?php echo number_format($sum['FmFeTransactionRecord']['sum_foreign_credit'], 2, '.', ','); ?></td>
                <td colspan="2">&nbsp;</td>
                <td class="right">
                    <?php echo number_format($sum['FmFeTransactionRecord']['sum_domestic_debit'], 3, '.', ','); ?>
                    <?php $total_domestic_debit += $sum['FmFeTransactionRecord']['sum_domestic_debit']; ?>
                </td>
                <td class="right">
                    <?php echo number_format($sum['FmFeTransactionRecord']['sum_domestic_credit'], 3, '.', ','); ?>
                    <?php $total_domestic_credit += $sum['FmFeTransactionRecord']['sum_domestic_credit']; ?>
                </td>
            </tr>
            <?php $sum_count++; ?>
            <?php endforeach; ?>
            <tr class="highlight">
                <td colspan="<?php echo ($colspan + 5); ?>">&nbsp;</td>
                <td class="right"><?php echo number_format($total_domestic_debit, 3, '.', ','); ?></td>
                <td class="right"><?php echo number_format($total_domestic_credit, 3, '.', ','); ?></td>
            </tr>            
        </tbody>
    </table>       
<?php } ?>
<div class="clear"></div>
<?php if(empty($save_form) && $show_menu){ ?>
    <div class="left">
        <ul class="button-bar">
            <li class="first last">
                <?php echo $this->Js->link('<i class="icon-plus-sign" style="color:green;"></i> Dodaj stavku',
                    array('controller' => 'FmFeTransactionRecords', 'action' => 'save', $fe_transaction['FmFeTransaction']['id']),
                    array(
                        'update' => '#records',
                        'buffer' => false,
                        'htmlAttributes' => array('title' => 'Dodaj stavku', 'escape' => false
                    ))
                ); ?>
            </li>
        </ul> 
    </div>
    <?php }else{ ?>
    <?php if($fe_transaction['FmFeTransaction']['transaction_status'] == 'opened'){ ?>
        <div class="left">
            <ul class="button-bar">
                <li class="first last">
                    <?php echo $this->Html->link('<i class="icon-remove-sign" style="color:red;"></i> Storniraj obrazac', array('controller' => 'FmFeTransactionRecords', 'action' => 'cancel', $fe_transaction['FmFeTransaction']['id']), array('escape' => false), __("Da li ste sigurni da želite da stornirate obrazac za knjiženje?")); ?>
                </li>
            </ul> 
        </div>
    <?php } ?>
<?php } ?>
<div class="clear"></div>
<script type="text/javascript">
$('#records').ready(function(){
    //Load drop down menu
    $('.dropit_menu').dropit();
});
</script>