<?php


namespace AutoSwagger\SWG;


use OpenApi\Annotations\AbstractAnnotation;
use OpenApi\Annotations\Operation;

interface HasMethodAnnotations
{

    /**
     * @param string $methodName
     * @param Operation $parent
     * @return array|AbstractAnnotation[]
     */
    public static function getMethodAnnotations(string $methodName, Operation $parent);

}
