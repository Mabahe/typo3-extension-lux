<?php

declare(strict_types=1);
namespace In2code\Lux\Domain\Model;

use DateTime;
use In2code\Lux\Domain\Repository\CompanyRepository;
use In2code\Lux\Domain\Repository\PagevisitRepository;
use In2code\Lux\Domain\Repository\VisitorRepository;
use In2code\Lux\Domain\Service\BranchService;
use In2code\Lux\Domain\Service\CountryService;
use In2code\Lux\Domain\Service\Image\CompanyImageService;
use In2code\Lux\Domain\Service\SiteService;
use In2code\Lux\Utility\BackendUtility;
use In2code\Lux\Utility\EnvironmentUtility;
use In2code\Lux\Utility\StringUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Company extends AbstractEntity
{
    public const TABLE_NAME = 'tx_lux_domain_model_company';

    protected string $title = '';
    protected string $branchCode = '';
    protected string $city = '';
    protected string $contacts = '';
    protected string $continent = '';
    protected string $countryCode = '';
    protected string $region = '';
    protected string $street = '';
    protected string $zip = '';
    protected string $domain = '';
    protected string $foundingYear = '';
    protected string $phone = '';
    protected string $revenue = '';
    protected string $revenueClass = '';
    protected string $size = '';
    protected string $sizeClass = '';
    protected string $description = '';
    protected ?Category $category = null;

    /**
     * Calculated value
     *
     * @var int
     */
    protected int $scoring = 0;

    protected ?DateTime $crdate = null;
    protected ?DateTime $tstamp = null;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getBranchCode(): string
    {
        return $this->branchCode;
    }

    public function setBranchCode(string $branchCode): self
    {
        $this->branchCode = $branchCode;
        return $this;
    }

    public function getBranch(): string
    {
        $branchService = GeneralUtility::makeInstance(BranchService::class);
        return $branchService->getBranchNameByCode($this->getBranchCode());
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getContacts(): string
    {
        return $this->contacts;
    }

    public function getContactsArray(): array
    {
        $contactsJson = $this->contacts;
        if (StringUtility::isJsonArray($contactsJson)) {
            return json_decode($contactsJson, true);
        }
        return [];
    }

    public function setContacts(string $contacts): self
    {
        $this->contacts = $contacts;
        return $this;
    }

    public function getContinent(): string
    {
        return $this->continent;
    }

    public function setContinent(string $continent): self
    {
        $this->continent = $continent;
        return $this;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function getCountryCodeUpper(): string
    {
        return strtoupper($this->getCountryCode());
    }

    public function getCountry(): string
    {
        $countryService = GeneralUtility::makeInstance(CountryService::class);
        return $countryService->getPropertyByAlpha2($this->getCountryCode());
    }

    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    public function getRegion(): string
    {
        return $this->region;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;
        return $this;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;
        return $this;
    }

    public function getZip(): string
    {
        return $this->zip;
    }

    public function setZip(string $zip): self
    {
        $this->zip = $zip;
        return $this;
    }

    /**
     * @return string like "Kunstmühlstr. 12a, 83026 Rosenheim, Germany"
     */
    public function getAddress(): string
    {
        return $this->getStreet() . ', ' . $this->getZip() . ' ' . $this->getCity() . ', ' . $this->getCountry();
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): self
    {
        $this->domain = $domain;
        return $this;
    }

    public function getFoundingYear(): string
    {
        return $this->foundingYear;
    }

    public function setFoundingYear(string $foundingYear): self
    {
        $this->foundingYear = $foundingYear;
        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getRevenue(): string
    {
        return $this->revenue;
    }

    public function setRevenue(string $revenue): self
    {
        $this->revenue = $revenue;
        return $this;
    }

    public function getRevenueClass(): string
    {
        return $this->revenueClass;
    }

    public function setRevenueClass(string $revenueClass): self
    {
        $this->revenueClass = $revenueClass;
        return $this;
    }

    public function getSize(): string
    {
        return $this->size;
    }

    public function setSize(string $size): self
    {
        $this->size = $size;
        return $this;
    }

    public function getSizeClass(): string
    {
        return $this->sizeClass;
    }

    public function setSizeClass(string $sizeClass): self
    {
        $this->sizeClass = $sizeClass;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): self
    {
        $this->category = $category;
        return $this;
    }

    public function getCrdate(): ?DateTime
    {
        return $this->crdate;
    }

    public function setCrdate(?DateTime $crdate): self
    {
        $this->crdate = $crdate;
        return $this;
    }

    public function getTstamp(): ?DateTime
    {
        return $this->tstamp;
    }

    public function setTstamp(?DateTime $tstamp): self
    {
        $this->tstamp = $tstamp;
        return $this;
    }

    public function getScoring(): int
    {
        if ($this->scoring > 0) {
            return $this->scoring;
        }
        $visitorRepository = GeneralUtility::makeInstance(VisitorRepository::class);
        return $visitorRepository->getScoringSumFromCompany($this);
    }

    public function setScoring(int $scoring): self
    {
        $this->scoring = $scoring;
        return $this;
    }

    public function getVisitors(): array
    {
        $visitorRepository = GeneralUtility::makeInstance(VisitorRepository::class);
        return $visitorRepository->findByCompany($this);
    }

    public function getFirstPagevisit(): ?Pagevisit
    {
        $companyRepository = GeneralUtility::makeInstance(PagevisitRepository::class);
        return $companyRepository->findFirstForCompany($this);
    }

    public function getLatestPagevisit(): ?Pagevisit
    {
        $companyRepository = GeneralUtility::makeInstance(PagevisitRepository::class);
        return $companyRepository->findLatestForCompany($this);
    }

    public function getNumberOfVisits(): int
    {
        $companyRepository = GeneralUtility::makeInstance(CompanyRepository::class);
        return $companyRepository->findNumberOfPagevisitsByCompany($this);
    }

    public function getNumberOfVisitors(): int
    {
        $companyRepository = GeneralUtility::makeInstance(CompanyRepository::class);
        return $companyRepository->findNumberOfVisitorsByCompany($this);
    }

    public function getImageUrl(): string
    {
        $visitorImageService = GeneralUtility::makeInstance(CompanyImageService::class);
        return $visitorImageService->getUrl(['company' => $this]);
    }

    /**
     * Check if this record can be viewed by current editor
     *
     * @return bool
     */
    public function canBeRead(): bool
    {
        if (EnvironmentUtility::isBackend() === false || BackendUtility::isAdministrator()) {
            return true;
        }
        $sites = GeneralUtility::makeInstance(SiteService::class)->getAllowedSites();
        return GeneralUtility::makeInstance(CompanyRepository::class)
            ->canCompanyBeReadBySites($this, array_keys($sites));
    }
}
