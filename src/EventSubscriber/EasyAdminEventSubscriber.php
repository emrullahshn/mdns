<?php

namespace App\EventSubscriber;

use App\Entity\Campaign;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class EasyAdminEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * EasyAdminEventSubscriber constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            EasyAdminEvents::PRE_PERSIST => 'onPrePersist',
            EasyAdminEvents::PRE_UPDATE => 'onPreUpdate',
        ];
    }

    public function onPrePersist(GenericEvent $event): void
    {
        if ($event->getSubject() instanceof Campaign){
            $this->setRelatedProducts($event);
        }

    }

    public function onPreUpdate(GenericEvent $event): void
    {
        if ($event->getSubject() instanceof Campaign){
            $this->setRelatedProducts($event);
        }

    }

    public function setRelatedProducts(GenericEvent $event): void
    {
        /**
         * @var Campaign $campaign
         */
        $campaign = $event->getSubject();
        $products = $this->entityManager->getRepository(Product::class)->getByBrandAndCategory($campaign);

        /**
         * @var Product $product
         */
        foreach ($products as $product) {
            $product->addRelatedCampaign($campaign);
        }
    }
}
