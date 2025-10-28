<?php

class FormationsModel
{
    public ?int $id_formation;
    public string $nom;

    public function __construct(?int $id_formation = null, string $nom = '')
    {
        $this->id_formation = $id_formation;
        $this->nom = $nom;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['id_formation']) && $data['id_formation'] !== null ? (int)$data['id_formation'] : null,
            (string)($data['nom'] ?? '')
        );
    }

    public function toArray(): array
    {
        return [
            'id_formation' => $this->id_formation,
            'nom' => $this->nom,
        ];
    }
}
