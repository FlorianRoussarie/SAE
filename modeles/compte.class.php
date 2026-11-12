<?php
/** @brief Données métier d'un compte SHARE, sans accès SQL. */
class Compte
{
    private ?int $id;
    private ?string $pseudo;
    private ?string $prenom;
    private ?string $nom;
    private ?string $adresse_mail;
    private ?string $telephone;
    private ?string $mdp;
    private ?string $date_naissance;
    private ?string $adresse;
    private ?string $statut;
    private ?bool $est_admin;

    /**
     * @brief Initialise les attributs du compte ; l'identifiant est attribué par la base.
     * @param int|null $id Valeur de id.
     * @param string|null $pseudo Valeur de pseudo.
     * @param string|null $prenom Valeur de prenom.
     * @param string|null $nom Valeur de nom.
     * @param string|null $adresse_mail Valeur de adresse_mail.
     * @param string|null $telephone Valeur de telephone.
     * @param string|null $mdp Hash du mot de passe.
     * @param string|null $date_naissance Valeur de date_naissance.
     * @param string|null $adresse Valeur de adresse.
     * @param string|null $statut Valeur de statut.
     * @param bool|null $est_admin Valeur de est_admin.
     */
    public function __construct(
        ?int $id = null,
        ?string $pseudo = null,
        ?string $prenom = null,
        ?string $nom = null,
        ?string $adresse_mail = null,
        ?string $telephone = null,
        ?string $mdp = null,
        ?string $date_naissance = null,
        ?string $adresse = null,
        ?string $statut = null,
        ?bool $est_admin = null
    ) {
        $this->setId($id);
        $this->setPseudo($pseudo);
        $this->setPrenom($prenom);
        $this->setNom($nom);
        $this->setAdresseMail($adresse_mail);
        $this->setTelephone($telephone);
        $this->setMdp($mdp);
        $this->setDateNaissance($date_naissance);
        $this->setAdresse($adresse);
        $this->setStatut($statut);
        $this->setEstAdmin($est_admin);
    }

    /** @brief Retourne id. @return int|null Valeur de id. */
    public function getId(): ?int
    {
        return $this->id;
    }

    /** @brief Affecte id. @param int|null $id Valeur de id. @return void */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /** @brief Retourne pseudo. @return string|null Valeur de pseudo. */
    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    /** @brief Affecte pseudo. @param string|null $pseudo Valeur de pseudo. @return void */
    public function setPseudo(?string $pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    /** @brief Retourne prenom. @return string|null Valeur de prenom. */
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    /** @brief Affecte prenom. @param string|null $prenom Valeur de prenom. @return void */
    public function setPrenom(?string $prenom): void
    {
        $this->prenom = $prenom;
    }

    /** @brief Retourne nom. @return string|null Valeur de nom. */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /** @brief Affecte nom. @param string|null $nom Valeur de nom. @return void */
    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }

    /** @brief Retourne adresse_mail. @return string|null Valeur de adresse_mail. */
    public function getAdresseMail(): ?string
    {
        return $this->adresse_mail;
    }

    /** @brief Affecte adresse_mail. @param string|null $adresse_mail Valeur de adresse_mail. @return void */
    public function setAdresseMail(?string $adresse_mail): void
    {
        $this->adresse_mail = $adresse_mail;
    }

    /** @brief Retourne telephone. @return string|null Valeur de telephone. */
    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    /** @brief Affecte telephone. @param string|null $telephone Valeur de telephone. @return void */
    public function setTelephone(?string $telephone): void
    {
        $this->telephone = $telephone;
    }

    /** @brief Retourne mdp. @return string|null Valeur de mdp. */
    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    /** @brief Affecte mdp. @param string|null $mdp Valeur de mdp. @return void */
    public function setMdp(?string $mdp): void
    {
        $this->mdp = $mdp;
    }

    /** @brief Retourne date_naissance. @return string|null Valeur de date_naissance. */
    public function getDateNaissance(): ?string
    {
        return $this->date_naissance;
    }

    /** @brief Affecte date_naissance. @param string|null $date_naissance Valeur de date_naissance. @return void */
    public function setDateNaissance(?string $date_naissance): void
    {
        $this->date_naissance = $date_naissance;
    }

    /** @brief Retourne adresse. @return string|null Valeur de adresse. */
    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    /** @brief Affecte adresse. @param string|null $adresse Valeur de adresse. @return void */
    public function setAdresse(?string $adresse): void
    {
        $this->adresse = $adresse;
    }

    /** @brief Retourne statut. @return string|null Valeur de statut. */
    public function getStatut(): ?string
    {
        return $this->statut;
    }

    /** @brief Affecte statut. @param string|null $statut Valeur de statut. @return void */
    public function setStatut(?string $statut): void
    {
        $this->statut = $statut;
    }

    /** @brief Retourne est_admin. @return bool|null Valeur de est_admin. */
    public function getEstAdmin(): ?bool
    {
        return $this->est_admin;
    }

    /** @brief Affecte est_admin. @param bool|null $est_admin Valeur de est_admin. @return void */
    public function setEstAdmin(?bool $est_admin): void
    {
        $this->est_admin = $est_admin;
    }
}

