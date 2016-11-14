<div class="breadcrumbs_holder">
    <ul class="breadcrumbs">
        <li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
        <li><?php echo $this->Html->link(__('Finansijsko knjigovodstvo'), array('controller' => 'ErpModules', 'action' => 'start', 'financial')); ?></li>
        <li><?php echo $this->Html->link(__('Devizni izvodi banke'), array('controller' => 'FmFeBasics', 'action' => 'index')); ?></li>
        <li class="last"><a href="" onclick="return false"><?php echo __('Pregled izvoda'); ?></a></li>
    </ul>
</div>
<div class="content_data" style="margin-top:0;">    
    <h4><i class="icon-book"></i> <?php echo __('Pregled izvoda br.'); ?> <?php echo $fe_basic['FmFeBasic']['fe_number']; ?></h4>
    <table>
        <thead>
            <tr>
                <th colspan="3"><?php echo __('Osnovni podaci o izvodu'); ?></th>
                <th class="right">
                    <ul class="button-bar small">
                        <li class="first last">
                            <?php echo $this->Html->link('<i class="icon-table"></i>', array('controller' => 'FmFeBasics', 'action' => 'view', $fe_basic['FmFeBasic']['id'], 'excel'), array('title' => 'Štampaj izvod', 'escape' => false)); ?>
                        </li>
                    </ul>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong><?php echo __('Šifra banke'); ?></strong></td>
                <td><?php echo $fe_basic['FmBusinessAccount']['CbBank']['code']; ?></td>
                <td><strong><?php echo __('Naziv banke'); ?></strong></td>
                <td><?php echo $fe_basic['FmBusinessAccount']['CbBank']['name']; ?></td>                    
            </tr>
            <tr>
                <td><strong><?php echo __('Devizna valuta'); ?></strong></td>
                <td><?php echo $fe_basic['FmBusinessAccount']['Currency']['iso']; ?></td>
                <td><strong><?php echo __('Broj tekućeg računa'); ?></strong></td>
                <td><?php echo $fe_basic['FmBusinessAccount']['account_number']; ?></td>
            </tr>
            <tr>
                <td><strong><?php echo __('Broj izvoda'); ?></strong></td>
                <td><?php echo $fe_basic['FmFeBasic']['fe_number']; ?></td>
                <td><strong><?php echo __('Datum izvoda'); ?></strong></td>
                <td><?php echo date('d.m.Y', strtotime($fe_basic['FmFeBasic']['fe_date'])); ?></td>
            </tr>            
            <tr>
                <td><strong><?php echo __('Kurs devizne valute'); ?></strong></td>
                <td><?php echo $fe_basic['FmFeBasic']['exchange_rate']; ?></td>
                <td colspan="2">&nbsp;</td>                
            </tr>
        </tbody>
    </table>
    <div id="transactions">
        <?php echo $this->element('../FmFeTransactions/index'); ?>
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