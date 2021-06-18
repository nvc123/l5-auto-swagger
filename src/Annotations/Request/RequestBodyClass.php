<?php


namespace Swagger\Annotations\Request;

use AutoSwagger\SWG\Annotations\AbstractDtoAnnotation;
use Swagger\Annotations\AbstractAnnotation;
use Swagger\Annotations\Parameter;
use Swagger\Annotations\Schema;


/**
 * @Annotation
 */
class RequestBodyClass extends AbstractDtoAnnotation
{

    /**
     * @param string $data
     * @return AbstractAnnotation
     */
    public function generateAnnotation($data)
    {
        return new Parameter([
            'name' => 'body',
            'in' => 'body',
            'required' => true,
            'description' => $data,
            'value' => [new Schema([
                'type' => 'object',
                'value' => (new Property(['value' => $data]))->generateAnnotations()
            ])]
        ]);
    }

    protected function generateAnnotationsByReflectionClass(\ReflectionClass $reflectionClass)
    {
        return [$this->generateAnnotation($this->target)];
    }
}