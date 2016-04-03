<?php
/**
 * Created by PhpStorm.
 * User: spigiel
 * Date: 04/06/14
 * Time: 12:00
 */

namespace Sru\CoreBundle\Controller;


use Sru\CoreBundle\Entity\Logger;
use Sru\CoreBundle\Service\LoggerHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LoggerController extends Controller {

    public function indexAction(){

        $logsDb = $this->getDoctrine()->getEntityManager()->createQuery(
            "SELECT l
             FROM SruCoreBundle:Logger l
             ORDER BY l.thrownAt DESC")->getResult();
        $handle = fopen("logs.txt","r");
        $contents = "";



        if(filesize("logs.txt") > 0)
            $contents = fread($handle, filesize("logs.txt"));
        $logsTxt = explode("\n", $contents);


        return $this->render("SruCoreBundle:BackOffice/Logger:index.html.twig",
            array("logsDb"=>$logsDb, "logsTxt" => $logsTxt));
    }

    public function resumeAction(){
         $logs =     $this->getDoctrine()->getEntityManager()->createQuery(
                    "SELECT l
                     FROM SruCoreBundle:Logger l
                     WHERE (l.consulted = :consulted)
                     ORDER BY l.thrownAt DESC")->setParameter("consulted",0)->getResult();

        return $this->render("SruCoreBundle:BackOffice/Logger:resume.html.twig",
                array("logs"=>$logs));

    }

    public function readFromTxtAction(){

        $handle = fopen("logs.txt","r");
        $contents = "";

        if(filesize("logs.txt") > 0)
            $contents = fread($handle, filesize("logs.txt"));
        $contents = explode("\n", $contents);

        return $this->render("SruCoreBundle:BackOffice/Logger:txt.html.twig",
            array("logs" => $contents));

    }


    public function readFromDatabaseAction(){
        $errors = $this->getDoctrine()->getEntityManager()->createQuery(
                "SELECT l
                 FROM SruCoreBundle:Logger l
                 WHERE (l.isError = :error)
                 ORDER BY l.thrownAt DESC")->setParameter("error",true)->getResult();

        $informations = $this->getDoctrine()->getEntityManager()->createQuery(
                        "SELECT l
                         FROM SruCoreBundle:Logger l
                         WHERE (l.isInformation = :information)
                         ORDER BY l.thrownAt DESC")->setParameter("information",true)->getResult();


        return $this->render("SruCoreBundle:BackOffice/Logger:database.html.twig",
            array("errors" => $errors,
                  "informations" => $informations));
    }

    public function deleteTxtAction(){
        
        $handle = fopen('logs.txt',"w");
        ftruncate($handle,0);

        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Suppression de logs.txt",
            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->generateUrl("logger_txt_delete"),
            "priority" => 1,
            "href" => $this->generateUrl("logger"),
            "isInformation" => true,
            "isError" => false
        ));

        return $this->redirect($this->generateUrl("logger"));
    }

    public function deleteDbAction(){
        $em = $this->getDoctrine()->getManager();

        /** @var Logger[] $logs */
        $logs = $this->getDoctrine()->getRepository("SruCoreBundle:Logger")->findAll();

        foreach($logs as $log){
            $em->remove($log);
        }

        $em->flush();

        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Suppression de logs en base de données",
            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->generateUrl("logger_db_delete"),
            "priority" => 1,
            "href" => $this->generateUrl("logger"),
            "isInformation" => true,
            "isError" => false
        ));

        return $this->redirect($this->generateUrl("logger"));

    }

    public function deleteAllAction(){

        /* TXT FILE */

        $handle = fopen('logs.txt',"w");
        ftruncate($handle,0);

        /* DATABASE */

        $em = $this->getDoctrine()->getManager();

        /** @var Logger[] $logs */
        $logs = $this->getDoctrine()->getRepository("SruCoreBundle:Logger")->findAll();

        foreach($logs as $log){
            $em->remove($log);
        }

        $em->flush();


        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Suppression des logs complet",
            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->generateUrl("logger_all_delete"),
            "priority" => 1,
            "href" => $this->generateUrl("logger"),
            "isInformation" => true,
            "isError" => false
        ));

        return $this->redirect($this->generateUrl("logger"));

    }

    public function consultedAction(){

        $em = $this->getDoctrine()->getManager();
        /** @var Logger[] $logs */
        $logs = $this->getDoctrine()->getRepository("SruCoreBundle:Logger")->findBy(array("consulted" => false));

        foreach($logs as $log){
            $log->setConsulted(true);
            $em->persist($log);
        }
        $em->flush();

        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Consultation des notifications sur les logs",
            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->generateUrl("logger_consulted"),
            "priority" => 1,
            "href" => $this->generateUrl("logger"),
            "isInformation" => true,
            "isError" => false
        ));



        return $this->resumeAction();
    }



} 