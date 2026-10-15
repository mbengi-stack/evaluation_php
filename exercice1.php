<?php

class Imprimante
{
    public const CAPACITE_MAX = 250;
    
    public const AJOUT_MAX = 100;

    private string $nom;
    
    private int $nbFeuilles;
   
    private bool $allumee = false;

    public function __construct(string $nom, int $nbFeuilles)
    {
        if ($nbFeuilles < 0 || $nbFeuilles > self::CAPACITE_MAX) {
            throw new InvalidArgumentException('Le stock initial doit être compris entre 0 et 250 feuilles.');
        }
        $this->nom = $nom;
        $this->nbFeuilles = $nbFeuilles;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getNbFeuilles(): int
    {
        return $this->nbFeuilles;
    }

    public function estAllumee(): bool
    {
        return $this->allumee;
    }

    public function allumer(): void
    {
        $this->allumee = true;
    }

    public function eteindre(): void
    {
        $this->allumee = false;
    }

    // Respecte les limites du bac de l'imprimante
    public function ajouterPapier(int $n): void
    {
        if ($n <= 0) {
            throw new Exception('Il faut ajouter au moins 1 feuille.');
        }
        if ($n > self::AJOUT_MAX) {
            throw new Exception('On ne peut pas ajouter plus de 100 feuilles à la fois.');
        }
        if ($this->nbFeuilles + $n > self::CAPACITE_MAX) {
            throw new Exception('Le bac ne peut pas dépasser 250 feuilles.');
        }
        $this->nbFeuilles += $n;
    }

    public function imprimer(int $n): void
    {
        if ($n <= 0) {
            throw new Exception('Il faut imprimer au moins 1 page.');
        }
        if (!$this->allumee) {
            throw new Exception('L\'imprimante est éteinte.');
        }
        if ($n > $this->nbFeuilles) {
            throw new Exception('Pas assez de papier.');
        }
        $this->nbFeuilles -= $n;
    }
}