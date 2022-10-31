<?php

declare(strict_types=1);
namespace In2code\Lux\Domain\DataProvider;

use Exception;
use In2code\Lux\Domain\Repository\UtmRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class UtmSourceDataProvider
 */
class UtmSourceDataProvider extends AbstractDataProvider
{
    /**
     * Set values like:
     *  [
     *      'amounts' => [
     *          13,
     *          23,
     *          33,
     *      ],
     *      'titles' => [
     *          'Twitter',
     *          'Facebook',
     *          'LinkedIn',
     *      ]
     *  ]
     *
     * @return void
     * @throws Exception
     */
    public function prepareData(): void
    {
        $utmRepository = GeneralUtility::makeInstance(UtmRepository::class);
        $rows = $utmRepository->findCombinedByField('utm_source', $this->filter);
        foreach ($rows as $row) {
            $this->data['titles'][] = $row['utm_source'];
            $this->data['amounts'][] = $row['count'];
        }
    }
}