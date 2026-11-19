<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Students;
use Illuminate\Http\Request;

class StudentsController extends Controller
{
    // GET /api/students
    public function index()
    {
        $students = Students::with('schoolClass:id,name')
            ->latest()
            ->paginate(10);

        return response()->json($students);
    }

    // GET /api/students/{id} -> Get a single student
    public function show($id)
    {
        $student = Students::with('schoolClass:id,name')->findOrFail($id);

        return response()->json($student);
    }

    // GET /student/create
    public function create()
    {
        $schoolClass = SchoolClass::all();

        return view('students.create', compact('schoolClass'));
    }

    // POST /api/students -> Create a new student (API)
    public function store(Request $request)
    {
        $validated = $request->validate(
            $this->validationRules(),
            $this->validationMessages()
        );

        $student = Students::create($validated);
        $student->load('schoolClass:id,name');

        return response()->json([
            'message' => 'Student created successfully.',
            'data'    => $student,
        ], 201);
    }

    // PUT or PATCH /api/students/{id}
    public function update(Request $request, $id)
    {
        $student = Students::findOrFail($id);

        // Allow this same student to keep their current email
        $rules = $this->validationRules($student->id);

        $validated = $request->validate($rules, $this->validationMessages());

        $student->update($validated);
        $student->load('schoolClass:id,name');

        return response()->json([
            'message' => 'Student updated successfully.',
            'data'    => $student,
        ]);
    }

    // DELETE /api/students/{id} -> Delete a student (API)
    public function destroy($id)
    {
        $student = Students::findOrFail($id);
        $student->delete();

        return response()->json([
            'message' => 'Student deleted successfully.',
        ]);
    }

    //    // POST /student/store -> Create a new student from Blade form (Web)
        public function webStore(Request $request)
        {
            $validated = $request->validate(
                $this->validationRules(),
                $this->validationMessages()
            );

            Students::create($validated);

            return redirect()
                ->route('students.index')
                ->with('success', 'Student created successfully.');
        }

    // Reusable validation rules
    private function validationRules($ignoreStudentId = null): array
    {
        $emailRule = 'required|email|max:255|unique:students,email';

        // When updating, ignore the current student's own email
        if ($ignoreStudentId) {
            $emailRule .= ',' . $ignoreStudentId;
        }

        return [
            'name'     => 'required|string|max:255',
            'email'    => $emailRule,
            'phone'    => 'nullable|string|max:25',
            'class_id' => 'required|integer|exists:school_classes,id',
        ];
    }

    // Custom error messages in Burmese
    private function validationMessages(): array
    {
        return [
            'name.required'     => 'ကျောင်းသားအမည်ကိုထည့်ပါ။',
            'email.required'    => 'ကျောင်းသားအီးမေးလ်ကိုထည့်ပါ။',
            'email.email'       => 'ကျောင်းသားအီးမေးလ်ကိုမှန်ကန်စွာထည့်ပါ။',
            'email.unique'      => 'ဒီအီးမေးလ်ကိုအခြားကျောင်းသားတစ်ယောက်မှာပြီးသားဖြစ်ပါတယ်။',
            'phone.string'      => 'ကျောင်းသားဖုန်းနံပါတ်ကိုမှန်ကန်စွာထည့်ပါ။',
            'class_id.required' => 'အတန်းကိုရွေးချယ်ပါ။',
            'class_id.integer'  => 'အတန်းကိုမှန်ကန်စွာရွေးချယ်ပါ။',
            'class_id.exists'   => 'ရွေးချယ်ထားသောအတန်း မရှိပါ။',
        ];
    }
}
