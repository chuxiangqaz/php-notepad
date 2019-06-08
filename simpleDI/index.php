<?php
function di($className)
{
    $reflector = (new ReflectionClass($className));
    if (!$reflector->isInstantiable()) {
        // 这些类无法实例化
        throw new \Exception("{$className} not instant");
    }

    $constructor = $reflector->getConstructor();
    if (is_null($constructor)) {
        // 无构造函数
        return new $className();
    }

    $params = $reflector->getConstructor()->getParameters();
    $constructorParams = [];
    if (count($params) > 0) {
        foreach ($params as $param) {
            // 判断参数是否是可选择的
            if ($param->isOptional()) {
                $value = $param->getDefaultValue();
            } else {
                // 获取对象类型
                $class = $param->getClass();
                if (is_null($class)) {
                    // 非对象类型
                    throw new \Exception("{$param->name} not is {$className} or not has a default value!");
                } else {
                    //
                    $value = $class->name;
                    $value = di($value);
                }
            }

            $constructorParams [] = $value;
        }
    }

    return new $className(...$constructorParams);
}

var_dump(di('People'));

Class People
{
    public function __construct(Woman $woman, Man $man) {}
}

Class Woman {}

Class Man
{
    public function __construct(Power $power, $desc = 'man') {}
}

Class Power {}
