<?php
/**
 * Created by PhpStorm.
 * User: spigiel
 * Date: 10/06/14
 * Time: 14:23
 */

namespace Sru\CoreBundle\Controller;


use Sru\CoreBundle\Entity\Category;
use Sru\CoreBundle\Entity\Picture;
use Sru\CoreBundle\Entity\Product;
use Sru\CoreBundle\Entity\Provider;
use Sru\CoreBundle\Service\ProviderImportBase;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ImportController extends Controller {

    private $services;

    public function __construct(){
        $this->services = array("Pixmania_import");
    }

    public function indexAction(){

        $providers = $this->getDoctrine()->getRepository("SruCoreBundle:Provider")->findAll();


        return $this->render("SruCoreBundle:BackOffice/Import:index.html.twig", array(
            "services" => $this->services,
            "providers" => $providers
        ));
    }

    public function uploadAction(Request $request){

        if($request->getMethod() == "POST"){

            if(in_array($request->get("service"), $this->services)){


                /** @var ProviderImportBase $service */
                $service = $this->get($request->get("service"));
                $service->setFile($_FILES["file"]);


                try{
                    $informations = $service->process();
                }catch(\Exception $e){



                    return $this->render("SruCoreBundle:BackOffice/Import:index.html.twig", array(
                        "services" => $this->services,
                        "erreur" => $e->getMessage(),
                    ));
                }


                return $this->render("SruCoreBundle:BackOffice/Import:resume.html.twig", array(
                    "informations" => $informations,
                    "provider" => $service->getProvider()
                ));
            }

        }

    }

} 