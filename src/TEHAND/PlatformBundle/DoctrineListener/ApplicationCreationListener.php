<?php

namespace TEHAND\PlatformBundle\DoctrineListener;

use TEHAND\PlatformBundle\Entity\Application;
use TEHAND\PlatformBundle\Email\ApplicationMailer;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class ApplicationCreationListener {

    /**
     *
     * @var ApplicationMailer
     */
    private $applicationMailer;

    public function __construct(ApplicationMailer $applicationMailer) {
        $this->applicationMailer = $applicationMailer;
    }

    public function postPersist(LifecycleEventArgs $args) {
        $entity = $args->getObject();

        try {
            if (!$entity instanceof Application) {
                return;
            }

            $this->applicationMailer->sendNewNotification($entity);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

}
