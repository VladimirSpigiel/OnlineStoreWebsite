<?php
/**
 * Created by PhpStorm.
 * User: spigiel
 * Date: 04/06/14
 * Time: 14:34
 */

namespace Sru\CoreBundle\Service;



use Doctrine\ORM\EntityManager;
use Sru\CoreBundle\Entity\Logger;

class LoggerHandler {


    public function __construct($om, $options){
        $log = new Logger();

        $log->setMessage($options["message"])
            ->setThrownAt(new \DateTime('now'))
            ->setPriority($options["priority"])
            ->setThrownBy($options["thrownBy"])
            ->setIsError($options["isError"])
            ->setIsInformation($options["isInformation"])
            ->setThrownFrom($options["thrownFrom"]);
            if(array_key_exists("href",$options)){
                $log->setHref($options["href"]);
            }
            if(array_key_exists("reason",$options))
                $log->setReason($options["reason"]);

        $contents = "";
        if(file_exists("logs.txt"))
            $contents= file_get_contents("logs.txt");
        $handle = fopen("logs.txt","w+");

        $txt = $log->getThrownAt()->format("d-m-Y H:i:s")." :: ";

        if($log->getIsError())
            $txt .= "Erreur :: ";
        else
            $txt .= "Info :: ";

        $txt .= "Message : ".$log->getMessage()."     ";
        if($log->getIsError())
            $txt .= "Raison : ".$log->getReason()."     ";

        $txt .= "Provoqué par :  ".$log->getThrownBy()."     ";
        $txt .= "Depuis la page : ".$log->getThrownFrom()."     ";
        $txt .= "Lien : ".$log->getHref()."\n";



        fputs($handle, $txt.$contents);

        fclose($handle);

        $om->clear();
        $om->persist($log);
        $om->flush();
    }


} 