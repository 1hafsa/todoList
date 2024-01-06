<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php

$host = "localhost";
$dbname = "votre_base_de_donnees";
$user = "votre_utilisateur";
$password = "votre_mot_de_passe";

try {
    $bdd = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

// Récupération des tâches depuis la base de données
$req = $bdd->query('SELECT * FROM todo ORDER BY created_at DESC');
$taches = $req->fetchAll(PDO::FETCH_ASSOC);

// Traitement des actions
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = $_POST['id'];

    switch ($action) {
        case 'new':
            // Ajouter une nouvelle tâche
            $title = $_POST['title'];
            $stmt = $bdd->prepare('INSERT INTO todo (title, created_at) VALUES (?, NOW())');
            $stmt->execute([$title]);
            break;

        case 'delete':
            // Supprimer la tâche
            $stmt = $bdd->prepare('DELETE FROM todo WHERE id = ?');
            $stmt->execute([$id]);
            break;

        case 'toggle':
            // Basculer la valeur du champ "done"
            $stmt = $bdd->prepare('UPDATE todo SET done = 1 - done WHERE id = ?');
            $stmt->execute([$id]);
            break;
    }

    // Redirection pour éviter la réexécution du formulaire lors de la actualisation de la page
    header('Location: index.php');
    exit();
}
?>
    
</body>
</html>