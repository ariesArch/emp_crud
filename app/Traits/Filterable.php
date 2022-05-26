<?php

namespace App\Traits;

trait Filterable
{
    public $filters = [];

    public function setFilters($filters = null)
    {
        $this->filters = $filters;
    }
}
