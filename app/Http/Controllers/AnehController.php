<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
// return type redirectResponse

// import model administrator
use App\Models\Administrator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class AnehController extends Controller
{
    // function index untuk get all data
    public function index()
    {
        return view('aneh');
    }
}