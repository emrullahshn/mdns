<?php


namespace App\Service\CampaignChecker;


use App\Entity\Campaign;
use App\Service\CampaignChecker\Interfaces\CampaignCheckersInterface;
use Doctrine\Common\Collections\ArrayCollection;

class CampaignCheckerService
{
    /**
     * @var CampaignCheckersInterface $checkers
     */
    private $checkers;

    /**
     * CampaignCheckerService constructor.
     */
    public function __construct()
    {
        $this->checkers = new ArrayCollection();
    }


    /**
     * @param CampaignCheckersInterface $checker
     * @return CampaignCheckerService
     */
    public function addChecker(CampaignCheckersInterface $checker): self
    {
        $this->checkers->add($checker);

        return $this;
    }

    /**
     * @param Campaign $campaign
     * @return bool
     */
    public function checkCampaign(Campaign $campaign): bool
    {
        /**
         * @var CampaignCheckersInterface $checker
         */
        foreach ($this->checkers as $checker){
            if ($checker->check($campaign) === false){
                return false;
            }
        }

        return true;
    }
}
