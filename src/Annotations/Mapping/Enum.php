<?php


namespace AutoSwagger\SWG\Annotations\Mapping;


/**
 * @Annotation
 */
class Enum
{

    /**
     * @var array|string[]
     */
    protected $values;

    /**
     * Enum constructor.
     * @param array $data
     */
    public function __construct($data)
    {
        $this->values = $data['value'];
    }

    /**
     * @return array|string[]
     */
    public function getValues()
    {
        return $this->values;
    }

}