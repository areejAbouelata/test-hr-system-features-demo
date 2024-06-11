<?php

namespace Modules\Hr\App\Http\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;


class FileUploadController extends Controller
{
    public function save(Request $request)
    {
        $request->validate([
            'file' => 'image|max:1024', // Adjust max size as needed
        ]);
       

        $request->file->store('public/profile-pictures');

        session()->flash('message', 'Profile picture uploaded and associated with the user.');

        // Optionally, you can store the media ID in your database or perform other actions
    }
    
}
