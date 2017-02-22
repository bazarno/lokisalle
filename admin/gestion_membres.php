<?php
require_once('../inc/init.inc.php');

if(!userAdmin()){
	header('location:connexion.php');
}


if(isset($_GET['action']) && $_GET['action'] == 'affichage'){
	//si un affichage est demandé dans l'url, on recupère les infos de tous les produits
	// et on les affiche via des boucles dans un tableau
	$resultat= $pdo -> query("SELECT * FROM membre");

	$contenu .= '<table border="1">';
	$contenu .= '<tr>';
	for($i = 0; $i < $resultat -> columnCount(); $i++){
	  $meta = $resultat -> getColumnMeta($i);
			if($meta['name'] !='mdp'){
	  	$contenu .= '<th>' . $meta['name'] . '</th>';
			}
	}
		$contenu .= '<th colspan="2">Actions</th>';
		$contenu .= '</tr>';

	while($membre = $resultat -> fetch(PDO::FETCH_ASSOC)){
	  	$contenu .= '<tr>';
	  foreach ($membre as $indice => $valeur) {
			if($indice != 'mdp'){
				$contenu.= '<td>' . $valeur . '</td>';
				}
	  }
			$contenu .= '<td><a href="?action=modifier&id_membre=' . $membre['id_membre'] .'"><img src ="' . RACINE_SITE . 'img/edit.png"/></a></td>';
			$contenu .= '<td><a href="?action=supprimer&id_membre=' . $membre['id_membre'] .'"><img src ="' . RACINE_SITE . 'img/delete.png"/></a></td>';
	  	$contenu .= '</tr>';
	}

		$contenu .= '</table>';

}



// ajouter et modifier un produit via formulaire

if($_POST){
	//debug($_POST);
	//debug($_FILES);



	// enregistrement en base
	// si c'est une modification on utilise REPLACE pour ajouter également l'id
	if(isset($_GET['action']) && $_GET['action'] == 'modifier'){
		$resultat = $pdo -> prepare("REPLACE INTO membre (id_membre, pseudo, mdp, nom, prenom, email, civilite, ville, code_postal, adresse, statut) VALUES (:id_membre, :pseudo, :mdp, :nom, :prenom, :email, :civilite, :ville, :code_postal, :adresse, :statut)");
		$resultat -> bindParam(':id_membre', $_POST['id_membre'], PDO::PARAM_INT);
	}else{
	//si c'est un ajout on utilise INSERT INTO sans s'occuper de l'id qui s'incrémente tout seul
	$resultat = $pdo -> prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, ville, code_postal, adresse, statut) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, :ville, :code_postal, :adresse, :statut)");

	}

	$resultat -> bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
	$resultat -> bindParam(':mdp', $_POST['mdp'], PDO::PARAM_STR);
	$resultat -> bindParam(':nom', $_POST['nom'], PDO::PARAM_STR);
	$resultat -> bindParam(':prenom', $_POST['prenom'], PDO::PARAM_STR);
	$resultat -> bindParam(':email', $_POST['email'], PDO::PARAM_STR);
	$resultat -> bindParam(':civilite', $_POST['civilite'], PDO::PARAM_STR);
	$resultat -> bindParam(':ville', $_POST['ville'], PDO::PARAM_STR);
	$resultat -> bindParam(':adresse', $_POST['adresse'], PDO::PARAM_STR);
	//int
	$resultat -> bindParam(':code_postal', $_POST['code_postal'], PDO::PARAM_INT);
	$resultat -> bindParam(':statut', $_POST['statut'], PDO::PARAM_INT);

	//on met le execute dans un if pour etre sur que les traitements contenus dans le if
	// ne s'effcuteront qu'en cas de succès de la requête
	if($resultat -> execute()){
		$_GET['action'] = 'affichage';
		$last_id = $pdo -> lastInsertId();
		$msg .= '<dic class="validation">Le membre ' . $last_id . 'a bien été enregistré</div>';
	}


}

if(isset($_GET['action']) && $_GET['action'] == 'supprimer' ){
	if(isset($_GET['id_membre']) && is_numeric($_GET['id_membre'])){
		$resultat = $pdo -> prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
		$resultat -> bindParam(':id_membre', $_GET['id_membre'], PDO::PARAM_INT);
		$resultat -> execute();
		if($resultat -> rowCount() > 0){
			$membre = $resultat -> fetch(PDO::FETCH_ASSOC);
			$resultat = $pdo -> exec("DELETE FROM membre WHERE id_membre = $membre[id_membre]");
			if($resultat != FALSE){
				$_GET['action'] = 'affichage';
				$msg .= '<dic class="validation">Le membre N° ' . $membre['id_membre'] . 'a bien été supprimé</div>';
			}
		}
	}
}


//redirection si pas admin



$page = 'Gestion Membres';
require_once('../inc/header.inc.php');

?>

<!-- Contenu -->
<h1>Gestion des membres</h1>

<ul>
	<li><a href="?action=affichage">Afficher les membres</a></li>
	<li><a href="?action=ajout">Ajouter un membre</a></li>
</ul>
<hr><br>
<?= $msg ?>
<?= $contenu ?>

<!-- ajouter et modifier un produit via formulaire
si une action d'ajout OU de modification est demandée on affiche le formulaire
-->
<?php if(isset($_GET['action']) && ($_GET['action'] == 'ajout' || $_GET['action'] == 'modifier')) : ?>

	<!-- s'il s'agit de modifier un membre on recupère ses infos via l'id dans l'url -->
	<?php
	if(isset($_GET['id_membre']) && is_numeric($_GET['id_membre'])){
		$resultat = $pdo -> prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
		$resultat -> bindParam(':id_membre', $_GET['id_membre'], PDO::PARAM_INT);
		if($resultat -> execute()){
			$membre_actuel = $resultat -> fetch(PDO::FETCH_ASSOC);
			//produit_actuel est un array avec toutes les infos du produit à modifier
		}
	}
	//toutes les infos du produit sont stockées dans des variables
	//si il y a un produit_actuel (à modfier) on y stocke sa valeur pour pouvoir les afficher dans le Formulaire
	// s'il n'y a pas de produit_actuel c'est donc un ajout donc on affiche rien ('') dans la variable
	$pseudo = (isset($membre_actuel)) ? $membre_actuel['pseudo'] : '';
	$mdp = (isset($membre_actuel)) ? $membre_actuel['mdp'] : '';
	$nom = (isset($membre_actuel)) ? $membre_actuel['nom'] : '';
	$prenom = (isset($membre_actuel)) ? $membre_actuel['prenom'] : '';
	$email = (isset($membre_actuel)) ? $membre_actuel['email'] : '';
	$civilite = (isset($membre_actuel)) ? $membre_actuel['civilite'] : '';
	$ville = (isset($membre_actuel)) ? $membre_actuel['ville'] : '';
	$code_postal = (isset($membre_actuel)) ? $membre_actuel['code_postal'] : '';
	$adresse = (isset($membre_actuel)) ? $membre_actuel['adresse'] : '';
	$statut = (isset($membre_actuel)) ? $membre_actuel['statut'] : '';

	$action = (isset($membre_actuel)) ? 'Modifier' : 'Ajouter' ;
	$id_membre = (isset($membre_actuel)) ? $membre_actuel['id_membre'] : '';


	 ?>
<h2><?= $action ?> un membre</h2>
<form action="" method="post">
	<!-- encrypt permet de recuperer les fichiers uploader grace à la superglobale $_file -->
	<input type="hidden" name="id_membre" value="<?= $id_membre ?>">

	<label>Pseudo</label><br>
	<input type="text" name="pseudo" value="<?= $pseudo ?>"><br>

	<label>Mot de passe</label><br>
	<input type="text" name="mdp" value="<?= $mdp ?>"><br>

	<label>Nom</label><br>
	<input type="text" name="nom" value="<?= $nom ?>"><br>

	<label>Prenom</label><br>
	<input type="text" name="prenom" value="<?= $prenom ?>"><br>

	<label>Email</label><br>
	<textarea name="email" rows="5" cols="40"><?= $email ?></textarea><br>

	<label>Civilite: </label>
		<select name="civilite">
			<option>-- Selectionnez --</option>
			<option <?= ($civilite == 'm') ? 'selected' : '' ?> value="m">Homme</option>
			<option <?= ($civilite == 'f') ? 'selected' : '' ?> value="f">Femme</option>
		</select><br/><br>

	<label>Ville</label><br>
	<input type="text" name="ville" value="<?= $ville ?>"><br>

	<label>Code Postal</label><br>
	<input type="text" name="code_postal" value="<?= $code_postal ?>"><br>

	<label>Adresse</label><br>
	<input type="text" name="adresse" value="<?= $adresse ?>"><br>

	<label>Statut : </label>
		<select name="statut">
			<option>-- Selectionnez --</option>
			<option <?= ($statut == '0') ? 'selected' : '' ?> value="0">0</option>
			<option <?= ($statut == '1') ? 'selected' : '' ?> value="1">1</option>
		</select><br/><br>

	<input type="submit" value="<?= $action ?>">
	<br><br>


</form>

<?php endif; ?>



<?php
 require_once('../inc/footer.inc.php')
?>
