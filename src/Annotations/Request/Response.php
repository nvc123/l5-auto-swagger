<?php


namespace OpenApi\Annotations\Request;


use AutoSwagger\SWG\Annotations\AbstractDtoAnnotation;
use OpenApi\Annotations\AbstractAnnotation;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\MediaType;
use OpenApi\Annotations\Schema;

/**
 * @Annotation
 */
class Response extends AbstractDtoAnnotation
{

    public $code;

    public function __construct(array $properties)
    {
        $this->code = $properties['code'];
        parent::__construct($properties);
    }

    /**
     * @param string $data
     * @return AbstractAnnotation
     */
    public function generateAnnotation($data)
    {
        return new \OpenApi\Annotations\Response([
            'response' => $this->code,
            'description' => $data,
            'value' => [new MediaType([
                'mediaType' => 'application/json',
                'value' => [new Schema([
                    'type' => "object",
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
