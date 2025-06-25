<?php

namespace App\Repositories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\CourseRepositoryInterface;

class CourseRepository implements CourseRepositoryInterface
{
    public function searchByKeyword(string $keyword): Collection
    {
        return Course::where('name', 'like', "%$keyword%")->orWhere('about', 'like', "%$keyword%")->get();
    }

    public function getAllClassesWithCategory(): Collection
    {
        return Course::with('category')->get();
    }
}
