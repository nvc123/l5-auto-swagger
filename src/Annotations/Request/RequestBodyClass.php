<?php


namespace OpenApi\Annotations\Request;

use AutoSwagger\SWG\Annotations\AbstractDtoAnnotation;
use OpenApi\Annotations\AbstractAnnotation;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Schema;


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
