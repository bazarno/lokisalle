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
        $resultat = $pdo -> prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, 0, NOW())");

        $mdp = md5($_POST['mdp']);
        $resultat -> bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
        $resultat -> bindParam(':mdp',$mdp , PDO::PARAM_STR);
        $resultat -> bindParam(':nom', $_POST['nom'], PDO::PARAM_STR);
        $resultat -> bindParam(':prenom', $_POST['prenom'], PDO::PARAM_STR);
        $resultat -> bindParam(':email', $_POST['email'], PDO::PARAM_STR);
        $resultat -> bindParam(':civilite', $_POST['civilite'], PDO::PARAM_STR);

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

require_once('inc/header.inc.php');
?>

<!-- Contenu html -->
<div class="formulaire">
  <form method="post" action="">
    <div class="form-group row">
      <label for="pseudo" class="col-sm-2 col-form-label">Pseudo</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name="pseudo" placeholder="Pseudo">
      </div>
    </div>

    <div class="form-group row">
      <label for="inputPassword3" class="col-sm-2 col-form-label">Mot de passe</label>
      <div class="col-sm-10">
        <input type="password" class="form-control" name="mdp" placeholder="Mot de passe">
      </div>
    </div>

		<div class="form-group row">
      <label for="nom" class="col-sm-2 col-form-label">Nom</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name="nom" placeholder="nom">
      </div>
    </div>

		<div class="form-group row">
      <label for="prenom" class="col-sm-2 col-form-label">Prénom</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name="prenom" placeholder="prenom">
      </div>
    </div>

		<div class="form-group row">
      <label for="email" class="col-sm-2 col-form-label">Email</label>
      <div class="col-sm-10">
        <input type="email" class="form-control" name="email" placeholder="email">
      </div>
    </div>

		<label>Civilité :</label>
		<select name="civilite">
			<option value="m">Homme</option>
			<option value="f">Femme</option>
		</select><br><br>

    <div class="form-group row">
      <div class="offset-sm-2 col-sm-10">
        <button type="submit" name="connexion" class="btn btn-primary">Valider</button>
      </div>
    </div>
  </form>
</div>
<?php
 require_once('inc/footer.inc.php')
?>
