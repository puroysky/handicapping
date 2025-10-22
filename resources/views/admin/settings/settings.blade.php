@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Settings</h2>
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            Default Tee Settings
        </div>
        <div class="card-body">
            <form id="settingsForm" method="POST" action="{{ route('admin.settings.save') }}">
                @csrf
                <div class="mb-3">
                    <label for="default_tee_ladies" class="form-label">Default Tee for Ladies</label>
                    <select class="form-select" id="default_tee_ladies" name="default_tee_ladies">
                        @foreach($tees as $tee)
                        <option value="{{ $tee->id }}" {{ $tee->id == $defaultTeeLadies ? 'selected' : '' }}>{{ $tee->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <h5 class="mt-4 mb-2">Tee Ratings</h5>
                    @foreach($tees as $tee)
                    <div class="border rounded p-3 mb-2">
                        <div class="d-flex align-items-center mb-2">
                            <span class="fw-bold me-2">{{ $tee->name }}</span>
                            @if($tee->course)
                            <span class="text-muted">{{ $tee->course->name }}</span>
                            @if(isset($tee->course->version))
                            <span class="ms-2 text-info">{{ $tee->course->version }}</span>
                            @endif
                            @endif
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <input type="number" step="0.1" min="0" class="form-control" name="course_rating[{{ $tee->id }}]" value="{{ old('course_rating.' . $tee->id, $tee->course_rating ?? '') }}" placeholder="Course Rating">
                            </div>
                            <div class="col-md-6">
                                <input type="number" step="1" min="0" class="form-control" name="slope_rating[{{ $tee->id }}]" value="{{ old('slope_rating.' . $tee->id, $tee->slope_rating ?? '') }}" placeholder="Slope Rating">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Optionally add AJAX save logic here for a more modern UX
    // $('#settingsForm').on('submit', function(e) {
    //     e.preventDefault();
    //     // ... AJAX logic ...
    // });
</script>
@endpush