<script type="text/javascript">
            function getConfirmation(){
               var retVal = confirm("Etes vous certain ?");
               if( retVal == true ){
                  return true;
               }
               else{
                  return false;
               }
            }
</script>
<?php
require_once('../inc/init.inc.php');

if(!userAdmin()){
	header('location:connexion.php');
}


	$resultat= $pdo -> query(
    "SELECT p.*, s.titre, s.photo
  FROM salle s, produit p
  WHERE p.id_salle = s.id_salle"
  );

	$contenu .= '<table border="1">';
	$contenu .= '<tr>';
	for($i = 0; $i < $resultat -> columnCount(); $i++){
	  $meta = $resultat -> getColumnMeta($i);
    //var_dump($meta);
	  	$contenu .= '<th>' . $meta['name'] . '</th>';
	}
		$contenu .= '<th colspan="2">Actions</th>';
		$contenu .= '</tr>';

	while($produit = $resultat -> fetch(PDO::FETCH_ASSOC)){
	  	$contenu .= '<tr>';
	  foreach ($produit as $indice => $valeur) {
      //var_dump($produit);
      if($indice == 'photo'){
        $contenu .='<td><img src ="../photo/' . $valeur . '" height="100"/></td>';
      }else{
        $contenu.= '<td>' . $valeur . '</td>';
      }
        //debug($produit);
	  }
			$contenu .= '<td><a href="?action=modifier&id_produit=' . $produit['id_produit'] .'"><img src ="' . RACINE_SITE . 'img/edit.png"/></a></td>';
			$contenu .= '<td><a href="?action=supprimer&id_produit=' . $produit['id_produit'] .'" onclick="getConfirmation();" ><img src ="' . RACINE_SITE . 'img/delete.png"/></a></td>';
	  	$contenu .= '</tr>';
	}

		$contenu .= '</table>';




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
		$resultat = $pdo -> prepare("REPLACE INTO produit (id_produit, id_salle, date_arrivee, date_depart, prix, etat) VALUES (:id_produit, :id_salle, :date_arrivee, :date_depart, :prix, :etat)");
		$resultat -> bindParam(':id_produit', $_POST['id_produit'], PDO::PARAM_INT);
	}else{
	//si c'est un ajout on utilise INSERT INTO sans s'occuper de l'id qui s'incrémente tout seul
	$resultat = $pdo -> prepare("INSERT INTO salle (id_salle, date_arrivee, date_depart, prix, etat) VALUES (:id_salle, :date_arrivee, :date_depart, :prix, :etat)");

	}

	$resultat -> bindParam(':id_salle', $_POST['id_salle'], PDO::PARAM_STR);
	$resultat -> bindParam(':date_arrivee', $_POST['date_arrivee'], PDO::PARAM_STR);
	$resultat -> bindParam(':date_depart', $_POST['date_depart'], PDO::PARAM_STR);
	$resultat -> bindParam(':prix', $_POST['prix'], PDO::PARAM_STR);
	$resultat -> bindParam(':etat', $_POST['etat'], PDO::PARAM_STR);


	//on met le execute dans un if pour etre sur que les traitements contenus dans le if
	// ne s'effcuteront qu'en cas de succès de la requête
	if($resultat -> execute()){
		$_GET['action'] = 'affichage';
		$last_id = $pdo -> lastInsertId();
		$msg .= '<dic class="validation">Le produit ' . $last_id . 'a bien été enregistré</div>';
	}


}

if(isset($_GET['action']) && $_GET['action'] == 'supprimer' ){
	if(isset($_GET['id_produit']) && is_numeric($_GET['id_produit'])){
		$resultat = $pdo -> prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
		$resultat -> bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);
		$resultat -> execute();
		if($resultat -> rowCount() > 0){
			$produit = $resultat -> fetch(PDO::FETCH_ASSOC);
			$resultat = $pdo -> exec("DELETE FROM produit WHERE id_produit = $produit[id_produit]");
			if($resultat != FALSE){
				$_GET['action'] = 'affichage';
				$msg .= '<dic class="validation">Le produit N° ' . $produit['id_produit'] . 'a bien été supprimé</div>';
			}
		}
	}
}


require_once('../inc/header.inc.php');

?>
<section>
<div class="container">
		<div class="row">
				<div class="col-md-12">
<!-- Contenu -->
				<h1>Gestion des produits</h1>

				<ul>
					<li><a href="?action=ajout">Ajouter un produit</a></li>
				</ul>
				<hr><br>
				<?= $contenu ?>

				<!-- ajouter et modifier un produit via formulaire
				si une action d'ajout OU de modification est demandée on affiche le formulaire
				-->
				<?php if(isset($_GET['action']) && ($_GET['action'] == 'ajout' || $_GET['action'] == 'modifier')) : ?>

					<?php
					if(isset($_GET['id_produit']) && is_numeric($_GET['id_produit'])){
						$resultat = $pdo -> prepare(
              "SELECT p.*, s.photo, s.titre
              FROM produit p, salle s
              WHERE p.id_salle = s.id_salle
              AND id_produit = $_GET[id_produit]"
            );
						$resultat -> bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);
						if($resultat -> execute()){
							$produit_actuel = $resultat -> fetch(PDO::FETCH_ASSOC);
							//produit_actuel est un array avec toutes les infos du produit à modifier

						}
					}
          //debug($_GET['id_produit']);
					$id_produit = (isset($produit_actuel)) ? $produit_actuel['id_produit'] : '';
          $id_salle = (isset($produit_actuel)) ? $produit_actuel['id_salle'] : '';
					$date_arrivee = (isset($produit_actuel)) ? $produit_actuel['date_arrivee'] : '';
					$date_depart = (isset($produit_actuel)) ? $produit_actuel['date_depart'] : '';
					$prix = (isset($produit_actuel)) ? $produit_actuel['prix'] : '';
					$etat = (isset($produit_actuel)) ? $produit_actuel['etat'] : '';
          $titre = (isset($produit_actuel)) ? $produit_actuel['titre'] : '';

          $photo = (isset($produit_actuel)) ? $produit_actuel['photo'] : '';

					$action = (isset($produit_actuel)) ? 'Modifier' : 'Ajouter' ;
					 ?>


				<form class="formulaire" action="" method="post">
					<h2><?= $action ?> un produit</h2>
					<!-- encrypt permet de recuperer les fichiers uploader grace à la superglobale $_file -->
          <input type="hidden" name="id_produit" value="<?= $id_produit ?>">

          <label>Titre</label><br>
					<input type="text" name="titre" value="<?= $titre ?>"><br>

          <label>Date d'arrivée</label><br>
					<input type="text" name="date_arrivee" value="<?= $date_arrivee ?>"><br>

          <label>Date de départ</label><br>
					<input type="text" name="date_depart" value="<?= $date_depart ?>"><br>

					<label>prix</label><br>
					<input type="text" name="prix" value="<?= $prix ?>"><br>

					<label>etat</label><br>
					<input type="text" name="etat" value="<?= $etat ?>"><br>

          <?php if(isset($salle_actuelle)) : ?>
					<input type="hidden" name"photo_actuelle" value="<?= $photo ?>" />
					<img src="<?= RACINE_SITE ?>photo/<?= $photo ?>" width="100" />
					<?php endif; ?><br>

          <input type="file" name="photo" /><br>

					<br/><br>

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
