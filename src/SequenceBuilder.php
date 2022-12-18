<?php

namespace WahyuDwiKrisnanto\SequenceNumberGenerator;

use Exception;

class SequenceBuilder
{
    private SequenceService $sequenceService;

    public function __construct()
    {
        $this->reset();
    }

    public function reset(): void
    {
        $this->sequenceService = new SequenceService();
    }

    public function prefix(string $prefix): void
    {
        $this->sequenceService->prefix = $prefix;
    }

    public function start(int $start): void
    {
        $this->sequenceService->start = $start;
    }

    public function digits(int $digits): void
    {
        $this->sequenceService->digits = $digits;
    }

    public function type(string $type): void
    {
        $this->sequenceService->type = $type;
    }

    public function skip(int $skip): void
    {
        $this->sequenceService->skip = $skip;
    }

    public function ignoreUpdate(): void
    {
        $this->sequenceService->ignoreUpdate = true;
    }

    /**
     * @throws Exception
     */
    public function generate($isReturnInstance = false): SequenceService|string
    {
        $service = $this->sequenceService;

        $this->reset();

        return $service->result($isReturnInstance);
    }

    public function getLastSequence(): string
    {
        $service = $this->sequenceService;

        $this->reset();

        return $service->getLastSequence();
    }

    /**
     * @throws Exception
     */
    public function getNextSequence(): SequenceService|string
    {
        $service = $this->sequenceService;

        $this->ignoreUpdate();

        $this->reset();

        return $service->result(false);
    }

    public function prefixSequenceSeparator(string $separator): void
    {
        $this->sequenceService->prefixSequenceSeparator = $separator;
    }
}
