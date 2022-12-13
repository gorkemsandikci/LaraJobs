<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobs = Job::latest()
            ->filter(request(['tag', 'search']))
            ->paginate(6);

        return view('jobs.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('jobs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'title' => 'required|unique:jobs',
            'company' => 'required',
            'location' => 'required',
            'email' => 'required|email',
            'website' => 'required|url',
            'tags' => 'required',
            'description' => 'required',
        ]);

        if($request->hasFile('logo'))
        {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $formFields['user_id'] = auth()->id();

        Job::create($formFields);

        return redirect('/')->with('message', 'Job created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job)
    {
        return view('jobs.show', compact('job'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function edit(Job $job)
    {
        // Ensure logged in user is owner
        if($job->user_id !== auth()->id())
        {
            return abort(403, 'Unauthorized Action');
        }

        return view('jobs.edit', compact('job'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Job $job)
    {
        // Ensure logged in user is owner
        if($job->user_id !== auth()->id())
        {
            return abort(403, 'Unauthorized Action');
        }

        $formFields = $request->validate([
            'title' => 'required',
            'company' => 'required',
            'location' => 'required',
            'email' => 'required|email',
            'website' => 'required|url',
            'tags' => 'required',
            'description' => 'required'
        ]);

        if($request->hasFile('logo'))
        {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $job->update($formFields);

        return redirect('/jobs/' . $job->id)->with('message', 'Job updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {
        // Ensure logged in user is owner
        if($job->user_id !== auth()->id())
        {
            return abort(403, 'Unauthorized Action');
        }

        unlink('storage/' . $job->logo);
        $job->delete();

        return redirect('/')->with('message', 'Job deleted successfully.');
    }

    public function remove_logo(Job $job)
    {
        // Ensure logged in user is owner
        if($job->user_id !== auth()->id())
        {
            return abort(403, 'Unauthorized Action');
        }
        
        // Remove the actual logo file from the storage
        @unlink('storage/' . $job->logo);

        // Nullify the logo value of the job
        $job->logo = null;
        $job->update();

        return back()->with('message', 'Logo removed successfully.');
    }
}
