<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private readonly StudentController $studentController)
    {
    }

    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'mentor') {
            return redirect()->route('mentor.antrian');
        }

        return $this->studentController->dashboard($request);
    }
}
