<?php


namespace OpenApi\Annotations\Request;

use AutoSwagger\SWG\Annotations\AbstractDtoAnnotation;
use AutoSwagger\SWG\Annotations\Mapping\Enum;
use AutoSwagger\SWG\Annotations\Mapping\Field;
use OpenApi\Annotations\AbstractAnnotation;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Schema;


/**
 * @Annotation
 */
class Parameters extends AbstractDtoAnnotation
{

    /**
     * @var string
     */
    public $type;

    /**
     * @param Field $data
     * @return AbstractAnnotation
     */
    public function generateAnnotation($data)
    {
        if (is_object($data->type)) {
            $annotation = $data->type;
            if ($annotation instanceof Enum){
                /** @var Enum $annotation */
                $schema = new Schema([
                    'type' => 'string',
                    'enum' => $annotation->getValues()
                ]);
            }
        } else {
            if ($data->type !== strtolower($data->type)){
                $schema = new Schema([
                    'type' => "object",
                    'value' => (new Property(['value' => $data->type]))->generateAnnotations()
                ]);
            }else{
                $schema = new Schema(['type' => $data->type]);
            }
        }

        return new Parameter([
            'name' => $data->name,
            'description' => $data->description,
            'required' => $data->required,
            'in' => $this->type,
            'value' => [$schema]
        ]);
    }
}
