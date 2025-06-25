<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface CourseRepositoryInterface
{
    public function searchByKeyword(string $keyword): Collection;

    public function getAllClassesWithCategory(): Collection;
}
