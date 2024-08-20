<?php


namespace OpenApi\Annotations\Methods;


use AutoSwagger\SWG\Annotations\AbstractDtoAnnotation;
use AutoSwagger\SWG\HasMethodAnnotations;
use OpenApi\Annotations\Operation;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Path;


trait Method
{

    /**
     * @var bool
     */
    public static $hasBeenCreated = false;

    /**
     * @param \OpenApi\Annotations\AbstractAnnotation[] $annotations
     * @param bool $ignore
     * @return \OpenApi\Annotations\AbstractAnnotation[]
     */
    public function merge($annotations, $ignore = false): array
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
                $this->tags[] = $reflectionClass->getName();
            }
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

    public function getRoot(): string
    {
        return get_parent_class($this);
    }

}
