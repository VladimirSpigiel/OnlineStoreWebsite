<?php
/**
 * Created by PhpStorm.
 * User: spigiel
 * Date: 10/06/14
 * Time: 14:04
 */

namespace Sru\CoreBundle\Service;


use Sru\CoreBundle\Entity\Provider;

interface ProviderImportInterface {

    public function process();


    public function getProvider();

    public function setFile($file);





} 