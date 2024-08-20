<?php


namespace AutoSwagger\SWG\Annotations\Mapping;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @Annotation
 */
class Field
{

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var boolean
     */
    public $required = true;

    /**
     * @var mixed
     */
    public $type;

    /**
     * Field constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->name = Arr::get($values, 'name');
        $this->description = Arr::get($values, 'description');
        $this->required = Arr::get($values, 'required');
        $this->type = Arr::get($values, 'type');
    }

    /**
     * @param \ReflectionProperty $reflectionProperty
     */
    public function fillEmptyData(\ReflectionProperty $reflectionProperty)
    {
        if (empty($this->name)) {
            $this->name = $reflectionProperty->getName();
        }

        if (empty($this->type)) {
            $this->required = true;

            if (method_exists($reflectionProperty, 'getType')) {
                $type = $reflectionProperty->getType();
            } else {
                $type = null;
            }

            if ($type) {
                $this->type = $type->getName();
                $this->required = !$type->allowsNull();
                
                if ($this->type === 'bool'){
                    $this->type = 'boolean';
                }
            } else {
                $doc = $reflectionProperty->getDocComment();
                $type = 'string';
                if (Str::contains($doc, '@var ')) {
                    $type = Str::after($doc, '@var ');
                    $type = Str::before($type, "\n");
                    $type = Str::before($type, "\r");

                    $types = explode('|', $type);
                    if (count($types) === 1) {
                        $type = $types[0];
                    } else {
                        $isString = true;
                        foreach ($types as $typeItem) {
                            switch ($typeItem) {
                                case 'string':
                                    $isString = true;
                                    break;
                                case 'null':
                                    $this->required = false;
                                    break;
                                case 'array':
                                    break;
                                case 'object':
                                    $isString = false;
                                    break;
                                case 'integer':
                                    $isString = false;
                                    $type = 'integer';
                                    break;
                                case 'UploadedFile':
                                    $isString = false;
                                    $type = 'file';
                                    break;
                                case 'UploadedFile[]':
                                    $isString = false;
                                    $type = 'file[]';
                                    break;
                                default:
                                    $type = $typeItem;
                                    if (strtolower($type) !== $type || Str::contains($type, '[]')) {
                                        $isString = false;
                                    }
                                    break;
                            }
                        }

                        if ($isString) {
                            $type = 'string';
                        }
                    }
                }
                $this->type = $type;
            }
        }
    }

}
