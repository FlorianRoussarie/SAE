<?php

class Controller{
    public function call($methode){
        if (!method_exists($this, $methode)) {
            throw new Exception("Méthode inconnue : " . $methode);
        }

        if ($methode === '__construct' || $methode === 'call') {
            throw new Exception("Méthode non autorisée");
        }

        $this->$methode();
    }
}