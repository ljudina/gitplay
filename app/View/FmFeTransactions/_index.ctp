<table>
    <thead>
        <tr>
            <th colspan="9">&nbsp;</th>
        </tr>
        <tr>
            <th class="center"><?php echo __("Red. Br."); ?></th>
            <th class="center"><?php echo __("Priliv/Odliv"); ?></th>
            <th class="center"><?php echo __("Vrsta isplatioca/primaoca"); ?></th>
            <th><?php echo __("Šifra komitenta"); ?></th>
            <th><?php echo __("Naziv komitenta"); ?></th>
            <th class="center"><?php echo __("Vrsta transakcije"); ?></th>
            <th class="right"><?php echo __("Ukupna devizna"); ?></th>
            <th class="right"><?php echo __("Ukupna dinarska"); ?></th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($fe_transactions as $fe_transaction): ?>
        <tr>
            <td class="center"><?php echo $fe_transaction['FmFeTransaction']['ordinal']; ?></td>
            <td class="center"><?php echo $flow_types[$fe_transaction['FmFeTransaction']['flow_type']]; ?></td>
            <td class="center"><?php echo $payer_recipients[$fe_transaction['FmFeTransaction']['payer_recipient']]; ?></td>
            <td style="white-space: nowrap;"><?php echo $fe_transaction['Client']['code']; ?></td>
            <td style="white-space: nowrap;"><?php echo $fe_transaction['Client']['title']; ?></td>
            <td class="center"><?php echo $transaction_types[$fe_transaction['FmFeTransactionType']['transaction_type']]; ?></td>
            <td class="right"><?php echo number_format($fe_transaction['FmFeTransaction']['transaction_value'], 2, '.', ','); ?></td>
            <td class="right"><?php echo number_format($fe_transaction['FmFeTransaction']['transaction_value_rsd'], 2, '.', ','); ?></td>
            <td style="white-space: nowrap;">
            <?php if(empty($fe_basic['FmFeBasic']['user_id_verified'])){ ?>
                <ul class="button-bar">
                    <li class="first">
                        <?php echo $this->Js->link('<i class="icon-check" style="color:orange;"></i>', array('controller' => 'FmFeTransactions', 'action' => 'select_entry', $fe_basic['FmFeBasic']['id'], $fe_transaction['FmFeTransaction']['id']), array('update' => '#records', 'buffer' => false, 'htmlAttributes' => array('title' => 'Izbor stavki', 'escape' => false))); ?>
                    </li>
                    <li class="last">
                        <?php echo $this->Html->link('<i class="icon-key" style="color:purple;"></i>', array('controller' => 'FmFeTransactions', 'action' => 'delete', $fe_transaction['FmFeTransaction']['id']), array('title' => __('Obrazac za kniženje'), 'escape' => false)); ?>
                    </li>                    
                </ul>             
                <ul class="button-bar">
                    <li class="first">
                        <?php echo $this->Js->link('<i class="icon-edit"></i>', array('controller' => 'FmFeTransactions', 'action' => 'save', $fe_basic['FmFeBasic']['id'], $fe_transaction['FmFeTransaction']['id']), array('update' => '#records', 'buffer' => false, 'htmlAttributes' => array('title' => 'Izmeni transakciju', 'escape' => false))); ?>
                    </li>
                    <li class="last">
                        <?php echo $this->Html->link('<i class="icon-remove" style="color:red;"></i>', array('controller' => 'FmFeTransactions', 'action' => 'delete', $fe_transaction['FmFeTransaction']['id']), array('title' => __('Brisanje transakcije'), 'escape' => false), __("Da li ste sigurni da želite da obrišete transakciju pod rednim brojem ".$fe_transaction['FmFeTransaction']['ordinal']."?")); ?>
                    </li>                    
                </ul> 
            <?php } ?>                           
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>