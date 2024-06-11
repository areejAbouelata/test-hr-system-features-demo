<?php

namespace Modules\Hr\App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Modules\Hr\App\Models\Moderator;

class ModeratorsController extends Controller
{
    public function index()
    {
        return view('hr::moderators.index');
    }

    public function show(Moderator $moderator)
    {
        return view('hr::moderators.show',compact('moderator'));
    }
}
