<?php


namespace AutoSwagger\SWG\Annotations;

use AutoSwagger\SWG\Annotations\Mapping\AbstractNotField;
use AutoSwagger\SWG\Annotations\Mapping\Field;
use Doctrine\Common\Annotations\Reader;
use Illuminate\Support\Str;
use Swagger\Annotations\AbstractAnnotation;

/**
 * @Annotation
 */
abstract class AbstractDtoAnnotation extends AbstractAnnotation
{

    /**
     * @var string
     */
    public $target;

    public function __construct(array $properties)
    {
        $this->target = $properties['value'];
        parent::__construct([]);
    }

    /**
     * @param Field $data
     * @return AbstractAnnotation
     */
    public abstract function generateAnnotation($data);

    /**
     * @return array|AbstractAnnotation[]
     * @throws \Throwable
     */
    public function generateAnnotations()
    {
        try{
            if (!Str::contains($this->target, "\\") && isset($this->_context->uses[$this->target])){
                $this->target = $this->_context->uses[$this->target];
            }
            $reflectionClass = new \ReflectionClass($this->target);
        }catch (\Throwable $throwable){
            throw $throwable;
        }

        return $this->generateAnnotationsByReflectionClass($reflectionClass);
    }

    /**
     * @return string
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * @param \ReflectionClass $reflectionClass
     * @return array
     */
    protected function generateAnnotationsByReflectionClass(\ReflectionClass $reflectionClass){
        /** @var Reader $annotationReader */
        $annotationReader = app(Reader::class);
        $result = [];

        /** @var \ReflectionProperty $reflectionProperty */
        foreach ($reflectionClass->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED) as $reflectionProperty) {
            /** @var Field $mappingAnnotation */
            $mappingAnnotation = $annotationReader->getPropertyAnnotation($reflectionProperty, Field::class);
            if (!$mappingAnnotation) {
                if (!$annotationReader->getPropertyAnnotation($reflectionProperty, AbstractNotField::class)){
                    $mappingAnnotation = new Field([]);
                }
            }

            if ($mappingAnnotation){
                $mappingAnnotation->fillEmptyData($reflectionProperty);
                $result[] = $this->generateAnnotation($mappingAnnotation);
            }
        }

        return $result;
    }

}