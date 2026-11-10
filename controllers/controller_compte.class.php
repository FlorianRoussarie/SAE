<?php
/** @brief Affichage et traitement de l'inscription des comptes SHARE. */
class ControllerCompte extends Controller
{
    /**
     * @brief Initialise le contrôleur selon l'ordre des paramètres du cours.
     * @param \Twig\Environment $twig Environnement Twig.
     * @param \Twig\Loader\FilesystemLoader $loader Chargeur des vues.
     */
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    /**
     * @brief Affiche le formulaire ou réaffiche les saisies non sensibles après une erreur.
     * @param array $erreurs Messages de validation indexés par champ.
     * @param array $valeurs Saisies à conserver, sans mot de passe.
     * @return void
     */
    public function afficherFormulaireInscription(array $erreurs = array(), array $valeurs = array()): void
    {
        $succes = $_SESSION['inscription_reussie'] ?? false;
        unset($_SESSION['inscription_reussie']);
        $template = $this->getTwig()->load('inscription.html.twig');
        echo $template->render(array(
            'menu' => 'inscription',
            'erreurs' => $erreurs,
            'valeurs' => $valeurs,
            'succes' => $succes
        ));
    }

    /**
     * @brief Valide le POST, hashe le mot de passe et enregistre le compte par le DAO.
     * @return void
     */
    public function traiterFormulaireInscription(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            header('Location: index.php?controleur=compte&methode=afficherFormulaireInscription');
            exit;
        }

        $post = $this->getPost() ?? array();
        $erreurs = array();
        $valeurs = array();
        // Longueurs et champs obligatoires du CREATE TABLE Compte fourni.
        $champs = array(
            'pseudo' => array('Pseudo', 50, true),
            'prenom' => array('Prénom', 50, true),
            'nom' => array('Nom', 50, true),
            'adresse_mail' => array('Adresse e-mail', 50, true),
            'telephone' => array('Téléphone', 30, false),
            'date_naissance' => array('Date de naissance', 10, true),
            'adresse' => array('Adresse postale', 250, false)
        );
        foreach ($champs as $champ => $contraintes) {
            if (isset($post[$champ]) && !is_string($post[$champ])) {
                $erreurs[$champ] = $contraintes[0] . ' : saisie invalide.';
                $valeurs[$champ] = '';
                continue;
            }
            $valeurs[$champ] = trim($post[$champ] ?? '');
            $longueur = preg_match_all('/./us', $valeurs[$champ]);
            if ($contraintes[2] && $valeurs[$champ] === '') {
                $erreurs[$champ] = $contraintes[0] . ' : ce champ est obligatoire.';
            } elseif ($longueur === false || $longueur > $contraintes[1]) {
                $erreurs[$champ] = $contraintes[0] . ' : maximum ' . $contraintes[1] . ' caractères.';
            }
        }

        if (!isset($erreurs['adresse_mail'])
            && (filter_var($valeurs['adresse_mail'], FILTER_VALIDATE_EMAIL) === false
                || preg_match('/\A[^@\s]+@[^@\s]+\.[A-Za-z]{2,}\z/', $valeurs['adresse_mail']) !== 1)) {
            $erreurs['adresse_mail'] = 'Saisissez une adresse e-mail valide, par exemple nom@exemple.fr.';
        }

        if (!isset($erreurs['date_naissance'])) {
            $date = $valeurs['date_naissance'];
            if (!preg_match('/^([1-9][0-9]{3})-([0-9]{2})-([0-9]{2})$/', $date, $parties)
                || !checkdate((int) $parties[2], (int) $parties[3], (int) $parties[1])
                || $date > (new DateTimeImmutable('today', new DateTimeZone('Europe/Paris')))->format('Y-m-d')) {
                $erreurs['date_naissance'] = 'Saisissez une date de naissance valide, qui ne soit pas dans le futur.';
            }
        }

        // Ne pas supprimer les espaces du mot de passe ni le renvoyer à Twig.
        $mdp = isset($post['mdp']) && is_string($post['mdp']) ? $post['mdp'] : '';
        $confirmationMdp = isset($post['confirmation_mdp']) && is_string($post['confirmation_mdp']) ? $post['confirmation_mdp'] : '';
        $regexMdp = '/\A(?=.*\p{Lu})(?=.*\p{Ll})(?=.*[0-9])(?=.*[^\p{L}\p{N}\s]).{8,}\z/us';
        if (preg_match($regexMdp, $mdp) !== 1 || strpos($mdp, "\0") !== false) {
            $erreurs['mdp'] = 'Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.';
        } elseif (PASSWORD_DEFAULT === PASSWORD_BCRYPT && strlen($mdp) > 72) {
            $erreurs['mdp'] = 'Le mot de passe est trop long. Choisissez un mot de passe plus court.';
        }
        if ($confirmationMdp === '' || $mdp !== $confirmationMdp) {
            $erreurs['confirmation_mdp'] = 'La confirmation doit être identique au mot de passe.';
        }

        if (empty($erreurs)) {
            $compte = new Compte(
                null,
                $valeurs['pseudo'],
                $valeurs['prenom'],
                $valeurs['nom'],
                $valeurs['adresse_mail'],
                $valeurs['telephone'] !== '' ? $valeurs['telephone'] : null,
                null,
                $valeurs['date_naissance'],
                $valeurs['adresse'] !== '' ? $valeurs['adresse'] : null
            );
            $managerCompte = new CompteDao($this->getPdo());
            $messagesDoublons = array(
                'pseudo' => 'Ce pseudo est déjà utilisé.',
                'adresse_mail' => 'Cette adresse e-mail est déjà utilisée.',
                'telephone' => 'Ce numéro de téléphone est déjà utilisé.'
            );
            try {
                foreach ($managerCompte->findDoublons($compte) as $champ) {
                    $erreurs[$champ] = $messagesDoublons[$champ];
                }
                if (empty($erreurs)) {
                    $compte->setMdp(password_hash($mdp, PASSWORD_DEFAULT));
                    $managerCompte->enregistrerCompte($compte);
                    // Message temporaire uniquement : aucune authentification automatique.
                    $_SESSION['inscription_reussie'] = true;
                    header('Location: index.php?controleur=compte&methode=afficherFormulaireInscription');
                    exit;
                }
            } catch (PDOException $e) {
                if (($e->errorInfo[1] ?? null) == 1062) {
                    // Une inscription concurrente peut créer un doublon après la recherche.
                    $erreurs['general'] = 'Le pseudo, l’adresse e-mail ou le téléphone est déjà utilisé. Modifiez votre saisie.';
                } else {
                    $erreurs['general'] = 'L’inscription n’a pas pu être enregistrée. Veuillez réessayer ultérieurement.';
                }
            } catch (LengthException $e) {
                $erreurs['general'] = 'Le mot de passe hashé ne peut pas être enregistré.';
            }
        }

        $this->afficherFormulaireInscription($erreurs, $valeurs);
    }
}
