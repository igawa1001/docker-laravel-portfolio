<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\Csv\Reader;
use App\Models\HealthCheckResult;

class HealthCheckResultController extends Controller
{
    public function index()
    {
        return view('health_check_results.index');
    }

}
