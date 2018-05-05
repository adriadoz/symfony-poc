<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\UserCase;

use Symfony\Component\DependencyInjection\ContainerInterface;

final class UseCaseSearcher
{
    public function __invoke(ContainerInterface $container): array
    {
        foreach ($container->findTaggedServiceIds('g3.use_case') as $id => $tags) {
            dump($id);
            dump($tags);
        }

        return [];
    }
}
