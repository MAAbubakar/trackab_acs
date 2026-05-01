<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class AutomationController extends Controller
{
    public function index(): View
    {
        $tasks = [
            'close-expired-checkpoints' => 'attendance:close-expired-checkpoints',
            'compute-daily-summaries' => 'attendance:compute-daily-summaries',
            'generate-flags' => 'attendance:generate-flags',
            'evaluate-certificate-eligibility' => 'attendance:evaluate-certificate-eligibility',
        ];

        return view('admin.automation.index', compact('tasks'));
    }

    public function run(string $task): RedirectResponse
    {
        $map = [
            'close-expired-checkpoints' => 'attendance:close-expired-checkpoints',
            'compute-daily-summaries' => 'attendance:compute-daily-summaries',
            'generate-flags' => 'attendance:generate-flags',
            'evaluate-certificate-eligibility' => 'attendance:evaluate-certificate-eligibility',
        ];

        abort_unless(isset($map[$task]), 404);

        Artisan::call($map[$task]);

        return back()->with('success', "Task [{$task}] executed successfully.");
    }
}
