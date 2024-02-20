<?php
// src/OpenApi/OpenApiFactory.php
namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\OpenApi;
use ApiPlatform\OpenApi\Model;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;

#[AsDecorator(decorates: 'api_platform.openapi.factory')]
final readonly class OpenApiFactoryDecorator implements OpenApiFactoryInterface
{

    public function __construct(private OpenApiFactoryInterface $decorated)
    {
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);

        $paths = $openApi->getPaths()->getPaths();
        $filteredPaths = new Model\Paths();
        foreach ($paths as $path => $pathItem) {
            // If a prefix is configured on API Platform's routes, it must appear here.
            if ($path === '/api/notification_datas') {
                continue;
            }
            $filteredPaths->addPath($path, $pathItem);
        }

        return $openApi->withPaths($filteredPaths);
    }
}
