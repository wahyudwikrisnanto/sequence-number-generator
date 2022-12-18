<?php

return [
    /**
     * This to set the sequence number prefix, output example.
     * the default value is INV so the output will be {INV}0001
     * (without brackets)
     */

    'prefix' => 'INV',

    /**
     * The number of digits or length of the sequence,
     * the default value is 5 and the example output will be {00001}
     * (without brackets)
     */

    'digits' => 5,

    /**
     * The start number of the sequence,
     * the default value is 1 and the example output will be 0000{1)
     * (without brackets)
     */

    'start'  => 1,

    /**
     * The separator between prefix part and the sequence number part,
     * the default value is '-' and the example output is INV{-}00001
     * (without brackets)
     */

    'prefix_sequence_separator' => '-',

    /**
     * Type is the category of the generated sequence number
     */

    'type'   => 'default',

    /**
     * Skip is the number that will be skipped when generating sequence number
     */

    'skip' => 0,
];
