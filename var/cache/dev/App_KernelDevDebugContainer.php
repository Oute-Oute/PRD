<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerUkL1h05\App_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerUkL1h05/App_KernelDevDebugContainer.php') {
    touch(__DIR__.'/ContainerUkL1h05.legacy');

    return;
}

if (!\class_exists(App_KernelDevDebugContainer::class, false)) {
    \class_alias(\ContainerUkL1h05\App_KernelDevDebugContainer::class, App_KernelDevDebugContainer::class, false);
}

return new \ContainerUkL1h05\App_KernelDevDebugContainer([
    'container.build_hash' => 'UkL1h05',
    'container.build_id' => '07a9b381',
    'container.build_time' => 1655723967,
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerUkL1h05');
