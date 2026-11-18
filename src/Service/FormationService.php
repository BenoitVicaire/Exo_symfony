<?php

namespace App\Service;

use App\Entity\Formation;
use App\Repository\FormationRepository;

class FormationService
{
    public function __construct(private FormationRepository $FormationRepository)
    {
    }

    /**
     * @return Formation[]
     */
    public function getFormations(): array
    {
        return $this->FormationRepository->findAll();
    }

    public function getFormationById(int $id): ?Formation
    {
        return $this->FormationRepository->find($id);
    }
}
