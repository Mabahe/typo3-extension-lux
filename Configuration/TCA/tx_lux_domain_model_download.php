<?php

use In2code\Lux\Domain\Model\Download;
use In2code\Lux\Domain\Model\File;
use In2code\Lux\Domain\Model\Pagevisit;
use In2code\Lux\Domain\Model\Visitor;

return [
    'ctrl' => [
        'title' => 'LLL:EXT:lux/Resources/Private/Language/locallang_db.xlf:' . Download::TABLE_NAME,
        'label' => 'href',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'default_sortby' => 'ORDER BY crdate DESC',
        'delete' => 'deleted',
        'iconfile' => 'EXT:lux/Resources/Public/Icons/' . Download::TABLE_NAME . '.svg',
        'rootLevel' => -1,
        'hideTable' => true,
    ],
    'types' => [
        '1' => ['showitem' => 'crdate,href,page,file,properties,domain,site,visitor'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => ['type' => 'language'],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:lux/Resources/Private/Language/locallang_db.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => Download::TABLE_NAME,
                'foreign_table_where' => 'AND ' . Download::TABLE_NAME . '.pid=###CURRENT_PID### AND ' .
                    Download::TABLE_NAME . '.sys_language_uid IN (-1,0)',
                'default' => 0,
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],

        'crdate' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lux/Resources/Private/Language/locallang_db.xlf:' . Download::TABLE_NAME . '.crdate',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 30,
                'eval' => 'datetime',
                'readOnly' => true,
            ],
        ],
        'href' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lux/Resources/Private/Language/locallang_db.xlf:' . Download::TABLE_NAME . '.href',
            'config' => [
                'type' => 'input',
                'readOnly' => true,
            ],
        ],
        'page' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lux/Resources/Private/Language/locallang_db.xlf:' . Pagevisit::TABLE_NAME . '.page',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'pages',
                'foreign_table_where' => 'ORDER BY pages.title',
                'default' => 0,
                'readOnly' => true,
            ],
        ],
        'file' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lux/Resources/Private/Language/locallang_db.xlf:' . Download::TABLE_NAME . '.file',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => File::TABLE_NAME,
                'size' => 1,
                'maxitems' => 1,
                'default' => 0,
                'readOnly' => true,
            ],
        ],
        'domain' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lux/Resources/Private/Language/locallang_db.xlf:' . Download::TABLE_NAME . '.domain',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'readOnly' => true,
            ],
        ],
        'site' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lux/Resources/Private/Language/locallang_db.xlf:' . Download::TABLE_NAME . '.site',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'readOnly' => true,
            ],
        ],
        'visitor' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lux/Resources/Private/Language/locallang_db.xlf:' . Download::TABLE_NAME . '.visitor',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => Visitor::TABLE_NAME,
                'default' => 0,
                'readOnly' => true,
            ],
        ],
    ],
];
