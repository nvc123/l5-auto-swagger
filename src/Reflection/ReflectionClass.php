<?php


namespace AutoSwagger\SWG\Reflection;

use Illuminate\Support\Str;

class ReflectionClass extends \ReflectionClass
{

    /**
     * @var array|string[]
     */
    protected $uses;

    const AS_OPERATOR = ' as ';

    public function __construct($argument)
    {
        parent::__construct($argument);
        $this->uses = [];
    }

    /**
     * @param string $alias
     * @return string
     */
    public function findClassNameByAlias($alias)
    {
        if (substr($alias, 0, 1) === '\\') {
            return substr($alias, 1);
        }

        $name = Str::before($alias, '\\');

        foreach ($this->getUses() as $useAlias => $namespace) {
            if ($name === $useAlias) {
                return Str::replaceFirst($name, $namespace, $alias);
            }
        }

        return $this->getNamespaceName() . '\\' . $alias;
    }

    /**
     * @return array|string[]
     */
    public function getUses(): array
    {
        if (!$this->uses) {
            $this->updateUses();
        }

        return $this->uses;
    }

    protected function updateUses()
    {
        $uses = $this->getUsesByClass();

        $result = [];

        foreach ($uses as $use) {
            $asPos = strpos($use, self::AS_OPERATOR);

            if ($asPos === false) {
                $alias = Str::afterLast($use, '\\');
            } else {
                $alias = Str::afterLast($use, self::AS_OPERATOR);
                $use = Str::beforeLast($use, self::AS_OPERATOR);
            }

            $result[$alias] = $use;
        }

        $this->uses = $result;
    }

    /**
     * @return array
     */
    public function getUsesByClass(): array
    {
        $classText = file_get_contents($this->getFileName());
        $preClassText = substr($classText, 0, strpos($classText, 'class '));
        $useExplode = explode('use ', $preClassText);

        $uses = [];
        for ($i = 1; $i < count($useExplode); $i++){
            $uses[] = substr($useExplode[$i], 0, strpos($useExplode[$i], ';'));
        }

        return $uses;
    }

}