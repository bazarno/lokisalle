<?php
require_once('inc/init.inc.php');
if(userConnecte()){
	header('location:profil.php');
}

//traitement pour l'inscription'
if($_POST){
//debug($_POST);
//verif des infos pour le pseudo
$verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#', $_POST['pseudo']);
//fonction qui nous permet de verifier les caractères d'une chaine de carateres
// 1er argument caractère autorisés (expression regulière ou REGEX
// je 2em = la chaine qu'on verifie
// renvoie true ou false


if(!empty($_POST['pseudo'])){
  if($verif_caractere){
    if(strlen($_POST['pseudo']) < 3 || strlen($_POST['pseudo']) > 20){
    $msg .= '<div class="erreur">Veuillez renseigner un pseudo de 3 à 20 caractères</div>';
    }
  }
  else{
  $msg .= '<div class="erreur">Caractères acceptés : A à Z, 0 à 9 et ".", "-", "_"</div>';
  }
}
else{
$msg .= '<div class="erreur">Veuillez renseigner un pseudo</div>';
}

// ++ verifs a faire sur les autres champs


// avant d'inserer le nouveau membre il faut verifier que le pseudo est disponible
if(empty($msg)){ // si $msg est vide tout est ok
$result = $pdo -> prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
$result -> bindParam (':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
$result -> execute();

    if ($result -> rowCount() > 0){ // si sup à 0 c'est qu'il y en a au moins 1
        $msg .= '<div class="erreur">Ce pseudo ' . $_POST['pseudo'] . ' est déjà pris</div>';
    }
    else{
        $resultat = $pdo -> prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, ville, code_postal, adresse, statut) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, :ville, :code_postal, :adresse, 0)");

        $mdp = md5($_POST['mdp']);
        $resultat -> bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
        $resultat -> bindParam(':mdp',$mdp , PDO::PARAM_STR);
        $resultat -> bindParam(':nom', $_POST['nom'], PDO::PARAM_STR);
        $resultat -> bindParam(':prenom', $_POST['prenom'], PDO::PARAM_STR);
        $resultat -> bindParam(':email', $_POST['email'], PDO::PARAM_STR);
        $resultat -> bindParam(':civilite', $_POST['civilite'], PDO::PARAM_STR);
        $resultat -> bindParam(':ville', $_POST['ville'], PDO::PARAM_STR);
        $resultat -> bindParam(':adresse', $_POST['adresse'], PDO::PARAM_STR);
        //ini
        $resultat -> bindParam(':code_postal', $_POST['code_postal'], PDO::PARAM_INT);
        //$resultat -> execute();
        if($resultat -> execute()){
          header('location:connexion.php');
        }

        $msg .='<div class="validation">Inscription validée</div>';

      }
    }
}
$pseudo = (isset($_POST['pseudo'])) ? $_POST['pseudo'] : '';
$nom = (isset($_POST['nom'])) ? $_POST['nom'] : '';
$prenom = (isset($_POST['prenom'])) ? $_POST['prenom'] : '';
$email = (isset($_POST['email'])) ? $_POST['email'] : '';
$civilite = (isset($_POST['civilite'])) ? $_POST['civilite'] : '';
$ville = (isset($_POST['ville'])) ? $_POST['ville'] : '';
$code_postal = (isset($_POST['code_postal'])) ? $_POST['code_postal'] : '';
$adresse = (isset($_POST['adresse'])) ? $_POST['adresse'] : '';

$page = 'Inscription';
require_once('inc/header.inc.php');
?>

<!-- Contenu html -->
<h1 class="titre_formulaire">Inscription</h1>

<form action="" method="post" >
  <?php echo $msg; ?>
	<label>Pseudo :</label><br>
	<input type="text" name="pseudo" value="<?= $pseudo ?>"/><br><br>

	<label>Mot de passe :</label><br>
	<input type="password" name="mdp" value="" /><br><br>

  <label>Nom :</label><br>
	<input type="text" name="nom" value="<?= $nom ?>" /><br><br>

  <label>prénom :</label><br>
	<input type="text" name="prenom" value="<?= $prenom ?>" /><br><br>

  <label>Email :</label><br>
	<input type="text" name="email" value="<?= $email ?>" /><br><br>

  <label>Civilité :</label>
	<select name="civilite">
    <option value="m" <?= ($civilite == 'm') ? 'selected' : '' ?>>Homme</option>
    <option value="f" <?= ($civilite == 'f') ? 'selected' : '' ?>>Femme</option>
  </select><br><br>

  <label>Ville :</label><br>
	<input type="text" name="ville" value="<?= $ville ?>" /><br><br>

  <label>Code postal :</label><br>
	<input type="text" name="code_postal" value="<?= $code_postal ?>" /><br><br>

  <label>Adresse :</label><br>
	<input type="text" name="adresse" value="<?= $adresse ?>" /><br><br>

  <input type="submit" value="inscription" />
</form>

<?php
 require_once('inc/footer.inc.php')
?>
