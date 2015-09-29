<div id="normal-content-wrapper">
<?php 
echo '<pre>' ;
//print_r($commandes); 
echo '</pre>' ;
?>
<?php if(is_array($commandes)) : ?>


    <table cellspacing="0" id="commande-table">
        <thead>
                <tr valign="bottom">
                    <th>Date - <?php echo lang('commandes:address_label') ?></th>
                    <th><?php echo lang('commandes:order_processed_label') ?> ?</th>
                    <th><?php echo lang('commandes:commande_label') ?></th>
                </tr>
        </thead>
        <tbody>

                <?php foreach ($commandes as $commande ) : ?>
                        <tr>
                                <td><?php echo $commande['entry_title'] ?> à <?php echo $commande['adresse_livraison'] ?> &nbsp;</td>
                                <td align="center">
                                    <?php 
                                        if ($commande['is_active'] == 0) 
                                        {
                                        echo lang('global:yes') ;                               
                                        } else {
                                         echo lang('global:no') ;
                                        }                                 
                                    ?>
                                </td>
                                <td><?php echo $commande['order_html'] ; ?><br /></td>
                        </tr>
                <?php endforeach ?>


        </tbody>
    </table>
<?php elseif(!empty($commandes)) : ?>
      <?php echo $commandes  ?>        
<?php endif ?>
</div>
<div id="normal-content-end"></div>