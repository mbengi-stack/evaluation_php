<?php

declare(strict_types=1);


class Equipement
{
    private string $numeroInventaire;
    private string $marque;

    public function __construct(string $numeroInventaire, string $marque)
    {
        $this->numeroInventaire = $numeroInventaire;
        $this->marque = $marque;
    }

    public function getNumeroInventaire(): string
    {
        return $this->numeroInventaire;
    }

    public function getMarque(): string
    {
        return $this->marque;
    }

    /**
     * Retourne une description contenant le numéro d'inventaire et la marque.
     */
    public function description(): string
    {
        return 'Équipement n° ' . $this->numeroInventaire . ' - marque : ' . $this->marque;
    }
}

/**
 * Contrat que doit respecter tout matériel pouvant être prêté.
 * L'interface ne contient aucune règle : seulement les signatures des méthodes.
 */
interface Empruntable
{
    public function emprunter(string $nom): void;

    public function restituer(): void;

    public function estDisponible(): bool;
}

/**
 * Ordinateur portable : un équipement qui peut être emprunté.
 */
class OrdinateurPortable extends Equipement implements Empruntable
{
    private int $ramGo;
    private ?string $emprunteur = null;   // null = disponible

    /**
     * @throws InvalidArgumentException si la RAM n'est pas strictement positive
     */
    public function __construct(string $numeroInventaire, string $marque, int $ramGo)
    {
        if ($ramGo <= 0) {
            throw new InvalidArgumentException('La RAM doit être strictement positive.');
        }
        parent::__construct($numeroInventaire, $marque);   // initialise la partie « Equipement »
        $this->ramGo = $ramGo;
    }

    public function getRamGo(): int
    {
        return $this->ramGo;
    }

    public function getEmprunteur(): ?string
    {
        return $this->emprunteur;
    }

    /**
     
     *
     * @throws InvalidArgumentException si le nom est vide
     * @throws LogicException si le portable est déjà emprunté
     */
    public function emprunter(string $nom): void
    {
        if (trim($nom) === '') {
            throw new InvalidArgumentException('Le nom de l\'emprunteur ne peut pas être vide.');
        }
        if ($this->emprunteur !== null) {
            throw new LogicException('Ce portable est déjà emprunté par ' . $this->emprunteur . '.');
        }
        $this->emprunteur = trim($nom);
    }

    /**
     * Enregistre une restitution.
     *
     * @throws LogicException si aucun emprunt n'est en cours
     */
    public function restituer(): void
    {
        if ($this->emprunteur === null) {
            throw new LogicException('Aucun emprunt en cours : restitution impossible.');
        }
        $this->emprunteur = null;
    }

    /**
     * Vrai uniquement si aucun emprunteur n'est enregistré.
     */
    public function estDisponible(): bool
    {
        return $this->emprunteur === null;
    }

    /**
     * Redéfinit la description du parent pour y ajouter la RAM.
     */
    public function description(): string
    {
        return parent::description() . ' - RAM : ' . $this->ramGo . ' Go';
    }
}

/**
 * Fonctionne avec n'importe quel objet Empruntable (portable, vidéoprojecteur, etc.).
 */
function afficherDisponibilite(Empruntable $objet): void
{
    echo ($objet->estDisponible() ? 'Disponible' : 'Emprunté') . "\n";
}

// ----------------------------- TESTS -----------------------------

function verifier(string $description, bool $reussi): void
{
    echo ($reussi ? '[OK] ' : '[ERREUR] ') . $description . "\n";
}

$portable = new OrdinateurPortable('LENOVO-024', 'Lenovo', 16);


echo $portable->description() . "\n";
afficherDisponibilite($portable);
verifier('Le portable est disponible au départ', $portable->estDisponible());

// Test 2 : emprunt par Camille
$portable->emprunter('Camille');
afficherDisponibilite($portable);
verifier('Après l\'emprunt, le portable n\'est plus disponible', !$portable->estDisponible());

// Test 3 : second emprunt refusé
try {
    $portable->emprunter('Louis');
    verifier('Second emprunt refusé', false);
} catch (LogicException $e) {
    verifier('Second emprunt refusé (' . $e->getMessage() . ')', true);
}
verifier('L\'emprunteur est toujours Camille', $portable->getEmprunteur() === 'Camille');

// Test 4 : restitution
$portable->restituer();
afficherDisponibilite($portable);
verifier('Après la restitution, le portable est disponible', $portable->estDisponible());

// Test 5 : seconde restitution refusée
try {
    $portable->restituer();
    verifier('Seconde restitution refusée', false);
} catch (LogicException $e) {
    verifier('Seconde restitution refusée (' . $e->getMessage() . ')', true);
}

// Tests bonus : nom vide et RAM invalide
try {
    $portable->emprunter('   ');
    verifier('Nom composé d\'espaces refusé', false);
} catch (InvalidArgumentException $e) {
    verifier('Nom composé d\'espaces refusé (' . $e->getMessage() . ')', true);
}
verifier('Le portable est resté disponible', $portable->estDisponible());

try {
    new OrdinateurPortable('HP-001', 'HP', 0);
    verifier('RAM de 0 Go refusée', false);
} catch (InvalidArgumentException $e) {
    verifier('RAM de 0 Go refusée (' . $e->getMessage() . ')', true);
}