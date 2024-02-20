<?php

namespace App\Normalizer;

use ApiPlatform\Exception\InvalidArgumentException as LegacyInvalidArgumentException;
use ApiPlatform\Metadata\Exception\InvalidArgumentException;
use ApiPlatform\Metadata\HttpOperation;
use ApiPlatform\Metadata\IriConverterInterface;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Property\Factory\PropertyMetadataFactoryInterface;
use ApiPlatform\Metadata\Property\Factory\PropertyNameCollectionFactoryInterface;
use ApiPlatform\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use ApiPlatform\Metadata\ResourceClassResolverInterface;
use ApiPlatform\Metadata\UrlGeneratorInterface;
use ApiPlatform\Serializer\AbstractItemNormalizer;
use ApiPlatform\Symfony\Security\ResourceAccessCheckerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

#[AsDecorator('api_platform.serializer.normalizer.item', priority: -900)]
class ItemNormalizer extends AbstractItemNormalizer
{
    private readonly LoggerInterface $logger;

    public function __construct(
        PropertyNameCollectionFactoryInterface $propertyNameCollectionFactory,
        PropertyMetadataFactoryInterface $propertyMetadataFactory,
        IriConverterInterface $iriConverter,
        ResourceClassResolverInterface $resourceClassResolver,
        PropertyAccessorInterface $propertyAccessor = null,
        NameConverterInterface $nameConverter = null,
        ClassMetadataFactoryInterface $classMetadataFactory = null,
        LoggerInterface $logger = null,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataFactory = null,
        ResourceAccessCheckerInterface $resourceAccessChecker = null,
        array $defaultContext = []
    ) {
        parent::__construct($propertyNameCollectionFactory, $propertyMetadataFactory, $iriConverter,
            $resourceClassResolver, $propertyAccessor, $nameConverter, $classMetadataFactory, $defaultContext,
            $resourceMetadataFactory, $resourceAccessChecker);

        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * {@inheritdoc}
     *
     * @throws NotNormalizableValueException
     */
    public function denormalize(mixed $data, string $class, string $format = null, array $context = []): mixed
    {

        /** ⚠ Override the ApiPlatform Normalizer to enable setId in application/json*/
        if($context['operation'] instanceof Post){
            return parent::denormalize($data, $class, $format, $context);
        }

        // Avoid issues with proxies if we populated the object
        if (isset($data['id']) && !isset($context[self::OBJECT_TO_POPULATE])) {
            if (isset($context['api_allow_update']) && true !== $context['api_allow_update']) {
                throw new NotNormalizableValueException('Update is not allowed for this operation.');
            }


            if (isset($context['resource_class'])) {
                $this->updateObjectToPopulate($data, $context);
            } else {
                // See https://github.com/api-platform/core/pull/2326 to understand this message.
                $this->logger->warning('The "resource_class" key is missing from the context.', [
                    'context' => $context,
                ]);
            }
        }


        return parent::denormalize($data, $class, $format, $context);
    }

    private function updateObjectToPopulate(array $data, array &$context): void
    {
        try {
            $context[self::OBJECT_TO_POPULATE] = $this->iriConverter->getResourceFromIri((string)$data['id'],
                $context + ['fetch_data' => true]);
        } catch (LegacyInvalidArgumentException|InvalidArgumentException) {
            $operation = $this->resourceMetadataCollectionFactory?->create($context['resource_class'])->getOperation();
            if (
                !$operation || (
                    null !== ($context['uri_variables'] ?? null)
                    && $operation instanceof HttpOperation
                    && \count($operation->getUriVariables() ?? []) > 1
                )
            ) {
                throw new InvalidArgumentException('Cannot find object to populate, use JSON-LD or specify an IRI at path "id".');
            }
            $uriVariables = $this->getContextUriVariables($data, $operation, $context);
            $iri = $this->iriConverter->getIriFromResource($context['resource_class'], UrlGeneratorInterface::ABS_PATH,
                $operation, ['uri_variables' => $uriVariables]);

            $context[self::OBJECT_TO_POPULATE] = $this->iriConverter->getResourceFromIri($iri,
                $context + ['fetch_data' => true]);
        }
    }

    private function getContextUriVariables(array $data, $operation, array $context): array
    {
        $uriVariables = $context['uri_variables'] ?? [];

        $operationUriVariables = $operation->getUriVariables();
        if ((null !== $uriVariable = array_shift($operationUriVariables)) && \count($uriVariable->getIdentifiers())) {
            $identifier = $uriVariable->getIdentifiers()[0];
            if (isset($data[$identifier])) {
                $uriVariables[$uriVariable->getParameterName()] = $data[$identifier];
            }
        }

        return $uriVariables;
    }
}
