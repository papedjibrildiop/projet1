<!DOCTYPE html>
<html>
<head>
    <title>Actualite Polytechnicienne</title>
    <link rel="stylesheet" href="C:/xampp/htdocs/ingapp1/style.css">
</head>

<body>
    <header>
        <nav>
            <h1 class="actu"> Actu <span>Polytechnicienne</span></h1>
            <ul>
               <li><a class="actif"  href="actualite.php?page=accueil">Accueil</a></li>
                <li><a href="actualite.php?page=sport">Sport</a></li>
                <link rel="stylesheet" href="style.css">
                <li><a href="actualite.php?page=education">Education</a></li>
                <link rel="stylesheet" href="style.css">
                <li><a href="actualite.php?page=politique">Politique</a></li>
                  <link rel="stylesheet" href="style.css">
            </ul>
        </nav>
    </header>

    <main>
        <?php
        // Code pour afficher le contenu de la page demandée
        $page = isset($_GET['page']) ? $_GET['page'] : 'accueil';

        // Modèle (Model)
        class ConnexionManager
        {
            public static function getInstance()
            {
                $host = 'localhost';
                $dbname = 'mglsi_news';
                $username = 'root';
                $password = '';

                try {
                    $bdd = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
                    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $e) {
                    echo "Erreur de connexion à la base de données : " . $e->getMessage();
                    $bdd = null;
                }

                return $bdd;
            }
        }

        class ModeleActualite
        {
            public static function getContenu($page)
            {
                try {
                    $bdd = ConnexionManager::getInstance();
                    $query = $bdd->prepare('SELECT titre, contenu FROM Article INNER JOIN Categorie ON Article.categorie = Categorie.id WHERE Categorie.libelle = :page');
                    $query->bindParam(':page', $page);
                    $query->execute();
                    $result = $query->fetchAll(PDO::FETCH_ASSOC);
                    $bdd = null;
                    return $result;
                } catch (PDOException $e) {
                    return array();
                }
            }
        }

        class VueActualite
        {
            public static function afficherContenu($contenu)
            {
                if (empty($contenu)) {
                    include 'accueil.php' ;
                } else {
                    foreach ($contenu as $article) {
                        echo '<h2>' . $article['titre'] . '</h2>';
                        echo '<p>' . $article['contenu'] . '</p>';
                        echo '<hr>';
                    }
                }
            }
        }

        class Controller
        {
            public function afficherPage($page)
            {
                $modele = new ModeleActualite();
                $contenu = $modele->getContenu($page);
                VueActualite::afficherContenu($contenu);
            }
        }

        // Logique pour traiter la page demandée
        $controller = new Controller();

        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            $controller->afficherPage($page);
        } else {
            $controller->afficherPage('accueil'); // Affiche la page d'accueil par défaut
        }
        ?>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Actualite Polytechnicienne. Tous droits réservés.</p>
    </footer>
</body>
</html>