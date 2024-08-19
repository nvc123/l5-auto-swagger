<?php


namespace OpenApi\Annotations\Request;

use AutoSwagger\SWG\Annotations\AbstractDtoAnnotation;
use OpenApi\Annotations\AbstractAnnotation;
use OpenApi\Annotations\MediaType;
use OpenApi\Annotations\RequestBody;
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
        return new RequestBody([
            'value' => [new MediaType([
                'mediaType' => 'application/json',
                'value' => [new Schema([
                    'type' => 'object',
                    'description' => $data,
                    'value' => (new Property(['value' => $data]))->generateAnnotations()
                ])]
            ])]
        ]);
    }

    protected function generateAnnotationsByReflectionClass(\ReflectionClass $reflectionClass)
    {
        return [$this->generateAnnotation($this->target)];
    }
}
