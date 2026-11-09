<?php

class VideoDAO{
    private $bd;

    public function __construct($bd){
        $this->bd = $bd;
    }

    public function getVideoAleatoire()
    {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "Video ORDER BY RAND() LIMIT 1";
        
        $req = $this->bd->prepare($sql);
        $req->execute();

        return $req->fetch(PDO::FETCH_ASSOC);
    }
}

?>