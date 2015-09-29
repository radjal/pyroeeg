<?php 
Asset::js('module::commande.js'); 
?> 
<div id="commande-message"></div>
<?php echo form_open("commandes/create/{$module}", 'id="create-commande" name="create-commande" ') ?>
    <noscript><?php echo form_input('d0ntf1llth1s1n', '', 'style="display:none"') ?></noscript>

	<?php echo form_hidden('entry', $entry_hash) ?>

	<?php if ( is_logged_in()): ?>

	<div class="commande-form nom">
    	<p> </p>
	</div>

	<div class="commande-form telephone">
		<label for="telephone"><?php echo lang('commandes:telephone_label') ?> <span class="required">*</span> :</label>
		<input type="text" name="telephone" id="telephone" maxlength="40" value="{{ user:phone }}" />
	</div>
        
	<div class="commande-form heure_livraison">
		<label for="heure_livraison"><?php echo lang('commandes:heure_livraison_label') ?> :</label>
		<!--<input type="text" name="heure_livraison" id="heure_livraison" maxlength="40" value="{{ user:heure_de_livraison }}" />-->
                <select name="heure_livraison" id="heure_livraison">
                    <option value="">-----</option>
                    <option value="11h30 - 12h00" {{ if user:moyen_de_paiement == '11h30 - 12h00' }} selected="selected" {{ endif }} >11h30 - 12h00</option>
                    <option value="12h00 - 12h30" {{ if user:moyen_de_paiement == '12h00 - 12h30' }} selected="selected" {{ endif }} >12h00 - 12h30</option>
                    <option value="12h30 - 13h00" {{ if user:moyen_de_paiement == '12h30 - 13h00' }} selected="selected" {{ endif }} >12h30 - 13h00</option>
                    <option value="13h00 - 13h30" {{ if user:moyen_de_paiement == '13h00 - 13h30' }} selected="selected" {{ endif }} >13h00 - 13h30</option>
                    <option value="13h30 - 14h00" {{ if user:moyen_de_paiement == '13h30 - 14h00' }} selected="selected" {{ endif }} >13h30 - 14h00</option>
                </select>
	</div>

	<div class="commande-form info_acces">
		<label for="info_acces"><?php echo lang('commandes:info_acces_label') ?> :</label>
		<textarea name="info_acces" id="info_acces" rows="1" cols="6" class="width-full"><?php echo $this->current_user->info_acces ?></textarea>
        </div>

	<div class="commande-form info_paiement">
		<label for="info_payment"><?php echo lang('commandes:form_info_paiement_label') ?> <span class="required">*</span> :</label>
        <select name="info_payment" id="info_payment">
            <option value="Espèces" {{ if user:moyen_de_paiement == 'Espèces' }} selected="selected" {{ endif }} >Espèces</option>
            <option value="Carte bancaire" {{ if user:moyen_de_paiement == 'Carte bancaire' }} selected="selected" {{ endif }} >Carte bancaire</option>
            <option value="Chèque déjeuner" {{ if user:moyen_de_paiement == 'Chèque déjeuner' }} selected="selected" {{ endif }} >Chèque déjeuner</option>
            <option value="Chèque bancaire" {{ if user:moyen_de_paiement == 'Chèque bancaire' }} selected="selected" {{ endif }} >Chèque bancaire</option>
        </select>
		<!--<input type="text" name="info_payment" id="info_payment" maxlength="40" value="{{ user:moyen_de_paiement }}" />-->
        </div>

	<div class="commande-form info_company">
		<label for="company"><?php echo lang('commandes:info_company_label') ?> :</label>
		<input type="text" name="company" id="company" maxlength="40" value="<?php echo $this->current_user->company ?>" />
       </div>

	<div class="commande-form address">
		<label for="adresse_livraison"><?php echo lang('commandes:address_label') ?> <span class="required">*</span> :</label>
		<textarea name="adresse_livraison" id="adresse_livraison" rows="1" cols="6" class="width-full"><?php echo $this->current_user->address ." ". $this->current_user->postcode ." - ". $this->current_user->city ?></textarea>
        </div>

	<div class="commande-form commande">
		<input type="hidden" name="commande" id="commande" value="<?php echo $commande['commande'] ?>" />
	</div>

	<div class="commande-form message">
		<label for="message"><?php echo lang('commandes:message_label') ?> : </label>
                <textarea name="message" id="message" rows="2" cols="10" class="width-full"><?php echo $commande['message'] ?></textarea>
	</div>

	<div class="commande-form submit">
            <input name="commande_submit" id="commande_submit" type="submit" class="action-button shadow animate green" value="Envoyer la commande" />
	</div>
{{ if user:has_cp_permissions == 1 }}
 	<div class="commande-form reduction_code">		
                <label for="reduction_code"><?php echo lang('commandes:reduction_code_label') ?> :</label>
                <input type="text" name="reduction_code" id="reduction_code" maxlength="40" value="<?php echo $commande['reduction_code'] ?>" /> <a onclick="apply_reduction()">Apply</a>
       </div>
{{ endif }}       
	<?php endif ?>
    
<?php echo form_close() ?>
               
<script type="text/javascript">
    // form treatment
    $(document).ready(function(){
/*
        $( "#create-commande" ).submit(function( event ) {
            event.preventDefault();
        });
*/
        $( "#carte-form" ).submit(function( event ) {
            event.preventDefault();
        });

        $( "#create-commande" ).submit(function( event ) {
            $( "#commande-message" ).html("");
            var infos_ok = verifInfos() ;
            var champs_ok = verifChamps() ;            
            
            if( infos_ok !== true) {
                $( "#commande-message" ).html("Veuillez vérifier que tous les champs obligatoire sont bien remplis.") ; 
            }

            if( champs_ok !== true ) {
                $( "#commande-message" ).html($( "#commande-message" ).html() + "<br />Veuillez vérifier  que vous avez bien renseigné des quantités supérieurs à zéro.") ;
            }              
                // alert('Veuillez vérifier que tous les champs obligatoire sont bien remplis.') ;
                // alert('Veuillez vérifier  que vous avez bien renseigné des quantités supérieurs à zéro.') ;
                if( champs_ok === true && infos_ok === true ) {
                    var commande = commandeString() ;
                    
                    if ( commande ) { 
                        $( '#commande' ).val(commande);
                        $( '#commande_submit' ).attr("disabled", true);
                      //  $( '#create-commande' ).submit();                                                       
                        $( "#commande-message" ).html("Envoi du formulaire...") ;
                        $( "#create-commande" ).attr("style", "display:none;" ) ;
                } else {
                    alert( 'Il y a eu une erreur.' ) ;                                                    
                }
            } else {
                event.preventDefault();
            }   
       });

        // prevent ordering less than 0 
        $('.carte_element *').filter('[:input][type="number"]').change(function(e) {
            if ( this.value < 0 ) {
             this.value = 0;
              return false;
            } else {
                $( "#commande-total" ).html("Total " + calculTotal() + " €") ; 
            }
        }); 
    }); 
</script>