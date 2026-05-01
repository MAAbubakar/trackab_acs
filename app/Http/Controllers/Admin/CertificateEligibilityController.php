<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CertificateEligibility;
use App\Models\Participant;
use App\Services\CertificateEligibilityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CertificateEligibilityController extends Controller
{
    public function __construct(private readonly CertificateEligibilityService $certificateEligibilityService)
    {
    }

    public function index(): View
    {
        $eligibilities = CertificateEligibility::with(['participant', 'course', 'batch'])
            ->latest()
            ->paginate(20);

        $participants = Participant::orderBy('full_name')->get();

        return view('admin.certificate-eligibilities.index', compact('eligibilities', 'participants'));
    }

    public function compute(Participant $participant): RedirectResponse
    {
        $this->certificateEligibilityService->compute($participant);

        return redirect()
            ->route('admin.certificate-eligibilities.index')
            ->with('success', 'Certificate eligibility computed successfully.');
    }
}
