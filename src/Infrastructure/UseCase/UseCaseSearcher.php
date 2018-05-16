<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\UseCase;

use Closure;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\Kernel;

final class UseCaseSearcher
{
    public function __invoke(Kernel $kernel): array
    {
        $container        = $this->getContainerBuilder($kernel);
        $allServicesClass = $this->filterServiceByTag($container);
        dump($allServicesClass);

        return $allServicesClass;
    }

    public function filterServiceByTag(ContainerBuilder $builder): array
    {
        $serviceIds        = $builder->findTaggedServiceIds('g3.use_case');
        $foundServiceClass = [];
        foreach ($serviceIds as $serviceClass => $serviceId) {
            $foundServiceClass[] = $serviceClass;
        }

        return $foundServiceClass;
    }

    public function callBackContainer(): Closure
    {
        return $this->buildContainer();
    }

    private function getContainerBuilder(Kernel $kernel): ContainerBuilder
    {
        $kernelContainer = $kernel->getContainer()->getParameter('debug.container.dump');
        $debug           = true;

        $kernelDebug = new ConfigCache($kernelContainer, $debug);

        if (!$kernel->isDebug() || !($kernelDebug)->isFresh()) {
            $container = $this->recoveryContainer($kernel);
        } else {
            $container = $this->createContainer($kernel);
        }

        return $container;
    }

    private function recoveryContainer(Kernel $kernel): ContainerBuilder
    {
        $buildContainer = $this->getBuildContainer($kernel);
        $container      = $buildContainer();
        $container->getCompilerPassConfig()->setRemovingPasses([]);
        $container->compile();

        return $container;
    }

    private function createContainer(Kernel $kernel): ContainerBuilder
    {
        $container     = new ContainerBuilder();
        $fileLocator   = new FileLocator();
        $xmlFileLoader = new XmlFileLoader($container, $fileLocator);
        try {
            $xmlFileLoader->load($kernel->getContainer()->getParameter('debug.container.dump'));
        } catch (\Exception $e) {
            throw new Exception(
                sprintf('Error xml kernel Load')
            );
        }

        return $container;
    }

    private function getBuildContainer(Kernel $kernel): Closure
    {
        $buildContainer = Closure::bind(
            $this->callBackContainer(),
            $kernel,
            get_class($kernel)
        );

        return $buildContainer;
    }
}
