@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Bulk Import Participants</h3>
            <div class="page-subtitle">Upload Excel or CSV files for participant onboarding.</div>
        </div>

        <div>
            
<div class="flex items-center gap-3 mb-4">
    <a href="{{ route('admin.participants.index') }}" class="btn btn-secondary">Back to Participants</a>
    
<a href="{{ route('admin.participants.import-template') }}" class="btn btn-secondary">
    Download Sample CSV Template
</a>

</div>

        </div>
    </div>

    @if(session('import_errors'))
        <div class="app-alert app-alert-danger">
            <strong>Import warnings/errors:</strong>
            <ul style="margin:10px 0 0 18px;">
                @foreach(session('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-body">
                <strong>Optional verification columns supported in import file:</strong>
                <div style="margin-top: 8px;">
                    age, nationality, academic_background, employment_status, employment_sector, employer_name
                </div>
            </div>
        </div>

        
<form action="{{ route('admin.participants.import') }}" method="POST" enctype="multipart/form-data" class="form-grid content-narrow">
                @csrf

                <div class="two-col-grid">
                    <div>
                        <label for="course_id">Course</label>
                        <select name="course_id" id="course_id" required>
                            <option value="">Select course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="batch_id">Batch</label>
                        <select name="batch_id" id="batch_id" required>
                            <option value="">Select batch</option>
                            @foreach($batches as $batch)
                                <option value="{{ $batch->id }}" {{ old('batch_id') == $batch->id ? 'selected' : '' }}>
                                    {{ $batch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="file">Upload File</label>
                    <input type="file" name="file" id="file" accept=".xlsx,.xls,.csv" required>
                </div>

                <div class="card" style="background:#f8fbfa;">
                    <div class="card-body">
                        <strong>Accepted column formats:</strong>
                        <div style="margin-top:8px; line-height:1.8;">
                            <div><code>full_name</code> + <code>participant_no</code></div>
                            <div><code>Name</code> + <code>reg no</code></div>
                        </div>
                        <div style="margin-top:12px; color:#64748b;">
                            The importer auto-detects the correct header row.
                        </div>
                    </div>
                </div>

                <div style="padding-top:8px;">
                    <button type="submit" class="btn btn-primary">Import Participants</button>
                </div>
            </form>
        </div>
    </div>
@endsection
