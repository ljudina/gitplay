<table>
    <thead>
        <tr>
            <th>&nbsp;</th>
            <th class="right bold"><?php echo $fe_basic['FmBusinessAccount']['Currency']['iso']; ?></th>
            <th class="right bold"><?php echo __('RSD'); ?></th>
        </tr>
    </thead>
    <tbody>    
        <tr>
            <td class="bold"><?php echo __("Prethodni saldo"); ?></td>
            <td class="right">       
                <?php echo number_format($fe_basic['FmFeBasic']['previous_balance_currency'], 2, '.', ','); ?>
            </td>
            <td class="right">
                <?php echo number_format($fe_basic['FmFeBasic']['previous_balance_rsd'], 2, '.', ','); ?>
            </td>
        </tr>            
        <tr>
            <td class="bold"><?php echo __("Tekući priliv"); ?></td>
            <td class="right">
                <?php $inflow_total = 0; ?>
                <?php if(!empty($flow_sums['inflow'])){ ?>
                    <?php echo number_format($flow_sums['inflow']['flow_total'], 2, '.', ','); ?>
                    <?php $inflow_total = round($flow_sums['inflow']['flow_total'], 2); ?>
                <?php }else{ ?>
                    &nbsp;
                <?php } ?>                
            </td>
            <td class="right">
                <?php $inflow_total_rsd = 0; ?>
                <?php if(!empty($flow_sums['inflow'])){ ?>
                    <?php echo number_format($flow_sums['inflow']['flow_total_rsd'], 2, '.', ','); ?>
                    <?php $inflow_total_rsd = round($flow_sums['inflow']['flow_total_rsd'], 2); ?>
                <?php }else{ ?>
                    &nbsp;
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td class="bold"><?php echo __("Tekući odliv"); ?></td>
            <td class="right">
                <?php $outflow_total = 0; ?>
                <?php if(!empty($flow_sums['outflow'])){ ?>
                    <?php echo number_format($flow_sums['outflow']['flow_total'], 2, '.', ','); ?>
                    <?php $outflow_total = round($flow_sums['outflow']['flow_total'], 2); ?>
                <?php }else{ ?>
                    &nbsp;
                <?php } ?>                                
            </td>
            <td class="right">
                <?php $outflow_total_rsd = 0; ?>
                <?php if(!empty($flow_sums['outflow'])){ ?>
                    <?php echo number_format($flow_sums['outflow']['flow_total_rsd'], 2, '.', ','); ?>
                    <?php $outflow_total_rsd = round($flow_sums['outflow']['flow_total_rsd'], 2); ?>
                <?php }else{ ?>
                    &nbsp;
                <?php } ?>                                
            </td>
        </tr>
        <tr>
            <td class="bold"><?php echo __("Konačni saldo"); ?></td>
            <td class="right">
                <?php $flow_total = round($fe_basic['FmFeBasic']['previous_balance_currency'], 2) + $inflow_total - $outflow_total; ?>
                <?php if(!empty($flow_total)){ ?>
                    <?php echo number_format($flow_total, 2, '.', ','); ?>
                <?php }else{ ?>
                    &nbsp;
                <?php } ?>
            </td>
            <td class="right">
                <?php $flow_total_rsd = round($fe_basic['FmFeBasic']['previous_balance_rsd'], 2) + $inflow_total_rsd - $outflow_total_rsd; ?>
                <?php if(!empty($flow_total_rsd)){ ?>
                    <?php echo number_format($flow_total_rsd, 2, '.', ','); ?>
                <?php }else{ ?>
                    &nbsp;
                <?php } ?>
            </td>
        </tr>            
        <tr>
            <td colspan="2" class="bold"><?php echo __("Kursna razlika (za sredstva na deviznom računu)"); ?></td>
            <td class="right">
                <?php $exchange_diff = round($flow_total * $fe_basic['FmFeBasic']['exchange_rate'], 2) - $flow_total_rsd; ?>
                <?php if(!empty($exchange_diff)){ ?>
                    <?php echo number_format($exchange_diff, 2, '.', ','); ?>
                <?php }else{ ?>
                    &nbsp;
                <?php } ?>                
            </td>
        </tr>                      
    </tbody>
</table>
<h5><i class="icon-exchange"></i> <?php echo __('Transakcije'); ?></h5>
<?php if(empty($fe_transactions)){ ?>
    <div class="notice warning">
        <i class="icon-warning-sign icon-large"></i>
        <?php echo __("Za ovaj izvod trenutno nema definisanih transakcija!"); ?>
    </div>
<?php }else{ ?>
    <table>
        <thead>
            <tr>
                <th colspan="9">&nbsp;</th>
            </tr>
            <tr>
                <th>&nbsp;</th>
                <th class="center"><?php echo __("Red. Br."); ?></th>
                <th class="center"><?php echo __("Priliv/Odliv"); ?></th>
                <th class="center"><?php echo __("Vrsta isplatioca/primaoca"); ?></th>
                <th><?php echo __("Šifra komitenta"); ?></th>
                <th><?php echo __("Naziv komitenta"); ?></th>
                <th class="center"><?php echo __("Vrsta transakcije"); ?></th>
                <th class="right"><?php echo __("Ukupna devizna"); ?></th>
                <th class="right"><?php echo __("Ukupna dinarska"); ?></th>                
            </tr>
        </thead>
        <tbody>
            <?php foreach ($fe_transactions as $fe_transaction): ?>
            <tr>
                <td style="white-space: nowrap;">
                    <?php if(empty($fe_basic['FmFeBasic']['user_id_verified'])){ ?>
                    <ul class="dropit_menu filemenu">
                        <li>
                            <a href="#"><i class="icon-cog"></i> Opcije</a>
                            <ul>
                                <?php if($fe_transaction['FmFeTransaction']['transaction_status'] == 'opened'){ ?>
                                    <?php if(!empty($fe_transaction['FmFeTransactionType']['fm_fe_account_scheme_id'])){ ?>
                                        <?php if(empty($fe_transaction['FmFeTransactionRecord'])){ ?>
                                        <li><?php echo $this->Html->link('<i class="icon-check" style="color:orange;"></i> Pregled otvorenih stavki', array('controller' => 'FmFeTransactionEntries', 'action' => 'load_entries', $fe_transaction['FmFeTransaction']['id']), array('escape' => false)); ?></li>                                        
                                        <li><?php echo $this->Html->link('<i class="icon-ok-circle" style="color:green;"></i> Obrazac za evidentiranje devizne transakcije', array('controller' => 'FmFeTransactionEntries', 'action' => 'save', $fe_transaction['FmFeTransaction']['id']), array('escape' => false)); ?></li>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php if(empty($fe_transaction['FmFeTransactionRecord'])){ ?>
                                        <li><?php echo $this->Js->link('<i class="icon-edit"></i> Izmeni transakciju', array('controller' => 'FmFeTransactions', 'action' => 'save', $fe_basic['FmFeBasic']['id'], $fe_transaction['FmFeTransaction']['id']), array('update' => '#transactions', 'buffer' => false, 'htmlAttributes' => array('escape' => false))); ?></li>
                                    <?php } ?>
                                    <li><?php echo $this->Js->link('<i class="icon-lock" style="color:blue;"></i> Zatvori transakciju', array('controller' => 'FmFeTransactions', 'action' => 'close', $fe_transaction['FmFeTransaction']['id']), array('update' => '#transactions', 'buffer' => false, 'htmlAttributes' => array('escape' => false), 'confirm' => __("Da li ste sigurni da želite da zatvorite transakciju pod rednim brojem ".$fe_transaction['FmFeTransaction']['ordinal']."?"))); ?></li>
                                <?php } ?>
                                <?php if(empty($fe_transaction['FmFeTransactionType']['fm_fe_account_scheme_id']) || !empty($fe_transaction['FmFeTransactionRecord'])){ ?>
                                    <li><?php echo $this->Html->link('<i class="icon-key" style="color:purple;"></i> Obrazac za knjiženje deviznih transakcija', array('controller' => 'FmFeTransactionRecords', 'action' => 'index', $fe_transaction['FmFeTransaction']['id']), array('escape' => false)); ?></li>
                                <?php } ?>                                                        
                                <li><?php echo $this->Js->link('<i class="icon-remove" style="color:red;"></i> Obriši transakciju', array('controller' => 'FmFeTransactions', 'action' => 'delete', $fe_transaction['FmFeTransaction']['id']), array('update' => '#transactions', 'buffer' => false, 'htmlAttributes' => array('escape' => false), 'confirm' => __("Da li ste sigurni da želite da obrišete transakciju pod rednim brojem ".$fe_transaction['FmFeTransaction']['ordinal']."?"))); ?></li>                                
                            </ul>
                        </li>
                    </ul>
                    <?php } ?>
                </td>
                <td class="center"><?php echo $fe_transaction['FmFeTransaction']['ordinal']; ?></td>
                <td class="center"><?php echo $flow_types[$fe_transaction['FmFeTransaction']['flow_type']]; ?></td>
                <td class="center"><?php echo $payer_recipients[$fe_transaction['FmFeTransaction']['payer_recipient']]; ?></td>
                <td style="white-space: nowrap;"><?php echo $fe_transaction['Client']['code']; ?></td>
                <td style="white-space: nowrap;"><?php echo $fe_transaction['Client']['title']; ?></td>
                <td style="white-space: nowrap;" class="center"><?php echo $transaction_types[$fe_transaction['FmFeTransactionType']['transaction_type']]; ?></td>
                <td style="white-space: nowrap;" class="right"><?php echo number_format($fe_transaction['FmFeTransaction']['transaction_value'], 2, '.', ','); ?></td>
                <td style="white-space: nowrap;" class="right"><?php echo number_format($fe_transaction['FmFeTransaction']['transaction_value_rsd'], 2, '.', ','); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>   
<?php } ?>
<?php if(empty($save_form)){ ?>
<div id='alert' style="float:left; width:960px;">
    <?php echo $this->Session->flash(); ?>  
</div>      
<div id="add_transaction" style="float:right; margin-top:8px;">
    <ul class="button-bar">
        <li class="first last">
            <?php echo $this->Js->link('<i class="icon-plus-sign" style="color:green;"></i> Dodaj transakciju',
                array('controller' => 'FmFeTransactions', 'action' => 'save', $fe_basic['FmFeBasic']['id']), 
                array(
                    'update' => '#transactions', 
                    'buffer' => false, 
                    'htmlAttributes' => array('title' => 'Dodaj transakciju', 'escape' => false)
                )
            ); ?>
        </li>
    </ul>        
</div>
<?php } ?>
<div class="clear"></div>
<script type="text/javascript">
$('#transactions').ready(function(){
    //Load drop down menu
    $('.dropit_menu').dropit();
});
</script>