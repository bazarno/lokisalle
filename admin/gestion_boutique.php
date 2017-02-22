<?php
require_once('../inc/init.inc.php');

if(!userAdmin()){
	header('location:connexion.php');
}


//recupperer toutes les infos de tous les produits
// Afficher toutes les infos de tous les produits

if(isset($_GET['action']) && $_GET['action'] == 'affichage'){
	//si un affichage est demandé dans l'url, on recupère les infos de tous les produits
	// et on les affiche via des boucles dans un tableau
	$resultat= $pdo -> query("SELECT * FROM produit");

	$contenu .= '<table border="1">';
	$contenu .= '<tr>';
	for($i = 0; $i < $resultat -> columnCount(); $i++){
	  $meta = $resultat -> getColumnMeta($i);
	  	$contenu .= '<th>' . $meta['name'] . '</th>';
	}
		$contenu .= '<th colspan="2">Actions</th>';
		$contenu .= '</tr>';

	while($produit = $resultat -> fetch(PDO::FETCH_ASSOC)){
	  	$contenu .= '<tr>';
	  foreach ($produit as $indice => $valeur) {
			//traitement special pour les photos
			if($indice == 'photo'){
				$contenu .='<td><img src ="../photo/' . $valeur . '" height="100"/></td>';
			}else{
				$contenu.= '<td>' . $valeur . '</td>';
			}
	  }
			$contenu .= '<td><a href="?action=modifier&id_produit=' . $produit['id_produit'] .'"><img src ="' . RACINE_SITE . 'img/edit.png"/></a></td>';
			$contenu .= '<td><a href="?action=supprimer&id_produit=' . $produit['id_produit'] .'"><img src ="' . RACINE_SITE . 'img/delete.png"/></a></td>';
	  	$contenu .= '</tr>';
	}

		$contenu .= '</table>';

}



// ajouter et modifier un produit via formulaire

if($_POST){
	//debug($_POST);
	//debug($_FILES);

	//s'il n'y a pas de photo ajoutée dans le formulaire,
	//on place une photo par defaut, pour éviter que cela soit vide
	$nom_photo = 'default.jpg';

//si c'est une modification
	if(isset($_POST['photo_actuelle'])){
		$nom_photo = $_POST['photo_actuelle'];
	}

// si c'est un ajout
	if(!empty($_FILES['photo']['name'])){
		//renommer la photo pour éviter les doublons
		$nom_photo = $_POST['reference'] . '_' . $_FILES['photo']['name'];
		//on enregistre la photo sur le serveur
		$chemin_photo =$_SERVER['DOCUMENT_ROOT'].RACINE_SITE.'photo/'.$nom_photo;
		//deplace la photo de son emplacement temporaire vers son emplacement definitif
		copy($_FILES['photo']['tmp_name'], $chemin_photo);
	}


	// enregistrement en base
	// si c'est une modification on utilise REPLACE pour ajouter également l'id
	if(isset($_GET['action']) && $_GET['action'] == 'modifier'){
		$resultat = $pdo -> prepare("REPLACE INTO produit (id_produit, reference, categorie, titre, description, couleur, taille, public, photo, prix, stock) VALUES (:id_produit, :reference, :categorie, :titre, :description, :couleur, :taille, :public, '$nom_photo', :prix, :stock)");
		$resultat -> bindParam(':id_produit', $_POST['id_produit'], PDO::PARAM_INT);
	}else{
	//si c'est un ajout on utilise INSERT INTO sans s'occuper de l'id qui s'incrémente tout seul
	$resultat = $pdo -> prepare("INSERT INTO produit (reference, categorie, titre, description, couleur, taille, public, photo, prix, stock) VALUES (:reference, :categorie, :titre, :description, :couleur, :taille, :public, '$nom_photo', :prix, :stock)");

	}

	$resultat -> bindParam(':reference', $_POST['reference'], PDO::PARAM_STR);
	$resultat -> bindParam(':categorie', $_POST['categorie'], PDO::PARAM_STR);
	$resultat -> bindParam(':titre', $_POST['titre'], PDO::PARAM_STR);
	$resultat -> bindParam(':description', $_POST['description'], PDO::PARAM_STR);
	$resultat -> bindParam(':couleur', $_POST['couleur'], PDO::PARAM_STR);
	$resultat -> bindParam(':taille', $_POST['taille'], PDO::PARAM_STR);
	$resultat -> bindParam(':public', $_POST['public'], PDO::PARAM_STR);
	//
	$resultat -> bindParam(':prix', $_POST['prix'], PDO::PARAM_STR); // STR car pas INTeger si c'est un chiffre avec virgule
	//int
	$resultat -> bindParam(':stock', $_POST['stock'], PDO::PARAM_INT);

	//on met le execute dans un if pour etre sur que les traitements contenus dans le if
	// ne s'effcuteront qu'en cas de succès de la requête
	if($resultat -> execute()){
		$_GET['action'] = 'affichage';
		$last_id = $pdo -> lastInsertId();
		$msg .= '<dic class="validation">Le produit N° ' . $last_id . 'a bien été enregistré</div>';
	}


}

//supprimer un produit
// supprimer d'abord la photo du serveur
if(isset($_GET['action']) && $_GET['action'] == 'supprimer' ){
	if(isset($_GET['id_produit']) && is_numeric($_GET['id_produit'])){
		$resultat = $pdo -> prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
		$resultat -> bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);
		$resultat -> execute();
		//si il y a au moins un produit dans la requete via l'id
		if($resultat -> rowCount() > 0){
			$produit = $resultat -> fetch(PDO::FETCH_ASSOC);
			//on reconstitue le chemin absolu de la photo depuis le root du serveur
			$chemin_de_la_photo_a_supprimer = $_SERVER['DOCUMENT_ROOT'] . RACINE_SITE . 'photo/' . $produit['photo'];

			if(file_exists($chemin_de_la_photo_a_supprimer) && $produit['photo'] != 'default.jpg'){
				unlink($chemin_de_la_photo_a_supprimer);
			}
			//ensuite on supprime le produit
			$resultat = $pdo -> exec("DELETE FROM produit WHERE id_produit = $produit[id_produit]");

			if($resultat != FALSE){
				$_GET['action'] = 'affichage';
				$msg .= '<dic class="validation">Le produit N° ' . $produit['id_produit'] . 'a bien été supprimé</div>';
			}
		}
	}
}








//redirection si pas admin



$page = 'Gestion Boutique';
require_once('../inc/header.inc.php');

?>

<!-- Contenu -->
<h1>Gestion de la boutique</h1>

<ul>
	<li><a href="?action=affichage">Afficher les produits</a></li>
	<li><a href="?action=ajout">Ajouter un produit</a></li>
</ul>
<hr><br>
<?= $msg ?>
<?= $contenu ?>


<!-- ajouter et modifier un produit via formulaire
si une action d'ajout OU de modification est demandée on affiche le formulaire
-->
<?php if(isset($_GET['action']) && ($_GET['action'] == 'ajout' || $_GET['action'] == 'modifier')) : ?>

	<!-- s'il s'agit de modifier un produit on recupère ses infos via l'id dans l'url -->
	<?php
	if(isset($_GET['id_produit']) && is_numeric($_GET['id_produit'])){
		$resultat = $pdo -> prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
		$resultat -> bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);
		if($resultat -> execute()){
			$produit_actuel = $resultat -> fetch(PDO::FETCH_ASSOC);
			//produit_actuel est un array avec toutes les infos du produit à modifier
		}
	}
	//toutes les infos du produit sont stockées dans des variables
	//si il y a un produit_actuel (à modfier) on y stocke sa valeur pour pouvoir les afficher dans le Formulaire
	// s'il n'y a pas de produit_actuel c'est donc un ajout donc on affiche rien ('') dans la variable
	$reference = (isset($produit_actuel)) ? $produit_actuel['reference'] : '';
	$categorie = (isset($produit_actuel)) ? $produit_actuel['categorie'] : '';
	$titre = (isset($produit_actuel)) ? $produit_actuel['titre'] : '';
	$description = (isset($produit_actuel)) ? $produit_actuel['description'] : '';
	$couleur = (isset($produit_actuel)) ? $produit_actuel['couleur'] : '';
	$taille = (isset($produit_actuel)) ? $produit_actuel['taille'] : '';
	$photo = (isset($produit_actuel)) ? $produit_actuel['photo'] : '';
	$public = (isset($produit_actuel)) ? $produit_actuel['public'] : '';
	$prix = (isset($produit_actuel)) ? $produit_actuel['prix'] : '';
	$stock = (isset($produit_actuel)) ? $produit_actuel['stock'] : '';

	$action = (isset($produit_actuel)) ? 'Modifier' : 'Ajouter' ;
	$id_produit = (isset($produit_actuel)) ? $produit_actuel['id_produit'] : '';


	 ?>
<h2><?= $action ?> un produit</h2>
<form action="" method="post" enctype="multipart/form-data">
	<!-- encrypt permet de recuperer les fichiers uploader grace à la superglobale $_file -->
	<input type="hidden" name="id_produit" value="<?= $id_produit ?>">

	<label>Référence</label><br>
	<input type="text" name="reference" value="<?= $reference ?>"><br>

	<label>Categorie</label><br>
	<input type="text" name="categorie" value="<?= $categorie ?>"><br>

	<label>Titre</label><br>
	<input type="text" name="titre" value="<?= $titre ?>"><br>

	<label>Description</label><br>
	<textarea name="description" rows="5" cols="40"><?= $description ?></textarea><br>

	<label>Couleur</label><br>
	<input type="text" name="couleur" value="<?= $couleur ?>"><br>

	<label>Taille</label><br>
	<input type="text" name="taille" value="<?= $taille ?>"><br>

	<label>Public: </label>
	<select name="public">
		<option>-- Selectionnez --</option>
		<option <?= ($public == 'm') ? 'selected' : '' ?> value="m">Homme</option>
		<option <?= ($public == 'f') ? 'selected' : '' ?> value="f">Femme</option>
		<option <?= ($public == 'mixte') ? 'selected' : '' ?> value="mixte">Mixte</option>
	</select><br/>
	<br><br>

	<?php if(isset($produit_actuel)) : ?>
	<input type="hidden" name"photo_actuel" value="<?= $photo ?>" />
	<img src="<?= RACINE_SITE ?>photo/<?= $photo ?>" width="100" />
	<?php endif; ?>

	<input type="file" name="photo" /><br>

	<label>Prix</label><br>
	<input type="text" name="prix" value="<?= $prix ?>"><br>

	<label>Stock</label><br>
	<input type="text" name="stock" value="<?= $stock ?>"><br>

	<input type="submit" value="<?= $action ?>"><br>


</form>

<?php endif; ?>



<?php
 require_once('../inc/footer.inc.php')
?>
