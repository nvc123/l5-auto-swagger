<?php


namespace Swagger\Annotations\Request;


use AutoSwagger\SWG\Annotations\AbstractDtoAnnotation;
use Swagger\Annotations\AbstractAnnotation;
use Swagger\Annotations\Items;
use Swagger\Annotations\Schema;

/**
 * @Annotation
 */
class ArrayResponse extends AbstractDtoAnnotation
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
        return new \Swagger\Annotations\Response([
            'response' => $this->code,
            'description' => $data,
            'value' => [new Schema([
                'type' => "array",
                'value' => new Items(['value' => (new Property(['value' => $data]))->generateAnnotations()]),
            ])]
        ]);
    }

    protected function generateAnnotationsByReflectionClass(\ReflectionClass $reflectionClass)
    {
        return [$this->generateAnnotation($this->target)];
    }

}