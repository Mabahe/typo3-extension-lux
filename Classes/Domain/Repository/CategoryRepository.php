<?php

/** @noinspection SqlNoDataSourceInspection */
/** @noinspection SqlDialectInspection */
declare(strict_types=1);
namespace In2code\Lux\Domain\Repository;

use Doctrine\DBAL\Exception as ExceptionDbal;
use In2code\Lux\Domain\Model\Category;
use In2code\Lux\Domain\Service\PermissionTrait;
use In2code\Lux\Utility\DatabaseUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

class CategoryRepository extends AbstractRepository
{
    use PermissionTrait;

    public function findAllLuxCategories(): array
    {
        $query = $this->createQuery();
        $query->matching($query->equals('lux_category', true));
        $query->setOrderings(['title' => QueryInterface::ORDER_ASCENDING]);
        $records = $query->execute()->toArray();
        return $this->filterRecords($records, Category::TABLE_NAME);
    }

    public function findAllLuxCompanyCategories(): array
    {
        $query = $this->createQuery();
        $query->matching($query->equals('lux_company_category', true));
        $query->setOrderings(['title' => QueryInterface::ORDER_ASCENDING]);
        $records = $query->execute()->toArray();
        return $this->filterRecords($records, Category::TABLE_NAME);
    }

    /**
     * @param int $pageIdentifier
     * @param int $categoryIdentifier
     * @return bool
     * @throws ExceptionDbal
     */
    public function isPageIdentifierRelatedToCategoryIdentifier(int $pageIdentifier, int $categoryIdentifier): bool
    {
        $queryBuilder = DatabaseUtility::getQueryBuilderForTable('sys_category_record_mm');
        $relations = $queryBuilder
            ->select('*')
            ->from('sys_category_record_mm')
            ->where('tablenames = "pages" and fieldname = "categories" and uid_local = '
                . $categoryIdentifier . ' and uid_foreign = ' . $pageIdentifier)
            ->executeQuery()
            ->fetchAllAssociative();
        return !empty($relations[0]);
    }

    /**
     * @return int
     * @throws ExceptionDbal
     */
    public function findAllAmount(): int
    {
        $connection = DatabaseUtility::getConnectionForTable(Category::TABLE_NAME);
        $query = 'select count(uid) from ' . Category::TABLE_NAME . ' where lux_category=1 and deleted=0';
        return (int)$connection->executeQuery($query)->fetchOne();
    }

    /**
     * @param int $newsIdentifier
     * @return array
     * @throws ExceptionDbal
     */
    public function findAllCategoryIdentifiersToNews(int $newsIdentifier): array
    {
        $queryBuilder = DatabaseUtility::getQueryBuilderForTable('sys_category_record_mm');
        return $queryBuilder
            ->select('uid_local')
            ->from('sys_category_record_mm')
            ->where('uid_foreign=' . $newsIdentifier
                . ' and tablenames="tx_news_domain_model_news" and fieldname="categories"')
            ->executeQuery()
            ->fetchFirstColumn();
    }

    /**
     * @param int $identifier
     * @return bool
     * @throws ExceptionDbal
     */
    public function isLuxCategory(int $identifier): bool
    {
        $queryBuilder = DatabaseUtility::getQueryBuilderForTable(Category::TABLE_NAME);
        return $queryBuilder
            ->select('lux_category')
            ->from(Category::TABLE_NAME)
            ->where('uid=' . $identifier)
            ->executeQuery()
            ->fetchOne() === 1;
    }
}
