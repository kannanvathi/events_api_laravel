<?php

namespace App\Exports;

use App\Models\Event;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;

class EventsExport implements FromQuery, WithMapping
{

    use Exportable;
    protected $events;

    public function __construct($events)
    {
        $this->events = $events;
    }

    public function map($events): array
    {
        return [
            'Tamilnadu-'.$events->event_name,
            $events->location,
            $events->start_date,
            $events->end_date,
            $events->description,
        ];
    }

    public function query()
    {
        return Event::query()->whereKey($this->events);
    }


}
