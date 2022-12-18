<?php

namespace WahyuDwiKrisnanto\InvoiceNumberGenerator;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use WahyuDwiKrisnanto\InvoiceNumberGenerator\Models\LastSequenceNumber;
use function Symfony\Component\Translation\t;

class SequenceService
{
    public string $prefix;
    public int $start;
    public int $digits;
    public string $prefixSequenceSeparator;
    public string $type;
    public bool $once = false;

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
            $this->setStart();
            $this->setType();
            $this->setPrefix();
            $this->setSeparator();
            $this->setSequence();

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
    public function setSequence(): void
    {
        $this->lastSequenceNumber = LastSequenceNumber::query()
            ->where('type', $this->type)
            ->lockForUpdate()
            ->first();

        $this->digits = $this->getDigits();
        $this->start  = $this->getLastSequence();
        $sequence     = sprintf("%'.0{$this->digits}d", $this->start);

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

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function getSequence(): int
    {
        return $this->start;
    }

    public function getType()
    {
        return $this->digits;
    }

    private function setType()
    {
        if (empty($this->type)) {
            $this->type = config('invoicenumbergenerator.type');
        }
    }

    private function setStart()
    {
        if (isset($this->start)) {
            $this->start = config('invoicenumbergenerator.start');
        }
    }

    protected function getLastSequence(): mixed
    {
        if ($this->lastSequenceNumber?->last_sequence) {
            if ($this->once) {
                $this->lastSequenceNumber->last_sequence++;
            } else {
                $this->lastSequenceNumber->increment('last_sequence');
            }

            return $this->lastSequenceNumber->last_sequence;
        }

        LastSequenceNumber::query()->create([
            'type' => $this->type,
            'last_sequence' => $this->start
        ]);

        return $this->start;
    }

    protected function getDigits()
    {
        return $this->digits ?? config('invoicenumbergenerator.digits');
    }
}
