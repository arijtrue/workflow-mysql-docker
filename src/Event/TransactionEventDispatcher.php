<?php

namespace App\Event;

use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\GuardEvent;

class TransactionEventDispatcher implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param GuardEvent $event
     */
    public function guardAccept(GuardEvent $event) :void
    {
        /** @var Transaction $transaction */
        $transaction = $event->getSubject();

        if ($transaction->getTotalPrice() > 9) {
            $event->setBlocked(true);
        }
    }
    /**
     * @param GuardEvent $event
     */
    public function enterAccept(GuardEvent $event) :void
    {
        /** @var Transaction $transaction */
        $transaction = $event->getSubject();
        // todo: do actions
        //$this->em->getRepository()
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'workflow.transaction.guard.accept' => ['guardAccept'],
            'workflow.[workflow name].enter' => ['enterAccept'],
        ];
    }
}
