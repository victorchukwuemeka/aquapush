<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    
    //
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|in:support,feedback,general',
            'message' => 'required|string',
        ]);

        Contact::create($validated);

        return redirect()->route('contact')->with('success', 'Your message has been sent successfully!');
    }
}
