<?php

namespace WahyuDwiKrisnanto\SequenceNumberGenerator;

use Illuminate\Support\Facades\DB;
use WahyuDwiKrisnanto\SequenceNumberGenerator\Models\LastSequenceNumber;

class SequenceService
{
    public string $prefix;
    public int $start;
    public int $digits;
    public string $prefixSequenceSeparator;
    public string $type;
    public bool $ignoreUpdate = false;
    public int $skip;

    private string $result;
    private $lastSequenceNumber;

    public function __construct()
    {
        $this->result = "";
    }

    /**
     * @throws \Exception
     */
    public function result($isReturnInstance): static|string
    {
        DB::beginTransaction();

        try {
            $this->setSkip();
            $this->setStart();
            $this->setType();
            $this->setPrefix();
            $this->setSeparator();
            $this->setDigits();
            $this->generateSequence();

            DB::commit();

            if ($isReturnInstance) return $this;

            return $this->result;
        } catch (\Exception $exception) {
            info('sequence_number_generator', [
                'message' => $exception->getMessage(),
                'file'    => $exception->getFile(),
                'line'    => $exception->getLine()
            ]);

            DB::rollBack();

            throw new \Exception("Fail to generate sequence number");
        }
    }

    /**
     * @throws \Exception
     */
    public function generateSequence($ignoreGetNextSequence = false): void
    {
        $this->lastSequenceNumber = LastSequenceNumber::query()
            ->where('type', $this->type)
            ->lockForUpdate()
            ->first();

        if (! $ignoreGetNextSequence) {
            $this->start = $this->getNextSequence();
        }

        $sequence      = sprintf("%'.0{$this->digits}d", $this->start);
        $this->result .= $sequence;
    }

    public function setPrefix(): void
    {
        $this->result .= $this->prefix
            ?? config('invoicenumbergenerator.prefix');
    }

    public function setSeparator(): void
    {
        $this->result .= $this->prefixSequenceSeparator
            ?? config('invoicenumbergenerator.prefix_sequence_separator');
    }

    private function setType(): void
    {
        if (empty($this->type)) {
            $this->type = config('invoicenumbergenerator.type');
        }
    }

    private function setSkip(): void
    {
        if (empty($this->skip)) {
            $this->skip = intval(config('invoicenumbergenerator.skip'));
        }

        if ($this->skip < 1) {
            $this->skip = 1;
        } else {
            $this->skip++;
        }
    }

    private function setStart(): void
    {
        if (empty($this->start)) {
            $this->start = config('invoicenumbergenerator.start');
        }
    }

    private function setDigits(): void
    {
        $this->digits = $this->getDigits();
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function getType()
    {
        return $this->digits;
    }

    public function getLastSequence()
    {
        $this->setStart();
        $this->setType();
        $this->setPrefix();
        $this->setSeparator();
        $this->setDigits();

        $this->lastSequenceNumber = LastSequenceNumber::query()
            ->where('type', $this->type)
            ->lockForUpdate()
            ->first();

        if ($this->lastSequenceNumber) {
            $this->start = $this->lastSequenceNumber->last_sequence;
        }

        $this->result .= sprintf("%'.0{$this->digits}d", $this->start);

        return $this->result;
    }

    protected function getNextSequence(): int
    {
        if ($this->lastSequenceNumber?->last_sequence) {
            if ($this->ignoreUpdate) {
                $this->lastSequenceNumber->last_sequence += $this->skip;
            } else {
                $this->lastSequenceNumber->increment('last_sequence', $this->skip);
            }

            return $this->lastSequenceNumber->last_sequence;
        }

        LastSequenceNumber::query()->create([
            'type' => $this->type,
            'last_sequence' => $this->start
        ]);

        return $this->start;
    }

    private function calculateSkip()
    {

    }

    protected function getDigits()
    {
        return $this->digits ?? config('invoicenumbergenerator.digits');
    }
}
