<?php


namespace Swagger\Annotations\Request;

use AutoSwagger\SWG\Annotations\AbstractDtoAnnotation;
use AutoSwagger\SWG\Annotations\Mapping\Field;
use AutoSwagger\SWG\Reflection\ReflectionClass;
use Illuminate\Support\Str;
use Swagger\Annotations\AbstractAnnotation;
use Swagger\Annotations\Items;
use Swagger\Annotations\Parameter;
use Swagger\Annotations\Schema;


/**
 * @Annotation
 */
class Property extends AbstractDtoAnnotation
{

    /**
     * @param Field $data
     * @return AbstractAnnotation
     */
    public function generateAnnotation($data)
    {
        $properties = [
            'property' => $data->name,
            'description' => $data->description,
            'required' => [$data->required ? 'true' : 'false']
        ];

        $type = 'string';
        $preType = $data->type;

        /// TODO: поправить определение типа, добавить механизм описания таблиц и проверить пхпдок во всех все дтошках
        if (in_array($preType, ['integer', 'file'])){
            $type = $preType;
        }

        $isArray = Str::endsWith($preType, '[]');
        if ($isArray) {
            $preType = Str::before($preType, '[]');
        }

        $nestedProperties = null;

        $isObject = $preType !== strtolower($preType);
        if ($isObject) {
            try {
                $reflectionClass = new ReflectionClass($this->getTarget());
                $className = $reflectionClass->findClassNameByAlias($preType);
                if (empty($properties['description'])){
                    $properties['description'] = $className;
                }
                $nestedProperties = (new Property(['value' => $className]))->generateAnnotations();
                $type = 'object';
            } catch (\ReflectionException $reflectionException) {

            }
        }

        if ($isArray){
            if ($isObject){
                $nestedProperties = new Items(['value' => $nestedProperties]);
            }else{
                $nestedProperties = new Items(['type' => $preType]);
            }
            $type = 'array';
        }

        if ($nestedProperties){
            $properties['value'] = $nestedProperties;
        }

        $properties['type'] = $type;

        return new \Swagger\Annotations\Property($properties);
    }
}