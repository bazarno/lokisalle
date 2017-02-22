<?php
require_once('inc/init.inc.php');

//Effacer le panier
if(isset($_GET['action']) && $_GET['action'] == 'vider'){
	unset($_SESSION['panier']);
} //on ne vide que la partie ['panier']

//supprimer un produit
if(isset($_GET['action']) && $_GET['action'] == 'supprimer'){
	if(isset($_GET['id_produit']) && !empty($_GET['id_produit']) && is_numeric($_GET['id_produit'])){
		retirerProduit($_GET['id_produit']);
	}
}

//incrementation dans la limite du stock
if(isset($_GET['action']) && $_GET['action'] == 'incrementation'){
	if(isset($_GET['id_produit']) && !empty($_GET['id_produit']) && is_numeric($_GET['id_produit'])){
		//requete pour obtenir le stock disponioble pour le produit
		$resultat = $pdo -> prepare("SELECT stock FROM produit WHERE id_produit = :id_produit");
		$resultat -> bindParam(':id_produit', $_GET['id_produit'], PDO:: PARAM_INT);
		$resultat -> execute();

		if($resultat -> rowCount() > 0){
			// transforme en array l'objet resultat
			$produit = $resultat -> fetch(PDO::FETCH_ASSOC);
			// cherche la position du produit dans l'array panier
			$position = array_search($_GET['id_produit'], $_SESSION['panier']['id_produit']);
			if($position !== FALSE){
				// SI la quantité en stock est superieur ou égale à la quantité demandée, on incrémente
				if($produit['stock'] >= $_SESSION['panier']['quantite'][$position] +1){
					$_SESSION['panier']['quantite'][$position] ++;
					header('location:panier.php');
				}
				else{
					// SINON message
					$msg .= '<div class="erreur">Le stock du produit' . $_SESSION['panier']['titre'][$postion] .' est insuffisant </div>';
				}
			}
		}
	}
}

//decrementation tant qu'il y a des produits dans le panier
if(isset($_GET['action']) && $_GET['action'] == 'decrementation'){
	if(isset($_GET['id_produit']) && !empty($_GET['id_produit']) && is_numeric($_GET['id_produit'])){
			$position = array_search($_GET['id_produit'], $_SESSION['panier']['id_produit']);
			if($position !== FALSE){
				// tant qu'il y a des produits on les retirent
				if($_SESSION['panier']['quantite'][$position] > 1){
					$_SESSION['panier']['quantite'][$position] --;
					header('location:panier.php');
				}
				else{
					// sinon on supprime la ligne
					retirerProduit($_GET['id_produit']);
				}
			}
		}
	}

//PAIEMENT
//vérifier que le stock est disponioble : soit il n'y en a plus soit pas assez

if(isset($_POST['paiement']) && !empty($_SESSION['panier']['id_produit'])){
	for($i=0; $i<sizeof($_SESSION['panier']['id_produit']); $i++){
		$id_produit = $_SESSION['panier']['id_produit'][$i];
		//$req = "SELECT stock FROM produit WHERE id_produit = $id_produit";
		$resultat = $pdo -> query("SELECT stock FROM produit WHERE id_produit = $id_produit");

		$produit = $resultat -> fetch(PDO::FETCH_ASSOC);
		debug($produit);
		debug($_SESSION);

		if($produit['stock'] < $_SESSION['panier']['quantite'][$i]){
			$msg .= '<div class="erreur">' . $_SESSION['panier']['titre'][$i] . ' : stock restant : ' . $produit['stock'] . '. Quantité demandée : ' . $_SESSION['panier']['quantite'][$i] . '</div>';

			//2 cas de figure : stock nul ou simplement insuffisant
			if($produit['stock'] > 0){ // stock insuffisant
				$msg .= '<div class="erreur">Le stock du produit : ' . $_SESSION['panier']['titre'][$i] . 'n\'est pas suffisant, votre commande a été modifiée. Veuillez vérifier la nouvelle commande avant de valider.</div>';

				$_SESSION['panier']['quantite'][$i] = $produit['stock'];

			}else{ // stock nul
				$msg .= '<div class="erreur">Le produit : ' . $_SESSION['panier']['titre'][$i] . 'n\'est plus disponible, nous avons supprimer ce produit de votre commande.</div>';

				retirerProduit($_SESSION['panier']['id_produit'][$i]);
				// Comme $i parcourt toutes les lignes du panier. Lorsqu'on supprime une ligne et que les suivantes remontent
				// $i risque d'en rater une en continuant, il faut donc le décrementer pour qu'il remonte
				$i --;
			}
		}
	} //fin du for
	if(empty($msg)){ // msg vide cela veut dire qu'il n'y a pas de probleme de stock, on continu le traitement
		// enregistrement en bdd - table commande
		$id_membre = $_SESSION['membre']['id_membre'];
		$montant = montantTotal();
		$resultat = $pdo -> exec("INSERT INTO commande (id_membre, montant, date_enregistrement, etat) VALUES ('$id_membre', '$montant', NOW(), 'en cours de traitement')");

		//modification des stocks dans la table produit et enregistrement dans la table detail commande
		// on fait une boucle pour effectuer le traitement pour chaque produit
		$id_commande = $pdo -> lastInsertId();
		for($i = 0; $i < sizeof($_SESSION['panier']['id_produit']); $i++){
			$id_produit = $_SESSION['panier']['id_produit'][$i];
			$quantite = $_SESSION['panier']['quantite'][$i];
			$prix = $_SESSION['panier']['prix'][$i];

			$resultat = $pdo -> exec("INSERT INTO details_commande (id_commande, id_produit, quantite, prix) VALUES('$id_commande', '$id_produit', '$quantite', '$prix')");

			$resultat = $pdo -> exec("UPDATE produit set stock = (stock - $quantite)");
		}// fin for

		unset($_SESSION['panier']);
		$msg .='<div class="validation"> Félicitations ! Votre numéro de commande est : ' . $id_commande . '</div>';


		// envoyer un email

		// supprimer le panier

	} // fin if empty($msg)

} //fin if post




$page = 'Panier';
require_once('inc/header.inc.php');
?>
<script type="text/javascript">
			 function CanContinue () {
					 var confRet = window.confirm ("Etes vous sur ?");
					 if (confRet) {
							 //alert ("The operation is continued.");
					 }
					 else {
							 alert ("Suppression annulée");
					 }
			 }
	</script>
<!-- Contenu HTML -->
<h1 class="titre_formulaire">Votre panier</h1>
<?= $msg ?>
<table border="1" style ="border collapse: collapse; cellpadding:7;">
	<tr>
		<th colspan="6">PANIER<?php= (quantitePanier()) ? quantitePanier() . 'Produit(s) dans le panier ' : '' ?></th>
	</tr>
	<tr>
		<th>Photos</th>
		<th>Titre</th>
		<th>Quantité</th>
		<th>Prix unitaire</th>
		<th>Total</th>
		<th>Supprimer</th>
	</tr>
<?php if(empty($_SESSION['panier']['id_produit'])) : ?>
	<tr>
		<td colspan="6">Votre panier est vide</td>
	</tr>
<?php else : ?>
<?php for($i=0; $i < count($_SESSION['panier']['id_produit']); $i++) : ?>
<tr>
	<td><img src="<?= RACINE_SITE ?>photo/<?= $_SESSION['panier']['photo'][$i] ?>" height="30" /></td>
	<td><?= $_SESSION['panier']['titre'][$i] ?></td>
	<td>
		<a href="?action=decrementation&id_produit=<?= $_SESSION['panier']['id_produit'][$i] ?>"><img src="img/moins.png" height="20" style="margin-right:5px;"></a>
		<span style="padding: 3px; border: solid 1px black; text-align: center; width: 20px; display: inline-block"><?= $_SESSION['panier']['quantite'][$i] ?></span>
		<a href="?action=incrementation&id_produit=<?= $_SESSION['panier']['id_produit'][$i] ?>"><img src="img/plus.png" height="20" style="margin-left:5px;"></a>
	</td>
	<td><?= $_SESSION['panier']['prix'][$i] ?> €</td>
	<td><?= $_SESSION['panier']['prix'][$i] * $_SESSION['panier']['quantite'][$i] ?> €</td>
	<td>
		<a href="?action=supprimer&id_produit=<?= $_SESSION['panier']['id_produit'][$i] ?>" onclick="CanContinue ()"><img src="img/delete.png" height="22"/></a>
	</td>
</tr>
<?php endfor; ?>
<tr>
	<td colspan="4">Montant total</td>
	<td colspan="2"><?= montantTotal() ?> €</td>
</tr>
<tr>
	<?php if(userConnecte()) : ?>
		<td colspan="6">
			<form class="none" method="post" action"">
				<input type="hidden" name="montant" value"<?= montantTotal() ?>" />
				<input type="submit" name="paiement" value="payer" />
			</form>
		</td>

	<?php else : ?>
		<tr>
			<td colspan="6">Vous n'êtes pas connecté. Veuillez vous <a href="connexion.php">connecter</a> ou vous <a href="inscription.php">inscrire</a> pour payer votre panier.</td>
		</tr>
	<?php endif; ?>
	</tr>
	<tr>
		<td colspan="6"><a href="?action=vider">Vider votre panier</a></td>
	</tr>
<?php endif; ?>
</table>









<?php
require_once('inc/footer.inc.php');
?>
