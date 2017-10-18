<?php
namespace pengweifu;

interface Visit
{
    public function go();
}
/**
*
*/
class Leg implements Visit
{
    public function go()
    {
        echo "walk";
    }
}
/**
*
*/
class Car implements Visit
{
    public function go()
    {
        echo "drive";
    }
}
/**
*
*/
class Plane implements Visit
{
    public function go()
    {
        echo "fly";
    }
}

/**
*
*/
class ToolFactory
{
    
    public function createTool($tool)
    {
        switch ($tool) {
            case 'leg':
                return new Leg();
                break;
            case 'plane':
                return new Plane();
                break;
            case 'car':
                return new Car();
                break;
            default:
                throw new \Exception("wrong tool type", 1);
                break;
        }
    }
}

/**
*
*/
class Traveller
{
    protected $_tool;
    public function __construct(Visit $tool)
    {
        $this->_tool=$tool;
    }
    public function visit()
    {
        $this->_tool->go();
    }
}

/**
*
*/
class Container
{
    protected $bindings=[];

    public function __get($name)
    {
        return $this->$name;
    }

    public function bind($abstract, $concrete = null, $shared = false)
    {
        if (!$concrete instanceof \Closure) {
            $concrete=$this->getClosure($abstract, $concrete);
        }
        var_dump($concrete);
        $this->bindings[$abstract]=compact('concrete','shared');
    }

    protected function getClosure($abstract, $concrete){
        return function($c) use($abstract, $concrete){
            $method=$abstract==$concrete?'build':'make';
            return $c->$method($concrete);
        };
    }

    public function make($abstract)
    {
        $concrete=$this->getConcrete($abstract);
        if ($this->isBuildable($concrete, $abstract)) {
            $object=$this->build($concrete);
        }else{
            $object=$this->make($concrete);
        }
        return $object;
    }

    public function build($concrete)
    {
        if ($concrete instanceof \Closure) {
            return $concrete($this);
        }
        $reflector=new \ReflectionClass($concrete);
        if (!$reflector->isInstantiable()) {
            throw new \Exception("Target is not instantiable", 2);
        }
        $constructor=$reflector->getConstructor();
        if (is_null($constructor)) {
            return new $concrete;
        }
        $parameters=$constructor->getParameters();
        $instances=$this->getDependencies($parameters);
        return $reflector->newInstanceArgs($instances);
    }

    protected function isBuildable($concrete, $abstract)
    {
        return $concrete==$abstract || $concrete instanceof \Closure;
    }

    protected function getConcrete($abstract)
    {
        if (!isset($this->bindings[$abstract])) {
            return $abstract;
        }
        return $this->bindings[$abstract]['concrete'];
    }

    protected function getDependencies($parameters)
    {
        $dependencies=[];
        foreach ($parameters as $parameter) {
            $object=$parameter->getClass();
            if (is_null($object)) {
                $dependencies[]=null;
            }else{
                $dependencies[]=$this->resolveClass($parameter);
            }
        }
        return $dependencies;
    }

    protected function resolveClass($parameter)
    {
        return $this->make($parameter->getClass()->name);
    }
}

$app=new Container();
$app->bind('pengweifu\Visit','pengweifu\Plane');
$app->bind('traveller','pengweifu\Traveller');
$travel=$app->make('traveller');
$travel->visit();
