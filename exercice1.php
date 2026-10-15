<?php
//test
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

// ----------------------------- TESTS -----------------------------

$imprimante = new Imprimante('Salle 204', 40);
echo "Au départ : " . $imprimante->getNbFeuilles() . " feuilles\n";


try {
    $imprimante->imprimer(10);
    echo "ERREUR : l'impression aurait dû être refusée\n";
} catch (Exception $e) {
    echo "Test 1 OK, refusé : " . $e->getMessage() . "\n";
}


$imprimante->ajouterPapier(80);
echo "Test 2 : après ajout de 80, stock = " . $imprimante->getNbFeuilles() . " (attendu : 120)\n";


$imprimante->allumer();
$imprimante->imprimer(30);
echo "Test 3 : après impression de 30 pages, stock = " . $imprimante->getNbFeuilles() . " (attendu : 90)\n";


try {
    $imprimante->imprimer(100);
    echo "ERREUR : l'impression aurait dû être refusée\n";
} catch (Exception $e) {
    echo "Test 4 OK, refusé : " . $e->getMessage() . "\n";
}
echo "Test 4 : le stock reste à " . $imprimante->getNbFeuilles() . " (attendu : 90)\n";


try {
    $imprimante->ajouterPapier(120);
    echo "ERREUR : l'ajout aurait dû être refusé\n";
} catch (Exception $e) {
    echo "Test 5 OK, refusé : " . $e->getMessage() . "\n";
}


try {
    $imprimante->ajouterPapier(0);
    echo "ERREUR : l'ajout de 0 aurait dû être refusé\n";
} catch (Exception $e) {
    echo "Test 6 OK, refusé : " . $e->getMessage() . "\n";
}
try {
    $imprimante->ajouterPapier(-5);
    echo "ERREUR : l'ajout de -5 aurait dû être refusé\n";
} catch (Exception $e) {
    echo "Test 6 OK, refusé : " . $e->getMessage() . "\n";
}

echo "Stock final : " . $imprimante->getNbFeuilles() . " feuilles\n";