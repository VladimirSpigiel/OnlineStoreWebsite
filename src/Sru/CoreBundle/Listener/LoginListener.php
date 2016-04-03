<?php
/**
 * Created by PhpStorm.
 * User: spigiel
 * Date: 05/06/14
 * Time: 15:14
 */

namespace Sru\CoreBundle\Listener;


use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\ORM\EntityManager;
use Sru\CoreBundle\Service\LoggerHandler;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginListener {

    private $em;

    public function __construct($securityContext, $doctrine){
        $em = $doctrine;


    }

    public function onSecurityInteractiveLogin( InteractiveLoginEvent $event )
    {
        /** @var User $user */
       /* $user = $event->getAuthenticationToken()->getUser();

        new LoggerHandler($this->em, array(
            "message" => "Connexion d'un membre",
            "thrownBy" => $user->getEmail(),
            "thrownFrom" => "page de connexion",
            "priority" => 1,
            "isInformation" => true,
            "isError" => false
        ));*/

        // do all your magic here
    }
} 