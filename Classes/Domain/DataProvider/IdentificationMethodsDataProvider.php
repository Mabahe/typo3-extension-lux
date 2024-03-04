<?php

declare(strict_types=1);
namespace In2code\Lux\Domain\DataProvider;

use Exception;
use In2code\Lux\Domain\Model\Log;
use In2code\Lux\Domain\Repository\LogRepository;
use In2code\Lux\Utility\LocalizationUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class IdentificationMethodsDataProvider extends AbstractDataProvider
{
    /**
     * Set values like:
     *  [
     *      'amounts' => [
     *          120,
     *          88,
     *          33
     *      ],
     *      'titles' => [
     *          'email4link',
     *          'frontenduser login',
     *          'form listener'
     *      ]
     *  ]
     *
     * @return void
     * @throws Exception
     */
    public function prepareData(): void
    {
        $logRepository = GeneralUtility::makeInstance(LogRepository::class);
        $this->data = [
            'amounts' => [
                $logRepository->findByStatusAmount(Log::STATUS_IDENTIFIED_EMAIL4LINK, $this->filter),
                $logRepository->findByStatusAmount(Log::STATUS_IDENTIFIED, $this->filter),
                $logRepository->findByStatusAmount(Log::STATUS_IDENTIFIED_FORMLISTENING, $this->filter),
                $logRepository->findByStatusAmount(Log::STATUS_IDENTIFIED_FRONTENDAUTHENTICATION, $this->filter),
                $logRepository->findByStatusAmount(Log::STATUS_IDENTIFIED_LUXLETTERLINK, $this->filter),
            ],
            'titles' => [
                $this->getStatisticsLabel('identifiedemail4link'),
                $this->getStatisticsLabel('identifiedfieldlistening'),
                $this->getStatisticsLabel('identifiedformlistening'),
                $this->getStatisticsLabel('identifiedfrontendlogin'),
                $this->getStatisticsLabel('identifiedluxletter'),
            ],
        ];
    }

    protected function getStatisticsLabel(string $key): string
    {
        return LocalizationUtility::getLanguageService()->sL(
            'LLL:EXT:lux/Resources/Private/Language/locallang_db.xlf:module.analysis.statistics.' . $key
        );
    }
}
