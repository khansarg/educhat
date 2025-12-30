<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Clo;
use App\Models\Material;
use App\Models\MaterialFile;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    private function ensureAdmin($user)
    {
        if (!$user || $user->role !== 'admin') {
            abort(response()->json(['message' => 'Forbidden'], 403));
        }
    }
    public function updateCourse(Request $request, Course $course)
{
    $data = $request->validate([
        'name' => ['required', 'string', 'max:255'],
    ]);

    $course->update([
        'name' => $data['name'],
    ]);

    return response()->json(['success' => true]);
}

public function destroyCourse(Course $course)
{
    
    $course->delete();

    return response()->json(['success' => true]);
}

public function destroyClo(Clo $clo)
{
    $clo->delete();

    return response()->json(['success' => true]);
}
    // GET /admin/lecturers?search=...
    public function searchLecturers(Request $request)
    {
        $user = $request->user();
        $this->ensureAdmin($user);

        $q = $request->query('search');

        $lecturers = User::where('role', 'dosen')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('email', 'like', "%{$q}%")
                       ->orWhere('name', 'like', "%{$q}%");
                });
            })
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name', 'email']);

        return response()->json($lecturers);
    }

    // POST /admin/coordinator
    public function assignCoordinator(Request $request)
    {
        $user = $request->user();
        $this->ensureAdmin($user);

        $data = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'user_id'   => 'required|exists:users,id',
        ]);

        $course = Course::findOrFail($data['course_id']);
        $lecturer = User::where('role', 'dosen')->findOrFail($data['user_id']);

        $course->coordinators()->syncWithoutDetaching([
            $lecturer->id => ['type' => 'coordinator'],
        ]);

        return response()->json([
            'message' => 'Coordinator assigned',
            'course'  => $course->load('coordinators:id,name,email'),
        ]);
    }

    // GET /admin/courses
    public function coursesWithClos(Request $request)
    {
        $user = $request->user();
        $this->ensureAdmin($user);

        $courses = Course::with([
            'coordinators:id,name,email',
            'clos' => fn($q) => $q->orderBy('order'),
        ])->orderBy('code')->get();

        return response()->json($courses);
    }
    // =========================
    // COURSE
    // =========================

    // GET /admin/courses/{id}
    public function courseDetail(Request $request, $id)
    {
        $this->ensureAdmin($request->user());

        $course = Course::with([
            'coordinators:id,name,email',
            'clos' => function ($q) {
                $q->orderBy('order')
                  ->with(['materials.files']);
            }
        ])->findOrFail($id);

        return response()->json($course);
    }

    // PATCH /admin/course/{id}/summary
    public function updateCourseSummary(Request $request, $id)
    {
        $this->ensureAdmin($request->user());

        $data = $request->validate([
            'description' => 'required|string',
        ]);

        $course = Course::findOrFail($id);
        $course->update($data);

        return response()->json(['message' => 'Course summary updated']);
    }

    // =========================
    // CLO
    // =========================

    // PATCH /admin/clo/{id}/summary
    public function updateCloSummary(Request $request, $id)
    {
        $this->ensureAdmin($request->user());

        $data = $request->validate([
            'summary' => 'required|string',
        ]);

        $clo = Clo::findOrFail($id);
        $clo->update($data);

        return response()->json(['message' => 'CLO summary updated']);
    }

    // =========================
    // MATERIAL
    // =========================

    // POST /admin/clo/{id}/materials
    public function addMaterial(Request $request, $id)
    {
        $this->ensureAdmin($request->user());

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $clo = Clo::findOrFail($id);

        $material = $clo->materials()->create([
            'title'       => $data['title'],
            'description' => $data['description'] ?? '',
        ]);

        return response()->json([
            'message'  => 'Material created',
            'material' => $material,
        ]);
    }

    // POST /admin/material/{id}/upload
    public function uploadMaterialPdf(Request $request, $id)
{
    $this->ensureAdmin($request->user());

    $request->validate([
        'file' => 'required|mimes:pdf|max:10240',
    ]);

    $material = Material::findOrFail($id);

    // Menyimpan file PDF
    $uploaded = $request->file('file');
    $path = $uploaded->store('materials', 'public');  // Menyimpan file di storage/app/public/materials

    // Menyimpan data file di tabel MaterialFile
    $file = $material->files()->create([
        'original_name' => $uploaded->getClientOriginalName(),
        'pdf_path'      => $path,
        'pdf_url'       => Storage::url($path), // Akan dihasilkan otomatis oleh URL generator
    ]);

    return response()->json([
        'message' => 'PDF uploaded',
        'file'    => $file,
    ]);
}


   
    public function showCourse($id)
{
    $course = Course::with([
        'coordinators',
        'clos.materials.files'
    ])->findOrFail($id);

    return view('admin.course', [
        'course' => $course,
        'currentCourse' => $course->id
    ]);
}
// FORM TAMBAH COURSE
public function createCourse()
{
    return view('admin.course-create');
}

// SIMPAN COURSE
public function storeCourse(Request $request)
{
    $this->ensureAdmin($request->user());

    $data = $request->validate([
        'code'        => 'required|string|max:20|unique:courses,code',
        'name'        => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    $course = Course::create($data);

    return redirect()
        ->route('admin.course.show', $course->id)
        ->with('success', 'Course berhasil ditambahkan');
}

public function removeCoordinator(Request $request, $courseId, $userId)
{
    $this->ensureAdmin($request->user());

    $course = Course::findOrFail($courseId);

    // detach hanya yang pivot type=coordinator
    $course->coordinators()->detach($userId);

    return response()->json(['message' => 'Coordinator removed']);
}
// POST /admin/course/{id}/clos
public function addClo(Request $request, $id)
{
    $this->ensureAdmin($request->user());

    $data = $request->validate([
        'title' => 'required|string|max:255',
    ]);

    $course = Course::findOrFail($id);

    $nextOrder = (int) ($course->clos()->max('order') ?? 0) + 1;

    $clo = $course->clos()->create([
        'title' => $data['title'],
        'summary' => '',
        'order' => $nextOrder,
    ]);

    return response()->json([
        'message' => 'CLO created',
        'clo' => $clo,
    ]);
}
public function createMateri(Request $request, $courseId, $cloId)
{
    $this->ensureAdmin($request->user());

    $course = Course::findOrFail($courseId);

    // pastikan CLO milik course tsb
    $clo = Clo::where('course_id', $courseId)->findOrFail($cloId);

    return view('admin.materi-create', [
        'course' => $course,
        'clo' => $clo,
    ]);
}
public function editMateri(Request $request, $id)
{
    $this->ensureAdmin($request->user());

    $material = Material::with(['files', 'clo.course'])->findOrFail($id);

    $clo = $material->clo;
    $course = $clo->course;

    return view('admin.materi-create', [
        'course'   => $course,
        'clo'      => $clo,
        'material' => $material,
    ]);
}

public function updateMaterial(Request $request, $id)
{
    $this->ensureAdmin($request->user());

    $data = $request->validate([
        'title'       => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    $material = Material::findOrFail($id);
    $material->update([
        'title'       => $data['title'],
        'description' => $data['description'] ?? '',
    ]);

    return response()->json(['message' => 'Material updated']);
}

public function deleteMaterialFile(Request $request, $id)
{
    $this->ensureAdmin($request->user());

    $file = MaterialFile::findOrFail($id);

    // hapus file fisik kalau ada
    if ($file->pdf_path) {
        Storage::disk('public')->delete($file->pdf_path);
    }

    $file->delete();

    return response()->json(['message' => 'File deleted']);
}

// opsional kalau kamu mau delete materi
public function deleteMaterial(Request $request, $id)
{
    $this->ensureAdmin($request->user());

    $material = Material::with('files')->findOrFail($id);

    foreach ($material->files as $f) {
        if ($f->pdf_path) {
            Storage::disk('public')->delete($f->pdf_path);
        }
        $f->delete();
    }

    $material->delete();

    return response()->json(['message' => 'Material deleted']);
}
}
