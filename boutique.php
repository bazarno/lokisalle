<?php
require_once('/inc/init.inc.php');

$resultat = $pdo -> query("SELECT DISTINCT categorie FROM produit");
$categorie = $resultat -> fetchAll(PDO::FETCH_ASSOC);
//fetchAll nous retourne un tableau multidimentionnel

//recuperer les produits par categorie
if(isset($_GET['categorie']) && $_GET['categorie'] != ''){
	$resultat = $pdo -> prepare("SELECT * FROM produit WHERE categorie = :categorie");
	$resultat -> bindParam(':categorie', $_GET['categorie'], PDO::PARAM_STR);
	$resultat -> execute();

	if($resultat -> rowCount() > 0){
		$produits = $resultat -> fetchAll(PDO::FETCH_ASSOC);
	}
	else{
		$resultat = $pdo -> query("SELECT * FROM produit");
		$produits = $resultat -> fetchAll(PDO::FETCH_ASSOC);
		//si on est dans ce else que notre requete n'a rien trouvé dans cette categorie
	}
}
else{
	$resultat = $pdo -> query("SELECT * FROM produit");
	$produits = $resultat -> fetchAll(PDO::FETCH_ASSOC);
}// dans ce else il n'y a pas de categorie dans l'url



$page = 'Boutique';
require_once('/inc/header.inc.php');

?>

<h1>Boutique</h1>
<div class="boutique-gauche">
	<ul>
		<?php foreach($categorie as $valeur) : ?>
		<li><a href="?categorie=<?= $valeur['categorie'] ?>"><?= $valeur['categorie'] ?></a></li>
		<?php endforeach; ?>
	</ul>
</div>

<div class="boutique-droite">
	<?php foreach($produits as $valeur) : ?>
	<div class="boutique-produit">
		<h3><?= $valeur['titre'] ?></h3>
		<a href="fiche_produit.php?id_produit=<?= $valeur['id_produit'] ?>"><img src="<?= RACINE_SITE ?>/photo/<?= $valeur['photo'] ?>" height="100"></a>
		<p style="font_weight: bold; font-size: 20px;"><?= $valeur['prix'] ?>€</p>
		<p style ="height: 40px"><?=substr($valeur['description'], 0, 40) ?></p><br>
		<a style="padding: 5px 15px; background: red; color: white; text-align: center; border 2px solid black; border-radius: 3px; border: 1px solid black;" href="fiche_produit.php?id_produit=<?= $valeur['id_produit'] ?>">Voir la fiche</a>
	</div>
	<?php endforeach; ?>
</div>





<?php
 require_once('/inc/footer.inc.php')
?>
