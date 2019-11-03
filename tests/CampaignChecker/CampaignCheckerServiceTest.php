<?php

namespace App\Tests\CampaignChecker;


use App\Entity\Campaign;
use App\Service\CampaignChecker\CampaignCheckerService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CampaignCheckerServiceTest extends WebTestCase
{
    /**
     * @var CampaignCheckerService $campaignCheckerService
     */
    private static $campaignCheckerService;

    public function setUp(){
        $client = self::createClient();
        $container = $client->getContainer();
        self::$campaignCheckerService = $container->get(CampaignCheckerService::class);
    }

    public function testTrueDateBetween()
    {
        $checker = self::$campaignCheckerService->checkCampaign($this->mockCampaign());

        $this->assertTrue($checker);
    }

    public function testFalseDateBetween()
    {
        $checker = self::$campaignCheckerService->checkCampaign($this->mockCampaign2());

        $this->assertFalse($checker);
    }

    private function mockCampaign()
    {
        $campaign = new Campaign();
        $campaign->setStartDate(new \DateTime('-5 days'));
        $campaign->setEndDate(new \DateTime('+5 days'));

        return $campaign;
    }

    private function mockCampaign2()
    {
        $campaign = new Campaign();
        $campaign->setStartDate(new \DateTime('+5 days'));
        $campaign->setEndDate(new \DateTime('+15 days'));

        return $campaign;
    }
}
