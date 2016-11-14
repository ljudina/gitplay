<table class="basic_mini">
    <tbody>
        <tr class="nowrap">
            <td class="bold"><?php echo __("Tekući račun"); ?></td>
            <td><?php echo $business_account['CbBank']['code']; ?> - <?php echo $business_account['FmBusinessAccount']['account_number']; ?> - <?php echo $business_account['Currency']['iso']; ?></td>
            <td class="bold"><?php echo __("Br. Izvoda"); ?></td>
            <td><?php echo $fe_transaction['FmFeBasic']['fe_number']; ?></td>
            <td class="bold"><?php echo __("Na dan"); ?></td>
            <td><?php echo date('d.m.Y', strtotime($fe_transaction['FmFeBasic']['fe_date'])); ?></td>
        </tr>
        <tr class="nowrap">
            <td class="bold"><?php echo __("Komitent"); ?></td>
            <td colspan="3"><?php echo $fe_transaction['Client']['code']; ?> - <?php echo $fe_transaction['Client']['title']; ?></td>            
            <td class="bold"><?php echo __("Priliv/Odliv"); ?></td>
            <td><?php echo $flow_types[$fe_transaction['FmFeTransaction']['flow_type']]; ?></td>            
        </tr>
        <tr class="nowrap">
            <td class="bold"><?php echo __("Vrsta transakcije"); ?></td>
            <td><?php echo $transaction_types[$fe_transaction['FmFeTransactionType']['transaction_type']]; ?></td>
            <td class="bold"><?php echo __("Vrsta komitenta"); ?></td>
            <td><?php echo $payer_recipients[$fe_transaction['FmFeTransaction']['payer_recipient']]; ?></td>
            <td class="bold"><?php echo __("Devizna vrednost"); ?></td>
            <td><?php echo number_format($fe_transaction['FmFeTransaction']['transaction_value'], 2, '.', ','); ?> <?php echo $business_account['Currency']['iso']; ?></td>
        </tr>           
    </tbody>
</table>