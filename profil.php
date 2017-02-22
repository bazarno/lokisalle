<?php
require_once('inc/init.inc.php');

//redirection si pas connecté
if(!userConnecte()){
	header('location:connexion.php');
}

extract($_SESSION['membre']);

$page = 'Profil';
require_once('inc/header.inc.php');

?>
<!-- Contenu html -->
<h1 class="titre_formulaire">Profil de  <?= $pseudo ?></h1>
<div class="profil_infos">
	<ul>
		<li>Pseudo : <b><?= $pseudo ?></b></li>
		<li>Prénom : <?= $prenom ?></li>
		<li>Nom : <?= $nom ?></li>
		<li>Adresse : <?= $adresse ?></li>
		<li>Ville : <?= $ville ?></li>
	</ul>


<?php
 require_once('inc/footer.inc.php')
?>
