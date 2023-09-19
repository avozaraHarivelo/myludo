<?php
// api/src/Doctrine/CurrentUserExtension.php

namespace App\Doctrine;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Offer;
use App\Entity\Pret;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;

final class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        $this->addWhere($queryBuilder, $resourceClass ,$context);
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, Operation $operation = null, array $context = []): void
    {
        $this->addWhere($queryBuilder, $resourceClass ,$context);
    }

    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass, array $context): void
    {

        if ($this->security->isGranted('ROLE_ADMIN')) {
            if (User::class === $resourceClass && $context["operation_type"] === "collection" && $context["collection_operation_name"] === "get") {
                $rootAlias = $queryBuilder->getRootAliases()[0];
                $queryBuilder->andWhere("$rootAlias.roles NOT LIKE '%ROLE_ADMIN%'");
            }
            return;
        };

        

        $rootAlias = $queryBuilder->getRootAliases()[0];
        if (null === $user = $this->security->getUser()  ) return;

        if($user->isBloquer()) return;
        if ($this->security->isGranted('ROLE_MEMBER')) {
            if (Pret::class === $resourceClass && $context["operation_type"] === "collection" && $context["collection_operation_name"] === "get") {
                $queryBuilder->andWhere(":current_user = $rootAlias.user")
                ->setParameter('current_user', $user->getId()); 
            }
        }


       

    }
}