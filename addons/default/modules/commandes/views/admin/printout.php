<!--printout.php--><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Impression de la commande</title>

<style type="text/css">
    /* CSS for print */
    /* 	@charset "utf-8";

    @import "../fonts/eraserdustregular.css";
    @import "../fonts/archistico_simple.css";
    @import "../fonts/archistico_bold.css";*/
    @import "addons/default/themes/eeg2/fonts/edwardian_script_itc.css";

    body {
        font-size: 12pt;
        color: black;
        font-weight:100;
    }
    a {

    }
    #site_title {
        width:80%;
        margin: auto;
        font-size: 35pt;
        text-align: center;
    }
    .slogan {
        width:40%;
        margin: auto;
        float:right;
        font-size: 13pt;
        text-align: right;
    }
    #commande-printout{
        width:90%;
        margin: auto;
        margin-top:28pt;
    }
    #info-livraison {
        margin-top:20pt;
    }
    i#nfo-commande {
    }
    #info-message {
        margin-top:20pt;
    }

    p span {
        font-weight: bold;
    }
    h1, h2, h3, h4, h5, h6 {
        font-family: 'Edwardian Script ITC';
        font-size: 23pt;
    }
	.order-product-info {}
    .order-details {
        width: 100%;
    }
    .order-ht, .order-tva, .order-line-taxfree, .order-line-total {
		margin-left: 3pt;
		font-style: italic;
		font-size: 8pt;
    }
	.order-title {

    }
    .order-qty {
        font-weight: bold;
    }
    .order-ttc {
        font-size: 8pt;
		margin-left: 30pt;
		font-weight: bold;
		font-style: italic;
	    }

    .order-ht {
    }
    .order-tva {
    }
	.order-product-info {
		display: inline-block;	
	}
	.order-lines-info {
		display: inline-block;
	}
	.order-lines-total {
		float: right;
		display: inline-block;
	}
    .order-line-taxfree {
 
	}
	.order-line-total{
		font-weight: bold;
	}
	.order-totals {
		float: right;
		margin-top: 20pt;
	}
	.order-total {
       	font-weight: bold;
		margin-left: 10pt;
    }
	.order-total-taxfree {
		margin-top:10pt;
	}
	.footer {
		font-size: 10pt;
		text-align: center;
		bottom: 0px;
		margin-top: 50px;
	}
</style>

</head>

<body>
<div id="commande-printout">

	<h1 id="site_title">Equilibre &amp; gourmandise</h1>
	<h2 class="slogan">Du fait maison au bureau.</h2>

	<p id="info-livraison"><span class="title">Livraison :</span><br/>
            <span>Nom Prénom </span> : <?php echo ucfirst($commande->first_name) ." ". strtoupper($commande->last_name) ." ( ". $commande->username ." )" ?>
            <br/>
            <span>Heure souhaité </span> : <?php echo $commande->heure_livraison ?>
            <br/>
            <span>Moyen de paiement </span> : <?php echo $commande->info_payment ?>
            <br/>
            <span>Adresse </span> : <?php echo $commande->adresse_livraison ?>
            <br/>
            <span>Informations d'accès </span> : <?php echo $commande->info_acces ?>
            <br/>
            <span>N° de téléphone </span> : <?php echo $commande->telephone ?>
            <br/>
            <span>Société </span> : <?php echo $commande->company ?>
	</p>

	<p id="info-commande"><span class="title">Commande :</span><br/>
	<?php echo $commande_html ?>
	</p>
    <p id="info-message"><span class="title">Message :</span><br/>
	<?php echo (Settings::get('commande_markdown') and $commande->parsed != '') ? $commande->parsed : nl2br($commande->message) ?>
    </p> 
<p class="footer">
<?php echo $variables['infos_societe'] ?>
</p>
</div>
</body>
</html>
