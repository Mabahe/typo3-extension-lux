<?php

declare(strict_types=1);
namespace In2code\Lux\Domain\Tracker;

use In2code\Lux\Domain\Model\Pagevisit;
use In2code\Lux\Domain\Model\Search;
use In2code\Lux\Domain\Model\Visitor;
use In2code\Lux\Domain\Repository\VisitorRepository;
use In2code\Lux\Events\Log\SearchEvent;
use In2code\Lux\Utility\DatabaseUtility;
use In2code\Lux\Utility\ObjectUtility;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;

class SearchTracker
{
    protected VisitorRepository $visitorRepository;
    private EventDispatcherInterface $eventDispatcher;

    protected array $settings = [];

    /**
     * @param VisitorRepository $visitorRepository
     * @param EventDispatcherInterface $eventDispatcher
     * @throws InvalidConfigurationTypeException
     */
    public function __construct(VisitorRepository $visitorRepository, EventDispatcherInterface $eventDispatcher)
    {
        $this->visitorRepository = $visitorRepository;
        $this->eventDispatcher = $eventDispatcher;
        $configurationService = ObjectUtility::getConfigurationService();
        $this->settings = $configurationService->getTypoScriptSettings();
    }

    /**
     * @param Visitor $visitor
     * @param array $arguments
     * @param Pagevisit|null $pagevisit
     * @return void
     */
    public function track(Visitor $visitor, array $arguments, Pagevisit $pagevisit = null): void
    {
        if ($this->isTrackingActivated($visitor, $arguments)) {
            $searchTerm = $this->getSearchTerm($arguments['currentUrl']);
            $queryBuilder = DatabaseUtility::getQueryBuilderForTable(Search::TABLE_NAME);
            $properties = [
                'searchterm' => $searchTerm,
                'visitor' => $visitor->getUid(),
                'crdate' => time(),
                'tstamp' => time(),
            ];
            if ($pagevisit !== null) {
                $properties['pagevisit'] = $pagevisit->getUid();
            }
            $queryBuilder->insert(Search::TABLE_NAME)->values($properties)->executeStatement();
            $searchUid = $queryBuilder->getConnection()->lastInsertId();
            $this->eventDispatcher->dispatch(
                GeneralUtility::makeInstance(SearchEvent::class, $visitor, (int)$searchUid)
            );
        }
    }

    protected function isTrackingActivated(Visitor $visitor, array $arguments): bool
    {
        return $visitor->isNotBlacklisted() && $this->isTrackingActivatedInSettings()
            && $this->isSearchTermGiven($arguments['currentUrl']);
    }

    /**
     * Check if tracking of search is turned on via TypoScript
     *
     * @return bool
     */
    protected function isTrackingActivatedInSettings(): bool
    {
        return !empty($this->settings['tracking']['search']['_enable'])
            && $this->settings['tracking']['search']['_enable'] === '1';
    }

    protected function isSearchTermGiven(string $currentUrl): bool
    {
        return $this->getSearchTerm($currentUrl) !== '';
    }

    /**
     * Read the searchterm from URL GET parameters
     *
     * @param string $currentUrl
     * @return string
     */
    protected function getSearchTerm(string $currentUrl): string
    {
        $parsed = parse_url($currentUrl);
        if (!empty($parsed['query']) && !empty($this->settings['tracking']['search']['getParameters'])) {
            $searchKeys = GeneralUtility::trimExplode(
                ',',
                $this->settings['tracking']['search']['getParameters'],
                true
            );
            foreach ($searchKeys as $searchKey) {
                $searchKey = str_replace(['[', ']'], ['\[', '\]'], $searchKey);
                preg_match('~' . $searchKey . '=([^\&\?]+)~', urldecode($parsed['query']), $result);
                if (!empty($result[1])) {
                    return strtolower($result[1]);
                }
            }
        }
        return '';
    }
}
