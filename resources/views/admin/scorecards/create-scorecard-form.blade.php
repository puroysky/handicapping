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
                                    <input type="text" value="" class="form-control" id="scorecard_name" name="scorecard_name" placeholder="Scorecard Name" required>
                                    <label for="scorecard_desc">Scorecard Name</label>
                                    <div class="invalid-feedback">
                                        Please provide a valid scorecard name.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" value="" class="form-control text-uppercase" id="scorecard_code" name="scorecard_code" required minlength="2" maxlength="10" pattern="^[A-Za-z0-9-]{2,10}$" title="2–10 chars: letters, numbers or hyphen" inputmode="text" autocomplete="off">
                                    <label for="scorecard_code">Scorecard Code *</label>
                                    <div class="invalid-feedback">
                                        Please provide a valid scorecard code (2-10 characters).
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" value="" id="scorecard_desc" name="scorecard_desc" placeholder="Scorecard Description" style="height: 120px;" maxlength="500">
                                    </textarea>
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
                                    <select class="form-select" id="course_id" name="course_id" required>

                                        <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>

                                    </select>
                                    <label for="course_id">Course *</label>
                                    <div class="invalid-feedback">
                                        Please select a course.
                                    </div>
                                </div>
                                <div class="form-floating">
                                    <select class="form-select" id="scorecard_type" name="scorecard_type" required>
                                        {{-- <option value="">Select Course Type...</option> --}}
                                        <option value="tournament">Tournament</option>
                                        <option value="regular">Regular</option>
                                    </select>
                                    <label for="scorecard_type">Course Type *</label>
                                    <div class="invalid-feedback">
                                        Please select a course type.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <select class="form-select" id="x_value" name="x_value" required>
                                        {{-- <option value="">Select X Value...</option> --}}
                                        >
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
                                        {{-- <option value="">-- Select Adjusted Gross Score Formula --</option> --}}
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
                        <div class="card-header py-1 px-3 d-flex align-items-center justify-content-between" style="background: #f8fafc; color: #2F4A3C; border-bottom: 1px solid #e0e8d8;">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-chart-line fa-lg" style="color: #5E7C4C;"></i>
                                <span class="fw-bold" style="font-size: 1rem; color: #2F4A3C;">Course & Slope Ratings</span>
                            </div>
                            <span class="badge" style="background-color: #e0e8d8; color: #5E7C4C; font-size: 0.7rem;">Rating Configuration</span>
                        </div>
                        <div class="card-body p-2">

                            <!-- Overall Ratings Section -->
                            <div class="mb-2">
                                <h6 class="fw-bold mb-2" style="color: #2F4A3C; border-bottom: 2px solid #5E7C4C; padding-bottom: 0.25rem; font-size: 0.9rem;">
                                    <i class="fas fa-tee-golf me-2" style="color: #5E7C4C;"></i>Overall Tee Ratings
                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-borderless align-middle mb-0">
                                        <thead>
                                            <tr style="background-color: #f0f4f0;">
                                                <th class="text-uppercase text-muted small fw-bold" style="color: #2F4A3C; width: 15%; padding: 0.35rem; font-size: 0.75rem;">Tee</th>
                                                @foreach($course->tees as $tee)
                                                <th class="text-center" style="padding: 0.35rem;">
                                                    <div class="fw-bold" style="color: #2F4A3C; font-size: 0.8rem;">{{ $tee->tee_name }}</div>
                                                    <small style="color: #8DA66E; font-size: 0.65rem;">Tee Name</small>
                                                </th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="fw-bold" style="color: #5E7C4C; padding: 0.3rem; font-size: 0.8rem;">Course Rating</td>
                                                @foreach($course->tees as $tee)
                                                <td class="text-center" style="padding: 0.3rem;">
                                                    <input type="number" step="0.1" class="form-control form-control-sm" name="course_rating[{{ $tee->tee_id }}]" value="" placeholder="-" style="max-width: 100%; border: 2px solid #e0e8d8; border-radius: 6px; text-align: center; font-size: 0.75rem; padding: 0.25rem;" title="Overall Course Rating">
                                                </td>
                                                @endforeach
                                            </tr>
                                            <tr style="background-color: #f8fafc;">
                                                <td class="fw-bold" style="color: #5E7C4C; padding: 0.3rem; font-size: 0.8rem;">Slope Rating</td>
                                                @foreach($course->tees as $tee)
                                                <td class="text-center" style="padding: 0.3rem;">
                                                    <input type="number" step="1" class="form-control form-control-sm" name="slope_rating[{{ $tee->tee_id }}]" value="" placeholder="-" style="max-width: 100%; border: 2px solid #e0e8d8; border-radius: 6px; text-align: center; font-size: 0.75rem; padding: 0.25rem;" title="Overall Slope Rating">
                                                </td>
                                                @endforeach
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Front 9 and Back 9 Ratings Section -->
                            <div>
                                <h6 class="fw-bold mb-2" style="color: #2F4A3C; border-bottom: 2px solid #5E7C4C; padding-bottom: 0.25rem; font-size: 0.9rem;">
                                    <i class="fas fa-arrows-alt-h me-2" style="color: #5E7C4C;"></i>Front 9 & Back 9 Ratings
                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-borderless align-middle mb-0">
                                        <thead>
                                            <tr style="background-color: #f0f4f0;">
                                                <th class="text-uppercase text-muted small fw-bold" style="color: #2F4A3C; width: 15%; padding: 0.35rem; font-size: 0.75rem;">Side</th>
                                                @foreach($course->tees as $tee)
                                                <th colspan="2" class="text-center fw-bold" style="color: #2F4A3C; padding: 0.35rem; font-size: 0.8rem;">
                                                    {{ $tee->tee_name }}
                                                    <br>
                                                    <small style="color: #8DA66E; font-weight: normal; font-size: 0.65rem;">CR / SR</small>
                                                </th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr style="background-color: #f0fdf4;">
                                                <td class="fw-bold" style="color: #5E7C4C; padding: 0.3rem; font-size: 0.8rem;">
                                                    <i class="fas fa-play me-2" style="color: #2F4A3C;"></i>Front 9
                                                </td>
                                                @foreach($course->tees as $tee)
                                                <td style="padding: 0.3rem;">
                                                    <input type="number" step="0.1" class="form-control form-control-sm" name="front_nine_course_rating[{{ $tee->tee_id }}]" value="" placeholder="CR" style="max-width: 100%; border: 2px solid #8DA66E; border-radius: 6px; text-align: center; background-color: #f9fdf8; font-size: 0.75rem; padding: 0.25rem;" title="Front 9 Course Rating">
                                                </td>
                                                <td style="padding: 0.3rem;">
                                                    <input type="number" step="1" class="form-control form-control-sm" name="front_nine_slope_rating[{{ $tee->tee_id }}]" value="" placeholder="SR" style="max-width: 100%; border: 2px solid #8DA66E; border-radius: 6px; text-align: center; background-color: #f9fdf8; font-size: 0.75rem; padding: 0.25rem;" title="Front 9 Slope Rating">
                                                </td>
                                                @endforeach
                                            </tr>
                                            <tr style="background-color: #fdf8f0;">
                                                <td class="fw-bold" style="color: #5E7C4C; padding: 0.3rem; font-size: 0.8rem;">
                                                    <i class="fas fa-arrow-right me-2" style="color: #2F4A3C;"></i>Back 9
                                                </td>
                                                @foreach($course->tees as $tee)
                                                <td style="padding: 0.3rem;">
                                                    <input type="number" step="0.1" class="form-control form-control-sm" name="back_nine_course_rating[{{ $tee->tee_id }}]" value="" placeholder="CR" style="max-width: 100%; border: 2px solid #8DA66E; border-radius: 6px; text-align: center; background-color: #fdfcf9; font-size: 0.75rem; padding: 0.25rem;" title="Back 9 Course Rating">
                                                </td>
                                                <td style="padding: 0.3rem;">
                                                    <input type="number" step="1" class="form-control form-control-sm" name="back_nine_slope_rating[{{ $tee->tee_id }}]" value="" placeholder="SR" style="max-width: 100%; border: 2px solid #8DA66E; border-radius: 6px; text-align: center; background-color: #fdfcf9; font-size: 0.75rem; padding: 0.25rem;" title="Back 9 Slope Rating">
                                                </td>
                                                @endforeach
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 scorecard-section-card" style="background: #fff; border: 1px solid #e0e8d8;">
                        <div class="card-header py-1 px-3 d-flex align-items-center justify-content-between" style="background: #f8fafc; color: #2F4A3C; border-bottom: 1px solid #e0e8d8;">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-ruler-combined fa-lg" style="color: #5E7C4C;"></i>
                                <span class="fw-bold" style="font-size: 1rem; color: #2F4A3C;">Tee Yardages</span>
                            </div>
                            <span class="badge" style="background-color: #e0e8d8; color: #5E7C4C; font-size: 0.7rem;">Distance by Tee</span>
                        </div>
                        <div class="card-body p-2">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle text-center mb-0 table-hover scorecard-table" style="min-width: 1100px;">
                                    <thead style="background: #f0f4f0; color: #2F4A3C;">
                                        <tr>
                                            <th class="fw-bold" style="color: #5E7C4C; background: #e8e8e8; font-size: 0.8rem; padding: 0.4rem;">Tee</th>
                                            @php
                                            $holes = range(1, 18);
                                            $frontNine = array_slice($holes, 0, 9);
                                            $backNine = array_slice($holes, 9, 9);


                                            @endphp
                                            @foreach($frontNine as $hole)
                                            <th style="background: #f0f4f0; font-size: 0.8rem; padding: 0.4rem;">{{ $hole }}</th>
                                            @endforeach
                                            <th style="background: #8DA66E; color: #fff; font-weight: bold; font-size: 0.8rem; padding: 0.4rem;">OUT</th>
                                            @foreach($backNine as $hole)
                                            <th style="background: #f0f4f0; font-size: 0.8rem; padding: 0.4rem;">{{ $hole }}</th>
                                            @endforeach
                                            <th style="background: #8DA66E; color: #fff; font-weight: bold; font-size: 0.8rem; padding: 0.4rem;">IN</th>
                                            <th style="background: #2F4A3C; color: #8DA66E; font-weight: bold; font-size: 0.8rem; padding: 0.4rem;">TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Tee Yardage Rows --}}
                                        @foreach($course->tees as $tee)
                                        <tr class="tee-row" data-tee-id="{{ $tee->tee_id }}" style="background-color: #f9faf9;">
                                            <td class="fw-bold" style="color: #5E7C4C; background: #e8e8e8; font-size: 0.8rem; padding: 0.3rem;">{{ $tee->tee_name }}</td>
                                            @foreach ($holes as $hole)
                                            <td style="color: #333; padding: 0.25rem;">
                                                <input type="number" value="" class="form-control form-control-sm scorecard-input tee-yardage-input" name="yardages[{{ $tee->tee_id }}][{{ $hole }}]" data-hole="{{ $hole }}" style="max-width: 70px; border-radius: 6px; border: 1px solid #8DA66E; background: #f8fafc; text-align: center; font-size: 0.75rem; padding: 0.3rem;" title="Yardage for {{ $tee->tee_name }} - Hole {{ $hole }}">
                                            </td>
                                            @if ($hole == 9)
                                            <td style="background: #8DA66E; color: #fff; font-weight: bold; padding: 0.25rem; font-size: 0.8rem;"></td>
                                            @endif
                                            @endforeach
                                            <td style="background: #8DA66E; color: #fff; font-weight: bold; padding: 0.25rem; font-size: 0.8rem;"></td>
                                            <td style="background: #2F4A3C; color: #8DA66E; font-weight: bold; padding: 0.25rem; font-size: 0.8rem;"></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 scorecard-section-card" style="background: #fff; border: 1px solid #e0e8d8;">
                        <div class="card-header py-1 px-3 d-flex align-items-center justify-content-between" style="background: #f8fafc; color: #2F4A3C; border-bottom: 1px solid #e0e8d8;">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-flag fa-lg" style="color: #5E7C4C;"></i>
                                <span class="fw-bold" style="font-size: 1rem; color: #2F4A3C;">Par & Handicap Index</span>
                            </div>
                            <span class="badge" style="background-color: #e0e8d8; color: #5E7C4C; font-size: 0.7rem;">Hole Configuration</span>
                        </div>
                        <div class="card-body p-2">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle text-center mb-0 table-hover scorecard-table" style="min-width: 1100px;">
                                    <thead style="background: #f0f4f0; color: #2F4A3C;">
                                        <tr>
                                            <th class="fw-bold" style="color: #5E7C4C; background: #e8e8e8; width: 12%; font-size: 0.8rem; padding: 0.4rem;">Hole Attr.</th>
                                            @foreach($frontNine as $hole)
                                            <th style="background: #f0f4f0; font-size: 0.8rem; padding: 0.4rem;">{{ $hole }}</th>
                                            @endforeach
                                            <th style="background: #8DA66E; color: #fff; font-weight: bold; font-size: 0.8rem; padding: 0.4rem;">OUT</th>
                                            @foreach($backNine as $hole)
                                            <th style="background: #f0f4f0; font-size: 0.8rem; padding: 0.4rem;">{{ $hole }}</th>
                                            @endforeach
                                            <th style="background: #8DA66E; color: #fff; font-weight: bold; font-size: 0.8rem; padding: 0.4rem;">IN</th>
                                            <th style="background: #2F4A3C; color: #8DA66E; font-weight: bold; font-size: 0.8rem; padding: 0.4rem;">TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Par Row --}}
                                        <tr class="par-row" style="background-color: #f9faf9;">
                                            <td class="fw-bold" style="color: #5E7C4C; background: #e8e8e8; padding: 0.3rem; font-size: 0.8rem;">PAR</td>
                                            @for ($i = 1; $i <= 18; $i++)
                                                <td style="padding: 0.25rem;">
                                                <input type="number" value="" class="form-control form-control scorecard-input par-input" name="par[{{ $i }}]" data-hole="{{ $i }}" style="width: 100%; border-radius: 6px; border: 2px solid #8DA66E; background: #f8fafc; text-align: center; padding: 0.25rem 0.2rem; font-weight: 600; font-size: 0.75rem;" title="Par for Hole {{ $i }}">
                                                </td>
                                                @if ($i == 9)
                                                <td style="background: #8DA66E; color: #fff; padding: 0.25rem; font-size: 0.8rem;"></td>
                                                @endif
                                                @endfor
                                                <td style="background: #8DA66E; color: #fff; padding: 0.25rem; font-size: 0.8rem;"></td>
                                                <td style="background: #2F4A3C; color: #8DA66E; padding: 0.25rem; font-size: 0.8rem;"></td>
                                        </tr>
                                        {{-- Men's Handicap --}}
                                        <tr class="male-handicap-row" style="background-color: #f9faf9;">
                                            <td class="fw-bold" style="color: #5E7C4C; background: #e8e8e8; padding: 0.3rem; font-size: 0.8rem;">M Handicap</td>
                                            @for ($i = 1; $i <= 18; $i++)
                                                <td style="padding: 0.25rem;">
                                                <input type="number" value="" class="form-control form-control scorecard-input" name="male_handicap[{{ $i }}]" data-hole="{{ $i }}" style="width: 100%; border-radius: 6px; border: 2px solid #8DA66E; background: #f8fafc; text-align: center; padding: 0.25rem 0.2rem; font-weight: 600; font-size: 0.75rem;" title="Men's Handicap Index for Hole {{ $i }}">
                                                </td>
                                                @if ($i == 9)
                                                <td style="background: #8DA66E; color: #fff; padding: 0.25rem; font-size: 0.8rem;"></td>
                                                @endif
                                                @endfor
                                                <td style="background: #8DA66E; color: #fff; padding: 0.25rem; font-size: 0.8rem;"></td>
                                                <td style="background: #2F4A3C; color: #8DA66E; padding: 0.25rem; font-size: 0.8rem;"></td>
                                        </tr>
                                        {{-- Ladies' Handicap --}}
                                        <tr class="ladies-handicap-row" style="background-color: #f9faf9;">
                                            <td class="fw-bold" style="color: #5E7C4C; background: #e8e8e8; padding: 0.3rem; font-size: 0.8rem;">W Handicap</td>
                                            @for ($i = 1; $i <= 18; $i++)
                                                <td style="padding: 0.25rem;">
                                                <input type="number" value="" class="form-control form-control scorecard-input" name="ladies_handicap[{{ $i }}]" data-hole="{{ $i }}" style="width: 100%; border-radius: 6px; border: 2px solid #8DA66E; background: #f8fafc; text-align: center; padding: 0.25rem 0.2rem; font-weight: 600; font-size: 0.75rem;" title="Women's Handicap Index for Hole {{ $i }}">
                                                </td>
                                                @if ($i == 9)
                                                <td style="background: #8DA66E; color: #fff; padding: 0.25rem; font-size: 0.8rem;"></td>
                                                @endif
                                                @endfor
                                                <td style="background: #8DA66E; color: #fff; padding: 0.25rem; font-size: 0.8rem;"></td>
                                                <td style="background: #2F4A3C; color: #8DA66E; padding: 0.25rem; font-size: 0.8rem;"></td>
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
        // Initialize computation on load
        computeAllTotals();

        // Add event listeners to all yardage inputs
        document.querySelectorAll('.tee-yardage-input').forEach(input => {
            input.addEventListener('input', computeAllTotals);
        });

        // Add event listeners to all par inputs
        document.querySelectorAll('.par-input').forEach(input => {
            input.addEventListener('input', computeAllTotals);
        });

        // Function to compute all totals
        function computeAllTotals() {
            computeYardageTotals();
            computeParTotals();
        }

        // Function to compute yardage totals (OUT, IN, TOTAL)
        function computeYardageTotals() {
            document.querySelectorAll('.tee-row').forEach(row => {
                const inputs = Array.from(row.querySelectorAll('.tee-yardage-input'));

                if (inputs.length === 18) {
                    // Calculate OUT (holes 1-9)
                    const outTotal = inputs.slice(0, 9).reduce((sum, input) => {
                        return sum + (parseFloat(input.value) || 0);
                    }, 0);

                    // Calculate IN (holes 10-18)
                    const inTotal = inputs.slice(9, 18).reduce((sum, input) => {
                        return sum + (parseFloat(input.value) || 0);
                    }, 0);

                    // Calculate TOTAL (all 18 holes)
                    const total = outTotal + inTotal;

                    // Get all cells in the row
                    const cells = Array.from(row.querySelectorAll('td'));

                    // Find OUT cell (after 9 holes + tee name) = index 10
                    if (cells[10]) {
                        cells[10].textContent = outTotal > 0 ? outTotal : '';
                        cells[10].style.fontWeight = 'bold';
                    }

                    // Find IN cell (after 18 holes + tee name + OUT) = index 20
                    if (cells[20]) {
                        cells[20].textContent = inTotal > 0 ? inTotal : '';
                        cells[20].style.fontWeight = 'bold';
                    }

                    // Find TOTAL cell (after 18 holes + tee name + OUT + IN) = index 21
                    if (cells[21]) {
                        cells[21].textContent = total > 0 ? total : '';
                        cells[21].style.fontWeight = 'bold';
                        cells[21].style.fontSize = '1.1rem';
                    }

                    // Display real-time total for each tee in console (for debugging)
                    const teeName = row.querySelector('td').textContent;
                    console.log(`${teeName} Yardage - OUT: ${outTotal}, IN: ${inTotal}, TOTAL: ${total}`);
                }
            });
        }

        // Function to compute par totals (OUT, IN, TOTAL)
        function computeParTotals() {
            const parRow = document.querySelector('.par-row');
            if (!parRow) return;

            const inputs = Array.from(parRow.querySelectorAll('.par-input'));

            if (inputs.length === 18) {
                // Calculate OUT (holes 1-9)
                const outTotal = inputs.slice(0, 9).reduce((sum, input) => {
                    return sum + (parseInt(input.value) || 0);
                }, 0);

                // Calculate IN (holes 10-18)
                const inTotal = inputs.slice(9, 18).reduce((sum, input) => {
                    return sum + (parseInt(input.value) || 0);
                }, 0);

                // Calculate TOTAL (all 18 holes)
                const total = outTotal + inTotal;

                // Get all cells in the row
                const cells = Array.from(parRow.querySelectorAll('td'));

                // Find and update the OUT cell (index 10)
                if (cells[10]) {
                    cells[10].textContent = outTotal > 0 ? outTotal : '';
                    cells[10].style.fontWeight = 'bold';
                }

                // Find and update the IN cell (index 20)
                if (cells[20]) {
                    cells[20].textContent = inTotal > 0 ? inTotal : '';
                    cells[20].style.fontWeight = 'bold';
                }

                // Find and update the TOTAL cell (index 21)
                if (cells[21]) {
                    cells[21].textContent = total > 0 ? total : '';
                    cells[21].style.fontWeight = 'bold';
                    cells[21].style.fontSize = '1.1rem';
                }
            }
        }

        //on submit #mainForm
        document.getElementById('mainForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const form = this;
            const submitBtn = document.getElementById('submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');

            // Validate form
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return false;
            }

            // Show loading state
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
            submitBtn.disabled = true;

            // Prepare form data
            const formData = new FormData(form);

            // Make AJAX request
            fetch('{{ route("admin.scorecards.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw {
                                status: response.status,
                                data: data
                            };
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    // Show success message
                    if (data.success || data.message) {
                        const message = data.message || 'Scorecard created successfully!';
                        alert('Success: ' + message);

                        // Reset form
                        form.reset();
                        form.classList.remove('was-validated');

                        // Redirect after delay
                        setTimeout(() => {
                            window.location.href = data.redirect || '/admin/scorecards';
                        }, 1500);
                    } else {
                        alert('Error: ' + (data.message || 'Failed to create scorecard'));
                        btnText.classList.remove('d-none');
                        btnLoading.classList.add('d-none');
                        submitBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);

                    // Handle validation errors
                    if (error.status === 422 && error.data.errors) {
                        let errorMessage = 'Validation errors:\n';
                        Object.keys(error.data.errors).forEach(field => {
                            errorMessage += `${field}: ${error.data.errors[field].join(', ')}\n`;
                        });
                        alert(errorMessage);
                    } else {
                        alert('Error: ' + (error.data?.message || error.message || 'An error occurred while creating the scorecard'));
                    }

                    // Reset button state
                    btnText.classList.remove('d-none');
                    btnLoading.classList.add('d-none');
                    submitBtn.disabled = false;
                });
        });
    });
</script>


@endsection