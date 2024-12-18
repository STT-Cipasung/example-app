<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;

class MyApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view(
            'my_applications.index',
            [
                'applications' => auth()->user()->jobApplications()
                    ->with([
                        'job' => function($query) {
                            return $query->withCount('jobApplications')->withAvg('jobApplications', 'expected_salary')->withTrashed();
                        },
                        'job.employer'
                    ])
                    ->latest()->get(),
            ],
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobApplication $myApplication)
    {
        $myApplication->delete();

        return redirect()->back()->with('success', 'Application removed successfully.');
    }
}
