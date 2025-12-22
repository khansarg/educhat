<?php

namespace App\Http\Controllers\Student;

use App\Models\Course;
use App\Models\Clo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentController extends Controller
{
    // Halaman dashboard (untuk web)
   private function sidebarCourses()
    {
        return Course::with('clos')->get();
    }

    public function dashboard()
    {
        return view('student.dashboard', [
            'courses' => $this->sidebarCourses(),
        ]);
    }

    public function courseShow($id)
    {
        $course = Course::with('clos')->findOrFail($id);

        return view('student.course-detail', [
            'courses' => $this->sidebarCourses(),
            'course' => $course,
            'currentCourse' => $course->id,
        ]);
    }

    public function courseClo($courseId, $cloId)
{
    $course = Course::with('clos')->findOrFail($courseId);

    $clo = Clo::where('course_id', $courseId)
        ->with(['materials.files']) // ✅ penting
        ->findOrFail($cloId);

    return view('student.chat', [
        'courses' => $this->sidebarCourses(),
        'course' => $course,
        'clo' => $clo,
        'currentCourse' => $course->id,
        'currentClo' => $clo->id,
    ]);
}


    // ===== API =====

    public function courses()
    {
        return Course::with('clos')->get();
    }

    public function cloDetail(Clo $clo)
    {
        $clo->load(['course', 'materials.files']);
        return $clo;
    }
}