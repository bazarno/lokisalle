<?php
require_once('../inc/init.inc.php');

if(!userAdmin()){
	header('location:connexion.php');
}


if(isset($_GET['action']) && $_GET['action'] == 'affichage'){
	//si un affichage est demandé dans l'url, on recupère les infos de tous les produits
	// et on les affiche via des boucles dans un tableau
	$resultat= $pdo -> query("SELECT * FROM salle");

	$contenu .= '<table border="1">';
	$contenu .= '<tr>';
	for($i = 0; $i < $resultat -> columnCount(); $i++){
	  $meta = $resultat -> getColumnMeta($i);
	  	$contenu .= '<th>' . $meta['name'] . '</th>';
	}
		$contenu .= '<th colspan="2">Actions</th>';
		$contenu .= '</tr>';

	while($salle = $resultat -> fetch(PDO::FETCH_ASSOC)){
	  	$contenu .= '<tr>';
	  foreach ($salle as $indice => $valeur) {
				$contenu.= '<td>' . $valeur . '</td>';
	  }
			$contenu .= '<td><a href="?action=modifier&id_salle=' . $salle['id_salle'] .'"><img src ="' . RACINE_SITE . 'img/edit.png"/></a></td>';
			$contenu .= '<td><a href="?action=supprimer&id_salle=' . $salle['id_salle'] .'"><img src ="' . RACINE_SITE . 'img/delete.png"/></a></td>';
	  	$contenu .= '</tr>';
	}

		$contenu .= '</table>';

}



// ajouter et modifier un produit via formulaire

if($_POST){
	//debug($_POST);
	//debug($_FILES);

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
		$resultat = $pdo -> prepare("REPLACE INTO salle (id_salle, titre, description, photo, pays, ville, adresse, cp, capacite, categorie) VALUES (:id_salle, :titre, :description, :photo, :pays, :ville, :adresse, :cp, :capacite, :categorie)");
		$resultat -> bindParam(':id_salle', $_POST['id_salle'], PDO::PARAM_INT);
	}else{
	//si c'est un ajout on utilise INSERT INTO sans s'occuper de l'id qui s'incrémente tout seul
	$resultat = $pdo -> prepare("INSERT INTO salle (titre, description, photo, pays, ville, adresse, cp, capacite, categorie) VALUES (:titre, :description, :photo, :pays, :ville, :adresse, :cp, :capacite, :categorie)");

	}

	$resultat -> bindParam(':titre', $_POST['titre'], PDO::PARAM_STR);
	$resultat -> bindParam(':description', $_POST['description'], PDO::PARAM_STR);
	$resultat -> bindParam(':photo', $_POST['photo'], PDO::PARAM_STR);
	$resultat -> bindParam(':pays', $_POST['pays'], PDO::PARAM_STR);
	$resultat -> bindParam(':ville', $_POST['ville'], PDO::PARAM_STR);
	$resultat -> bindParam(':adresse', $_POST['adresse'], PDO::PARAM_STR);
	$resultat -> bindParam(':cp', $_POST['cp'], PDO::PARAM_INT);
	$resultat -> bindParam(':capacite', $_POST['capacite'], PDO::PARAM_INT);
	$resultat -> bindParam(':categorie', $_POST['categorie'], PDO::PARAM_STR);

	//on met le execute dans un if pour etre sur que les traitements contenus dans le if
	// ne s'effcuteront qu'en cas de succès de la requête
	if($resultat -> execute()){
		$_GET['action'] = 'affichage';
		$last_id = $pdo -> lastInsertId();
		$msg .= '<dic class="validation">La salle ' . $last_id . 'a bien été enregistrée</div>';
	}


}

if(isset($_GET['action']) && $_GET['action'] == 'supprimer' ){
	if(isset($_GET['id_salle']) && is_numeric($_GET['id_salle'])){
		$resultat = $pdo -> prepare("SELECT * FROM salle WHERE id_salle = :id_salle");
		$resultat -> bindParam(':id_salle', $_GET['id_salle'], PDO::PARAM_INT);
		$resultat -> execute();
		if($resultat -> rowCount() > 0){
			$salle = $resultat -> fetch(PDO::FETCH_ASSOC);
			$resultat = $pdo -> exec("DELETE FROM salle WHERE id_salle = $salle[id_salle]");
			if($resultat != FALSE){
				$chemin_de_la_photo_a_supprimer = $_SERVER['DOCUMENT_ROOT'] . RACINE_SITE . 'photo/' . $produit['photo'];

				if(file_exists($chemin_de_la_photo_a_supprimer) && $produit['photo'] != 'default.jpg'){
					unlink($chemin_de_la_photo_a_supprimer);
				}
				$_GET['action'] = 'affichage';
				$msg .= '<dic class="validation">La salle N° ' . $salle['id_salle'] . 'a bien été supprimée</div>';
			}
		}
	}
}


//redirection si pas admin

require_once('../inc/header.inc.php');

?>
<section>
<div class="container">
		<div class="row">
				<div class="col-md-12">
<!-- Contenu -->
				<h1>Gestion des salles</h1>

				<ul>
					<li><a href="?action=affichage">Afficher les salles</a></li>
					<li><a href="?action=ajout">Ajouter une salle</a></li>
				</ul>
				<hr><br>
				<?= $msg ?>
				<?= $contenu ?>

				<!-- ajouter et modifier un produit via formulaire
				si une action d'ajout OU de modification est demandée on affiche le formulaire
				-->
				<?php if(isset($_GET['action']) && ($_GET['action'] == 'ajout' || $_GET['action'] == 'modifier')) : ?>

					<?php
					if(isset($_GET['id_salle']) && is_numeric($_GET['id_salle'])){
						$resultat = $pdo -> prepare("SELECT * FROM salle WHERE id_salle = :id_salle");
						$resultat -> bindParam(':id_salle', $_GET['id_salle'], PDO::PARAM_INT);
						if($resultat -> execute()){
							$salle_actuelle = $resultat -> fetch(PDO::FETCH_ASSOC);
							//produit_actuel est un array avec toutes les infos du produit à modifier
						}
					}

					$titre = (isset($salle_actuelle)) ? $salle_actuelle['titre'] : '';
					$description = (isset($salle_actuelle)) ? $salle_actuelle['description'] : '';
					$photo = (isset($salle_actuelle)) ? $salle_actuelle['photo'] : '';
					$pays = (isset($salle_actuelle)) ? $salle_actuelle['pays'] : '';
					$ville = (isset($salle_actuelle)) ? $salle_actuelle['ville'] : '';
					$adresse = (isset($salle_actuelle)) ? $salle_actuelle['adresse'] : '';
					$cp = (isset($salle_actuelle)) ? $salle_actuelle['cp'] : '';
					$capacite = (isset($salle_actuelle)) ? $salle_actuelle['capacite'] : '';
					$categorie = (isset($salle_actuelle)) ? $salle_actuelle['categorie'] : '';

					$action = (isset($salle_actuelle)) ? 'Modifier' : 'Ajouter' ;
					$id_salle = (isset($salle_actuelle)) ? $salle_actuelle['id_salle'] : '';


					 ?>
				<h2><?= $action ?> une salle</h2>
				<form class="formulaire" action="" method="post">
					<!-- encrypt permet de recuperer les fichiers uploader grace à la superglobale $_file -->
					<input type="hidden" name="id_salle" value="<?= $id_salle ?>">

					<label>Titre</label><br>
					<input type="text" name="titre" value="<?= $titre ?>"><br>

					<label>Description</label><br>
					<textarea name="description" rows="8" cols="50"> <?= $description ?> </textarea><br>

					<?php if(isset($salle_actuelle)) : ?>
					<input type="hidden" name"photo_actuel" value="<?= $photo ?>" />
					<img src="<?= RACINE_SITE ?>photo/<?= $photo ?>" width="100" />
					<?php endif; ?>

					<label>pays</label><br>
					<input type="text" name="pays" value="<?= $pays ?>"><br>

					<label>Ville</label><br>
					<input type="text" name="ville" value="<?= $ville ?>"><br>

					<label>Adresse</label><br>
					<input type="text" name="adresse" value="<?= $adresse ?>"><br>

					<label>Code Postal</label><br>
					<input type="text" name="cp" value="<?= $cp ?>"><br>

					<label>Capacité</label><br>
					<input type="text" name="capacite" value="<?= $capacite ?>"><br><br>

					<label>Catégorie </label><br>
						<select name="categorie">
							<option>-- Selectionnez --</option>
							<option <?= ($categorie == 'Réunion') ? 'selected' : '' ?> value="reunion">Réunion</option>
							<option <?= ($categorie == 'Bureau') ? 'selected' : '' ?> value="bureau">Bureau</option>
							<option <?= ($categorie == 'Formation') ? 'selected' : '' ?> value="formation">Formation</option>
						</select><br/>


						</select><br/><br>

					<input type="submit" value="<?= $action ?>">
					<br><br>


				</form>
		</div>
	</div>
</div>

<?php endif; ?>
</section>


<?php
 require_once('../inc/footer.inc.php')
?>
