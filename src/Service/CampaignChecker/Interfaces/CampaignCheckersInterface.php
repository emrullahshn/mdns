<?php


namespace App\Service\CampaignChecker\Interfaces;


use App\Entity\Campaign;

interface CampaignCheckersInterface
{
    /**
     * @param Campaign $campaign
     * @return bool
     */
    public function check(Campaign $campaign): bool;
}
