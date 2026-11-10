
<?php

class ControllerVideo extends Controller{
    private $videoDAO;
    private $twig;

    public function __construct($videoDAO, $twig){
        $this->videoDAO = $videoDAO;
        $this->twig = $twig;
    }

    // Afficher le fil de vidéos
    public function defiler(){
        $video = $this->videoDAO->getVideoAleatoire();

        echo $this->twig->render('video.html.twig', [
            'video' => $video
        ]);
    }

    // Publier une vidéo
    public function publier(){

        // Si le formulaire n'a pas encore été envoyé
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo $this->twig->render('publication_video.html.twig');
            return;
        }

        // Récupération des informations du formulaire
        $titre = trim($_POST['titre'] ?? '');
        $description = trim($_POST['description'] ?? '');

        // Vérification du titre
        if ($titre === '' || strlen($titre) > 100) {
            throw new Exception("Le titre est obligatoire et ne doit pas dépasser 100 caractères.");
        }

        // Vérification de la présence du fichier
        if (!isset($_FILES['video'])) {
            throw new Exception("Aucune vidéo n'a été envoyée.");
        }

        $fichier = $_FILES['video'];

        // Vérification des erreurs d'envoi
        if ($fichier['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Erreur lors de l'envoi de la vidéo. Code : " . $fichier['error']);
        }

        // Limite de 100 Mo
        $tailleMax = 100 * 1024 * 1024;

        if ($fichier['size'] > $tailleMax) {
            throw new Exception("La vidéo ne doit pas dépasser 100 Mo.");
        }

        // Vérification du véritable type du fichier
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $type = $finfo->file($fichier['tmp_name']);

        $typesAutorises = [
            'video/mp4' => 'mp4',
            'video/webm' => 'webm',
            'video/quicktime' => 'mov'
        ];

        if (!isset($typesAutorises[$type])) {
            throw new Exception("Format vidéo non autorisé. Formats acceptés : MP4, WebM et MOV.");
        }

        // Vérification de l'utilisateur connecté
        // À adapter selon le nom de votre variable de session
        $idAuteur = $_SESSION['id'] ?? null;

        if (!$idAuteur) {
            throw new Exception("Vous devez être connecté pour publier une vidéo.");
        }

        // Création du nom unique du fichier
        $extension = $typesAutorises[$type];
        $nomFichier = bin2hex(random_bytes(16)) . '.' . $extension;

        // Chemin du dossier de stockage
        $dossier = dirname(__DIR__) . '/videos/';

        // Création du dossier s'il n'existe pas
        if (!is_dir($dossier)) {
            mkdir($dossier, 0755, true);
        }

        $destination = $dossier . $nomFichier;

        // Déplacement de la vidéo sur le serveur
        if (!move_uploaded_file($fichier['tmp_name'], $destination)) {
            throw new Exception("Impossible d'enregistrer la vidéo sur le serveur.");
        }

        try {

            // Création de l'objet Video
            $video = new Video(
                null,
                $titre,
                $description,
                'videos/' . $nomFichier,
                $fichier['size'],
                $idAuteur
            );

            // Enregistrement dans la base de données
            $this->videoDAO->ajouterVideo($video);

        } catch (Throwable $e) {

            // Suppression du fichier si l'enregistrement SQL échoue
            if (file_exists($destination)) {
                unlink($destination);
            }

            throw $e;
        }

        // Affichage du message de réussite
        echo $this->twig->render('publication_video.html.twig', [
            'message' => 'Votre vidéo a été publiée avec succès !'
        ]);
    }
}

?>
