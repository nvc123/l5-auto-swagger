<?php


namespace AutoSwagger\SWG;


use Swagger\Annotations\AbstractAnnotation;
use Swagger\Annotations\Operation;

interface HasMethodAnnotations
{

    /**
     * @param string $methodName
     * @param Operation $parent
     * @return array|AbstractAnnotation[]
     */
    public static function getMethodAnnotations(string $methodName, Operation $parent);

}