<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Skill;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        $settings = Setting::first();
        $skills = Skill::all();
        $projects = Project::latest()->limit(2)->get();
        $services = Service::latest()->limit(4)->get();
        $experiences = Experience::all();

        return view('frontend.index', [
            'settings'   => $settings,
            'skills'     => $skills,
            'projects'   => $projects,
            'services'   => $services,
            'experiences'=> $experiences,
        ]);
    }
}
