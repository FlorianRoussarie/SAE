
<?php

class ControllerVideo extends Controller {

    private $videoDAO;
    private $twig;

    public function __construct($videoDAO, $twig) {
        $this->videoDAO = $videoDAO;
        $this->twig = $twig;
    }

    // Afficher le fil de vidéos
    public function defiler() {

        $video = $this->videoDAO->getVideoAleatoire();

        echo $this->twig->render('video.html.twig', [
            'video' => $video
        ]);
    }

    // Publier une vidéo
    public function publier() {

        // Afficher le formulaire si l'utilisateur arrive sur la page
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo $this->twig->render('publication_video.html.twig');
            return;
        }

        $destination = null;

        try {

            // Récupérer les informations du formulaire
            $titre = trim($_POST['titre'] ?? '');
            $description = trim($_POST['description'] ?? '');

            // Vérifier le titre
            if ($titre === '' || mb_strlen($titre) > 100) {
                throw new Exception("Le titre est obligatoire (100 caractères maximum).");
            }

            // Vérifier la description
            if (mb_strlen($description) > 1000) {
                throw new Exception("La description ne doit pas dépasser 1000 caractères.");
            }

            // Vérifier la présence du fichier
            if (!isset($_FILES['video'])) {
                throw new Exception("Aucune vidéo n'a été sélectionnée.");
            }

            $fichier = $_FILES['video'];

            // Vérifier les erreurs d'envoi
            if ($fichier['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("Erreur lors de l'envoi de la vidéo. Code : " . $fichier['error']);
            }

            if (!is_uploaded_file($fichier['tmp_name'])) {
                throw new Exception("Le fichier envoyé n'est pas valide.");
            }

            // Vérifier la taille : 100 Mo maximum
            $tailleMax = 100 * 1024 * 1024;

            if ($fichier['size'] <= 0 || $fichier['size'] > $tailleMax) {
                throw new Exception("La vidéo doit peser entre 1 octet et 100 Mo.");
            }

            // Vérifier le véritable format du fichier
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $type = $finfo->file($fichier['tmp_name']);

            $formatsAutorises = [
                'video/mp4' => 'mp4',
                'video/webm' => 'webm',
                'video/quicktime' => 'mov'
            ];

            if (!isset($formatsAutorises[$type])) {
                throw new Exception("Format non autorisé. Utilisez MP4, WebM ou MOV.");
            }

            // Identifiant temporaire de l'auteur pour les tests
            // À remplacer lorsque la connexion sera développée
            $idAuteur = 1;

            // Générer un nom de fichier unique
            $extension = $formatsAutorises[$type];
            $nomFichier = bin2hex(random_bytes(16)) . '.' . $extension;

            // Définir le dossier de stockage
            $dossier = dirname(__DIR__) . '/videos/';

            if (!is_dir($dossier) && !mkdir($dossier, 0755, true)) {
                throw new Exception("Impossible de créer le dossier vidéos.");
            }

            if (!is_writable($dossier)) {
                throw new Exception("Le dossier vidéos n'est pas accessible en écriture.");
            }

            $destination = $dossier . $nomFichier;

            //Déplacer le fichier dans le dossier videos
            if (!move_uploaded_file($fichier['tmp_name'], $destination)) {
                throw new Exception("Impossible de stocker la vidéo.");
            }

            // Créer un objet Video
            $video = new Video(
                null,
                $titre,
                $description,
                'videos/' . $nomFichier,
                $fichier['size'],
                $idAuteur
            );

            //  Enregistrer les informations dans la base de données
            $this->videoDAO->ajouterVideo($video);

            //Afficher le message de réussite
            echo $this->twig->render('publication_video.html.twig', [
                'message' => 'Votre vidéo a été publiée avec succès !'
            ]);

        } catch (Throwable $e) {

            // Supprimer le fichier si une erreur survient après son stockage
            if ($destination !== null && file_exists($destination)) {
                unlink($destination);
            }

            // Afficher le message d'erreur
            echo $this->twig->render('publication_video.html.twig', [
                'erreur' => $e->getMessage()
            ]);
        }
    }
}

?>
