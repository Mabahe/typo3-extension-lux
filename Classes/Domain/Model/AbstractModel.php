<?php

declare(strict_types=1);
namespace In2code\Lux\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

abstract class AbstractModel extends AbstractEntity
{
    /**
     * All records should be stored with sys_language_uid=-1 to get those values from persisted objects
     * in fe requests in every language
     */
    public function __construct()
    {
        $this->_setProperty('_languageUid', -1);
    }
}
