<?php

namespace ContainerFBaHqxD;


use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class get_ServiceLocator_SdyZWuDService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private '.service_locator.SdyZWuD' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        return $container->privates['.service_locator.SdyZWuD'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService, [
            'activity' => ['privates', '.errored..service_locator.SdyZWuD.App\\Entity\\Activity', NULL, 'Cannot autowire service ".service_locator.SdyZWuD": it references class "App\\Entity\\Activity" but no such service exists.'],
            'activityRepository' => ['privates', 'App\\Repository\\ActivityRepository', 'getActivityRepositoryService', true],
        ], [
            'activity' => 'App\\Entity\\Activity',
            'activityRepository' => 'App\\Repository\\ActivityRepository',
        ]);
    }
}
