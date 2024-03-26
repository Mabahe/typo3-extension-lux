<?php

declare(strict_types=1);
namespace In2code\Lux\Domain\DataProvider;

use Exception;
use In2code\Lux\Domain\Repository\VisitorRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class LeadsPerTimeDataProvider extends AbstractDynamicFilterDataProvider
{
    /**
     * Set values like:
     *  [
     *      'amounts' => [ // new visitors
     *          3,
     *          5,
     *          9,
     *      ],
     *      'amounts2' => [ // existing visitors
     *          10,
     *          8,
     *          14,
     *      ],
     *      'titles' => [
     *          'January',
     *          'February',
     *          'Now'
     *      ]
     *  ]
     *
     * @return void
     * @throws Exception
     */
    public function prepareData(): void
    {
        $this->filter->removeShortMode();

        $visitorRepository = GeneralUtility::makeInstance(VisitorRepository::class);
        $intervals = $this->filter->getIntervals();
        $frequency = (string)$intervals['frequency'];
        foreach ($intervals['intervals'] as $interval) {
            // New visitors
            $this->data['amounts'][] = $visitorRepository->findAmountOfNewVisitorsInTimeFrame(
                $interval['start'],
                $interval['end'],
                $this->filter
            );

            // Existing visitors
            $this->data['amounts2'][] = $visitorRepository->findAmountOfExistingVisitorsInTimeFrame(
                $interval['start'],
                $interval['end'],
                $this->filter
            );
            $this->data['titles'][] = $this->getLabelForFrequency($frequency, $interval['start']);
        }
        $this->overruleLatestTitle($frequency);
    }
}
