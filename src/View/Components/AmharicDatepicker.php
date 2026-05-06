<?php

namespace Yafet\AmharicDatepicker\View\Components;

use Illuminate\View\Component;

class AmharicDatepicker extends Component
{
    public function __construct(
        public string $name = 'date',
        public ?string $id = null,
        public ?string $value = null,
        public bool $inline = false,
        public string $format = 'dd/mm/yyyy',
        public string $locale = 'am',
        public int $yearStart = 1900,
        public ?int $yearEnd = null,
    ) {
        $this->id = $id ?? $name;
        $this->yearEnd = $yearEnd ?? ((new \DateTime())->format('Y') + 10);
    }

    public function render()
    {
        return view('amharic-datepicker::components.amharic-datepicker');
    }
}
