<?php

namespace ContainerBV41PFh;
include_once \dirname(__DIR__, 4).''.\DIRECTORY_SEPARATOR.'vendor'.\DIRECTORY_SEPARATOR.'doctrine'.\DIRECTORY_SEPARATOR.'persistence'.\DIRECTORY_SEPARATOR.'src'.\DIRECTORY_SEPARATOR.'Persistence'.\DIRECTORY_SEPARATOR.'ObjectManager.php';
include_once \dirname(__DIR__, 4).''.\DIRECTORY_SEPARATOR.'vendor'.\DIRECTORY_SEPARATOR.'doctrine'.\DIRECTORY_SEPARATOR.'orm'.\DIRECTORY_SEPARATOR.'lib'.\DIRECTORY_SEPARATOR.'Doctrine'.\DIRECTORY_SEPARATOR.'ORM'.\DIRECTORY_SEPARATOR.'EntityManagerInterface.php';
include_once \dirname(__DIR__, 4).''.\DIRECTORY_SEPARATOR.'vendor'.\DIRECTORY_SEPARATOR.'doctrine'.\DIRECTORY_SEPARATOR.'orm'.\DIRECTORY_SEPARATOR.'lib'.\DIRECTORY_SEPARATOR.'Doctrine'.\DIRECTORY_SEPARATOR.'ORM'.\DIRECTORY_SEPARATOR.'EntityManager.php';

class EntityManager_9a5be93 extends \Doctrine\ORM\EntityManager implements \ProxyManager\Proxy\VirtualProxyInterface
{

    /**
     * @var \Doctrine\ORM\EntityManager|null wrapped object, if the proxy is initialized
     */
    private $valueHoldere5477 = null;

    /**
     * @var \Closure|null initializer responsible for generating the wrapped object
     */
    private $initializer412ed = null;

    /**
     * @var bool[] map of public properties of the parent class
     */
    private static $publicProperties464c9 = [
        
    ];

    public function getConnection()
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'getConnection', array(), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->getConnection();
    }

    public function getMetadataFactory()
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'getMetadataFactory', array(), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->getMetadataFactory();
    }

    public function getExpressionBuilder()
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'getExpressionBuilder', array(), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->getExpressionBuilder();
    }

    public function beginTransaction()
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'beginTransaction', array(), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->beginTransaction();
    }

    public function getCache()
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'getCache', array(), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->getCache();
    }

    public function transactional($func)
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'transactional', array('func' => $func), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->transactional($func);
    }

    public function wrapInTransaction(callable $func)
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'wrapInTransaction', array('func' => $func), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->wrapInTransaction($func);
    }

    public function commit()
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'commit', array(), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->commit();
    }

    public function rollback()
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'rollback', array(), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->rollback();
    }

    public function getClassMetadata($className)
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'getClassMetadata', array('className' => $className), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->getClassMetadata($className);
    }

    public function createQuery($dql = '')
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'createQuery', array('dql' => $dql), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->createQuery($dql);
    }

    public function createNamedQuery($name)
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'createNamedQuery', array('name' => $name), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->createNamedQuery($name);
    }

    public function createNativeQuery($sql, \Doctrine\ORM\Query\ResultSetMapping $rsm)
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'createNativeQuery', array('sql' => $sql, 'rsm' => $rsm), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->createNativeQuery($sql, $rsm);
    }

    public function createNamedNativeQuery($name)
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'createNamedNativeQuery', array('name' => $name), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->createNamedNativeQuery($name);
    }

    public function createQueryBuilder()
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'createQueryBuilder', array(), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->createQueryBuilder();
    }

    public function flush($entity = null)
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'flush', array('entity' => $entity), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->flush($entity);
    }

    public function find($className, $id, $lockMode = null, $lockVersion = null)
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'find', array('className' => $className, 'id' => $id, 'lockMode' => $lockMode, 'lockVersion' => $lockVersion), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->find($className, $id, $lockMode, $lockVersion);
    }

    public function getReference($entityName, $id)
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'getReference', array('entityName' => $entityName, 'id' => $id), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->getReference($entityName, $id);
    }

    public function getPartialReference($entityName, $identifier)
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'getPartialReference', array('entityName' => $entityName, 'identifier' => $identifier), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->getPartialReference($entityName, $identifier);
    }

    public function clear($entityName = null)
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'clear', array('entityName' => $entityName), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->clear($entityName);
    }

    public function close()
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'close', array(), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->close();
    }

    public function persist($entity)
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'persist', array('entity' => $entity), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->persist($entity);
    }

    public function remove($entity)
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'remove', array('entity' => $entity), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->remove($entity);
    }

    public function refresh($entity)
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'refresh', array('entity' => $entity), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->refresh($entity);
    }

    public function detach($entity)
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'detach', array('entity' => $entity), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->detach($entity);
    }

    public function merge($entity)
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'merge', array('entity' => $entity), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->merge($entity);
    }

    public function copy($entity, $deep = false)
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'copy', array('entity' => $entity, 'deep' => $deep), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->copy($entity, $deep);
    }

    public function lock($entity, $lockMode, $lockVersion = null)
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'lock', array('entity' => $entity, 'lockMode' => $lockMode, 'lockVersion' => $lockVersion), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->lock($entity, $lockMode, $lockVersion);
    }

    public function getRepository($entityName)
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'getRepository', array('entityName' => $entityName), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->getRepository($entityName);
    }

    public function contains($entity)
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'contains', array('entity' => $entity), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->contains($entity);
    }

    public function getEventManager()
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'getEventManager', array(), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->getEventManager();
    }

    public function getConfiguration()
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'getConfiguration', array(), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->getConfiguration();
    }

    public function isOpen()
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'isOpen', array(), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->isOpen();
    }

    public function getUnitOfWork()
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'getUnitOfWork', array(), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->getUnitOfWork();
    }

    public function getHydrator($hydrationMode)
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'getHydrator', array('hydrationMode' => $hydrationMode), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->getHydrator($hydrationMode);
    }

    public function newHydrator($hydrationMode)
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'newHydrator', array('hydrationMode' => $hydrationMode), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->newHydrator($hydrationMode);
    }

    public function getProxyFactory()
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'getProxyFactory', array(), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->getProxyFactory();
    }

    public function initializeObject($obj)
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'initializeObject', array('obj' => $obj), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->initializeObject($obj);
    }

    public function getFilters()
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'getFilters', array(), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->getFilters();
    }

    public function isFiltersStateClean()
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'isFiltersStateClean', array(), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->isFiltersStateClean();
    }

    public function hasFilters()
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'hasFilters', array(), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return $this->valueHoldere5477->hasFilters();
    }

    /**
     * Constructor for lazy initialization
     *
     * @param \Closure|null $initializer
     */
    public static function staticProxyConstructor($initializer)
    {
        static $reflection;

        $reflection = $reflection ?? new \ReflectionClass(__CLASS__);
        $instance   = $reflection->newInstanceWithoutConstructor();

        \Closure::bind(function (\Doctrine\ORM\EntityManager $instance) {
            unset($instance->config, $instance->conn, $instance->metadataFactory, $instance->unitOfWork, $instance->eventManager, $instance->proxyFactory, $instance->repositoryFactory, $instance->expressionBuilder, $instance->closed, $instance->filterCollection, $instance->cache);
        }, $instance, 'Doctrine\\ORM\\EntityManager')->__invoke($instance);

        $instance->initializer412ed = $initializer;

        return $instance;
    }

    protected function __construct(\Doctrine\DBAL\Connection $conn, \Doctrine\ORM\Configuration $config, \Doctrine\Common\EventManager $eventManager)
    {
        static $reflection;

        if (! $this->valueHoldere5477) {
            $reflection = $reflection ?? new \ReflectionClass('Doctrine\\ORM\\EntityManager');
            $this->valueHoldere5477 = $reflection->newInstanceWithoutConstructor();
        \Closure::bind(function (\Doctrine\ORM\EntityManager $instance) {
            unset($instance->config, $instance->conn, $instance->metadataFactory, $instance->unitOfWork, $instance->eventManager, $instance->proxyFactory, $instance->repositoryFactory, $instance->expressionBuilder, $instance->closed, $instance->filterCollection, $instance->cache);
        }, $this, 'Doctrine\\ORM\\EntityManager')->__invoke($this);

        }

        $this->valueHoldere5477->__construct($conn, $config, $eventManager);
    }

    public function & __get($name)
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, '__get', ['name' => $name], $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        if (isset(self::$publicProperties464c9[$name])) {
            return $this->valueHoldere5477->$name;
        }

        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHoldere5477;

            $backtrace = debug_backtrace(false, 1);
            trigger_error(
                sprintf(
                    'Undefined property: %s::$%s in %s on line %s',
                    $realInstanceReflection->getName(),
                    $name,
                    $backtrace[0]['file'],
                    $backtrace[0]['line']
                ),
                \E_USER_NOTICE
            );
            return $targetObject->$name;
        }

        $targetObject = $this->valueHoldere5477;
        $accessor = function & () use ($targetObject, $name) {
            return $targetObject->$name;
        };
        $backtrace = debug_backtrace(true, 2);
        $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \ProxyManager\Stub\EmptyClassStub();
        $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $returnValue = & $accessor();

        return $returnValue;
    }

    public function __set($name, $value)
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, '__set', array('name' => $name, 'value' => $value), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHoldere5477;

            $targetObject->$name = $value;

            return $targetObject->$name;
        }

        $targetObject = $this->valueHoldere5477;
        $accessor = function & () use ($targetObject, $name, $value) {
            $targetObject->$name = $value;

            return $targetObject->$name;
        };
        $backtrace = debug_backtrace(true, 2);
        $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \ProxyManager\Stub\EmptyClassStub();
        $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $returnValue = & $accessor();

        return $returnValue;
    }

    public function __isset($name)
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, '__isset', array('name' => $name), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHoldere5477;

            return isset($targetObject->$name);
        }

        $targetObject = $this->valueHoldere5477;
        $accessor = function () use ($targetObject, $name) {
            return isset($targetObject->$name);
        };
        $backtrace = debug_backtrace(true, 2);
        $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \ProxyManager\Stub\EmptyClassStub();
        $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $returnValue = $accessor();

        return $returnValue;
    }

    public function __unset($name)
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, '__unset', array('name' => $name), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHoldere5477;

            unset($targetObject->$name);

            return;
        }

        $targetObject = $this->valueHoldere5477;
        $accessor = function () use ($targetObject, $name) {
            unset($targetObject->$name);

            return;
        };
        $backtrace = debug_backtrace(true, 2);
        $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \ProxyManager\Stub\EmptyClassStub();
        $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $accessor();
    }

    public function __clone()
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, '__clone', array(), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        $this->valueHoldere5477 = clone $this->valueHoldere5477;
    }

    public function __sleep()
    {
        $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, '__sleep', array(), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;

        return array('valueHoldere5477');
    }

    public function __wakeup()
    {
        \Closure::bind(function (\Doctrine\ORM\EntityManager $instance) {
            unset($instance->config, $instance->conn, $instance->metadataFactory, $instance->unitOfWork, $instance->eventManager, $instance->proxyFactory, $instance->repositoryFactory, $instance->expressionBuilder, $instance->closed, $instance->filterCollection, $instance->cache);
        }, $this, 'Doctrine\\ORM\\EntityManager')->__invoke($this);
    }

    public function setProxyInitializer(\Closure $initializer = null) : void
    {
        $this->initializer412ed = $initializer;
    }

    public function getProxyInitializer() : ?\Closure
    {
        return $this->initializer412ed;
    }

    public function initializeProxy() : bool
    {
        return $this->initializer412ed && ($this->initializer412ed->__invoke($valueHoldere5477, $this, 'initializeProxy', array(), $this->initializer412ed) || 1) && $this->valueHoldere5477 = $valueHoldere5477;
    }

    public function isProxyInitialized() : bool
    {
        return null !== $this->valueHoldere5477;
    }

    public function getWrappedValueHolderValue()
    {
        return $this->valueHoldere5477;
    }


}

if (!\class_exists('EntityManager_9a5be93', false)) {
    \class_alias(__NAMESPACE__.'\\EntityManager_9a5be93', 'EntityManager_9a5be93', false);
}
