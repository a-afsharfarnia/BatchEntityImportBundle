<?php

namespace JG\BatchEntityImportBundle\Service;

use JG\BatchEntityImportBundle\Utils\StringHelper;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use ReflectionClass;
use ReflectionException;

class PropertyExistenceChecker
{
    private ReflectionClass  $reflectionClass;
    private ?ReflectionClass $translationReflectionClass;

    /**
     * @param object $entity
     *
     * @throws ReflectionException
     */
    public function __construct(object $entity)
    {
        $this->reflectionClass            = new ReflectionClass($entity);
        $this->translationReflectionClass = $entity instanceof TranslatableInterface ? new ReflectionClass($entity->translate()) : null;
    }

    public function propertyExists(string $name): bool
    {
        $name = StringHelper::underscoreToCamelCase($name);

        return $this->reflectionClass->hasProperty($name) || $this->translationPropertyExists($name);
    }

    private function translationPropertyExists(string $name): bool
    {
        return $this->translationReflectionClass && $this->translationReflectionClass->hasProperty($name);
    }
}