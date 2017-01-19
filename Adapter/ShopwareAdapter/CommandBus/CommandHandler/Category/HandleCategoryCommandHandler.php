<?php

namespace ShopwareAdapter\CommandBus\CommandHandler\Category;

use Doctrine\ORM\EntityManagerInterface;
use PlentyConnector\Connector\CommandBus\Command\Category\HandleCategoryCommand;
use PlentyConnector\Connector\CommandBus\Command\CommandInterface;
use PlentyConnector\Connector\CommandBus\Command\HandleCommandInterface;
use PlentyConnector\Connector\CommandBus\CommandHandler\CommandHandlerInterface;
use PlentyConnector\Connector\IdentityService\IdentityServiceInterface;
use PlentyConnector\Connector\TransferObject\Category\Category;
use PlentyConnector\Connector\TransferObject\Category\CategoryInterface;
use PlentyConnector\Connector\TransferObject\Language\Language;
use PlentyConnector\Connector\TransferObject\Media\Media;
use PlentyConnector\Connector\TransferObject\Shop\Shop;
use PlentyConnector\Connector\Translation\TranslationHelperInterface;
use Shopware\Components\Api\Resource\Category as CategoryResource;
use Shopware\Models\Category\Category as CategoryModel;
use Shopware\Models\Category\Repository as CategoryRepository;
use Shopware\Models\Shop\Repository as ShopRepository;
use Shopware\Models\Shop\Shop as ShopModel;
use ShopwareAdapter\ShopwareAdapter;

/**
 * Class HandleCategoryCommandHandler.
 */
class HandleCategoryCommandHandler implements CommandHandlerInterface
{
    /**
     * @var CategoryResource
     */
    private $resource;

    /**
     * @var IdentityServiceInterface
     */
    private $identityService;

    /**
     * @var TranslationHelperInterface
     */
    private $translationHelper;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var ShopRepository
     */
    private $shopRepository;

    /**
     * HandleCategoryCommandHandler constructor.
     *
     * @param CategoryResource $resource
     * @param IdentityServiceInterface $identityService
     * @param TranslationHelperInterface $translationHelper
     */
    public function __construct(
        CategoryResource $resource,
        IdentityServiceInterface $identityService,
        TranslationHelperInterface $translationHelper,
        EntityManagerInterface $entityManager
    ) {
        $this->resource = $resource;
        $this->identityService = $identityService;
        $this->translationHelper = $translationHelper;
        $this->categoryRepository = $entityManager->getRepository(CategoryModel::class);
        $this->shopRepository = $entityManager->getRepository(ShopModel::class);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(CommandInterface $command)
    {
        return
            $command instanceof HandleCategoryCommand &&
            $command->getAdapterName() === ShopwareAdapter::NAME;
    }

    /**
     * @param CommandInterface $command
     *
     * @throws \Shopware\Components\Api\Exception\ValidationException
     * @throws \Shopware\Components\Api\Exception\NotFoundException
     * @throws \Shopware\Components\Api\Exception\ParameterMissingException
     * @throws \PlentyConnector\Connector\IdentityService\Exception\NotFoundException
     * @throws \Shopware\Components\Api\Exception\CustomValidationException
     * @throws \Exception
     */
    public function handle(CommandInterface $command)
    {
        /**
         * @var HandleCommandInterface $command
         * @var CategoryInterface $category
         */
        $category = $command->getTransferObject();

        $shopIdentity = $this->identityService->findOneBy([
            'objectIdentifier' => $category->getShopIdentifier(),
            'objectType' => Shop::TYPE,
            'adapterName' => ShopwareAdapter::NAME,
        ]);

        if (null === $shopIdentity) {
            // throw
        }

        $shop = $this->shopRepository->find($shopIdentity->getAdapterIdentifier());

        if (null === $shop) {
            // throw
        }

        $languageIdentity = $this->identityService->findOneBy([
            'adapterIdentifier' => (string)$shop->getLocale()->getId(),
            'adapterName' => ShopwareAdapter::NAME,
            'objectType' => Language::TYPE
        ]);

        if (null !== $languageIdentity) {
            $category = $this->translationHelper->translate($languageIdentity->getObjectIdentifier(), $category);
        }

        if (null === $category->getParentIdentifier()) {
            $parentCategory = $shop->getCategory()->getId();
        } else {
            $parentCategoryIdentity = $this->identityService->findOneBy([
                'objectIdentifier' => $category->getParentIdentifier(),
                'objectType' => Category::TYPE,
                'adapterName' => ShopwareAdapter::NAME
            ]);

            if (null === $parentCategoryIdentity) {
                // throw
            }

            $parentCategory = $parentCategoryIdentity->getAdapterIdentifier();
        }

        $categoryIdentity = $this->identityService->findOneBy([
            'objectIdentifier' => $category->getIdentifier(),
            'objectType' => Category::TYPE,
            'adapterName' => ShopwareAdapter::NAME,
        ]);

        if (null === $categoryIdentity) {
            $existingCategory = $this->findExistingCategory($category, $parentCategory);

            if (null !== $existingCategory) {
                $categoryIdentity = $this->identityService->create(
                    $category->getIdentifier(),
                    Category::TYPE,
                    (string)$existingCategory,
                    ShopwareAdapter::NAME
                );
            }
        }

        $parans = [
            'name' => $category->getName(),
            'parent' => $parentCategory,
        ];

        if (!empty($category->getImageIdentifiers())) {
            $mediaIdentifiers = $category->getImageIdentifiers();
            $mediaIdentifier = array_shift($mediaIdentifiers);

            $mediaIdentity = $this->identityService->findOneBy([
                'objectIdentifier' => $mediaIdentifier,
                'objectType' => Media::TYPE,
                'adapterName' => ShopwareAdapter::NAME,
            ]);

            if (null === $mediaIdentity) {
                // throw
            }

            $parans['media']['mediaId'] = $mediaIdentity->getAdapterIdentifier();
        }

        if (null === $categoryIdentity) {
            $newCategory = $this->resource->create($parans);

            $this->identityService->create(
                $category->getIdentifier(),
                Category::TYPE,
                (string)$newCategory->getId(),
                ShopwareAdapter::NAME
            );
        } else {
            $this->resource->update($categoryIdentity->getAdapterIdentifier(), $parans);
        }
    }

    /**
     * @param CategoryInterface $category
     * @param int $parentCategory
     *
     * @return int|null
     */
    private function findExistingCategory(CategoryInterface $category, $parentCategory)
    {
        $existingCategory = $this->categoryRepository->findOneBy([
            'name' => $category->getName(),
            'parentId' => $parentCategory
        ]);

        if (null === $existingCategory) {
            return null;
        }

        return $existingCategory->getId();
    }
}
