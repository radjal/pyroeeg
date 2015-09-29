<div id="carte-semaine-wrapper">
    <div class="post cartesemaine">
 <div id="blackboard-wrapper">
     <div id="blackboard">
        <div id="carte-nav-nextweek">
            <a href="carte/voirsemaine/{{ next_week_no }}" class="carte-button action-button shadow animate yellow">
                La semaine prochaine ( N° {{ next_week_no }} )
            </a>
        </div>
        <div id="carte-nav-previousweek">
            <a href="carte/voirsemaine/{{ previous_week_no }}" class="carte-button action-button shadow animate yellow">
                La semaine précédente ( N° {{ previous_week_no }} )
            </a>
        </div>
<div class="clearfix"></div>

{{ if posts }}

<div id="carte-semaine-titre-wrapper">
<div class="carte-semaine-titre">
Semaine {{ week  }}
</div>
<div class="carte-semaine-titre"> Du <?php echo format_date($week_begin, "%d %b"); ?>	
au  <?php echo format_date($week_finish, "%d %B"); ?> 	
</div>
</div>

<div id="texte-choix">Veuillez choisir un jour dans le menu ci desous!</div>
<div id="carte_wrapper">
<span class="clear"></span>
     
        {{ posts }}
 
        <div class="carte{{ if date_passed }} date-passed{{ endif }}">

            {{ if service_ferme:value == 'Ouvert' }}
                    <a href="carte/voirjour/{{ slug }}">

            <div class="message_jour">{{ message_jour }}</div>
            <div class="carte_titre">
				{{ helper:date timestamp=date_carte format="%A %d" }} 
            </div>
                        
                    {{ if entree_1:title or entree_2:title }}
                    
			<div class="carte_separator">Entrées</div>

			{{ if entree_1:title }}                          
				<div class="carte_element entree">
				{{ entree_1:title }}
				<span class="carte-prix">{{ entree_1:rrp }} €</span>
				</div>
			{{ endif }}
                        
			{{ if entree_2:title }}                          
				<div class="carte_element entree">
				{{ entree_2:title }}
				<span class="carte-prix">{{ entree_2:rrp }} €</span>
				</div>
			{{ endif }}
                        
                    {{ endif }}
                        
                    {{ if plat_1:title or plat_2:title }}
                                        
			<div class="carte_separator">Plats</div>

			{{ if plat_1:title }}                          
				<div class="carte_element plat">
				{{ plat_1:title }}
				<span class="carte-prix">{{ plat_1:rrp }} €</span>
				</div>
			{{ endif }}

			{{ if plat_2:title }}                          
				<div class="carte_element plat">
				{{ plat_2:title }}
				<span class="carte-prix">{{ plat_2:rrp }} €</span>
				</div>
			{{ endif }}
                        
                    {{ endif }}
                        
                    {{ if dessert_1:title or dessert_2:title }}
                    
			<div class="carte_separator">Desserts</div>

			{{ if dessert_1:title }}                          
				<div class="carte_element dessert">
				{{ dessert_1:title }}
				<span class="carte-prix">{{ dessert_1:rrp }} €</span>
				</div>
			{{ endif }}

			{{ if dessert_2:title }}                          
				<div class="carte_element dessert">
				{{ dessert_2:title }}
				<span class="carte-prix">{{ dessert_2:rrp }} €</span>
				</div>
			{{ endif }}
                         
                    {{ endif }}
                        
                    </a>     
                  {{ else  }}            
                    <div class="no-service"> Pas de service le {{ helper:date timestamp=date_carte format="%A %d" }}  </div>
                    <div class="message_jour">{{ message_jour }}</div>
                  {{ endif  }} 
		</div>
        {{ /posts }}

</div>
	{{ pagination }}
<div class="clearfix"></div>
</div><!-- end blackboard -->
</div>        
</div>

    
    
{{ else }}
	
<div class="carte-no-posts">{{ helper:lang line="carte:currently_no_posts" }}</div>

{{ endif }}
</div>