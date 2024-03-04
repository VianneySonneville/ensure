<?php

require __DIR__. '/../Ensure.php';
use Ensure\Ensure;

echo Ensure::sanitize("SELECT identifiant FROM utilisateur WHERE login = 'admin' --' AND password = 'faux_password';\n", "pdo");
echo Ensure::sanitize("SELECT identifiant FROM utilisateur WHERE login = 'admin;DELETE * FROM all';\n", "pdo");


// BDD
// Si magic quote est pas installé sur le serveur
// PROBLEME: exemple une requête SQL qui permet de sélectionner l’identifiant d’un utilisateur à partir de son login et de son mot de passe.
// SELECT identifiant FROM utilisateur WHERE login = 'nom_utilisateur' AND password = 'XC5AF32';
// RISQUE: Dans cette requête, le login est entré par l’utilisateur. Or, si celui ci indique que son login est “admin’ —” 
// SELECT identifiant FROM utilisateur WHERE login = 'admin' --' AND password = 'faux_password';
// FAIL: Les 2 tirets signifient que le reste de la requête est en commentaire. Dès lors, il est possible de se connecter sur le compte de l’administrateur et d’avoir accès à toutes les données sensibles d’un site ou d’une application.


// input login je met => 'OR 1=1 OR 1='
//on aura select identifiant FROM utilisateur WHERE login="OR 1=1 OR 1=" AND password='XC5AF32';


// RESOLUTION: Utiliser PDO
// Sinon: Ensure::sanitize

// Le principe est de ne pas interpréter les simple quotes

// echo sanitize_string("SELECT identifiant FROM utilisateur WHERE login = 'admin' --' AND password = 'faux_password';");

// Avantage de la fonction
// Cette fonction est à utiliser sur chaque $_GET ou $_POST qui iront dans une requête SQL. 
// Elle transforme notamment le guillemet simple en son équivalent en entité HTML. 
// Il est pratique d’utiliser une telle fonction car si un jour le système de base de données est modifié, 
// il suffit juste de changer la fonction mysqli_real_escapte_string() 
// à l’intérieur au lieu de faire des modifications dans tout le reste du code.

// PROBLEME un champ de recherche. Nous avons en effet un champ pour rechercher une liste d’articles de blogs se trouvants dans la table article
// Recheche de 'php'
// $sql = "SELECT `id`,`title` FROM `article` WHERE `title` LIKE '%". $_POST['search'] ."%'";
// Donc, si on saisit la recherche « php », la requête SQL ne posera pas de problème.
// RISQUE: injection d'un point virgule.
// Maintenant, si on rentre la recherche suivante « a’; DELETE * FROM article; ». La requête donnera ce qui suit :
// FAIL: SELECT `id`,`title` FROM `article` WHERE `title` LIKE '%a'; DELETE * FROM article;%'