@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-4">
    <div class="row">
        <div class="col-12">
            <div class="form-container fade-in">
                <!-- Form Body -->
                <div class="form-body">
                    <h3 class="mb-2 mt-0 text-center text-primary">
                        <i class="fas fa-trophy me-2 text-primary"></i>
                        Add New Tournament
                    </h3>
                    <p class="text-muted text-center mb-3">Create a new golf tournament in the system</p>

                    <!-- Stepper -->
                    <div class="stepper-container mb-4">
                        <div class="stepper">
                            <div class="step active" data-step="1">
                                <div class="step-circle">1</div>
                                <div class="step-label">Basic Info</div>
                            </div>
                            <div class="step-connector"></div>
                            <div class="step" data-step="2">
                                <div class="step-circle">2</div>
                                <div class="step-label">Courses</div>
                            </div>
                            <div class="step-connector"></div>
                            <div class="step" data-step="3">
                                <div class="step-circle">3</div>
                                <div class="step-label">Scorecard</div>
                            </div>
                            <div class="step-connector"></div>
                            <div class="step" data-step="4">
                                <div class="step-circle">4</div>
                                <div class="step-label">Handicap</div>
                            </div>
                            <div class="step-connector"></div>
                            <div class="step" data-step="5">
                                <div class="step-circle">5</div>
                                <div class="step-label">Tournament Handicap</div>
                            </div>
                            <div class="step-connector"></div>
                            <div class="step" data-step="6">
                                <div class="step-circle">6</div>
                                <div class="step-label">Course Handicap</div>
                            </div>
                            <div class="step-connector"></div>
                            <div class="step" data-step="7">
                                <div class="step-circle">7</div>
                                <div class="step-label">Divisions</div>
                            </div>
                            <div class="step-connector"></div>
                            <div class="step" data-step="8">
                                <div class="step-circle">8</div>
                                <div class="step-label">Review</div>
                            </div>
                        </div>
                    </div>

                    <form class="needs-validation" novalidate id="mainForm">
                        @csrf
                        <!-- STEP 1: Basic Information -->
                        <div class="form-step active" data-step="1">
                            <div class="step-header">
                                <h5>Step 1: Tournament Basic Information</h5>
                                <p class="text-muted">Enter the tournament name, description, and dates</p>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="tournament_name" name="tournament_name" placeholder="Tournament Name" required maxlength="100">
                                        <label for="tournament_name">Tournament Name *</label>
                                        <div class="invalid-feedback">
                                            Please provide a valid tournament name.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" id="tournament_desc" name="tournament_desc" placeholder="Tournament Description" style="height: 120px;" maxlength="255"></textarea>
                                        <label for="tournament_desc">Tournament Description</label>
                                        <div class="invalid-feedback">
                                            Tournament description cannot exceed 255 characters.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" id="tournament_start" name="tournament_start" required>
                                        <label for="tournament_start">Start Date *</label>
                                        <div class="invalid-feedback">
                                            Please provide a valid start date.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" id="tournament_end" name="tournament_end" required>
                                        <label for="tournament_end">End Date *</label>
                                        <div class="invalid-feedback">
                                            Please provide a valid end date.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- STEP 2: Course Selection -->
                        <div class="form-step" data-step="2">
                            <div class="step-header">
                                <h5>Step 2: Course Selection</h5>
                                <p class="text-muted">Select the courses for this tournament</p>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label">Select Courses *</label>
                                    <div class="course-selection-container">
                                        @if(isset($courses))
                                        @foreach($courses as $course)
                                        <div class="form-check-modern course-option">
                                            <input class="form-check-input" type="checkbox" id="course_{{ $course->course_id }}" name="course_ids[]" value="{{ $course->course_id }}">
                                            <label class="form-check-label" for="course_{{ $course->course_id  }}">
                                                <strong>{{ $course->course_name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $course->course_desc }}</small>
                                            </label>
                                        </div>
                                        @endforeach
                                        @else
                                        <div class="form-check-modern course-option">
                                            <input class="form-check-input" type="checkbox" id="course_north" name="course_ids[]" value="north">
                                            <label class="form-check-label" for="course_north">
                                                <strong>North Course</strong>
                                                <br>
                                                <small class="text-muted">Northern Golf Course</small>
                                            </label>
                                        </div>
                                        <div class="form-check-modern course-option">
                                            <input class="form-check-input" type="checkbox" id="course_south" name="course_ids[]" value="south">
                                            <label class="form-check-label" for="course_south">
                                                <strong>South Course</strong>
                                                <br>
                                                <small class="text-muted">Southern Golf Course</small>
                                            </label>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="invalid-feedback" id="course-error">
                                        Please select at least one course.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- STEP 3: Scorecard Configuration -->
                        <div class="form-step" data-step="3">
                            <div class="step-header">
                                <h5>Step 3: Scorecard Configuration</h5>
                                <p class="text-muted">Select scorecard templates for each course</p>
                            </div>

                            <div class="alert alert-info" id="scorecard-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Please select courses first to configure scorecards for each course.
                            </div>

                            <div id="scorecard-selections" class="row g-3" style="display: none;">
                                <!-- Dynamic scorecard selections will be added here -->
                            </div>
                        </div>

                        <!-- STEP 4: Handicap Configuration -->
                        <div class="form-step" data-step="4">
                            <div class="step-header">
                                <h5>Step 4: Local Handicap Index Configuration</h5>
                                <p class="text-muted">Configure USGA handicap calculation standards</p>
                            </div>

                            <div class="alert alert-info mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>USGA Handicap Index Calculation:</strong> The handicap index is calculated from the lowest score differentials in the scoring record. Configure below which differentials to use based on the number of scores.
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" id="scores_start_date" name="scores_start_date">
                                        <label for="scores_start_date">Score Date Range Start</label>
                                        <div class="invalid-feedback">
                                            Please provide a valid start date.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" id="scores_end_date" name="scores_end_date">
                                        <label for="scores_end_date">Score Date Range End</label>
                                        <div class="invalid-feedback">
                                            Please provide a valid end date.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-table me-2"></i>Number of Differentials to Use Based on Number of Scores
                                    </label>
                                    <p class="text-muted small mb-2">
                                        <strong>Note:</strong> This table applies when using the <strong>"Use Lowest Differentials"</strong> method. Each row specifies how many of the lowest score differentials to use and how to calculate them.
                                    </p>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered table-striped">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 30%;">Number of Differentials to Consider (Min-Max)</th>
                                                    <th style="width: 35%;">Calculation Method</th>
                                                    <th style="width: 25%;">Adjustment</th>
                                                    <th style="width: 10%; text-align: center;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[3][min]" value="3" placeholder="Min">
                                                            <span class="text-muted">to</span>
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[3][max]" value="3" placeholder="Max">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <select class="form-control form-control-sm" name="handicap_score_differential_config[3][method]" required>
                                                                <option value="LOWEST">Lowest</option>
                                                                <option value="AVERAGE_OF_LOWEST">Average of Lowest</option>
                                                                <option value="HIGHEST">Highest</option>
                                                            </select>
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[3][count]" value="1" min="1" placeholder="Count" style="width: 80px;">
                                                        </div>
                                                    </td>
                                                    <td><input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[3][adjustment]" value="-2.0" step="0.1"></td>
                                                    <td style="text-align: center;">
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeScoresConfigRow(this)">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[4][min]" value="4" placeholder="Min">
                                                            <span class="text-muted">to</span>
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[4][max]" value="4" placeholder="Max">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <select class="form-control form-control-sm" name="handicap_score_differential_config[4][method]" required>
                                                                <option value="LOWEST">Lowest</option>
                                                                <option value="AVERAGE_OF_LOWEST">Average of Lowest</option>
                                                                <option value="HIGHEST">Highest</option>
                                                            </select>
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[4][count]" value="1" min="1" placeholder="Count" style="width: 80px;">
                                                        </div>
                                                    </td>
                                                    <td><input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[4][adjustment]" value="-1.0" step="0.1"></td>
                                                    <td style="text-align: center;">
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeScoresConfigRow(this)">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>


                                                <tr>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[5][min]" value="5" placeholder="Min">
                                                            <span class="text-muted">to</span>
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[5][max]" value="5" placeholder="Max">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <select class="form-control form-control-sm" name="handicap_score_differential_config[5][method]" required>
                                                                <option value="LOWEST">Lowest</option>
                                                                <option value="AVERAGE_OF_LOWEST">Average of Lowest</option>
                                                                <option value="HIGHEST">Highest</option>
                                                            </select>
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[5][count]" value="1" min="1" placeholder="Count" style="width: 80px;">
                                                        </div>
                                                    </td>
                                                    <td><input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[5][adjustment]" value="0" step="0.1"></td>
                                                    <td style="text-align: center;">
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeScoresConfigRow(this)">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[6][min]" value="6" placeholder="Min">
                                                            <span class="text-muted">to</span>
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[6][max]" value="6" placeholder="Max">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <select class="form-control form-control-sm" name="handicap_score_differential_config[6][method]" required>
                                                                <option value="LOWEST">Lowest</option>
                                                                <option value="AVERAGE_OF_LOWEST" selected>Average of Lowest</option>
                                                                <option value="HIGHEST">Highest</option>
                                                            </select>
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[6][count]" value="2" min="1" placeholder="Count" style="width: 80px;">
                                                        </div>
                                                    </td>
                                                    <td><input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[6][adjustment]" value="-1.0" step="0.1"></td>
                                                    <td style="text-align: center;">
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeScoresConfigRow(this)">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>


                                                <tr>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[7][min]" value="7" placeholder="Min">
                                                            <span class="text-muted">to</span>
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[7][max]" value="8" placeholder="Max">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <select class="form-control form-control-sm" name="handicap_score_differential_config[7][method]" required>
                                                                <option value="LOWEST">Lowest</option>
                                                                <option value="AVERAGE_OF_LOWEST" selected>Average of Lowest</option>
                                                                <option value="HIGHEST">Highest</option>
                                                            </select>
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[7][count]" value="2" min="1" placeholder="Count" style="width: 80px;">
                                                        </div>
                                                    </td>
                                                    <td><input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[7][adjustment]" value="0" step="0.1"></td>
                                                    <td style="text-align: center;">
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeScoresConfigRow(this)">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>



                                                <tr>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[9][min]" value="9" placeholder="Min">
                                                            <span class="text-muted">to</span>
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[9][max]" value="11" placeholder="Max">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <select class="form-control form-control-sm" name="handicap_score_differential_config[9][method]" required>
                                                                <option value="LOWEST">Lowest</option>
                                                                <option value="AVERAGE_OF_LOWEST" selected>Average of Lowest</option>
                                                                <option value="HIGHEST">Highest</option>
                                                            </select>
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[9][count]" value="3" min="1" placeholder="Count" style="width: 80px;">
                                                        </div>
                                                    </td>
                                                    <td><input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[9][adjustment]" value="0" step="0.1"></td>
                                                    <td style="text-align: center;">
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeScoresConfigRow(this)">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>


                                                <tr>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[12][min]" value="12" placeholder="Min">
                                                            <span class="text-muted">to</span>
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[12][max]" value="14" placeholder="Max">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <select class="form-control form-control-sm" name="handicap_score_differential_config[12][method]" required>
                                                                <option value="LOWEST">Lowest</option>
                                                                <option value="AVERAGE_OF_LOWEST" selected>Average of Lowest</option>
                                                                <option value="HIGHEST">Highest</option>
                                                            </select>
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[12][count]" value="4" min="1" placeholder="Count" style="width: 80px;">
                                                        </div>
                                                    </td>
                                                    <td><input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[12][adjustment]" value="0" step="0.1"></td>
                                                    <td style="text-align: center;">
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeScoresConfigRow(this)">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>



                                                <tr>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[15][min]" value="15" placeholder="Min">
                                                            <span class="text-muted">to</span>
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[15][max]" value="16" placeholder="Max">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <select class="form-control form-control-sm" name="handicap_score_differential_config[15][method]" required>
                                                                <option value="LOWEST">Lowest</option>
                                                                <option value="AVERAGE_OF_LOWEST" selected>Average of Lowest</option>
                                                                <option value="HIGHEST">Highest</option>
                                                            </select>
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[15][count]" value="5" min="1" placeholder="Count" style="width: 80px;">
                                                        </div>
                                                    </td>
                                                    <td><input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[15][adjustment]" value="0" step="0.1"></td>
                                                    <td style="text-align: center;">
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeScoresConfigRow(this)">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>


                                                <tr>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[17][min]" value="17" placeholder="Min">
                                                            <span class="text-muted">to</span>
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[17][max]" value="18" placeholder="Max">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <select class="form-control form-control-sm" name="handicap_score_differential_config[17][method]" required>
                                                                <option value="LOWEST">Lowest</option>
                                                                <option value="AVERAGE_OF_LOWEST" selected>Average of Lowest</option>
                                                                <option value="HIGHEST">Highest</option>
                                                            </select>
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[17][count]" value="6" min="1" placeholder="Count" style="width: 80px;">
                                                        </div>
                                                    </td>
                                                    <td><input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[17][adjustment]" value="0" step="0.1"></td>
                                                    <td style="text-align: center;">
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeScoresConfigRow(this)">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[19][min]" value="19" placeholder="Min">
                                                            <span class="text-muted">to</span>
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[19][max]" value="19" placeholder="Max">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <select class="form-control form-control-sm" name="handicap_score_differential_config[19][method]" required>
                                                                <option value="LOWEST">Lowest</option>
                                                                <option value="AVERAGE_OF_LOWEST" selected>Average of Lowest</option>
                                                                <option value="HIGHEST">Highest</option>
                                                            </select>
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[19][count]" value="7" min="1" placeholder="Count" style="width: 80px;">
                                                        </div>
                                                    </td>
                                                    <td><input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[19][adjustment]" value="0" step="0.1"></td>
                                                    <td style="text-align: center;">
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeScoresConfigRow(this)">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>


                                                <tr>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[20][min]" value="20" placeholder="Min">
                                                            <span class="text-muted">to</span>
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[20][max]" value="20" placeholder="Max">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <select class="form-control form-control-sm" name="handicap_score_differential_config[20][method]" required>
                                                                <option value="LOWEST">Lowest</option>
                                                                <option value="AVERAGE_OF_LOWEST" selected>Average of Lowest</option>
                                                                <option value="HIGHEST">Highest</option>
                                                            </select>
                                                            <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[20][count]" value="8" min="1" placeholder="Count" style="width: 80px;">
                                                        </div>
                                                    </td>
                                                    <td><input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[20][adjustment]" value="0" step="0.1"></td>
                                                    <td style="text-align: center;">
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeScoresConfigRow(this)">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>









                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary mt-2" onclick="addScoresConfigRow()">
                                        <i class="fas fa-plus me-1"></i>Add Row
                                    </button>
                                    <small class="form-text text-muted d-block mt-2">
                                        <i class="fas fa-info-circle me-1"></i>
                                        <strong>How to use:</strong>
                                        <ul class="mb-0 mt-1 ms-4">
                                            <li><strong>Number of Scores Range (Min-Max):</strong> Define the allowed range for the score range (e.g., 1 to 5)</li>
                                            <li><strong>Calculation Method:</strong>
                                                <ul>
                                                    <li><strong>Lowest:</strong> Takes the single lowest value from the selected differentials</li>
                                                    <li><strong>Average of Lowest:</strong> Averages the specified number of lowest differentials (enter count in the adjacent field)</li>
                                                    <li><strong>Highest:</strong> Takes the single highest value from the selected differentials</li>
                                                </ul>
                                            </li>
                                            <li><strong>Count:</strong> The number of differentials to use in calculations (used for "Average of Lowest")</li>
                                            <li><strong>Adjustment:</strong> Multiplier or addition applied to the final handicap index calculation</li>
                                        </ul>
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- STEP 5: Tournament Handicap Configuration -->
                        <div class="form-step" data-step="5">
                            <div class="step-header">
                                <h5>Step 5: Tournament Handicap Configuration</h5>
                                <p class="text-muted">Configure how to calculate tournament handicaps</p>
                            </div>

                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Tournament Handicap Formulas:</strong> Define how to calculate handicaps based on player handicap data availability.
                                <ul class="mb-0 mt-2 ms-4">
                                    <li><strong>Formula 1:</strong> Both WHS and Local Handicap Index available</li>
                                    <li><strong>Formula 2:</strong> Only WHS Handicap Index available</li>
                                    <li><strong>Formula 3:</strong> Only Local Handicap Index available</li>
                                    <li><strong>Formula 4:</strong> Neither WHS nor Local Handicap Index available (default)</li>
                                </ul>
                            </div>

                            <div class="mb-4 p-3 bg-light border rounded">
                                <small class="text-muted">
                                    <strong>Available Variables:</strong>
                                    <ul class="mb-0 mt-2 ms-4">
                                        <li><code>LOCAL_HANDICAP_INDEX</code> - Local Handicap Index</li>
                                        <li><code>WHS_HANDICAP_INDEX</code> - World Handicap System Index</li>
                                    </ul>
                                    <strong class="d-block mt-2">Operators:</strong> +, -, *, / &nbsp;|&nbsp;
                                    <strong>Functions:</strong> MIN(), MAX(), ROUND(), AVG()
                                </small>
                            </div>

                            <div class="card shadow-sm">
                                <div class="card-header bg-primary text-white py-3">
                                    <h6 class="mb-0">
                                        <i class="fas fa-calculator me-2"></i>Handicap Formulas
                                    </h6>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h6 class="mb-3">
                                                <span class="badge bg-success me-2">FORMULA 1</span>
                                                <span class="text-muted small">Both WHS and Local Available</span>
                                            </h6>
                                            <div class="form-floating">
                                                <textarea class="form-control tournament-handicap-formula"
                                                    id="tournament_handicap_formula_1"
                                                    name="tournament_handicap_formula_1"
                                                    placeholder="Enter formula for when both handicaps are available"
                                                    style="height: 100px;"
                                                    maxlength="500">MIN(LOCAL_HANDICAP_INDEX, WHS_HANDICAP_INDEX)</textarea>
                                                <label for="tournament_handicap_formula_1">Formula 1 *</label>
                                                <div class="invalid-feedback">
                                                    Please provide a valid formula.
                                                </div>
                                            </div>
                                            <small class="form-text text-muted d-block mt-2">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Example: <code>MIN(LOCAL_HANDICAP_INDEX, WHS_HANDICAP_INDEX)</code>
                                            </small>
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h6 class="mb-3">
                                                <span class="badge bg-warning text-dark me-2">FORMULA 2</span>
                                                <span class="text-muted small">Only WHS Available</span>
                                            </h6>
                                            <div class="form-floating">
                                                <textarea class="form-control tournament-handicap-formula"
                                                    id="tournament_handicap_formula_2"
                                                    name="tournament_handicap_formula_2"
                                                    placeholder="Enter formula for when only WHS handicap is available"
                                                    style="height: 100px;"
                                                    maxlength="500">WHS_HANDICAP_INDEX * 0.8</textarea>
                                                <label for="tournament_handicap_formula_2">Formula 2 *</label>
                                                <div class="invalid-feedback">
                                                    Please provide a valid formula.
                                                </div>
                                            </div>
                                            <small class="form-text text-muted d-block mt-2">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Example: <code>WHS_HANDICAP_INDEX * 0.8</code>
                                            </small>
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h6 class="mb-3">
                                                <span class="badge bg-danger me-2">FORMULA 3</span>
                                                <span class="text-muted small">Only Local Available</span>
                                            </h6>
                                            <div class="form-floating">
                                                <textarea class="form-control tournament-handicap-formula"
                                                    id="tournament_handicap_formula_3"
                                                    name="tournament_handicap_formula_3"
                                                    placeholder="Enter formula for when only local handicap is available"
                                                    style="height: 100px;"
                                                    maxlength="500">LOCAL_HANDICAP_INDEX</textarea>
                                                <label for="tournament_handicap_formula_3">Formula 3 *</label>
                                                <div class="invalid-feedback">
                                                    Please provide a valid formula.
                                                </div>
                                            </div>
                                            <small class="form-text text-muted d-block mt-2">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Example: <code>LOCAL_HANDICAP_INDEX</code>
                                            </small>
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    <div class="row">
                                        <div class="col-12">
                                            <h6 class="mb-3">
                                                <span class="badge bg-secondary me-2">FORMULA 4</span>
                                                <span class="text-muted small">Neither Available (Default)</span>
                                            </h6>
                                            <div class="form-floating">
                                                <textarea class="form-control tournament-handicap-formula"
                                                    id="tournament_handicap_formula_4"
                                                    name="tournament_handicap_formula_4"
                                                    placeholder="Enter default formula when no handicap data is available"
                                                    style="height: 100px;"
                                                    maxlength="500"></textarea>
                                                <label for="tournament_handicap_formula_4">Formula 4 (Optional)</label>
                                                <div class="invalid-feedback">
                                                    Please provide a valid formula.
                                                </div>
                                            </div>
                                            <small class="form-text text-muted d-block mt-2">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Example: <code>0</code>
                                            </small>
                                            <div class="alert alert-info mt-3 mb-0" style="font-size: 0.9rem;">
                                                <i class="fas fa-lightbulb me-2"></i>
                                                <strong>Leave this blank to make handicap null.</strong> A handicap value of <code>0</code> is different from a null (empty) value.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow-sm mt-4">
                                <div class="card-header bg-info text-white py-3">
                                    <h6 class="mb-0">
                                        <i class="fas fa-flask me-2"></i>Test Tournament Handicap Formulas
                                    </h6>
                                </div>
                                <div class="card-body p-4">
                                    <p class="text-muted small mb-3">
                                        Enter test values below to verify your tournament handicap formulas work correctly:
                                    </p>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Local Handicap Index</label>
                                            <input type="number" step="0.1" class="form-control test-tournament-value" id="test-tournament-local-handicap" placeholder="e.g., 12.5">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">WHS Handicap Index</label>
                                            <input type="number" step="0.1" class="form-control test-tournament-value" id="test-tournament-whs-handicap" placeholder="e.g., 13.2">
                                        </div>
                                    </div>
                                    <div class="text-center mt-3">
                                        <button type="button" class="btn btn-info btn-sm" id="testTournamentFormulaBtn">
                                            <i class="fas fa-play me-1"></i>Test Formulas
                                        </button>
                                    </div>
                                    <div id="testTournamentResults" class="mt-3" style="display: none;">
                                        <div class="alert alert-info mb-0">
                                            <h6 class="mb-2">Results:</h6>
                                            <div id="tournamentResult-1" class="mb-2"><strong>1:</strong> <code></code></div>
                                            <div id="tournamentResult-2" class="mb-2"><strong>2:</strong> <code></code></div>
                                            <div id="tournamentResult-3" class="mb-2"><strong>3:</strong> <code></code></div>
                                            <div id="tournamentResult-4"><strong>4:</strong> <code></code></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4 mt-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-align-left me-2"></i>Formula Description (Optional)
                                </label>
                                <p class="text-muted small mb-2">
                                    Add a description to explain your handicap formulas:
                                </p>
                                <textarea
                                    class="form-control"
                                    id="tournament_handicap_formula_desc"
                                    name="tournament_handicap_formula_desc"
                                    placeholder="e.g., 'Uses the lower of WHS or Local handicap to ensure fair play' or 'Average of both systems for balanced scoring'"
                                    rows="3"
                                    maxlength="500"></textarea>
                                <small class="form-text text-muted d-block mt-1">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Maximum 500 characters.
                                </small>
                            </div>
                        </div>

                        <!-- STEP 6: Course Handicap Configuration -->
                        <div class="form-step" data-step="6">
                            <div class="step-header">
                                <h5>Step 6: Course Handicap Configuration</h5>
                                <p class="text-muted">Configure formulas for Regular and Plus handicaps by course</p>
                            </div>

                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Course Handicap Formulas:</strong> Define how to calculate course handicaps based on player handicap indices and course ratings.
                                <ul class="mb-0 mt-2 ms-4">
                                    <li><strong>Regular Handicap:</strong> Standard course handicap calculation</li>
                                    <li><strong>Plus Handicap:</strong> Alternative course handicap calculation (e.g., for handicap analysis or tournament adjustments)</li>
                                </ul>
                            </div>

                            <div class="mb-4 p-3 bg-light border rounded">
                                <small class="text-muted">
                                    <strong>Available Variables:</strong>
                                    <ul class="mb-0 mt-2 ms-4">
                                        <li><code>HANDICAP_INDEX</code> - Player's handicap index</li>
                                        <li><code>SLOPE_RATING</code> - Course slope rating</li>
                                        <li><code>COURSE_RATING</code> - Course rating</li>
                                        <li><code>PAR</code> - Course par</li>
                                    </ul>
                                    <strong class="d-block mt-2">Operators:</strong> +, -, *, / &nbsp;|&nbsp;
                                    <strong>Functions:</strong> MIN(), MAX(), ROUND(), CEIL(), FLOOR()
                                </small>
                            </div>

                            <div class="card shadow-sm">
                                <div class="card-header bg-primary text-white py-3">
                                    <h6 class="mb-0">
                                        <i class="fas fa-calculator me-2"></i>Course Handicap Formulas
                                    </h6>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h6 class="mb-3">
                                                <span class="badge bg-success me-2">REGULAR HANDICAP</span>
                                            </h6>
                                            <div class="form-floating">
                                                <textarea class="form-control course-handicap-formula"
                                                    id="course_handicap_formula_regular"
                                                    name="course_handicap_formula_regular"
                                                    placeholder="Enter formula for regular course handicap"
                                                    style="height: 100px;"
                                                    maxlength="500">MIN((HANDICAP_INDEX * (SLOPE_RATING / 113)) + (COURSE_RATING - PAR), 36)</textarea>
                                                <label for="course_handicap_formula_regular">Regular Handicap Formula *</label>
                                                <div class="invalid-feedback">
                                                    Please provide a valid formula.
                                                </div>
                                            </div>
                                            <small class="form-text text-muted d-block mt-2">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Example: <code>MIN((HANDICAP_INDEX * (SLOPE_RATING / 113)) + (COURSE_RATING - PAR), 36)</code>
                                            </small>
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    <div class="row">
                                        <div class="col-12">
                                            <h6 class="mb-3">
                                                <span class="badge bg-warning text-dark me-2">PLUS HANDICAP</span>
                                            </h6>
                                            <div class="form-floating">
                                                <textarea class="form-control course-handicap-formula"
                                                    id="course_handicap_formula_plus"
                                                    name="course_handicap_formula_plus"
                                                    placeholder="Enter formula for plus course handicap"
                                                    style="height: 100px;"
                                                    maxlength="500">HANDICAP_INDEX + 1</textarea>
                                                <label for="course_handicap_formula_plus">Plus Handicap Formula *</label>
                                                <div class="invalid-feedback">
                                                    Please provide a valid formula.
                                                </div>
                                            </div>
                                            <small class="form-text text-muted d-block mt-2">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Example: <code>HANDICAP_INDEX + 1</code>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow-sm mt-4">
                                <div class="card-header bg-info text-white py-3">
                                    <h6 class="mb-0">
                                        <i class="fas fa-flask me-2"></i>Test Course Handicap Formulas
                                    </h6>
                                </div>
                                <div class="card-body p-4">
                                    <p class="text-muted small mb-3">
                                        Enter test values below to verify your course handicap formulas work correctly:
                                    </p>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Handicap Index</label>
                                            <input type="number" step="0.1" class="form-control test-course-value" id="test-handicap-index" placeholder="e.g., 12.5">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Slope Rating</label>
                                            <input type="number" step="0.1" class="form-control test-course-value" id="test-slope-rating" placeholder="e.g., 130" value="130">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Course Rating</label>
                                            <input type="number" step="0.1" class="form-control test-course-value" id="test-course-rating" placeholder="e.g., 73.5" value="73.5">
                                        </div>
                                    </div>
                                    <div class="text-center mt-3">
                                        <button type="button" class="btn btn-info btn-sm" id="testCourseFormulaBtn">
                                            <i class="fas fa-play me-1"></i>Test Formulas
                                        </button>
                                    </div>
                                    <div id="testCourseResults" class="mt-3" style="display: none;">
                                        <div class="alert alert-info mb-0">
                                            <h6 class="mb-2">Results:</h6>
                                            <div id="courseResult-regular" class="mb-2"><strong>Regular:</strong> <code></code></div>
                                            <div id="courseResult-plus"><strong>Plus:</strong> <code></code></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4 mt-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-align-left me-2"></i>Formula Description (Optional)
                                </label>
                                <p class="text-muted small mb-2">
                                    Add a description to explain your course handicap formulas:
                                </p>
                                <textarea
                                    class="form-control"
                                    id="course_handicap_formula_desc"
                                    name="course_handicap_formula_desc"
                                    placeholder="e.g., 'Regular uses standard USGA calculation, Plus applies 1.1x modifier for tournament adjustments'"
                                    rows="3"
                                    maxlength="500"></textarea>
                                <small class="form-text text-muted d-block mt-1">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Maximum 500 characters.
                                </small>
                            </div>
                        </div>

                        <!-- STEP 7: Tournament Setup -->
                        <div class="form-step" data-step="7">
                            <div class="step-header">
                                <h5>Step 7: Tournament Divisions</h5>
                                <p class="text-muted">Define tournament divisions with eligibility criteria</p>
                            </div>

                            <div class="mb-4">
                                <h6 class="mb-3">Tournament Divisions</h6>
                                <p class="text-muted small mb-2">
                                    Define divisions within the tournament with eligibility criteria (e.g., Men's, Women's, Senior, Junior). You can add or remove divisions as needed.
                                </p>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-calendar me-2"></i>Age As Of Date (Optional)
                                    </label>
                                    <p class="text-muted small mb-2">
                                        The reference date used to calculate participant age for all divisions (e.g., tournament start date).
                                    </p>
                                    <input type="date" class="form-control" id="age_as_of_date" name="age_as_of_date" placeholder="YYYY-MM-DD">
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered table-striped" id="divisionsTable">
                                        <thead>
                                            <tr>
                                                <th style="width: 15%;">Division Name *</th>
                                                <th style="width: 15%;">Description</th>
                                                <th style="width: 12%; text-align: center;">Type</th>
                                                <th style="width: 10%; text-align: center;">Sex</th>
                                                <th style="width: 12%; text-align: center;">Participant Type</th>
                                                <th style="width: 15%; text-align: center;">Age Range</th>
                                                <th style="width: 15%; text-align: center;">Handicap Range</th>
                                                <th style="width: 8%; text-align: center;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="divisionsTbody">
                                            <tr>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm" name="divisions[0][name]" placeholder="e.g., Men's A" required>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm" name="divisions[0][description]" placeholder="e.g., Handicap 0-5">
                                                </td>
                                                <td style="text-align: center;">
                                                    <select class="form-control form-control-sm" name="divisions[0][type]">
                                                        <option value="regular" selected>Regular</option>
                                                        <option value="sponsored">Sponsored</option>
                                                    </select>
                                                </td>
                                                <td style="text-align: center;">
                                                    <select class="form-control form-control-sm" name="divisions[0][sex]">
                                                        <option value="X" selected>All</option>
                                                        <option value="M">Male</option>
                                                        <option value="F">Female</option>
                                                    </select>
                                                </td>
                                                <td style="text-align: center;">
                                                    <select class="form-control form-control-sm" name="divisions[0][participant_type]">
                                                        <option value="mixed" selected>Mixed</option>
                                                        <option value="member">Member</option>
                                                        <option value="guest">Guest</option>
                                                    </select>
                                                </td>
                                                <td style="text-align: center;">
                                                    <div class="d-flex gap-1">
                                                        <input type="number" class="form-control form-control-sm" name="divisions[0][age_min]" placeholder="Min" style="width: 45%;">
                                                        <span class="text-muted" style="width: 10%; text-align: center;">-</span>
                                                        <input type="number" class="form-control form-control-sm" name="divisions[0][age_max]" placeholder="Max" style="width: 45%;">
                                                    </div>
                                                </td>
                                                <td style="text-align: center;">
                                                    <div class="d-flex gap-1">
                                                        <input type="number" class="form-control form-control-sm" name="divisions[0][handicap_min]" placeholder="Min" step="0.01" style="width: 45%;">
                                                        <span class="text-muted" style="width: 10%; text-align: center;">-</span>
                                                        <input type="number" class="form-control form-control-sm" name="divisions[0][handicap_max]" placeholder="Max" step="0.01" style="width: 45%;">
                                                    </div>
                                                </td>
                                                <td style="text-align: center;">
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeDivisionRow(this)">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <button type="button" class="btn btn-sm btn-primary mt-2" onclick="addDivisionRow()">
                                    <i class="fas fa-plus me-1"></i>Add Division
                                </button>
                                <small class="form-text text-muted d-block mt-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <strong>Field Guide:</strong>
                                    <ul class="mb-0 mt-1 ms-4">
                                        <li><strong>Sex:</strong> All (X), Male (M), or Female (F)</li>
                                        <li><strong>Type:</strong> Member-only, Guest-only, or Mixed</li>
                                        <li><strong>Age Range:</strong> Optional - leave blank for no age restriction</li>
                                        <li><strong>Handicap Range:</strong> Optional - leave blank for no handicap restriction</li>
                                    </ul>
                                </small>
                            </div>
                        </div>

                        <!-- STEP 8: Review & Submit -->
                        <div class="form-step" data-step="8">
                            <div class="step-header">
                                <h5>Step 8: Review & Submit</h5>
                                <p class="text-muted">Review your tournament information and submit</p>
                            </div>

                            <div class="alert alert-info mb-3">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>Review your information</strong> - All required fields have been filled out. Click submit to create your tournament.
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <textarea class="form-control" id="remarks" name="remarks" placeholder="Enter any additional remarks about this tournament..." maxlength="1000"></textarea>
                                        <label for="remarks">Remarks (Optional)</label>
                                        <div class="invalid-feedback">
                                            Remarks cannot exceed 1000 characters.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="alert alert-light border p-3">
                                        <h6 class="mb-3">Tournament Summary</h6>
                                        <small>
                                            <p><strong>Name:</strong> <span id="summary-name">Not provided</span></p>
                                            <p><strong>Dates:</strong> <span id="summary-dates">Not provided</span></p>
                                            <p><strong>Courses:</strong> <span id="summary-courses">Not provided</span></p>
                                            <p><strong>Divisions:</strong> <span id="summary-divisions">Not provided</span></p>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions-wrapper mt-4">
                            <div class="d-flex justify-content-between align-items-center gap-3">
                                <button type="button" class="btn" id="prevBtn" style="display:none;">
                                    <i class="fas fa-chevron-left me-2"></i>
                                    Previous
                                </button>
                                <div class="d-flex gap-2 ms-auto">
                                    <button type="button" class="btn" id="cancelBtn">
                                        <i class="fas fa-times me-2"></i>
                                        Cancel
                                    </button>
                                    <button type="button" class="btn" id="nextBtn">
                                        <i class="fas fa-chevron-right me-2"></i>
                                        Next
                                    </button>
                                    <button type="submit" class="btn" id="submitBtn" style="display:none;">
                                        <span class="btn-text">
                                            <i class="fas fa-trophy me-2"></i>
                                            Create Tournament
                                        </span>
                                        <span class="btn-loading d-none">
                                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                            Creating...
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
    /* Stepper Styling */
    .stepper-container {
        margin-bottom: 2rem;
    }

    .stepper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        flex: 1;
    }

    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #e9ecef;
        border: 2px solid #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: #6c757d;
        font-size: 1rem;
        transition: all 0.3s ease;
        z-index: 2;
    }

    .step.active .step-circle {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
        box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
    }

    .step.completed .step-circle {
        background-color: #28a745;
        border-color: #28a745;
        color: white;
    }

    .step.completed .step-circle::after {
        content: '✓';
        position: absolute;
        font-size: 1.2rem;
    }

    .step-label {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 0.5rem;
        text-align: center;
        font-weight: 500;
    }

    .step.active .step-label {
        color: #007bff;
        font-weight: 600;
    }

    .step.completed .step-label {
        color: #28a745;
    }

    .step-connector {
        flex: 1;
        height: 2px;
        background-color: #dee2e6;
        position: relative;
        top: -24px;
        z-index: 1;
    }

    .step.completed~.step-connector,
    .step.active~.step-connector {
        background-color: #007bff;
    }

    .step:last-child .step-connector {
        display: none;
    }

    /* Form Step Styling */
    .form-step {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .form-step.active {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .step-header {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        padding: 1.5rem;
        margin: -1rem -1rem 1.5rem -1rem;
        border-radius: 0.375rem 0.375rem 0 0;
    }

    .step-header h5 {
        color: #0c5460;
        margin-bottom: 0.25rem;
        font-weight: 600;
    }

    .step-header p {
        margin: 0;
        font-size: 0.9rem;
    }

    .form-step .section-title {
        display: none;
    }

    /* Responsive stepper */
    @media (max-width: 768px) {
        .step-label {
            font-size: 0.7rem;
        }

        .step-circle {
            width: 35px;
            height: 35px;
            font-size: 0.9rem;
        }

        .stepper {
            gap: 0.25rem;
        }

        .step-connector {
            top: -20px;
        }
    }

    /* Course Selection Styling */
    .course-selection-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1rem;
        margin-top: 0.5rem;
    }

    .course-option {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 1rem;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .course-option:hover {
        border-color: #007bff;
        background: #e7f3ff;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.15);
    }

    .course-option input[type="checkbox"]:checked+.form-check-label {
        color: #007bff;
        font-weight: 600;
    }

    .course-option input[type="checkbox"]:checked {
        background-color: #007bff;
        border-color: #007bff;
    }

    .course-option.selected {
        border-color: #007bff;
        background: linear-gradient(135deg, #e7f3ff 0%, #f0f8ff 100%);
        box-shadow: 0 2px 8px rgba(0, 123, 255, 0.2);
    }

    .course-option .form-check-label {
        cursor: pointer;
        width: 100%;
        margin-bottom: 0;
    }

    .course-option .form-check-input {
        margin-top: 0.25rem;
        margin-right: 0.75rem;
        transform: scale(1.2);
    }

    .form-check-modern.course-option {
        display: flex;
        align-items: flex-start;
        margin-bottom: 0;
    }

    .course-selection-container.is-invalid {
        border: 1px solid #dc3545;
        border-radius: 6px;
        padding: 0.5rem;
    }

    .course-selection-container.is-invalid .course-option {
        border-color: #dc3545;
    }

    @media (max-width: 768px) {
        .course-selection-container {
            grid-template-columns: 1fr;
        }
    }

    /* Course Scorecard Selection Styling */
    .course-scorecard-selection .card {
        transition: all 0.3s ease;
        border: 2px solid #007bff;
    }

    .course-scorecard-selection .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
    }

    .course-scorecard-selection .card-header {
        border-bottom: 2px solid rgba(255, 255, 255, 0.2);
    }

    .course-scorecard-selection .card-header h6 {
        font-weight: 600;
        font-size: 0.95rem;
    }

    .course-scorecard-selection .form-floating {
        margin-bottom: 0;
    }

    #scorecard-info {
        background-color: #e7f3ff;
        border-color: #b3d9ff;
        color: #0c5460;
    }

    /* Handicap Index Configuration Table Styling */
    .table-sm input[type="number"] {
        padding: 0.25rem 0.5rem;
        height: 32px;
        font-size: 0.85rem;
    }

    .table-sm td {
        padding: 0.5rem;
        vertical-align: middle;
    }

    .table-sm thead th {
        background-color: #f5f6f7;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.75rem 0.5rem;
        border-bottom: 2px solid #dee2e6;
    }

    .table-sm tbody tr:hover {
        background-color: #f9f9f9;
    }

    .table-sm tbody tr:nth-child(odd) {
        background-color: #fbfbfb;
    }

    .form-actions-wrapper {
        padding: 1.5rem 0;
        margin-top: 2rem;
        border-top: 2px solid #dee2e6;
        background-color: #f8f9fa;
        margin-left: -1rem;
        margin-right: -1rem;
        margin-bottom: -1rem;
        padding-left: 1rem;
        padding-right: 1rem;
        border-radius: 0 0 0.375rem 0.375rem;
    }

    .form-actions-wrapper .d-flex {
        align-items: center;
        min-height: 50px;
    }

    .form-actions-wrapper .btn {
        min-width: 140px;
        font-weight: 500;
        padding: 0.625rem 1.5rem;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
    }

    .form-actions-wrapper #prevBtn {
        border: 2px solid #6c757d;
        color: #6c757d;
    }

    .form-actions-wrapper #prevBtn:hover {
        background-color: #6c757d;
        color: white;
        transform: translateX(-2px);
    }

    .form-actions-wrapper #cancelBtn {
        border: 2px solid #dc3545;
        color: #dc3545;
    }

    .form-actions-wrapper #cancelBtn:hover {
        background-color: #dc3545;
        color: white;
    }

    .form-actions-wrapper #nextBtn {
        background-color: #0d6efd;
        color: white;
        border: none;
    }

    .form-actions-wrapper #nextBtn:hover {
        background-color: #0b5ed7;
        transform: translateX(2px);
    }

    .form-actions-wrapper #submitBtn {
        background-color: #28a745;
        color: white;
        border: none;
    }

    .form-actions-wrapper #submitBtn:hover {
        background-color: #218838;
    }

    .form-actions-wrapper #submitBtn:disabled {
        background-color: #6c757d;
        opacity: 0.6;
    }

    /* Formula Description Styling */
    #handicap_formula_desc {
        resize: vertical;
        min-height: 80px;
        border: 2px solid #dee2e6;
        border-radius: 0.375rem;
        font-family: inherit;
        line-height: 1.5;
    }

    #handicap_formula_desc:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
    }

    /* Divisions Table Styling */
    #divisionsTable {
        margin-bottom: 0;
    }

    #divisionsTable thead th {
        background-color: #e7f3ff;
        border-bottom: 2px solid #007bff;
        font-weight: 600;
        color: #0056b3;
        font-size: 0.85rem;
    }

    #divisionsTable tbody td {
        vertical-align: middle;
        padding: 0.5rem;
    }

    #divisionsTable .form-control-sm {
        border: 1px solid #dee2e6;
        font-size: 0.875rem;
    }

    #divisionsTable .form-control-sm:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
    }

    #divisionsTable .d-flex {
        gap: 0.25rem;
    }

    #divisionsTable .btn-danger {
        padding: 0.375rem 0.5rem;
        font-size: 0.75rem;
    }

    #divisionsTable tbody tr:nth-child(odd) {
        background-color: #f9f9f9;
    }

    #divisionsTable tbody tr:hover {
        background-color: #f0f7ff;
    }

    @media (max-width: 768px) {
        .form-actions-wrapper {
            padding: 1rem;
            margin-left: -1.5rem;
            margin-right: -1.5rem;
            margin-bottom: -1.5rem;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        .form-actions-wrapper .d-flex {
            flex-direction: column;
            gap: 0.75rem !important;
        }

        .form-actions-wrapper .btn {
            width: 100%;
            min-width: auto;
        }

        .form-actions-wrapper .ms-auto {
            flex-direction: column !important;
            width: 100%;
        }

        .form-actions-wrapper #prevBtn {
            order: 1;
        }

        .form-actions-wrapper .ms-auto {
            order: 2;
        }

        .course-scorecard-selection {
            margin-bottom: 1rem;
        }

        .table-sm {
            font-size: 0.8rem;
        }

        .table-sm input[type="number"] {
            height: 28px;
            font-size: 0.8rem;
        }
    }

    @media (max-width: 576px) {
        .form-actions-wrapper {
            padding: 0.75rem 0.5rem;
        }

        .form-actions-wrapper .btn {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }

        .form-actions-wrapper .btn i {
            margin-right: 0.5rem !important;
        }
    }
</style>

<script>
    $(document).ready(function() {
        let currentStep = 1;
        const totalSteps = 8;
        let isSubmitting = false;

        // Initialize stepper
        initializeStepper();

        // Function to initialize stepper
        function initializeStepper() {
            showStep(currentStep);
            updateStepperUI();
        }

        // Function to show specific step
        function showStep(step) {
            // Hide all steps
            $('.form-step').removeClass('active').hide();

            // Show current step
            $(`.form-step[data-step="${step}"]`).addClass('active').show();

            updateStepperUI();
        }

        // Function to update stepper UI
        function updateStepperUI() {
            // Update step circles
            $('.step').each(function() {
                const stepNum = parseInt($(this).attr('data-step'));
                if (stepNum < currentStep) {
                    $(this).removeClass('active').addClass('completed');
                } else if (stepNum === currentStep) {
                    $(this).removeClass('completed').addClass('active');
                } else {
                    $(this).removeClass('active completed');
                }
            });

            // Update button visibility
            if (currentStep === 1) {
                $('#prevBtn').hide();
            } else {
                $('#prevBtn').show();
            }

            if (currentStep === totalSteps) {
                $('#nextBtn').hide();
                $('#submitBtn').show();
            } else {
                $('#nextBtn').show();
                $('#submitBtn').hide();
            }

            // Update summary on review step
            if (currentStep === 6) {
                updateSummary();
            }
        }

        // Function to update summary
        function updateSummary() {
            const name = $('#tournament_name').val() || 'Not provided';
            const startDate = $('#tournament_start').val() || 'Not set';
            const endDate = $('#tournament_end').val() || 'Not set';
            const courses = Array.from(document.querySelectorAll('input[name="course_ids[]"]:checked'))
                .map(cb => cb.closest('.course-option').querySelector('strong').textContent)
                .join(', ') || 'None selected';
            const divisions = $('#divisionsTbody tr').length || 'No divisions';

            $('#summary-name').text(name);
            $('#summary-dates').text(`${startDate} to ${endDate}`);
            $('#summary-courses').text(courses);
            $('#summary-divisions').text(divisions);
        }

        // Next button handler
        $('#nextBtn').on('click', function() {
            if (validateStep(currentStep)) {
                if (currentStep < totalSteps) {
                    currentStep++;
                    showStep(currentStep);
                    $(window).scrollTop(0);
                }
            }
        });

        // Previous button handler
        $('#prevBtn').on('click', function() {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
                $(window).scrollTop(0);
            }
        });

        // Function to validate current step
        function validateStep(step) {
            const form = document.getElementById('mainForm');
            let isValid = true;

            switch (step) {
                case 1: // Basic Information
                    const name = $('#tournament_name').val().trim();
                    const startDate = $('#tournament_start').val();
                    const endDate = $('#tournament_end').val();

                    if (!name) {
                        $('#tournament_name').addClass('is-invalid');
                        isValid = false;
                    } else {
                        $('#tournament_name').removeClass('is-invalid');
                    }

                    if (!startDate) {
                        $('#tournament_start').addClass('is-invalid');
                        isValid = false;
                    } else {
                        $('#tournament_start').removeClass('is-invalid');
                    }

                    if (!endDate) {
                        $('#tournament_end').addClass('is-invalid');
                        isValid = false;
                    } else {
                        $('#tournament_end').removeClass('is-invalid');
                    }

                    if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
                        $('#tournament_end').addClass('is-invalid');
                        isValid = false;
                    }
                    break;

                case 2: // Course Selection
                    if (!validateCourseSelection()) {
                        isValid = false;
                    }
                    break;

                case 3: // Scorecard Configuration
                    if (!validateScorecardSelection()) {
                        isValid = false;
                    }
                    break;

                case 4: // Handicap Configuration
                    // Optional validation - allow to proceed even if not all fields filled
                    isValid = true;
                    break;

                case 5: // Tournament Handicap Formula
                    const formula1 = $('#tournament_handicap_formula_1').val().trim();
                    const formula2 = $('#tournament_handicap_formula_2').val().trim();
                    const formula3 = $('#tournament_handicap_formula_3').val().trim();
                    const formula4 = $('#tournament_handicap_formula_4').val().trim();

                    if (!formula1) {
                        $('#tournament_handicap_formula_1').addClass('is-invalid');
                        isValid = false;
                    } else {
                        $('#tournament_handicap_formula_1').removeClass('is-invalid');
                    }

                    if (!formula2) {
                        $('#tournament_handicap_formula_2').addClass('is-invalid');
                        isValid = false;
                    } else {
                        $('#tournament_handicap_formula_2').removeClass('is-invalid');
                    }

                    if (!formula3) {
                        $('#tournament_handicap_formula_3').addClass('is-invalid');
                        isValid = false;
                    } else {
                        $('#tournament_handicap_formula_3').removeClass('is-invalid');
                    }


                    break;

                case 6: // Course Handicap Configuration
                    const regularFormula = $('#course_handicap_formula_regular').val().trim();
                    const plusFormula = $('#course_handicap_formula_plus').val().trim();

                    if (!regularFormula) {
                        $('#course_handicap_formula_regular').addClass('is-invalid');
                        isValid = false;
                    } else {
                        $('#course_handicap_formula_regular').removeClass('is-invalid');
                    }

                    if (!plusFormula) {
                        $('#course_handicap_formula_plus').addClass('is-invalid');
                        isValid = false;
                    } else {
                        $('#course_handicap_formula_plus').removeClass('is-invalid');
                    }
                    break;

                case 7: // Tournament Divisions
                    // Optional validation
                    isValid = true;
                    break;

                case 8: // Review
                    // Final validation before submit
                    isValid = true;
                    break;
            }

            return isValid;
        }

        // Custom validation for course selection
        function validateCourseSelection() {
            const checkboxes = document.querySelectorAll('input[name="course_ids[]"]');
            const courseContainer = document.querySelector('.course-selection-container');
            const courseError = document.getElementById('course-error');

            const selectedCourses = Array.from(checkboxes).filter(cb => cb.checked);

            if (selectedCourses.length === 0) {
                courseContainer.classList.add('is-invalid');
                courseError.style.display = 'block';
                return false;
            } else {
                courseContainer.classList.remove('is-invalid');
                courseError.style.display = 'none';
                return true;
            }
        }

        // Add event listeners for course checkboxes
        document.querySelectorAll('input[name="course_ids[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const courseOption = this.closest('.course-option');
                if (this.checked) {
                    courseOption.classList.add('selected');
                } else {
                    courseOption.classList.remove('selected');
                }
                validateCourseSelection();
                updateScorecardSelections();
            });
        });

        // Function to update scorecard selections based on selected courses
        function updateScorecardSelections() {
            const selectedCourses = Array.from(document.querySelectorAll('input[name="course_ids[]"]:checked'));
            const scorecardContainer = document.getElementById('scorecard-selections');
            const scorecardInfo = document.getElementById('scorecard-info');

            scorecardContainer.innerHTML = '';

            if (selectedCourses.length === 0) {
                scorecardContainer.style.display = 'none';
                scorecardInfo.style.display = 'block';
                scorecardInfo.innerHTML = '<i class="fas fa-info-circle me-2"></i>Please select courses first to configure scorecards for each course.';
                return;
            }

            scorecardContainer.style.display = 'block';
            scorecardInfo.style.display = 'none';

            const allScorecards = @json(isset($scorecards) ? $scorecards : []);

            selectedCourses.forEach(courseCheckbox => {
                const courseId = courseCheckbox.value;
                const courseName = courseCheckbox.closest('.course-option').querySelector('strong').textContent;

                const matchingScorecards = allScorecards.filter(scorecard =>
                    scorecard.course_id == courseId
                );

                let optionsHtml = '<option value="">Select Scorecard for ' + courseName + '</option>';

                if (matchingScorecards.length > 0) {
                    matchingScorecards.forEach(scorecard => {
                        optionsHtml += `<option value="${scorecard.scorecard_id}">${scorecard.scorecard_code} - ${scorecard.scorecard_name}</option>`;
                    });
                } else {
                    optionsHtml += '<option value="" disabled>No scorecards available for this course</option>';
                }

                const scorecardHtml = `
                    <div class="col-md-6 course-scorecard-selection" data-course-id="${courseId}">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white py-2">
                                <h6 class="mb-0">
                                    <i class="fas fa-golf-ball me-2"></i>
                                    ${courseName}
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="form-floating">
                                    <select class="form-control course-scorecard-select" 
                                            name="course_scorecards[${courseId}]" 
                                            id="scorecard_${courseId}" 
                                            required>
                                        ${optionsHtml}
                                    </select>
                                    <label for="scorecard_${courseId}">Scorecard Template *</label>
                                    <div class="invalid-feedback">
                                        Please select a scorecard for ${courseName}.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                scorecardContainer.insertAdjacentHTML('beforeend', scorecardHtml);
            });

            document.querySelectorAll('.course-scorecard-select').forEach(select => {
                select.addEventListener('change', validateScorecardSelection);
            });
        }

        // Custom validation for scorecard selection
        function validateScorecardSelection() {
            const scorecardSelects = document.querySelectorAll('.course-scorecard-select');
            let isValid = true;

            if (scorecardSelects.length === 0) {
                return true;
            }

            scorecardSelects.forEach(select => {
                if (!select.value) {
                    select.setCustomValidity('Please select a scorecard template');
                    isValid = false;
                } else {
                    select.setCustomValidity('');
                }
            });

            return isValid;
        }

        // Disable/enable form
        function toggleFormState(disabled) {
            const form = document.getElementById('mainForm');
            const submitBtn = document.getElementById('submitBtn');
            const nextBtn = document.getElementById('nextBtn');
            const prevBtn = document.getElementById('prevBtn');
            const cancelBtn = document.getElementById('cancelBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');

            const inputs = form.querySelectorAll('input, select, textarea, button');
            inputs.forEach(input => {
                input.disabled = disabled;
            });

            if (disabled) {
                btnText.classList.add('d-none');
                btnLoading.classList.remove('d-none');
                submitBtn.disabled = true;
            } else {
                btnText.classList.remove('d-none');
                btnLoading.classList.add('d-none');
                submitBtn.disabled = false;
            }
        }

        // AJAX form submission
        $(document).on('submit', '#mainForm', function(e) {
            e.preventDefault();

            if (isSubmitting) {
                return false;
            }

            if (!validateStep(6)) {
                return false;
            }

            const form = this;
            const formData = $('#mainForm').serializeArray();

            isSubmitting = true;
            toggleFormState(true);

            $.ajax({
                url: '{{ route("admin.tournaments.store") }}',
                type: 'POST',
                data: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                timeout: 30000,
                success: function(response) {
                    if (response.success) {
                        showModal('success', 'Success!', 'Tournament created successfully!');
                        form.reset();
                        form.classList.remove('was-validated');
                        $(form).find('.is-valid, .is-invalid').removeClass('is-valid is-invalid');

                        setTimeout(function() {
                            window.location.href = response.redirect || '/admin/tournaments';
                        }, 2000);
                    } else {
                        showModal('error', 'Error', response.message || 'An error occurred while creating the tournament.');
                    }
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        $(form).find('.is-invalid').removeClass('is-invalid');

                        $.each(errors, function(field, messages) {
                            const input = $(`[name="${field}"]`);
                            if (input.length) {
                                input.addClass('is-invalid');
                                const feedback = input.siblings('.invalid-feedback');
                                if (feedback.length && messages[0]) {
                                    feedback.text(messages[0]);
                                }
                            }
                        });

                        showModal('error', 'Validation Error', 'Please check the form for errors.', errors);
                    } else if (status === 'timeout') {
                        showModal('warning', 'Timeout', 'Request timed out. Please try again.');
                    } else {
                        const message = xhr.responseJSON?.message || 'An unexpected error occurred. Please try again.';
                        showModal('error', 'Error', message);
                    }
                },
                complete: function() {
                    isSubmitting = false;
                    toggleFormState(false);
                }
            });
        });

        // Real-time validation
        const inputs = document.querySelectorAll('#mainForm input, #mainForm select, #mainForm textarea');

        inputs.forEach(input => {
            input.addEventListener('blur', () => {
                if (input.checkValidity()) {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                } else {
                    input.classList.remove('is-valid');
                    input.classList.add('is-invalid');
                }
            });

            input.addEventListener('input', () => {
                if (input.classList.contains('was-validated') || input.classList.contains('is-invalid')) {
                    if (input.checkValidity()) {
                        input.classList.remove('is-invalid');
                        input.classList.add('is-valid');
                    } else {
                        input.classList.remove('is-valid');
                        input.classList.add('is-invalid');
                    }
                }
            });
        });

        // Cancel button
        $('#cancelBtn').on('click', function() {
            if (!isSubmitting && confirm('Are you sure you want to cancel? Any unsaved changes will be lost.')) {
                window.location.href = '/admin/tournaments';
            }
        });

        // Add row functions
        window.addScoresConfigRow = function() {
            const tbody = document.querySelector('table tbody');
            const rowCount = tbody.querySelectorAll('tr').length;
            const newRowIndex = 21 + rowCount;

            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>
                    <div class="d-flex gap-1">
                        <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[${newRowIndex}][min]" value="1" placeholder="Min">
                        <span class="text-muted">to</span>
                        <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[${newRowIndex}][max]" value="${newRowIndex}" placeholder="Max">
                    </div>
                </td>
                <td>
                    <div class="d-flex gap-2">
                        <select class="form-control form-control-sm" name="handicap_score_differential_config[${newRowIndex}][method]" required>
                            <option value="LOWEST">Lowest</option>
                            <option value="AVERAGE_OF_LOWEST">Average of Lowest</option>
                            <option value="HIGHEST">Highest</option>
                        </select>
                        <input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[${newRowIndex}][count]" value="1" min="1" placeholder="Count" style="width: 80px;">
                    </div>
                </td>
                <td><input type="number" class="form-control form-control-sm" name="handicap_score_differential_config[${newRowIndex}][adjustment]" value="0" step="0.1"></td>
                <td style="text-align: center;">
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeScoresConfigRow(this)">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            `;

            tbody.appendChild(newRow);
        };

        window.removeScoresConfigRow = function(button) {
            const row = button.closest('tr');
            if (confirm('Are you sure you want to remove this row?')) {
                row.remove();
            }
        };

        // Division Management
        window.addDivisionRow = function() {
            const tbody = document.querySelector('#divisionsTbody');
            const rowCount = tbody.querySelectorAll('tr').length;

            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>
                    <input type="text" class="form-control form-control-sm" name="divisions[${rowCount}][name]" placeholder="e.g., Men's A" required>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" name="divisions[${rowCount}][description]" placeholder="e.g., Handicap 0-5">
                </td>
                <td style="text-align: center;">
                    <select class="form-control form-control-sm" name="divisions[${rowCount}][type]">
                        <option value="regular" selected>Regular</option>
                        <option value="sponsored">Sponsored</option>
                    </select>
                </td>
                <td style="text-align: center;">
                    <select class="form-control form-control-sm" name="divisions[${rowCount}][sex]">
                        <option value="X" selected>All</option>
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                    </select>
                </td>
                <td style="text-align: center;">
                    <select class="form-control form-control-sm" name="divisions[${rowCount}][participant_type]">
                        <option value="mixed" selected>Mixed</option>
                        <option value="member">Member</option>
                        <option value="guest">Guest</option>
                    </select>
                </td>
                <td style="text-align: center;">
                    <div class="d-flex gap-1">
                        <input type="number" class="form-control form-control-sm" name="divisions[${rowCount}][age_min]" placeholder="Min" style="width: 45%;">
                        <span class="text-muted" style="width: 10%; text-align: center;">-</span>
                        <input type="number" class="form-control form-control-sm" name="divisions[${rowCount}][age_max]" placeholder="Max" style="width: 45%;">
                    </div>
                </td>
                <td style="text-align: center;">
                    <div class="d-flex gap-1">
                        <input type="number" class="form-control form-control-sm" name="divisions[${rowCount}][handicap_min]" placeholder="Min" step="0.01" style="width: 45%;">
                        <span class="text-muted" style="width: 10%; text-align: center;">-</span>
                        <input type="number" class="form-control form-control-sm" name="divisions[${rowCount}][handicap_max]" placeholder="Max" step="0.01" style="width: 45%;">
                    </div>
                </td>
                <td style="text-align: center;">
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeDivisionRow(this)">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(newRow);
        };

        window.removeDivisionRow = function(button) {
            const tbody = document.querySelector('#divisionsTbody');
            const rows = tbody.querySelectorAll('tr');

            if (rows.length === 1) {
                alert('You must have at least one division.');
                return;
            }

            if (confirm('Are you sure you want to remove this division?')) {
                button.closest('tr').remove();
            }
        };

        // Test Tournament Handicap Formula Functionality
        if (document.getElementById('testTournamentFormulaBtn')) {
            document.getElementById('testTournamentFormulaBtn').addEventListener('click', function() {
                const localHandicap = document.getElementById('test-tournament-local-handicap').value;
                const whsHandicap = document.getElementById('test-tournament-whs-handicap').value;

                const formulas = {
                    1: document.getElementById('tournament_handicap_formula_1').value,
                    2: document.getElementById('tournament_handicap_formula_2').value,
                    3: document.getElementById('tournament_handicap_formula_3').value,
                    4: document.getElementById('tournament_handicap_formula_4').value
                };

                if (!formulas[1] && !formulas[2] && !formulas[3] && !formulas[4]) {
                    alert('Please enter at least one formula.');
                    return;
                }

                const results = {};
                const resultsDiv = document.getElementById('testTournamentResults');

                // Show loading state
                resultsDiv.innerHTML = '<div class="alert alert-info mb-0"><i class="fas fa-spinner fa-spin"></i> Testing formulas...</div>';
                resultsDiv.style.display = 'block';

                // Test each formula
                let completed = 0;
                for (let i = 1; i <= 4; i++) {
                    if (formulas[i]) {
                        $.ajax({
                            url: BASE_URL + '/api/tournaments/validate-local-handicap-formula',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                formula: formulas[i],
                                local_handicap_index: parseFloat(localHandicap) || 0,
                                whs_handicap_index: parseFloat(whsHandicap) || 0,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                results[i] = response.result !== undefined ? response.result : 'Error';
                                completed++;
                                if (completed === Object.keys(formulas).filter(k => formulas[k]).length) {
                                    displayTournamentTestResults(formulas, results);
                                }
                            },
                            error: function(xhr) {
                                const errorMsg = xhr.responseJSON?.message || 'Error evaluating formula';
                                results[i] = 'Error: ' + errorMsg;
                                completed++;
                                if (completed === Object.keys(formulas).filter(k => formulas[k]).length) {
                                    displayTournamentTestResults(formulas, results);
                                }
                            }
                        });
                    }
                }

                function displayTournamentTestResults(formulas, results) {
                    let resultHTML = '<div class="alert alert-info mb-0"><h6 class="mb-2">Results:</h6>';
                    for (let i = 1; i <= 4; i++) {
                        if (formulas[i]) {
                            const value = typeof results[i] === 'number' ? results[i].toFixed(2) : results[i];
                            resultHTML += `<div class="mb-2"><strong>${i}:</strong> <code>${value}</code></div>`;
                        } else {
                            resultHTML += `<div class="mb-2"><strong>${i}:</strong> <code>Not defined</code></div>`;
                        }
                    }
                    resultHTML += '</div>';
                    resultsDiv.innerHTML = resultHTML;
                }
            });
        }

        // Test Course Handicap Formula Functionality
        if (document.getElementById('testCourseFormulaBtn')) {
            document.getElementById('testCourseFormulaBtn').addEventListener('click', function() {
                const handicapIndex = document.getElementById('test-handicap-index').value;
                const slopeRating = document.getElementById('test-slope-rating').value || 130;
                const courseRating = document.getElementById('test-course-rating').value || 73.5;

                if (!handicapIndex) {
                    alert('Please enter a Handicap Index value.');
                    return;
                }

                const formulas = {
                    regular: document.getElementById('course_handicap_formula_regular').value,
                    plus: document.getElementById('course_handicap_formula_plus').value
                };

                if (!formulas.regular && !formulas.plus) {
                    alert('Please enter at least one formula.');
                    return;
                }

                const results = {};
                const resultsDiv = document.getElementById('testCourseResults');

                // Show loading state
                resultsDiv.innerHTML = '<div class="alert alert-info mb-0"><i class="fas fa-spinner fa-spin"></i> Testing formulas...</div>';
                resultsDiv.style.display = 'block';

                // Test each formula
                let completed = 0;
                for (const [key, formula] of Object.entries(formulas)) {
                    if (formula) {
                        $.ajax({
                            url: BASE_URL + '/api/tournaments/validate-course-handicap-formula',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                formula: formula,
                                handicap_index: parseFloat(handicapIndex) || 0,
                                slope_rating: parseFloat(slopeRating) || 130,
                                course_rating: parseFloat(courseRating) || 73.5,
                                par: 72,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                results[key] = response.result !== undefined ? response.result : 'Error';
                                completed++;
                                if (completed === Object.keys(formulas).filter(k => formulas[k]).length) {
                                    displayCourseTestResults(formulas, results);
                                }
                            },
                            error: function(xhr) {
                                const errorMsg = xhr.responseJSON?.message || 'Error evaluating formula';
                                results[key] = 'Error: ' + errorMsg;
                                completed++;
                                if (completed === Object.keys(formulas).filter(k => formulas[k]).length) {
                                    displayCourseTestResults(formulas, results);
                                }
                            }
                        });
                    }
                }

                function displayCourseTestResults(formulas, results) {
                    let resultHTML = '<div class="alert alert-info mb-0"><h6 class="mb-2">Results:</h6>';
                    if (formulas.regular) {
                        const value = typeof results.regular === 'number' ? results.regular.toFixed(2) : results.regular;
                        resultHTML += `<div class="mb-2"><strong>Regular:</strong> <code>${value}</code></div>`;
                    } else {
                        resultHTML += `<div class="mb-2"><strong>Regular:</strong> <code>Not defined</code></div>`;
                    }
                    if (formulas.plus) {
                        const value = typeof results.plus === 'number' ? results.plus.toFixed(2) : results.plus;
                        resultHTML += `<div class="mb-2"><strong>Plus:</strong> <code>${value}</code></div>`;
                    } else {
                        resultHTML += `<div class="mb-2"><strong>Plus:</strong> <code>Not defined</code></div>`;
                    }
                    resultHTML += '</div>';
                    resultsDiv.innerHTML = resultHTML;
                }
            });
        }

    });
</script>
@endsection