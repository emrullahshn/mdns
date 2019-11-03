<?php


namespace App\Service\CampaignChecker\Checkers;


use App\Entity\Campaign;
use App\Service\CampaignChecker\Interfaces\CampaignCheckersInterface;
use Carbon\Carbon;
use DateTime;
use Exception;

class DateChecker implements CampaignCheckersInterface
{
    /**
     * @param Campaign $campaign
     * @return bool
     * @throws Exception
     */
    public function check(Campaign $campaign): bool
    {
        return $this->checkDateBetween($campaign);
    }

    /**
     * @param Campaign $campaign
     * @return bool
     * @throws Exception
     */
    public function checkDateBetween(Campaign $campaign): bool
    {
        $now = Carbon::parse((new DateTime())->format('Y-m-d'));

        $campaignStartDate = Carbon::instance($campaign->getStartDate());
        $campaignEndDate = Carbon::instance($campaign->getEndDate());

        return $now->between($campaignStartDate, $campaignEndDate, true);
    }
}
