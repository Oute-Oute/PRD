<?php

namespace ContainerOiy5GSK;
include_once \dirname(__DIR__, 4).''.\DIRECTORY_SEPARATOR.'vendor'.\DIRECTORY_SEPARATOR.'doctrine'.\DIRECTORY_SEPARATOR.'persistence'.\DIRECTORY_SEPARATOR.'src'.\DIRECTORY_SEPARATOR.'Persistence'.\DIRECTORY_SEPARATOR.'ObjectManager.php';
include_once \dirname(__DIR__, 4).''.\DIRECTORY_SEPARATOR.'vendor'.\DIRECTORY_SEPARATOR.'doctrine'.\DIRECTORY_SEPARATOR.'orm'.\DIRECTORY_SEPARATOR.'lib'.\DIRECTORY_SEPARATOR.'Doctrine'.\DIRECTORY_SEPARATOR.'ORM'.\DIRECTORY_SEPARATOR.'EntityManagerInterface.php';
include_once \dirname(__DIR__, 4).''.\DIRECTORY_SEPARATOR.'vendor'.\DIRECTORY_SEPARATOR.'doctrine'.\DIRECTORY_SEPARATOR.'orm'.\DIRECTORY_SEPARATOR.'lib'.\DIRECTORY_SEPARATOR.'Doctrine'.\DIRECTORY_SEPARATOR.'ORM'.\DIRECTORY_SEPARATOR.'EntityManager.php';

class EntityManager_9a5be93 extends \Doctrine\ORM\EntityManager implements \ProxyManager\Proxy\VirtualProxyInterface
{

    /**
     * @var \Doctrine\ORM\EntityManager|null wrapped object, if the proxy is initialized
     */
    private $valueHolderc6a35 = null;

    /**
     * @var \Closure|null initializer responsible for generating the wrapped object
     */
    private $initializere9a4f = null;

    /**
     * @var bool[] map of public properties of the parent class
     */
    private static $publicPropertiesd2286 = [
        
    ];

    public function getConnection()
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'getConnection', array(), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->getConnection();
    }

    public function getMetadataFactory()
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'getMetadataFactory', array(), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->getMetadataFactory();
    }

    public function getExpressionBuilder()
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'getExpressionBuilder', array(), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->getExpressionBuilder();
    }

    public function beginTransaction()
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'beginTransaction', array(), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->beginTransaction();
    }

    public function getCache()
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'getCache', array(), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->getCache();
    }

    public function transactional($func)
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'transactional', array('func' => $func), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->transactional($func);
    }

    public function wrapInTransaction(callable $func)
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'wrapInTransaction', array('func' => $func), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->wrapInTransaction($func);
    }

    public function commit()
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'commit', array(), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->commit();
    }

    public function rollback()
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'rollback', array(), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->rollback();
    }

    public function getClassMetadata($className)
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'getClassMetadata', array('className' => $className), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->getClassMetadata($className);
    }

    public function createQuery($dql = '')
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'createQuery', array('dql' => $dql), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->createQuery($dql);
    }

    public function createNamedQuery($name)
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'createNamedQuery', array('name' => $name), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->createNamedQuery($name);
    }

    public function createNativeQuery($sql, \Doctrine\ORM\Query\ResultSetMapping $rsm)
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'createNativeQuery', array('sql' => $sql, 'rsm' => $rsm), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->createNativeQuery($sql, $rsm);
    }

    public function createNamedNativeQuery($name)
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'createNamedNativeQuery', array('name' => $name), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->createNamedNativeQuery($name);
    }

    public function createQueryBuilder()
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'createQueryBuilder', array(), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->createQueryBuilder();
    }

    public function flush($entity = null)
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'flush', array('entity' => $entity), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->flush($entity);
    }

    public function find($className, $id, $lockMode = null, $lockVersion = null)
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'find', array('className' => $className, 'id' => $id, 'lockMode' => $lockMode, 'lockVersion' => $lockVersion), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->find($className, $id, $lockMode, $lockVersion);
    }

    public function getReference($entityName, $id)
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'getReference', array('entityName' => $entityName, 'id' => $id), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->getReference($entityName, $id);
    }

    public function getPartialReference($entityName, $identifier)
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'getPartialReference', array('entityName' => $entityName, 'identifier' => $identifier), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->getPartialReference($entityName, $identifier);
    }

    public function clear($entityName = null)
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'clear', array('entityName' => $entityName), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->clear($entityName);
    }

    public function close()
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'close', array(), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->close();
    }

    public function persist($entity)
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'persist', array('entity' => $entity), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->persist($entity);
    }

    public function remove($entity)
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'remove', array('entity' => $entity), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->remove($entity);
    }

    public function refresh($entity)
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'refresh', array('entity' => $entity), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->refresh($entity);
    }

    public function detach($entity)
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'detach', array('entity' => $entity), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->detach($entity);
    }

    public function merge($entity)
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'merge', array('entity' => $entity), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->merge($entity);
    }

    public function copy($entity, $deep = false)
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'copy', array('entity' => $entity, 'deep' => $deep), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->copy($entity, $deep);
    }

    public function lock($entity, $lockMode, $lockVersion = null)
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'lock', array('entity' => $entity, 'lockMode' => $lockMode, 'lockVersion' => $lockVersion), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->lock($entity, $lockMode, $lockVersion);
    }

    public function getRepository($entityName)
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'getRepository', array('entityName' => $entityName), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->getRepository($entityName);
    }

    public function contains($entity)
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'contains', array('entity' => $entity), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->contains($entity);
    }

    public function getEventManager()
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'getEventManager', array(), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->getEventManager();
    }

    public function getConfiguration()
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'getConfiguration', array(), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->getConfiguration();
    }

    public function isOpen()
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'isOpen', array(), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->isOpen();
    }

    public function getUnitOfWork()
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'getUnitOfWork', array(), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->getUnitOfWork();
    }

    public function getHydrator($hydrationMode)
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'getHydrator', array('hydrationMode' => $hydrationMode), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->getHydrator($hydrationMode);
    }

    public function newHydrator($hydrationMode)
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'newHydrator', array('hydrationMode' => $hydrationMode), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->newHydrator($hydrationMode);
    }

    public function getProxyFactory()
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'getProxyFactory', array(), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->getProxyFactory();
    }

    public function initializeObject($obj)
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'initializeObject', array('obj' => $obj), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->initializeObject($obj);
    }

    public function getFilters()
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'getFilters', array(), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->getFilters();
    }

    public function isFiltersStateClean()
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'isFiltersStateClean', array(), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->isFiltersStateClean();
    }

    public function hasFilters()
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'hasFilters', array(), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return $this->valueHolderc6a35->hasFilters();
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

        $instance->initializere9a4f = $initializer;

        return $instance;
    }

    protected function __construct(\Doctrine\DBAL\Connection $conn, \Doctrine\ORM\Configuration $config, \Doctrine\Common\EventManager $eventManager)
    {
        static $reflection;

        if (! $this->valueHolderc6a35) {
            $reflection = $reflection ?? new \ReflectionClass('Doctrine\\ORM\\EntityManager');
            $this->valueHolderc6a35 = $reflection->newInstanceWithoutConstructor();
        \Closure::bind(function (\Doctrine\ORM\EntityManager $instance) {
            unset($instance->config, $instance->conn, $instance->metadataFactory, $instance->unitOfWork, $instance->eventManager, $instance->proxyFactory, $instance->repositoryFactory, $instance->expressionBuilder, $instance->closed, $instance->filterCollection, $instance->cache);
        }, $this, 'Doctrine\\ORM\\EntityManager')->__invoke($this);

        }

        $this->valueHolderc6a35->__construct($conn, $config, $eventManager);
    }

    public function & __get($name)
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, '__get', ['name' => $name], $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        if (isset(self::$publicPropertiesd2286[$name])) {
            return $this->valueHolderc6a35->$name;
        }

        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHolderc6a35;

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

        $targetObject = $this->valueHolderc6a35;
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
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, '__set', array('name' => $name, 'value' => $value), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHolderc6a35;

            $targetObject->$name = $value;

            return $targetObject->$name;
        }

        $targetObject = $this->valueHolderc6a35;
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
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, '__isset', array('name' => $name), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHolderc6a35;

            return isset($targetObject->$name);
        }

        $targetObject = $this->valueHolderc6a35;
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
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, '__unset', array('name' => $name), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHolderc6a35;

            unset($targetObject->$name);

            return;
        }

        $targetObject = $this->valueHolderc6a35;
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
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, '__clone', array(), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        $this->valueHolderc6a35 = clone $this->valueHolderc6a35;
    }

    public function __sleep()
    {
        $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, '__sleep', array(), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;

        return array('valueHolderc6a35');
    }

    public function __wakeup()
    {
        \Closure::bind(function (\Doctrine\ORM\EntityManager $instance) {
            unset($instance->config, $instance->conn, $instance->metadataFactory, $instance->unitOfWork, $instance->eventManager, $instance->proxyFactory, $instance->repositoryFactory, $instance->expressionBuilder, $instance->closed, $instance->filterCollection, $instance->cache);
        }, $this, 'Doctrine\\ORM\\EntityManager')->__invoke($this);
    }

    public function setProxyInitializer(\Closure $initializer = null) : void
    {
        $this->initializere9a4f = $initializer;
    }

    public function getProxyInitializer() : ?\Closure
    {
        return $this->initializere9a4f;
    }

    public function initializeProxy() : bool
    {
        return $this->initializere9a4f && ($this->initializere9a4f->__invoke($valueHolderc6a35, $this, 'initializeProxy', array(), $this->initializere9a4f) || 1) && $this->valueHolderc6a35 = $valueHolderc6a35;
    }

    public function isProxyInitialized() : bool
    {
        return null !== $this->valueHolderc6a35;
    }

    public function getWrappedValueHolderValue()
    {
        return $this->valueHolderc6a35;
    }


}

if (!\class_exists('EntityManager_9a5be93', false)) {
    \class_alias(__NAMESPACE__.'\\EntityManager_9a5be93', 'EntityManager_9a5be93', false);
}
