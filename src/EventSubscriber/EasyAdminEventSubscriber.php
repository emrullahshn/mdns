<?php

namespace App\EventSubscriber;

use App\Entity\Brand;
use App\Entity\Campaign;
use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Common\Collections\ArrayCollection;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class EasyAdminEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            EasyAdminEvents::PRE_PERSIST => 'onPrePersist',
            EasyAdminEvents::PRE_UPDATE => 'onPreUpdate',
        ];
    }

    public function onPrePersist(GenericEvent $event): void
    {
        $this->setRelatedProducts($event);
    }

    public function onPreUpdate(GenericEvent $event): void
    {
        $this->setRelatedProducts($event);
    }

    public function setRelatedProducts(GenericEvent $event): void
    {
        /**
         * @var Campaign $campaign
         */
        $campaign = $event->getSubject();
        $products = array_merge(
            $campaign->getBrand()->getProducts()->toArray(),
            $campaign->getCategory()->getProducts()->toArray()
        );

        /**
         * @var Product $product
         */
        foreach ($products as $product) {
            $product->addRelatedCampaign($campaign);
        }
    }
}
