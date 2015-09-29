{{ post }}

<?php 
	// @todo should move this to controller
	// prevent passed date from being ordeable   
    $date_passed = false ; 
    $ccs_date_passed = '' ;
    if( format_date($post[0]['date_carte'], "Ymd") < date("Ymd") )
    {
        $date_passed = true ;     
        $ccs_date_passed = 'date-passed' ;
    }
?>
    
{{ if variables:message_sante }}    
<div id="wrapper-message-sante">
    <div id="message-sante">{{ variables:message_sante }}</div>        
</div>
{{ endif }}
    
<div id="carte-jour-wrapper">
<div class="post cartejour">

<div id="blackboard-wrapper">
    <div id="blackboard" class="carte-view">
            <div class="message_jour">{{ message_jour }}</div>
            <div class="carte_titre">
		<?php echo format_date($post[0]['date_carte'], "%A %d %B"); ?> 
            </div>
                    

                    
<form id="carte-form">
    {{ if service_ferme:value == 'Ouvert' }}
                    {{ if entree_1:title or entree_2:title }}

                    <div class="carte_separator">Entrées</div>
                    
			{{ if entree_1:title }}                          
                                <div class="carte_element entree <?php echo $ccs_date_passed ; if($post[0]['entree_1']['stock'] <= 0) echo " outofstock"; ?>">
				<?php if (!$date_passed AND $post[0]['entree_1']['stock'] > 0) : ?>
                                    <input id="entree_1" type="number" value="0" max="{{ entree_1:stock }}"> X 
                                    <input id="entree_1_code" type="hidden" value="{{ entree_1:code }}">
                                    <input id="entree_1_name" type="hidden" value="{{ entree_1:title }}">
                                    <input id="entree_1_price" type="hidden" value="{{ entree_1:rrp }}">
                                    <input id="entree_1_taxcode" type="hidden" value="{{ entree_1:tax_band }}">
				<?php endif ?> 
					{{ entree_1:title }} <span class="carte-prix">{{ entree_1:rrp }} €</span>
				</div>
			{{ endif }}

			{{ if entree_2:title }}                          
				<div class="carte_element entree <?php echo $ccs_date_passed ; if($post[0]['entree_2']['stock'] <= 0) echo " outofstock"; ?>">
				<?php if (!$date_passed AND $post[0]['entree_2']['stock'] > 0) : ?>
                                    <input id="entree_2" type="number" value="0" max="{{ entree_2:stock }}"> X 
					<input id="entree_2_code" type="hidden" value="{{ entree_2:code }}">
					<input id="entree_2_name" type="hidden" value="{{ entree_2:title }}">
					<input id="entree_2_price" type="hidden" value="{{ entree_2:rrp }}">                                
					<input id="entree_2_taxcode" type="hidden" value="{{ entree_2:tax_band }}">
				<?php endif ?> 
					{{ entree_2:title }} <span class="carte-prix">{{ entree_2:rrp }} €</span>
				</div>
			{{ endif }}
                        
                    {{ endif }}

                    {{ if plat_1:title or plat_2:title }}
                    
                    <div class="carte_separator">Plats</div>

			{{ if plat_1:title }}                          
				<div class="carte_element plat <?php echo $ccs_date_passed ; if($post[0]['plat_1']['stock'] <= 0) echo " outofstock"; ?>">
				<?php if (!$date_passed AND $post[0]['plat_1']['stock'] > 0) : ?>
					<input id="plat_1" type="number" value="0" max="{{ plat_1:stock }}"> X 
					<input id="plat_1_code" type="hidden" value="{{ plat_1:code }}">
					<input id="plat_1_name" type="hidden" value="{{ plat_1:title }}">
					<input id="plat_1_price" type="hidden" value="{{ plat_1:rrp }}">			
					<input id="plat_1_taxcode" type="hidden" value="{{ plat_1:tax_band }}">
				<?php endif ?> 
					{{ plat_1:title }} <span class="carte-prix">{{ plat_1:rrp }} €</span>
				</div>
			{{ endif }}

			{{ if plat_2:title }}                          
				<div class="carte_element plat <?php echo $ccs_date_passed ; if($post[0]['plat_2']['stock'] <= 0) echo " outofstock"; ?>">
				<?php if (!$date_passed AND $post[0]['plat_2']['stock'] > 0) : ?>
					<input id="plat_2" type="number" value="0" max="{{ plat_2:stock }}"> X 
					<input id="plat_2_code" type="hidden" value="{{ plat_2:code }}">
					<input id="plat_2_name" type="hidden" value="{{ plat_2:title }}">
					<input id="plat_2_price" type="hidden" value="{{ plat_2:rrp }}">			
					<input id="plat_2_taxcode" type="hidden" value="{{ plat_2:tax_band }}">
				<?php endif ?> 
					{{ plat_2:title }} <span class="carte-prix">{{ plat_2:rrp }} €</span>
				</div>
			{{ endif }}
                        
                    {{ endif }}

                    {{ if dessert_1:title or dessert_2:title }}
                    
                    <div class="carte_separator">Desserts</div>

			{{ if dessert_1:title }}                          
				<div class="carte_element dessert <?php echo $ccs_date_passed ; if($post[0]['dessert_1']['stock'] <= 0) echo " outofstock"; ?>">
				<?php if (!$date_passed AND $post[0]['dessert_1']['stock'] > 0) : ?>
					<input id="dessert_1" type="number" value="0" max="{{ dessert_1:stock }}"> X 
					<input id="dessert_1_code" type="hidden" value="{{ dessert_1:code }}">
					<input id="dessert_1_name" type="hidden" value="{{ dessert_1:title }}">
					<input id="dessert_1_price" type="hidden" value="{{ dessert_1:rrp }}">			
					<input id="dessert_1_taxcode" type="hidden" value="{{ dessert_1:tax_band }}">
				<?php endif ?> 
					{{ dessert_1:title }} <span class="carte-prix">{{ dessert_1:rrp }} €</span>
				</div>
			{{ endif }}

			{{ if dessert_2:title }}                          
				<div class="carte_element dessert <?php echo $ccs_date_passed ; if($post[0]['dessert_2']['stock'] <= 0) echo " outofstock"; ?>">
				<?php if (!$date_passed AND $post[0]['dessert_2']['stock'] > 0) : ?>
					<input id="dessert_2" type="number" value="0" max="{{ dessert_2:stock }}"> X 
					<input id="dessert_2_code" type="hidden" value="{{ dessert_2:code }}">
					<input id="dessert_2_name" type="hidden" value="{{ dessert_2:title }}">
					<input id="dessert_2_taxcode" type="hidden" value="{{ dessert_2:tax_band }}">
					<input id="dessert_2_price" type="hidden" value="{{ dessert_2:rrp }}">		
				<?php endif ?> 
					{{ dessert_2:title }} <span class="carte-prix">{{ dessert_2:rrp }} €</span>
				</div>
			{{ endif }}
                        
                    {{ endif }}

                    {{ if boisson_1:title or boisson_2:title or boisson_3:title or boisson_4:title }}
                    
                    <div class="carte_separator">Boissons</div>

			{{ if boisson_1:title }}                          
				<div class="carte_element boisson <?php echo $ccs_date_passed ; if($post[0]['boisson_1']['stock'] <= 0) echo " outofstock"; ?>">
				<?php if (!$date_passed AND $post[0]['boisson_1']['stock'] > 0) : ?>
					<input id="boisson_1" type="number" value="0" max="{{ boisson_1:stock }}"> X 
					<input id="boisson_1_code" type="hidden" value="{{ boisson_1:code }}">
					<input id="boisson_1_name" type="hidden" value="{{ boisson_1:title }}">
					<input id="boisson_1_price" type="hidden" value="{{ boisson_1:rrp }}">		
					<input id="boisson_1_taxcode" type="hidden" value="{{ boisson_1:tax_band }}">
				<?php endif ?> 
					{{ boisson_1:title }} <span class="carte-prix">{{ boisson_1:rrp }} €</span>
				</div>
			{{ endif }}

			{{ if boisson_2:title }}                          
				<div class="carte_element boisson <?php echo $ccs_date_passed ; if($post[0]['boisson_2']['stock'] <= 0) echo " outofstock"; ?>">
				<?php if (!$date_passed AND $post[0]['boisson_2']['stock'] > 0) : ?>
					<input id="boisson_2" type="number" value="0" max="{{ boisson_2:stock }}"> X 
					<input id="boisson_2_code" type="hidden" value="{{ boisson_2:code }}">
					<input id="boisson_2_name" type="hidden" value="{{ boisson_2:title }}">
					<input id="boisson_2_price" type="hidden" value="{{ boisson_2:rrp }}">		
					<input id="boisson_2_taxcode" type="hidden" value="{{ boisson_2:tax_band }}">
				<?php endif ?> 
					{{ boisson_2:title }} <span class="carte-prix">{{ boisson_2:rrp }} €</span>
				</div>
			{{ endif }}
			
			{{ if boisson_3:title }}                          
				<div class="carte_element boisson <?php echo $ccs_date_passed ; if($post[0]['boisson_3']['stock'] <= 0) echo " outofstock"; ?>">
				<?php if (!$date_passed AND $post[0]['boisson_3']['stock'] > 0) : ?>
					<input id="boisson_3" type="number" value="0" max="{{ boisson_3:stock }}"> X 
					<input id="boisson_3_code" type="hidden" value="{{ boisson_3:code }}">
					<input id="boisson_3_name" type="hidden" value="{{ boisson_3:title }}">
					<input id="boisson_3_price" type="hidden" value="{{ boisson_3:rrp }}">		
					<input id="boisson_3_taxcode" type="hidden" value="{{ boisson_3:tax_band }}">
				<?php endif ?> 
					{{ boisson_3:title }} <span class="carte-prix">{{ boisson_3:rrp }} €</span>
				</div>
			{{ endif }}

			{{ if boisson_4:title }}                          
				<div class="carte_element boisson <?php echo $ccs_date_passed ; if($post[0]['boisson_4']['stock'] <= 0) echo " outofstock"; ?>">
				<?php if (!$date_passed AND $post[0]['boisson_4']['stock'] > 0) : ?>
					<input id="boisson_4" type="number" value="0" max="{{ boisson_4:stock }}"> X 
					<input id="boisson_4_code" type="hidden" value="{{ boisson_4:code }}">
					<input id="boisson_4_name" type="hidden" value="{{ boisson_4:title }}">
					<input id="boisson_4_price" type="hidden" value="{{ boisson_4:rrp }}">		
					<input id="boisson_4_taxcode" type="hidden" value="{{ boisson_4:tax_band }}">
				<?php endif ?> 
					{{ boisson_4:title }} <span class="carte-prix">{{ boisson_4:rrp }} €</span>
				</div>
			{{ endif }}
                        
                    {{ endif }}
    {{ else  }}
        <div class="no-service"> Pas de service le {{ helper:date timestamp=date_carte format="%A %d" }}  </div>
        
    {{ endif }}
</form>
<div class="clearfix"></div>
</div><!-- end blackboard -->

</div>
</div>
                
<div id="commande-comments-wrapper">
    
<div id="commande-zone-wrapper">  
<?php if ( is_logged_in()): ?>

	<?php  if (Settings::get('enable_commandes') AND !$date_passed ) : ?>
	<div id="commande-zone">
            <div id="commande-total"></div>
            <h3>Passer une commande. </h3>
            <p>
                <span>Ces informations sont définis dans votre profil utilisateur, veuillez <a href="edit-profile">renseigner votre profil</a> afin d'éviter de renseigner ces données à chaque fois.</span>
            </p>		
            <div id="commande-form">

				<?php //if ($form_display): ?>
						<?php echo $this->commandes->form() ?>
 
				<?php //endif ?>
            </div>
	</div>
	<?php elseif($date_passed): //date passed?>	
                    <div id="commande-date-passed"><a href="/carte"> La date est passée, vous ne pouvez pas commander.</a></div>		
	<?php  endif ?>
		
<?php else: // not logged in?>	
    <div id="commande-connect">Vous devez être connecté pour passer une commande.</div>
<?php endif ?>			
</div>  



</div>
<div class="clearfix"></div>
</div>

{{ /post }}

