<?php
# https://symfony.com/doc/current/service_container.html

namespace App\Service;

use App\Repository\EtablissementRepository;

class StatsService
{
    private $etablissementRepository;

    public function __construct(EtablissementRepository $etablissementRepository)
    {
        $this->etablissementRepository = $etablissementRepository;
    }
    public function getEtablissementsCount(): int
    {
        return $this->etablissementRepository->count();
    }
}
