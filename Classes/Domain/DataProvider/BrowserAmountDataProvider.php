<?php

declare(strict_types=1);
namespace In2code\Lux\Domain\DataProvider;

use In2code\Lux\Domain\Repository\FingerprintRepository;
use In2code\Lux\Exception\ClassDoesNotExistException;
use In2code\Lux\Utility\LocalizationUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class BrowserAmountDataProvider extends AbstractDataProvider
{
    /**
     * Set values like:
     *  [
     *      'amounts' => [
     *          120,
     *          88,
     *      ],
     *      'titles' => [
     *          'OS X Chrome',
     *          'Windows Firefox',
     *      ]
     *  ]
     *
     * @return void
     * @throws ClassDoesNotExistException
     */
    public function prepareData(): void
    {
        $fingerprintRepo = GeneralUtility::makeInstance(FingerprintRepository::class);
        $osBrowsers = $fingerprintRepo->getAmountOfUserAgents($this->filter);
        $titles = $amounts = [];
        $counter = $additionalAmount = 0;
        foreach ($osBrowsers as $osBrowser => $amount) {
            if ($counter < 5) {
                $titles[] = $osBrowser;
                $amounts[] = $amount;
            } else {
                $additionalAmount += $amount;
            }
            $counter++;
        }
        if ($counter > 0) {
            $titles[] = $this->getFurtherLabel();
            $amounts[] = $additionalAmount;
        }
        $this->data = ['amounts' => $amounts, 'titles' => $titles];
    }

    protected function getFurtherLabel(): string
    {
        return LocalizationUtility::getLanguageService()->sL(
            'LLL:EXT:lux/Resources/Private/Language/locallang_db.xlf:module.dashboard.widget.browser.further'
        );
    }
}
