/* 
 * Pour passer la commande
 */

function verifInfos() {
    var infos_ok = true ;
 
    try 
    {
        if($( '[name="telephone"]' ).val().trim() === '') infos_ok = false ;
        if($( '[name="info_payment"] option:selected" ' ).text().trim() === '') infos_ok = false ;
        if($( '[name="adresse_livraison"]' ).val().trim() === '') infos_ok = false ;

    }
    catch(err) 
    {
        //alert('verifInfos ' + err);
    }
    
                 // if($( '[name="info_acces"]' ).val().trim() == '') infos_ok = false ;
                // if($( '[name="info_payment"]' ).val().trim() == '') infos_ok = false ;
     
    //return 
    if (infos_ok) {
        return true ;
    } else {
        return false ;
    }  
}

function verifChamps() {
    var champs_ok = false ;

    try 
   {  
       // vérif carte
       $( '.carte_element *' ).filter('[:input][type="number"]').each(function(){
           if (this.value > 0 ) {
               champs_ok = true ;
           }
       }); 
   } catch(err) 
   {
//       alert('verifChamps ' + err);
   }

    //return 
    if ( champs_ok ) {
        return true ;
    } else {
        return false ;
    }  
}

function commandeString() {
    var commande_str = ''; 
    
    try 
    {  
        $( '.carte_element *' ).filter('[:input][type="number"]').each(function(){
            if(this.id){
                         commande_str += $('#' + this.id + '_code').val() + ':' + encodeURI(this.value) + '|' ;        
            }
        });
    } catch(err) 
    {
        alert('commandeString ' + err);
    }

    if ( commande_str ) {
        return commande_str ;
    } else {
        return false ;
    }
}

function calculTotal() {
    var total_val = 0.00 ; 
    var line_total = 0.00 ;
   
        $( '.carte_element *' ).filter('[:input][type="number"]').each(function(){
                if(this.value > 0){
                    line_total =  this.value * $('#' + this.id + '_price' ).val() ;
                     total_val +=  line_total ;
        }
    });

    return total_val.toFixed(2) ;
}