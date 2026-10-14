<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class TableExport implements FromArray, WithHeadings, ShouldAutoSize, WithTitle
{
    public function __construct(
        protected array $headings,
        protected array $rows,
        protected string $title = 'Report',
    ) {
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function title(): string
    {
        return substr(preg_replace('/[^A-Za-z0-9 ]/', '', $this->title), 0, 30);
    }
}
