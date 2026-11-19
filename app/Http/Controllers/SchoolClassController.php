<?php

namespace App\Http\Controllers;
use App\Models\SchoolClass;

class SchoolClassController extends Controller
{
    // index Page
    public function index()
    {
        $schoolClass = SchoolClass::query()->orderBy('name')->get();

        return view('schoolClass.index', compact('schoolClass'));
        // retrun response()->json(['message' => 'this is testing']);
    }

    public function show() {}

    public function apiIndex()
    {
        return SchoolClass::query()
            ->orderBy('name')
            ->get(['id', 'name']);
    }
}
