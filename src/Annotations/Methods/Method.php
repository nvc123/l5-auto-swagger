<?php


namespace Swagger\Annotations\Methods;


use AutoSwagger\SWG\Annotations\AbstractDtoAnnotation;
use AutoSwagger\SWG\HasMethodAnnotations;
use Swagger\Annotations\Operation;
use Swagger\Annotations\Parameter;
use Swagger\Annotations\Path;


trait Method
{

    /**
     * @inheritdoc
     */
    public static $_parents = [
        Path::class
    ];

    /**
     * @var bool
     */
    public static $hasBeenCreated = false;

    /**
     * @param \Swagger\Annotations\AbstractAnnotation[] $annotations
     * @param bool $ignore
     * @return \Swagger\Annotations\AbstractAnnotation[]
     */
    public function merge($annotations, $ignore = false)
    {
        if (Method::$hasBeenCreated){
            return [];
        }

        $resultAnnotations = [];

        $reflectionClass = new \ReflectionClass($this->_context->namespace . '\\' . $this->_context->class);
        if ($reflectionClass->isSubclassOf(HasMethodAnnotations::class)) {
            $annotations = array_merge($reflectionClass->getMethod('getMethodAnnotations')->invoke(null, $this->_context->method, $this), $annotations);

            if (!($this->tags && is_array($this->tags))){
                $this->tags = [];
            }

            $this->tags[] = $reflectionClass->getName();
        }

        foreach ($annotations as $annotation) {
            if ($annotation instanceof AbstractDtoAnnotation) {
                foreach ($annotation->generateAnnotations() as $abstractAnnotation) {
                    $resultAnnotations[] = $abstractAnnotation;
                }
            } else {
                $resultAnnotations[] = $annotation;
            }
        }

        $result = parent::merge($resultAnnotations, $ignore);

        return $result;
    }

}