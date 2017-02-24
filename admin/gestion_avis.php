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


	//si un affichage est demandé dans l'url, on recupère les infos de tous les produits
	// et on les affiche via des boucles dans un tableau
	$resultat= $pdo -> query("SELECT a.*, s.titre, m.email
  FROM avis a, salle s, membre m
  WHERE a.id_salle = s.id_salle
  AND m.id_membre = a.id_membre"
  );

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

	while($note = $resultat -> fetch(PDO::FETCH_ASSOC)){
	  	$contenu .= '<tr>';
	  foreach ($note as $indice => $valeur) {
			if($indice == 'note'){
        //$contenu.='<span class="glyphicon glyphicon-star"></span></div></td>';
        //for($i = 0; $i < ($indice = 'note').length; $i++){ $contenu.='<td><div class="ratings"><span class="glyphicon glyphicon-star"></span></div></td>'};

      //}else{
        $contenu.= '<td>' . $valeur . '</td>';
      }

			$contenu .= '<td><a href="?action=modifier&id_avis=' . $note['id_avis'] .'"><img src ="' . RACINE_SITE . 'img/edit.png"/></a></td>';
			$contenu .= '<td><a href="?action=supprimer&id_avis=' . $note['id_avis'] .'" onclick="getConfirmation();"><img src ="' . RACINE_SITE . 'img/delete.png"/></a></td>';
	  	$contenu .= '</tr>';
	}

		$contenu .= '</table>';






if($_POST){
	//debug($_POST);
	//debug($_FILES);


	if(isset($_GET['action']) && $_GET['action'] == 'modifier'){
		$resultat = $pdo -> prepare("REPLACE INTO avis (id_avis, id_membre, id_salle, commentaire, note, date_enregistrement) VALUES (:id_avis, :id_membre, :id_salle, :commentaire, :note, NOW())");
		$resultat -> bindParam(':id_avis', $_POST['id_avis'], PDO::PARAM_INT);
  	$resultat -> bindParam(':id_membre', $_POST['id_membre'], PDO::PARAM_INT);
  	$resultat -> bindParam(':id_salle', $_POST['id_salle'], PDO::PARAM_INT);
  	$resultat -> bindParam(':commentaire', $_POST['commentaire'], PDO::PARAM_STR);
  	$resultat -> bindParam(':note', $_POST['note'], PDO::PARAM_STR);
	}


	if($resultat -> execute()){
		$_GET['action'] = 'affichage';
		$last_id = $pdo -> lastInsertId();
		$msg .= '<div class="validation">L avis'  . $last_id . 'a bien été enregistré</div>';
	}


}

if(isset($_GET['action']) && $_GET['action'] == 'supprimer' ){
	if(isset($_GET['id_avis']) && is_numeric($_GET['id_avis'])){
		$resultat = $pdo -> prepare("SELECT * FROM avis WHERE id_avis = :id_avis");
		$resultat -> bindParam(':id_avis', $_GET['id_avis'], PDO::PARAM_INT);
		$resultat -> execute();
		if($resultat -> rowCount() > 0){
			$avis = $resultat -> fetch(PDO::FETCH_ASSOC);
			$resultat = $pdo -> exec("DELETE FROM avis WHERE id_avis = $avis[id_avis]");
			if($resultat != FALSE){
				$_GET['action'] = 'affichage';
				$msg .= '<dic class="validation">L avis N° ' . $avis['id_avis'] . 'a bien été supprimé</div>';
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
				<h1>Gestion des avis</h1>

				<ul>
					<!--<li><a href="?action=affichage">Afficher les avis</a></li>
					<li><a href="?action=ajout">Ajouter un membre</a></li>-->
				</ul>
				<hr><br>
				<?= $contenu ?>

				<!-- ajouter et modifier un produit via formulaire
				si une action d'ajout OU de modification est demandée on affiche le formulaire
				-->
				<?php if(isset($_GET['action']) && $_GET['action'] == 'modifier') : ?>

					<!-- s'il s'agit de modifier un membre on recupère ses infos via l'id dans l'url -->
					<?php
					if(isset($_GET['id_avis']) && is_numeric($_GET['id_avis'])){
						$resultat = $pdo -> prepare("SELECT a.*, s.titre, m.email
            FROM avis a, salle s, membre m
            WHERE a.id_salle = s.id_salle
            AND m.id_membre = a.id_membre
            AND id_avis = :id_avis");
						$resultat -> bindParam(':id_avis', $_GET['id_avis'], PDO::PARAM_INT);
						if($resultat -> execute()){
							$membre_actuel = $resultat -> fetch(PDO::FETCH_ASSOC);
							//produit_actuel est un array avec toutes les infos du produit à modifier
						}
					}

          $id_avis = (isset($membre_actuel)) ? $membre_actuel['id_avis'] : '';
					$id_membre = (isset($membre_actuel)) ? $membre_actuel['id_membre'] : '';
					$id_salle = (isset($membre_actuel)) ? $membre_actuel['id_salle'] : '';
					$commentaire = (isset($membre_actuel)) ? $membre_actuel['commentaire'] : '';
					$note = (isset($membre_actuel)) ? $membre_actuel['note'] : '';
					$email = (isset($membre_actuel)) ? $membre_actuel['email'] : '';
          $titre = (isset($membre_actuel)) ? $membre_actuel['titre'] : '';

					$action = (isset($membre_actuel)) ? 'Modifier' : 'Ajouter' ;

					?>


				<form class="formulaire" action="" method="post">
          <h2><?= $action ?> un avis</h2>
					<input type="hidden" name="id_avis" value="<?= $id_avis ?>">
          <input type="hidden" name="id_membre" value="<?= $id_membre ?>">
          <input type="hidden" name="id_salle" value="<?= $id_salle ?>">

          <label>Salle</label><br>
					<input type="text" name="titre" value="<?= $titre ?>"><br>

          <label>Email</label><br>
					<input type="text" name="email" value="<?= $email ?>"><br>

          <label>Note</label><br>
					<input type="text" name="note" value="<?= $note ?>"><br>

					<label>Commentaire</label><br>
					<textarea name="commentaire" rows="8" cols="50"><?= $commentaire ?></textarea><br>


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
