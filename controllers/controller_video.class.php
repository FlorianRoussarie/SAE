<?php

/**
 * @brief Contrôleur permettant de gérer les vidéos.
 */
class ControllerVideo extends Controller
{
    /**
     * @brief Constructeur du contrôleur vidéo.
     * @param \Twig\Environment $twig Environnement Twig.
     * @param \Twig\Loader\FilesystemLoader $loader Chargeur des vues.
     */
    public function __construct(
        \Twig\Environment $twig,
        \Twig\Loader\FilesystemLoader $loader
    ) {
        parent::__construct($twig, $loader);
    }

    /**
     * @brief Affiche la page d'une vidéo, avec la vidéo suivante pour le défilement.
     * @details Paramètre GET facultatif id_video : la vidéo à afficher.
     * S'il est absent ou invalide, une vidéo est tirée au hasard.
     * Page réservée aux comptes connectés.
     * @return void
     */
    public function afficher(): void
    {
        $this->verifierConnexion();

        $idVideo = filter_input(INPUT_GET, 'id_video', FILTER_VALIDATE_INT);
        $managerVideo = new VideoDAO($this->getPdo());

        // Récupération de la vidéo
        $video = null;

        if ($idVideo !== null && $idVideo !== false) {
            $video = $managerVideo->find($idVideo);
        }

        if ($video === null) {
            $video = $managerVideo->getVideoAleatoire();
        }

        // Vidéo suivante : une autre vidéo au hasard
        $videoSuivante = null;

        if ($video !== null) {
            $videoSuivante = $managerVideo->getVideoAleatoire($video->getId());
        }

        // Affichage de la vue
        $template = $this->getTwig()->load('video.html.twig');

        echo $template->render(array(
            'video' => $video,
            'videoSuivante' => $videoSuivante
        ));
    }

    /**
     * @brief Affiche le formulaire ou enregistre une vidéo du compte connecté.
     * @return void
     * @throws \Twig\Error\Error Si le formulaire Twig ne peut pas être affiché.
     * @throws \Exception Si un jeton de formulaire ne peut pas être généré.
     */
    public function publier(): void
    {
        $this->verifierConnexion();

        $titre = '';
        $description = '';
        $erreur = null;
        $message = null;

        if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
            $post = $this->getPost() ?? array();

            $titre = is_string($post['titre'] ?? null)
                ? trim($post['titre'])
                : '';

            $description = is_string($post['description'] ?? null)
                ? trim($post['description'])
                : '';

            $jetonRecu = $post['csrf_publication'] ?? null;
            $jetonSession = $_SESSION['csrf_publication'] ?? null;

            if (empty($post) && empty($_FILES)) {
                $erreur = 'Aucune vidéo reçue. Vérifiez la limite de taille d’envoi.';
            } elseif (
                !is_string($jetonSession)
                || !is_string($jetonRecu)
                || !hash_equals($jetonSession, $jetonRecu)
            ) {
                http_response_code(403);
                $erreur = 'Le formulaire a expiré. Réessayez.';
            } else {
                $longueurTitre = preg_match_all('/./us', $titre);
                $longueurDescription = preg_match_all('/./us', $description);

                if (
                    $titre === ''
                    || $longueurTitre === false
                    || $longueurTitre > 100
                ) {
                    $erreur = 'Saisissez un titre valide de 100 caractères maximum.';
                } elseif (
                    (isset($post['description']) && !is_string($post['description']))
                    || $longueurDescription === false
                    || $longueurDescription > 1000
                ) {
                    $erreur = 'Saisissez une description valide de 1 000 caractères maximum.';
                } else {
                    $erreur = $this->enregistrerPublication($titre, $description);
                }

                if ($erreur === null) {
                    unset($_SESSION['csrf_publication']);
                    $_SESSION['publication_reussie'] = true;

                    header(
                        'Location: index.php?controleur=video&methode=publier',
                        true,
                        303
                    );
                    exit;
                }
            }
        } elseif (!empty($_SESSION['publication_reussie'])) {
            unset($_SESSION['publication_reussie']);
            $message = 'Votre vidéo a été publiée.';
        }

        $_SESSION['csrf_publication'] ??= bin2hex(random_bytes(32));

        $template = $this->getTwig()->load('publication_video.html.twig');

        echo $template->render(array(
            'titre' => $titre,
            'description' => $description,
            'erreur' => $erreur,
            'message' => $message,
            'jetonPublication' => $_SESSION['csrf_publication']
        ));
    }

    /**
     * @brief Stocke la vidéo envoyée et enregistre ses métadonnées.
     * @param string $titre Titre validé.
     * @param string $description Description validée.
     * @return string|null Message d'erreur ou null après enregistrement.
     */
    private function enregistrerPublication(
        string $titre,
        string $description
    ): ?string {
        $fichier = $_FILES['video'] ?? array();
        $codeEnvoi = $fichier['error'] ?? UPLOAD_ERR_NO_FILE;

        if ($codeEnvoi === UPLOAD_ERR_NO_FILE) {
            return 'Sélectionnez une vidéo.';
        }

        if (
            $codeEnvoi === UPLOAD_ERR_INI_SIZE
            || $codeEnvoi === UPLOAD_ERR_FORM_SIZE
        ) {
            return 'La vidéo dépasse la limite de taille d’envoi.';
        }

        $cheminTemporaire = $fichier['tmp_name'] ?? null;

        if (
            $codeEnvoi !== UPLOAD_ERR_OK
            || !is_string($cheminTemporaire)
            || !is_uploaded_file($cheminTemporaire)
        ) {
            return 'L’envoi de la vidéo a échoué.';
        }

        $taille = $fichier['size'] ?? null;

        if (
            !is_int($taille)
            || $taille <= 0
            || $taille > 100 * 1024 * 1024
        ) {
            return 'Sélectionnez une vidéo non vide de 100 Mio maximum.';
        }

        $dossier = dirname(__DIR__) . '/videos/';

        if (!is_dir($dossier) || !is_writable($dossier)) {
            return 'Le stockage des vidéos est indisponible.';
        }

        $fichierStocke = null;

        try {
            $formatsAutorises = array(
                'video/mp4' => 'mp4',
                'video/webm' => 'webm',
                'video/quicktime' => 'mov'
            );

            $type = (new finfo(FILEINFO_MIME_TYPE))
                ->file($cheminTemporaire);

            if (
                $type === false
                || !isset($formatsAutorises[$type])
            ) {
                return 'Sélectionnez une vidéo au format MP4, WebM ou MOV.';
            }

            $nomFichier = bin2hex(random_bytes(16))
                . '.'
                . $formatsAutorises[$type];

            $destination = $dossier . $nomFichier;

            if (!@move_uploaded_file($cheminTemporaire, $destination)) {
                return 'Impossible de stocker la vidéo.';
            }

            $fichierStocke = $destination;

            $video = new Video(
                null,
                $titre,
                $description,
                'videos/' . $nomFichier,
                $taille,
                (int) $_SESSION['id_compte']
            );

            $managerVideo = new VideoDAO($this->getPdo());
            $managerVideo->enregistrerVideo($video);

        } catch (Throwable $e) {
            if (
                $fichierStocke !== null
                && !@unlink($fichierStocke)
            ) {
                error_log(
                    'Impossible de supprimer une vidéo après un échec de publication.'
                );
            }

            error_log(
                'Échec de publication vidéo : ' . get_class($e)
            );

            return 'Impossible d’enregistrer la vidéo. Réessayez.';
        }

        return null;
    }
}