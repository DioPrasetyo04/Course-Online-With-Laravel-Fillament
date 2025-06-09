<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class CourseService
{

    public function enrolUser(Course $course)
    {
        $user = Auth::user();

        if (!$course->courseStudents()->where('user_id', $user->id)->exists()) {
            $course->courseStudents()->create([
                'user_id' => $user->id,
                'is_active' => true
            ]);
        }

        return $user->name;
    }

    public function getFirstSectionAndContent(Course $course)
    {
        $firstSectionId = $course->courseSections()->orderBy('id')->value('id');
        $firstContentId = $firstSectionId ? $course->courseSections()->find($firstSectionId)->sectionContents()->orderBy('id')->value('id') : null;

        return [
            'firstSectionId' => $firstSectionId,
            'firstContentId' => $firstContentId
        ];
    }

    public function getLearningData(Course $course, $contentSectionId, $sectionContentId)
    {
        $course->load(['courseSections.sectionContents']);

        // Mengambil section saat ini
        $currentSection = $course->courseSections()->find($contentSectionId);
        // mengambil content dari section saat ini
        $currentContent = $currentSection ? $currentSection->SectionContents()->find($sectionContentId) : null;

        // buat variable next content
        $nextContent = null;

        // check content ada gk
        if ($currentContent) {
            $nextContent = $currentSection->sectionContents()->where('id', '>', $currentSection->id)->sortBy('id')->first();
        }

        // check section ada gk
        if (!$nextContent && $currentSection) {
            $nextSection = $course->courseSections()->where('id', '>', $currentSection->id)->sortBy('id')->first();

            if ($nextSection) {
                $nextContent = $nextSection->sectionContents()->sortBy('id')->first();
            }
        }

        return [
            'course' => $course,
            'currentSection' => $currentSection,
            'currentContent' => $currentContent,
            'nextContent' => $nextContent,
            'isFinished' => !$nextContent
        ];
    }
}
