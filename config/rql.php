<?php

declare(strict_types=1);

use WayOfDev\RQL\Bridge\Cycle\Processors\Conditions;
use WayOfDev\RQL\Bridge\Cycle\Processors\CycleProcessor;

return [
    /*
     * Name of the HTTP query parameter used to carry the RQL (Resource Query Language) string.
     * Request parsers will use this parameter name to extract RQL for further processing.
     * For example, in a query like "?filter[name]=John&filter[surname]=Doe", 'filter' is the default query parameter.
     */
    'default_query_parameter' => 'filter',

    /*
     * This sets the default ORM (Object-Relational Mapping) processor that will be used for processing RQL strings.
     * Available options are 'cycle'.
     */
    'default_processor' => 'cycle',

    /*
     * This section contains the configuration of available ORM processors.
     */
    'processors' => [
        'cycle' => [
            /*
             * The class to be used as the handler for the 'cycle' processor.
             * This class should implement the ProcessorInterface.
             */
            'class' => CycleProcessor::class,

            /*
             * This array contains a list of classes that determine how different types of expressions will be processed.
             * Each class represents a different strategy for processing a particular type of expression.
             */
            'filter_specs' => [
                Conditions\ApplyComparison::class,
                Conditions\ApplyAdvancedComparison::class,
                Conditions\ApplyOr::class,
                Conditions\ApplyBetween::class,
            ],
        ],
    ],

    /*
     * This section contains the default configuration for filtering requests.
     * The options here determine the way RQL strings are interpreted and processed.
     */
    'filtering' => [
        /*
         * The prefix that is appended before all expression names in the RQL string.
         * For example, with a prefix of '$', an equality expression would be represented as '$eq'.
         */
        'filtering_prefix' => '$',

        /*
         * List of logical comparison operators that are allowed in RQL strings for data filtering and comparison.
         * Each string in this array represents the name of a valid comparison operator.
         */
        'allowed_expressions' => [
            'eq', // equal or =
            'notEq', // not equal or !=
            'lt', // less than
            'lte', // less than or equal
            'gt', // greater than
            'gte', // greater than or equal
            'like',
            'in',
            'notIn',
            'or',
            'between',
        ],

        /*
         * The default comparison operator that should be used when none is specified in the RQL string.
         */
        'default_expression' => 'eq',

        /*
         * The list of data types that are allowed in RQL strings.
         * Each data type can be handled differently when processing RQL.
         */
        'allowed_data_types' => [
            'string',
            'bool',
            'int',
            'date',
            'datetime',
        ],

        /*
         * The default data type that should be used when none is specified in the RQL string.
         */
        'default_data_type' => 'string',

        /*
         * If this is set to false, then when an unknown or not allowed data type is passed,
         * the parser will fall back to the default data type (defined by 'default_data_type').
         * If this is set to true, then when an unknown or not allowed data type is passed,
         * the parser will throw an exception.
         */
        'strict_comparison' => false,
    ],
];
