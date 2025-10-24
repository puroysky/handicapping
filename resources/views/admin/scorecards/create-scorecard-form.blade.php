@extends('layouts.app')

@section('content')
<style>
    .scorecard-table th:nth-child(11),
    .scorecard-table td:nth-child(11) {
        background-color: #8DA66E !important;
        color: #fff !important;
        font-weight: bold !important;
        border-left: 1px solid #2F4A3C !important;
      
    }

    .scorecard-table th:nth-child(11) {
        font-size: 1.1rem;
    }

    .scorecard-table tbody td:nth-child(11) {
        font-size: 1.05rem;
    }

    .scorecard-table th:nth-child(22),
    .scorecard-table td:nth-child(22) {
        background-color: #2F4A3C !important;
        color: #8DA66E !important;
        font-weight: bold !important;
        border: 2px solid #8DA66E !important;
    }

    .scorecard-table tbody td:nth-child(22) {
        font-size: 1.05rem;
    }

    .scorecard-table th:nth-child(21),
    .scorecard-table td:nth-child(21) {
        background-color: #5E7C4C !important;
        color: #fff !important;
        font-weight: bold !important;
    }
</style>



 <div class="row">
        <div class="col-12">
            <div class="form-container fade-in">
                <!-- Form Body -->
                <div class="form-body">
                    <h3 class="mb-2 mt-0 text-center text-primary">
                        <i class="fas fa-plus-circle me-2 text-primary"></i>
                        Add New Golf Scorecard
                    </h3>
                    <p class="text-muted text-center mb-1">Create a new golf scorecard in the system</p>

                    <form class="needs-validation" novalidate id="mainForm">
                        @csrf
                        <!-- Scorecard Basic Information Section -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-info-circle"></i>
                                Scorecard Information
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" value="East" class="form-control" id="scorecard_name" name="scorecard_name" placeholder="Scorecard Name" required>
                                        <label for="scorecard_desc">Scorecard Name</label>
                                        <div class="invalid-feedback">
                                            Please provide a valid scorecard name.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" value="v1.0.0" class="form-control" id="scorecard__code" name="scorecard__code" placeholder="Scorecard Code" required minlength="2" maxlength="10">
                                        <label for="scorecard__code">Scorecard Code *</label>
                                        <div class="invalid-feedback">
                                            Please provide a valid scorecard code (2-10 characters).
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" value="East Course scorecard" id="scorecard_desc" name="scorecard_desc" placeholder="Scorecard Description" style="height: 120px;" maxlength="500"></textarea>
                                        <label for="scorecard_desc">Scorecard Description</label>
                                        <div class="invalid-feedback">
                                            Scorecard description cannot exceed 500 characters.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Scorecard Settings Section -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-cog"></i>
                                Scorecard Settings
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="course" name="course" required>
                                            {{-- <option value="">Select Course...</option> --}}
                                           @foreach ($courses as $course)

                                               <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
                                           @endforeach
                                        </select>
                                        <label for="course">Course *</label>
                                        <div class="invalid-feedback">
                                            Please select a course.
                                        </div>
                                    </div>
                                    <div class="form-floating">
                                        <select class="form-select" id="course_type" name="course_type" required>
                                            {{-- <option value="">Select Course Type...</option> --}}
                                            <option value="tournament">Tournament</option>
                                            <option value="regular">Regular</option>
                                        </select>
                                        <label for="course_type">Course Type *</label>
                                        <div class="invalid-feedback">
                                            Please select a course type.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <select class="form-select" id="x_value" name="x_value" required>
                                            <option value="">Select X Value...</option>
                                            {{-- <option value="bogey">Bogey</option> --}}
                                            <option value="DOUBLE_BOGEY">Double Bogey</option>
                                            <option value="TRIPLE_BOGEY">Triple Bogey</option>
                                        </select>
                                        <label for="x_value">X Value *</label>
                                        <div class="invalid-feedback">
                                            Please select an X Value.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-check-modern">
                                <input class="form-check-input" type="checkbox" id="active_status" name="active_status" checked>
                                <label class="form-check-label" for="active_status">
                                    <strong>Active Scorecard</strong>
                                    <br>
                                    <small class="text-muted">Check if this scorecard is currently active for scoring</small>
                                </label>
                            </div>
                        </div>

                        <!-- Formula selection -->
                        <div class="form-section mt-3">
                            <div class="section-title">
                                <i class="fas fa-calculator"></i>
                                Formulas
                            </div>
                            <div class="row g-3">
                                <div class="col-md">
                                    <div class="form-floating">
                                        <select class="form-select" id="adjusted_gross_score_formula_id" name="adjusted_gross_score_formula_id">
                                            <option value="">-- Select Adjusted Gross Score Formula --</option>
                                            @if(isset($formulas) && $formulas->isNotEmpty())
                                                @foreach($formulas->where('code', 'AGS') as $formula)
                                                    <option value="{{ $formula->id }}" {{ old('adjusted_gross_score_formula_id') == $formula->id ? 'selected' : '' }}>{{ $formula->name }}</option>
                                                @endforeach
                                            @else
                                                <option disabled>No formulas available</option>
                                            @endif
                                        </select>
                                        <label for="adjusted_gross_score_formula_id">Adjusted Gross Score Formula</label>
                                    </div>
                                </div>

                                <div class="col-md">
                                    <div class="form-floating">
                                        <select class="form-select" id="score_differential_formula_id" name="score_differential_formula_id">
                                            {{-- <option value="">-- Select Score Differential Formula --</option> --}}
                                            @if(isset($formulas) && $formulas->isNotEmpty())
                                                @foreach($formulas->where('code', 'SD') as $formula)
                                                    <option value="{{ $formula->id }}" {{ old('score_differential_formula_id') == $formula->id ? 'selected' : '' }}>{{ $formula->name }}</option>
                                                @endforeach
                                            @else
                                                <option disabled>No formulas available</option>
                                            @endif
                                        </select>
                                        <label for="score_differential_formula_id">Score Differential Formula</label>
                                    </div>
                                </div>

                                <div class="col-md">
                                    <div class="form-floating">
                                        <select class="form-select" id="course_handicap_formula_id" name="course_handicap_formula_id">
                                            {{-- <option value="">-- Select Course Handicalp Formula --</option> --}}
                                            @if(isset($formulas) && $formulas->isNotEmpty())
                                                @foreach($formulas->where('code', 'CH') as $formula)
                                                    <option value="{{ $formula->id }}" {{ old('course_handicap_formula_id') == $formula->id ? 'selected' : '' }}>{{ $formula->name }}</option>
                                                @endforeach
                                            @else
                                                <option disabled>No formulas available</option>
                                            @endif
                                        </select>
                                        <label for="course_handicap_formula_id">Course Handicap Formula</label>
                                    </div>
                                </div>

                           


                        
                            </div>
                        </div>



                        <!-- Additional Remarks Section -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-sticky-note"></i>
                                Additional Remarks
                            </div>

                            <div class="form-floating">
                                <textarea class="form-control" id="remarks" name="remarks" placeholder="Enter any additional remarks about this scorecard..." maxlength="1000"></textarea>
                                <label for="remarks">Scorecard Remarks (Optional)</label>
                                <div class="invalid-feedback">
                                    Remarks cannot exceed 1000 characters.
                                </div>
                            </div>
                        </div>


{{-- //scorecard section --}}

                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 scorecard-section-card" style="background: #fff; border: 1px solid #e0e8d8;">
                            <div class="card-header py-2 px-4 d-flex align-items-center justify-content-between" style="background: #f8fafc; color: #2F4A3C; border-bottom: 1px solid #e0e8d8;">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-table fa-lg" style="color: #5E7C4C;"></i>
                                    <span class="fw-bold" style="font-size: 1.15rem; color: #2F4A3C;">Scorecard Layout</span>
                                </div>
                                <span class="badge" style="background-color: #e0e8d8; color: #5E7C4C;">Design & Input</span>
                            </div>
                            <div class="card-body p-4">
                                @if($scorecard->ratings->isNotEmpty())
                                <div class="table-responsive mb-3">
                                    <table class="table table-borderless align-middle text-center mb-0">
                                        <thead style="background-color: #e8e8e8;">
                                            <tr>
                                                <th class="text-uppercase text-muted small" style="color: #2F4A3C;">Scorecard / Slope Rating</th>
                                                @foreach($scorecard->ratings as $rating)
                                                <th>
                                                    <div class="fw-semibold" style="color: #2F4A3C;">{{ $rating->tee->tee_name }}</div>
                                                    <div class="d-flex justify-content-center align-items-center gap-2" style="font-size: 0.9rem;">
                                                        <input type="number" class="form-control form-control-sm" name="course_rating[{{ $rating->tee->tee_id }}]" value="{{ $rating->course_rating }}" placeholder="Course Rating" style="max-width: 80px; display: inline-block;">
                                                        /
                                                        <input type="number" class="form-control form-control-sm" name="slope_rating[{{ $rating->tee->tee_id }}]" value="{{ $rating->slope_rating }}" placeholder="Slope Rating" style="max-width: 80px; display: inline-block;">
                                                    </div>
                                                </th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                @endif
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle text-center mb-0 table-hover scorecard-table" style="min-width: 1100px;">
                                        <thead style="background: #f8fafc; color: #2F4A3C;">
                                            <tr>
                                                <th class="fw-bold">Hole</th>
                                                @php
                                                $holes = $scorecard->scorecardHoles->pluck('hole')->toArray();
                                                $frontNine = array_slice($holes, 0, 9);
                                                $backNine = array_slice($holes, 9, 9);
                                                @endphp
                                                @foreach($frontNine as $hole)
                                                <th>{{ $hole }}</th>
                                                @endforeach
                                                <th>OUT</th>
                                                @foreach($backNine as $hole)
                                                <th>{{ $hole }}</th>
                                                @endforeach
                                                <th>IN</th>
                                                <th>TOTAL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- Tee Rows --}}
                                            @foreach($scorecard->course->tees as $tee)
                                            <tr class="tee-row" data-tee-id="{{ $tee->tee_id }}">
                                                <td class="fw-bold">{{ $tee->tee_name }}</td>
                                                @foreach ($yardages[$tee->tee_id] as $hole => $yardage)

                                                

                                                    <td style="color: #333;">
                                                        <input type="number" value="{{ $yardage }}" class="form-control form-control-sm scorecard-input tee-yardage-input" name="yardages[{{ $tee->tee_id }}][{{ $hole }}]" data-hole="{{ $hole }}" style="max-width: 60px; border-radius: 6px; border: 1px solid #8DA66E; background: #f8fafc;" title="Enter yardage for hole {{ $hole }}">
                                                    </td>
                                                    @if ($hole == 9)
                                                        <td style="background: #f4f8f2; color: #5E7C4C; font-weight: 500;">{{ array_sum(array_slice($yardages[$tee->tee_id], 0, 9)) }}</td>
                                                    @endif
                                                @endforeach
                                                <td style="background: #f4f8f2; color: #5E7C4C; font-weight: 500;">{{ array_sum(array_slice($yardages[$tee->tee_id], 9, 9)) }}</td>
                                                <td style="background: #f4f8f2; color: #2F4A3C; font-weight: 500;">{{ array_sum($yardages[$tee->tee_id]) }}</td>
                                            </tr>
                                            @endforeach
                                            {{-- Par Row --}}
                                            <tr class="par-row" style="background-color: #e8e8e8; font-weight: bold;">
                                                <td style="color: #2F4A3C; background-color: #d0d0d0;">PAR</td>
                                                @for ($i = 1; $i <= 18; $i++)
                                                    <td>
                                                        <input type="number" value="{{ $i+5 }}" class="form-control form-control-sm scorecard-input par-input" name="par[{{ $i }}]" data-hole="{{ $i }}" style="max-width: 60px; border-radius: 6px; border: 1px solid #8DA66E; background: #f8fafc;" title="Enter par for hole {{ $i }}">
                                                    </td>
                                                    @if ($i == 9)
                                                        <td style="background: #f4f8f2; color: #5E7C4C;"></td>
                                                    @endif
                                                @endfor
                                                <td style="background: #f4f8f2; color: #5E7C4C;"></td>
                                                <td style="background: #f4f8f2; color: #2F4A3C;"></td>
                                            </tr>
                                            {{-- Men's Handicap --}}
                                            <tr class="male-handicap-row" style="background-color: #f8fafc;">
                                                <td class="fw-bold">Handicap</td>
                                                @for ($i = 1; $i <= 18; $i++)
                                                    <td>
                                                        <input type="number" value="{{ $i+1 }}" class="form-control form-control-sm scorecard-input" name="male_handicap[{{ $i }}]" data-hole="{{ $i }}" style="max-width: 60px; border-radius: 6px; border: 1px solid #8DA66E; background: #f8fafc;" title="Enter men's handicap for hole {{ $i }}">
                                                    </td>
                                                    @if ($i == 9)
                                                        <td style="background: #f4f8f2; color: #5E7C4C;"></td>
                                                    @endif
                                                @endfor
                                                <td style="background: #f4f8f2; color: #5E7C4C;"></td>
                                                <td style="background: #f4f8f2; color: #2F4A3C;"></td>
                                            </tr>
                                            {{-- Ladies' Handicap --}}
                                            <tr class="ladies-handicap-row" style="background-color: #f8fafc;">
                                                <td class="fw-bold">Ladies HDC</td>
                                                @for ($i = 1; $i <= 18; $i++)
                                                    <td>
                                                        <input type="number" value="{{ $i + 2 }}" class="form-control form-control-sm scorecard-input" name="ladies_handicap[{{ $i }}]" data-hole="{{ $i }}" style="max-width: 60px; border-radius: 6px; border: 1px solid #8DA66E; background: #f8fafc;" title="Enter ladies' handicap for hole {{ $i }}">
                                                    </td>
                                                    @if ($i == 9)
                                                        <td style="background: #f4f8f2; color: #5E7C4C;"></td>
                                                    @endif
                                                @endfor
                                                <td style="background: #f4f8f2; color: #5E7C4C;"></td>
                                                <td style="background: #f4f8f2; color: #2F4A3C;"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end flex-wrap gap-3">

                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-modern" id="cancelBtn">
                                    <i class="fas fa-times me-2"></i>
                                    Cancel
                                </button>
                                <button type="submit" class="btn btn-primary-modern text-white" id="submitBtn">
                                    <span class="btn-text">
                                        <i class="fas fa-plus me-2"></i>
                                        Create Scorecard
                                    </span>
                                    <span class="btn-loading d-none">
                                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                        Creating...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        // on load event listener
        document.addEventListener('DOMContentLoaded', function() {
            //on submit

        //on submit #mainForm
        document.getElementById('mainForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');

            // Show loading state
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');

            // Simulate form submission delay (replace with actual submission logic)
            setTimeout(function() {
                // Hide loading state
                btnLoading.classList.add('d-none');
                btnText.classList.remove('d-none');

                // Optionally, reset the form or redirect
                alert('Scorecard created successfully!');
                document.getElementById('mainForm').reset();
            }, 2000);


                alert("Aaa");

        });
    </script>


@endsection