<?php
/**
 * The contents of this file was generated using the WSDLs as provided by eBay.
 *
 * DO NOT EDIT THIS FILE!
 */

namespace DTS\eBaySDK\BulkDataExchange\Types;

/**
 *
 * @property string $recurringJobId
 * @property \DateTime $creationTime
 * @property integer $frequencyInMinutes
 * @property string $downloadJobType
 * @property \DTS\eBaySDK\BulkDataExchange\Enums\RecurringJobStatus $jobStatus
 * @property \DTS\eBaySDK\BulkDataExchange\Types\MonthlyRecurrence $monthlyRecurrence
 * @property \DTS\eBaySDK\BulkDataExchange\Types\WeeklyRecurrence $weeklyRecurrence
 * @property \DTS\eBaySDK\BulkDataExchange\Types\DailyRecurrence $dailyRecurrence
 */
class RecurringJobDetail extends \DTS\eBaySDK\Types\BaseType
{
    /**
     * @var array Properties belonging to objects of this class.
     */
    private static $propertyTypes = [
        'recurringJobId' => [
            'type' => 'string',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'recurringJobId'
        ],
        'creationTime' => [
            'type' => 'DateTime',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'creationTime'
        ],
        'frequencyInMinutes' => [
            'type' => 'integer',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'frequencyInMinutes'
        ],
        'downloadJobType' => [
            'type' => 'string',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'downloadJobType'
        ],
        'jobStatus' => [
            'type' => 'string',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'jobStatus'
        ],
        'monthlyRecurrence' => [
            'type' => 'DTS\eBaySDK\BulkDataExchange\Types\MonthlyRecurrence',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'monthlyRecurrence'
        ],
        'weeklyRecurrence' => [
            'type' => 'DTS\eBaySDK\BulkDataExchange\Types\WeeklyRecurrence',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'weeklyRecurrence'
        ],
        'dailyRecurrence' => [
            'type' => 'DTS\eBaySDK\BulkDataExchange\Types\DailyRecurrence',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'dailyRecurrence'
        ]
    ];

    /**
     * @param array $values Optional properties and values to assign to the object.
     */
    public function __construct(array $values = [])
    {
        list($parentValues, $childValues) = self::getParentValues(self::$propertyTypes, $values);

        parent::__construct($parentValues);

        if (!array_key_exists(__CLASS__, self::$properties)) {
            self::$properties[__CLASS__] = array_merge(self::$properties[get_parent_class()], self::$propertyTypes);
        }

        if (!array_key_exists(__CLASS__, self::$xmlNamespaces)) {
            self::$xmlNamespaces[__CLASS__] = 'xmlns="http://www.ebay.com/marketplace/services"';
        }

        $this->setValues(__CLASS__, $childValues);
    }
}
