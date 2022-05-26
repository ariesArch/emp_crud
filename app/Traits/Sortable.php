<?php

namespace App\Traits;

trait Sortable
{
    public $sortBy = 'created_at';

    public $sortOrder = 'desc';

    public function setSortBy($sortBy = 'created_at')
    {
        $this->sortBy = $sortBy;
    }

    public function setSortOrder($sortOrder = 'desc')
    {
        $this->sortOrder = $sortOrder;
    }
}
