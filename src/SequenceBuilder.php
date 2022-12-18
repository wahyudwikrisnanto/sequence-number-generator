<?php

namespace WahyuDwiKrisnanto\SequenceNumberGenerator;

use Exception;

class SequenceBuilder
{
    private SequenceService $sequenceNumber;

    public function __construct()
    {
        $this->reset();
    }

    public function reset(): void
    {
        $this->sequenceNumber = new SequenceService();
    }

    public function prefix(string $prefix): void
    {
        $this->sequenceNumber->prefix = $prefix;
    }

    public function start(int $start): void
    {
        $this->sequenceNumber->start = $start;
    }

    public function digits(int $digits): void
    {
        $this->sequenceNumber->digits = $digits;
    }

    public function type(string $type): void
    {
        $this->sequenceNumber->type = $type;
    }

    public function ignoreUpdate(): object
    {
        $this->sequenceNumber->ignoreUpdate = true;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function generate($isReturnInstance = false): SequenceService|string
    {
        $result = $this->sequenceNumber;

        $this->reset();

        return $result->result($isReturnInstance);
    }

    public function prefixSequenceSeparator(string $separator): void
    {
        $this->sequenceNumber->prefixSequenceSeparator = $separator;
    }
}
