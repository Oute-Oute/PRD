<?php

namespace ContainerJzzM9fQ;
include_once \dirname(__DIR__, 4).''.\DIRECTORY_SEPARATOR.'vendor'.\DIRECTORY_SEPARATOR.'doctrine'.\DIRECTORY_SEPARATOR.'persistence'.\DIRECTORY_SEPARATOR.'src'.\DIRECTORY_SEPARATOR.'Persistence'.\DIRECTORY_SEPARATOR.'ObjectManager.php';
include_once \dirname(__DIR__, 4).''.\DIRECTORY_SEPARATOR.'vendor'.\DIRECTORY_SEPARATOR.'doctrine'.\DIRECTORY_SEPARATOR.'orm'.\DIRECTORY_SEPARATOR.'lib'.\DIRECTORY_SEPARATOR.'Doctrine'.\DIRECTORY_SEPARATOR.'ORM'.\DIRECTORY_SEPARATOR.'EntityManagerInterface.php';
include_once \dirname(__DIR__, 4).''.\DIRECTORY_SEPARATOR.'vendor'.\DIRECTORY_SEPARATOR.'doctrine'.\DIRECTORY_SEPARATOR.'orm'.\DIRECTORY_SEPARATOR.'lib'.\DIRECTORY_SEPARATOR.'Doctrine'.\DIRECTORY_SEPARATOR.'ORM'.\DIRECTORY_SEPARATOR.'EntityManager.php';

class EntityManager_9a5be93 extends \Doctrine\ORM\EntityManager implements \ProxyManager\Proxy\VirtualProxyInterface
{

    /**
     * @var \Doctrine\ORM\EntityManager|null wrapped object, if the proxy is initialized
     */
    private $valueHolder95997 = null;

    /**
     * @var \Closure|null initializer responsible for generating the wrapped object
     */
    private $initializer404c2 = null;

    /**
     * @var bool[] map of public properties of the parent class
     */
    private static $publicPropertiesc8c38 = [
        
    ];

    public function getConnection()
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'getConnection', array(), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->getConnection();
    }

    public function getMetadataFactory()
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'getMetadataFactory', array(), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->getMetadataFactory();
    }

    public function getExpressionBuilder()
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'getExpressionBuilder', array(), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->getExpressionBuilder();
    }

    public function beginTransaction()
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'beginTransaction', array(), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->beginTransaction();
    }

    public function getCache()
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'getCache', array(), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->getCache();
    }

    public function transactional($func)
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'transactional', array('func' => $func), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->transactional($func);
    }

    public function wrapInTransaction(callable $func)
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'wrapInTransaction', array('func' => $func), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->wrapInTransaction($func);
    }

    public function commit()
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'commit', array(), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->commit();
    }

    public function rollback()
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'rollback', array(), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->rollback();
    }

    public function getClassMetadata($className)
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'getClassMetadata', array('className' => $className), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->getClassMetadata($className);
    }

    public function createQuery($dql = '')
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'createQuery', array('dql' => $dql), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->createQuery($dql);
    }

    public function createNamedQuery($name)
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'createNamedQuery', array('name' => $name), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->createNamedQuery($name);
    }

    public function createNativeQuery($sql, \Doctrine\ORM\Query\ResultSetMapping $rsm)
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'createNativeQuery', array('sql' => $sql, 'rsm' => $rsm), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->createNativeQuery($sql, $rsm);
    }

    public function createNamedNativeQuery($name)
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'createNamedNativeQuery', array('name' => $name), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->createNamedNativeQuery($name);
    }

    public function createQueryBuilder()
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'createQueryBuilder', array(), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->createQueryBuilder();
    }

    public function flush($entity = null)
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'flush', array('entity' => $entity), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->flush($entity);
    }

    public function find($className, $id, $lockMode = null, $lockVersion = null)
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'find', array('className' => $className, 'id' => $id, 'lockMode' => $lockMode, 'lockVersion' => $lockVersion), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->find($className, $id, $lockMode, $lockVersion);
    }

    public function getReference($entityName, $id)
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'getReference', array('entityName' => $entityName, 'id' => $id), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->getReference($entityName, $id);
    }

    public function getPartialReference($entityName, $identifier)
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'getPartialReference', array('entityName' => $entityName, 'identifier' => $identifier), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->getPartialReference($entityName, $identifier);
    }

    public function clear($entityName = null)
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'clear', array('entityName' => $entityName), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->clear($entityName);
    }

    public function close()
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'close', array(), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->close();
    }

    public function persist($entity)
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'persist', array('entity' => $entity), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->persist($entity);
    }

    public function remove($entity)
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'remove', array('entity' => $entity), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->remove($entity);
    }

    public function refresh($entity)
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'refresh', array('entity' => $entity), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->refresh($entity);
    }

    public function detach($entity)
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'detach', array('entity' => $entity), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->detach($entity);
    }

    public function merge($entity)
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'merge', array('entity' => $entity), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->merge($entity);
    }

    public function copy($entity, $deep = false)
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'copy', array('entity' => $entity, 'deep' => $deep), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->copy($entity, $deep);
    }

    public function lock($entity, $lockMode, $lockVersion = null)
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'lock', array('entity' => $entity, 'lockMode' => $lockMode, 'lockVersion' => $lockVersion), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->lock($entity, $lockMode, $lockVersion);
    }

    public function getRepository($entityName)
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'getRepository', array('entityName' => $entityName), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->getRepository($entityName);
    }

    public function contains($entity)
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'contains', array('entity' => $entity), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->contains($entity);
    }

    public function getEventManager()
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'getEventManager', array(), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->getEventManager();
    }

    public function getConfiguration()
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'getConfiguration', array(), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->getConfiguration();
    }

    public function isOpen()
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'isOpen', array(), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->isOpen();
    }

    public function getUnitOfWork()
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'getUnitOfWork', array(), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->getUnitOfWork();
    }

    public function getHydrator($hydrationMode)
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'getHydrator', array('hydrationMode' => $hydrationMode), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->getHydrator($hydrationMode);
    }

    public function newHydrator($hydrationMode)
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'newHydrator', array('hydrationMode' => $hydrationMode), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->newHydrator($hydrationMode);
    }

    public function getProxyFactory()
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'getProxyFactory', array(), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->getProxyFactory();
    }

    public function initializeObject($obj)
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'initializeObject', array('obj' => $obj), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->initializeObject($obj);
    }

    public function getFilters()
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'getFilters', array(), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->getFilters();
    }

    public function isFiltersStateClean()
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'isFiltersStateClean', array(), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->isFiltersStateClean();
    }

    public function hasFilters()
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'hasFilters', array(), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return $this->valueHolder95997->hasFilters();
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

        $instance->initializer404c2 = $initializer;

        return $instance;
    }

    protected function __construct(\Doctrine\DBAL\Connection $conn, \Doctrine\ORM\Configuration $config, \Doctrine\Common\EventManager $eventManager)
    {
        static $reflection;

        if (! $this->valueHolder95997) {
            $reflection = $reflection ?? new \ReflectionClass('Doctrine\\ORM\\EntityManager');
            $this->valueHolder95997 = $reflection->newInstanceWithoutConstructor();
        \Closure::bind(function (\Doctrine\ORM\EntityManager $instance) {
            unset($instance->config, $instance->conn, $instance->metadataFactory, $instance->unitOfWork, $instance->eventManager, $instance->proxyFactory, $instance->repositoryFactory, $instance->expressionBuilder, $instance->closed, $instance->filterCollection, $instance->cache);
        }, $this, 'Doctrine\\ORM\\EntityManager')->__invoke($this);

        }

        $this->valueHolder95997->__construct($conn, $config, $eventManager);
    }

    public function & __get($name)
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, '__get', ['name' => $name], $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        if (isset(self::$publicPropertiesc8c38[$name])) {
            return $this->valueHolder95997->$name;
        }

        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHolder95997;

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

        $targetObject = $this->valueHolder95997;
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
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, '__set', array('name' => $name, 'value' => $value), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHolder95997;

            $targetObject->$name = $value;

            return $targetObject->$name;
        }

        $targetObject = $this->valueHolder95997;
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
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, '__isset', array('name' => $name), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHolder95997;

            return isset($targetObject->$name);
        }

        $targetObject = $this->valueHolder95997;
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
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, '__unset', array('name' => $name), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHolder95997;

            unset($targetObject->$name);

            return;
        }

        $targetObject = $this->valueHolder95997;
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
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, '__clone', array(), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        $this->valueHolder95997 = clone $this->valueHolder95997;
    }

    public function __sleep()
    {
        $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, '__sleep', array(), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;

        return array('valueHolder95997');
    }

    public function __wakeup()
    {
        \Closure::bind(function (\Doctrine\ORM\EntityManager $instance) {
            unset($instance->config, $instance->conn, $instance->metadataFactory, $instance->unitOfWork, $instance->eventManager, $instance->proxyFactory, $instance->repositoryFactory, $instance->expressionBuilder, $instance->closed, $instance->filterCollection, $instance->cache);
        }, $this, 'Doctrine\\ORM\\EntityManager')->__invoke($this);
    }

    public function setProxyInitializer(\Closure $initializer = null) : void
    {
        $this->initializer404c2 = $initializer;
    }

    public function getProxyInitializer() : ?\Closure
    {
        return $this->initializer404c2;
    }

    public function initializeProxy() : bool
    {
        return $this->initializer404c2 && ($this->initializer404c2->__invoke($valueHolder95997, $this, 'initializeProxy', array(), $this->initializer404c2) || 1) && $this->valueHolder95997 = $valueHolder95997;
    }

    public function isProxyInitialized() : bool
    {
        return null !== $this->valueHolder95997;
    }

    public function getWrappedValueHolderValue()
    {
        return $this->valueHolder95997;
    }


}

if (!\class_exists('EntityManager_9a5be93', false)) {
    \class_alias(__NAMESPACE__.'\\EntityManager_9a5be93', 'EntityManager_9a5be93', false);
}
