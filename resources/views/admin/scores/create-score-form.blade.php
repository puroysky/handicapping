@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Modern Card Design -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">


                <!-- Compact Card Body -->
                <div class="card-body p-3" style="background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);">
                    <form class="needs-validation" method="POST" action="/admin/scores" novalidate>
                        @csrf
                        <!-- Player Selection Section -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-floating bg-white rounded-2 border border-light shadow-sm">
                                    <input type="text" name="player_search" id="player_search" class="form-control border-0 bg-light" placeholder="Search players..." autocomplete="off">
                                    <label for="player_search" class="fw-semibold text-dark small">
                                        <i class="fas fa-user-golf text-primary me-1"></i>Search Player
                                    </label>
                                    <!-- Player dropdown results -->
                                    <div id="player_dropdown" class="position-absolute w-100 bg-white border border-light rounded-2 shadow-sm mt-1 d-none" style="z-index: 1000; max-height: 200px; overflow-y: auto;">
                                    </div>
                                    <!-- Hidden input for selected player ID -->
                                    <input type="hidden" name="player_id" id="player_id" required>
                                </div>

                                <!-- Selected Player Info Display -->
                                <div id="selected_player_info" class="d-none mt-2">
                                    <div class="d-flex flex-wrap gap-2 align-items-center">
                                        <small class="badge bg-primary">
                                            <i class="fas fa-user me-1"></i>
                                            <span id="player_name_display"></span>
                                        </small>
                                        <small class="badge bg-secondary" id="player_whs_display">
                                            <i class="fas fa-id-card me-1"></i>WHS: -
                                        </small>
                                        <small class="badge bg-info" id="player_account_display">
                                            <i class="fas fa-hashtag me-1"></i>ACC: -
                                        </small>
                                        <small class="badge bg-success" id="player_gender_display">
                                            <i class="fas fa-venus-mars me-1"></i>-
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tournament, Course, Tee, and Score Mode Selection -->
                        <div class="row mb-3">
                            <div class="col-md-3 col-lg-3">
                                <div class="form-floating bg-white rounded-2 border border-light shadow-sm">
                                    <select name="tournament_id" id="tournament_id" class="form-select form-select-sm border-0 bg-light" required>
                                        <option value="">Select Tournament</option>
                                        @foreach($tournaments as $tournament)
                                        <option value="{{ $tournament->tournament_id}}">{{ $tournament->tournament_name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="tournament_id" class="fw-semibold text-dark small">
                                        <i class="fas fa-trophy text-primary me-1"></i>Tournament
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-3 col-lg-3">
                                <div class="form-floating bg-white rounded-2 border border-light shadow-sm">
                                    <select name="course_id" id="course_id" class="form-select form-select-sm border-0 bg-light" required disabled>
                                        <option value="">Select Course</option>
                                    </select>
                                    <label for="course_id" class="fw-semibold text-dark small">
                                        <i class="fas fa-golf-ball text-primary me-1"></i>Course
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-3 col-lg-3">
                                <div class="form-floating bg-white rounded-2 border border-light shadow-sm">
                                    <select name="tee_id" id="tee_id" class="form-select form-select-sm border-0 bg-light" required>

                                    </select>
                                    <label for="tee_id" class="fw-semibold text-dark small">
                                        <i class="fas fa-flag text-primary me-1"></i>Tee
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-3 col-lg-3">
                                <div class="form-floating bg-white rounded-2 border border-light shadow-sm">
                                    <select name="score_mode" id="score_mode" class="form-select form-select-sm border-0 bg-light" required disabled>
                                        <option value="">Select Score Mode</option>
                                        <option value="hole_by_hole">Hole by Hole</option>
                                        <option value="adjusted_score">Adjusted Score</option>
                                    </select>
                                    <label for="score_mode" class="fw-semibold text-dark small">
                                        <i class="fas fa-calculator text-primary me-1"></i>Score Mode
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Compact Scorecard Section -->
                        <div class="bg-white rounded-3 p-2 border border-light shadow-sm mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-primary bg-opacity-10 rounded-2 p-1 me-2">
                                    <i class="fas fa-table text-primary"></i>
                                </div>
                                <h5 class="mb-0 fw-bold text-dark" style="font-size: 1.25rem; font-weight: 700;">Scorecard</h5>
                            </div>

                            <div class="table-responsive border-0 rounded-3 overflow-hidden" style="box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                                <table class="table table-sm align-middle text-center mb-0 score-table">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th class="text-start ps-3 text-white fw-bold py-2" style="font-size: 0.875rem; font-weight: 700;">Hole</th>
                                            @for($i =1; $i <= 18; $i++)
                                                <th class="text-primary fw-bold py-2 column-header text-white" style="font-size: 0.875rem; font-weight: 700;" data-hole="{{ $i }}" data-column="{{ $i }}">{{ $i }}</th>
                                                @if($i == 9)
                                                <th class="text-white fw-bold py-2 bg-success border-start border-2 border-light" style="font-size: 0.875rem; font-weight: 700;">OUT</th>
                                                @endif
                                                @endfor
                                                <th class="text-white fw-bold py-2 bg-success border-start border-2 border-light" style="font-size: 0.875rem; font-weight: 700;">IN</th>
                                                <th class="text-white fw-bold py-2 bg-primary border-start border-2 border-light" style="font-size: 0.875rem; font-weight: 700;">TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody style="background: #ffffff;">


                                        <tr style="background: linear-gradient(90deg, #e9ecef 0%, #f8f9fa 100%);">
                                            <td class="text-start ps-3 fw-bold text-info py-2" style="font-size: 0.875rem; font-weight: 700;">Yards</td>
                                            @for($i = 1; $i <= 18; $i++)
                                                <td class="py-2 column-cell" data-column="{{ $i }}">
                                                <span class="yardage-span" data-hole="{{ $i }}" data-yardage="">-</span>
                                                </td>
                                                @if($i == 9)
                                                <td class="py-2 text-center fw-bold border-start border-2 border-warning">
                                                    <span class="front-yards-total">-</span>
                                                </td>
                                                @endif
                                                @endfor
                                                <td class="py-2 text-center fw-bold border-start border-2 border-warning">
                                                    <span class="back-yards-total">-</span>
                                                </td>
                                                <td class="py-2 text-center fw-bold border-start border-2 border-warning">
                                                    <span class="total-yards-total">-</span>
                                                </td>
                                        </tr>

                                        <tr style="background: linear-gradient(90deg, #f8f9fa 0%, #ffffff 100%);">
                                            <td class="text-start ps-3 fw-bold text-primary py-2" style="font-size: 0.875rem; font-weight: 700;">PAR</td>

                                            @for($i = 1; $i <= 18; $i++)
                                                <td class="py-2 column-cell" data-column="{{ $i }}">
                                                <span class="par-span" data-hole="{{ $i }}" data-par-value="">-</span>
                                                </td>
                                                @if($i == 9)
                                                <td class="py-2 text-center fw-bold border-start border-2 border-warning">
                                                    <span class="front-par-total">-</span>
                                                </td>
                                                @endif
                                                @endfor
                                                <td class="py-2 text-center fw-bold border-start border-2 border-warning">
                                                    <span class="back-par-total">-</span>
                                                </td>
                                                <td class="py-2 text-center fw-bold border-start border-2 border-warning">
                                                    <span class="total-par-total">-</span>
                                                </td>
                                        </tr>

                                        <tr>
                                            <td class="text-start ps-3 fw-bold text-warning py-2" style="font-size: 0.875rem; font-weight: 700;">Handicap</td>
                                            @for($i = 1; $i <= 18; $i++)
                                                <td class="py-2 column-cell" data-column="{{ $i }}">
                                                <span class="handicap-span" data-hole="{{ $i }}" data-handicap="">-</span>
                                                </td>
                                                @if($i == 9)
                                                <td class="py-2 text-center fw-bold border-start border-2 border-warning">
                                                    <span class="front-handicap-total">-</span>
                                                </td>
                                                @endif
                                                @endfor
                                                <td class="py-2 text-center fw-bold border-start border-2 border-warning">
                                                    <span class="back-handicap-total">-</span>
                                                </td>
                                                <td class="py-2 text-center fw-bold border-start border-2 border-warning">
                                                    <span class="total-handicap-total">-</span>
                                                </td>
                                        </tr>

                                        <tr style="background: #ffffff;">
                                            <td class="text-start ps-3 fw-bold text-dark py-2" style="font-size: 0.875rem; font-weight: 700;">Score</td>
                                            @foreach ($scorecard->scorecardHoles as $hole)
                                            <td class="py-2 column-cell" data-column="{{ $hole->hole }}">
                                                <input type="text" name="score[{{ $hole->hole }}]"
                                                    class="form-control form-control-sm text-center score-input border-1"
                                                    placeholder="–"
                                                    aria-label="Score hole {{ $hole->hole }}"
                                                    data-hole="{{ $hole->hole }}"
                                                    style="min-width: 40px; height: 35px; font-size: 1.1rem; border-radius: 6px; transition: all 0.2s ease;">
                                            </td>
                                            @if($hole->hole == 9)
                                            <td class="py-2 text-center fw-bold border-start border-2 border-warning">
                                                <input type="text" class="form-control text-center fw-bold border-0 front-score-total" readonly>
                                            </td>
                                            @endif
                                            @endforeach
                                            <td class="py-2 text-center fw-bold border-start border-2 border-warning">
                                                <input type="text" class="form-control text-center fw-bold border-0 back-score-total" readonly>
                                            </td>
                                            <td class="py-2 text-center fw-bold border-start border-2 border-warning">
                                                <input type="text" class="form-control text-center fw-bold border-0 total-score-total" readonly>
                                            </td>
                                        </tr>

                                        <tr style="background: linear-gradient(90deg, #f1f3f4 0%, #e9ecef 100%);">
                                            <td class="text-start ps-3 fw-bold text-muted py-2 small">

                                            </td>
                                            @for ($i = 1; $i <= 18; $i++)
                                                <td class="py-2 column-cell" data-column="{{ $i }}">
                                                <input type="text"
                                                    class="form-control form-control-sm text-center score-input-display bg-light text-muted border-0"
                                                    data-score-input-display="{{ $i }}"
                                                    placeholder="–"
                                                    aria-label="Computed score hole {{ $i }} (read-only)"
                                                    style="min-width: 40px; height: 28px; cursor: default; font-weight: 600; border-radius: 4px; font-size: 0.9rem;"
                                                    readonly disabled tabindex="-1" aria-disabled="true">
                                                </td>
                                                @if($i == 9)
                                                <td class="py-2 text-center fw-bold border-start border-2 border-warning">
                                                    <input type="text" class="form-control text-center border-0 front-computed-total" readonly>
                                                </td>
                                                @endif
                                                @endfor
                                                <td class="py-2 text-center fw-bold border-start border-2 border-warning">
                                                    <input type="text" class="form-control text-center border-0 back-computed-total" readonly>
                                                </td>
                                                <td class="py-2 text-center fw-bold border-start border-2 border-warning">
                                                    <input type="text" class="form-control text-center border-0 total-computed-total" readonly>
                                                </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Keyboard Shortcuts Help -->
                        <div class="text-center mb-3 keyboard-help">
                            <small class="text-muted d-flex align-items-center justify-content-center gap-3">
                                <span><kbd>Enter</kbd> Next field</span>
                                <span><kbd>←</kbd><kbd>→</kbd> Navigate holes</span>
                                <span><kbd>Ctrl</kbd>+<kbd>S</kbd> Save</span>
                            </small>
                        </div>

                        <!-- Compact Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center gap-2">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-3">
                                <i class="fas fa-arrow-left me-1"></i>Back
                            </button>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-danger rounded-pill px-3" id="clearAllBtn">
                                    <i class="fas fa-trash me-1"></i>Clear All
                                </button>
                                <button type="submit" class="btn rounded-pill px-4 text-white fw-bold" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border: none; box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);" title="Save scorecard (Ctrl+S)" data-bs-toggle="tooltip">
                                    <i class="fas fa-save me-1"></i>Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Recent Scores Section -->
            <div id="recent_scores_section" class="row mt-4 d-none">
                <div class="col-12">
                    <div class="bg-white rounded-3 p-3 border border-light shadow-sm">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-2 p-2 me-3">
                                    <i class="fas fa-history text-primary"></i>
                                </div>
                                <h5 class="mb-0 fw-bold text-dark">Recent Scores</h5>
                            </div>
                            <small class="badge bg-light text-dark" id="scores_count">0 entries</small>
                        </div>
                        <div id="recent_scores_list">
                            <!-- Recent scores will be populated here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Adjusted Total Input Dialog -->
<div class="modal" id="adjustedTotalModal" tabindex="-1" aria-labelledby="adjustedTotalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header golf-primary text-white">
                <h5 class="modal-title" id="adjustedTotalModalLabel">
                    <i class="fas fa-calculator me-2"></i>Adjusted Total Score
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-golf-ball text-primary" style="font-size: 3rem; opacity: 0.7;"></i>
                    <p class="mt-2 text-muted">Enter the adjusted total score for hole-by-hole entry</p>
                </div>
                <div class="form-floating">
                    <input type="number" class="form-control form-control-lg text-center" id="adjustedTotalInput"
                        placeholder="Enter adjusted total" min="18" max="200" step="1">
                    <label for="adjustedTotalInput">Adjusted Total Score</label>
                </div>
                <div class="mt-3">
                    <div class="alert alert-info d-flex align-items-center">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>This will be used as the target total for hole-by-hole score entry</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn golf-primary text-white" id="confirmAdjustedTotal">
                    <i class="fas fa-check me-1"></i>Confirm
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Gross Total Modal -->
<div class="modal" id="grossTotalModal" tabindex="-1" aria-labelledby="grossTotalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="grossTotalModalLabel">
                    <i class="fas fa-golf-ball me-2"></i>Gross Total Score
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-flag text-success" style="font-size: 3rem; opacity: 0.7;"></i>
                    <p class="mt-2 text-muted">Enter the total gross score for this round</p>
                </div>
                <div class="form-floating">
                    <input type="number" class="form-control form-control-lg text-center" id="grossTotalInput"
                        placeholder="Enter gross total" min="30" max="200" step="1">
                    <label for="grossTotalInput">Gross Total Score</label>
                </div>
                <div class="mt-3">
                    <div class="alert alert-success d-flex align-items-center">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>This is the actual total strokes taken for all 18 holes</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-success" id="confirmGrossTotal">
                    <i class="fas fa-check me-1"></i>Save Score
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Enhanced styling for score inputs */
    .score-input {
        background-color: rgba(141, 166, 110, 0.08) !important;
        border-color: #2F4A3C !important;
        color: #2F4A3C !important;
        font-weight: 700 !important;
    }

    .score-input:focus {
        border-color: #8DA66E !important;
        box-shadow: 0 0 0 2px rgba(141, 166, 110, 0.3) !important;
        transform: scale(1.02);
        background-color: rgba(141, 166, 110, 0.12) !important;
    }

    .score-input:valid {
        border-color: #8DA66E !important;
        background-color: rgba(141, 166, 110, 0.1) !important;
    }

    .score-input::placeholder {
        color: rgba(141, 166, 110, 0.6) !important;
        font-weight: 500;
    }

    /* Compact table styling - reduce padding/margin for tbody tr */
    tbody tr {
        padding: 0 !important;
        margin: 0 !important;
    }

    tbody tr td {
        padding: 4px 8px !important;
        margin: 0 !important;
        vertical-align: middle;
    }

    /* Exception for score-input elements - maintain comfortable spacing */
    tbody tr td .score-input {
        padding: 6px 8px !important;
        margin: 2px 0 !important;
        font-size: 1.3rem !important;
    }

    /* Minimum width for OUT, IN, and TOTAL columns to accommodate 2-digit numbers */
    th:contains("OUT"),
    th:contains("IN"),
    th:contains("TOTAL") {
        min-width: 50px !important;
        width: 50px !important;
    }

    /* Target the specific table columns for OUT, IN, TOTAL data cells */
    .table td:nth-last-child(3),
    /* OUT column */
    .table td:nth-last-child(2),
    /* IN column */
    .table td:nth-last-child(1),
    /* TOTAL column */
    .table th:nth-last-child(3),
    /* OUT header */
    .table th:nth-last-child(2),
    /* IN header */
    .table th:nth-last-child(1)

    /* TOTAL header */
        {
        min-width: 50px !important;
        width: 50px !important;
        text-align: center !important;
    }

    /* Ensure input fields in total columns have proper width */
    .table td:nth-last-child(3) input,
    .table td:nth-last-child(2) input,
    .table td:nth-last-child(1) input {
        min-width: 44px !important;
        width: 44px !important;
        text-align: center !important;
    }

    /* Enhanced Total Cards */
    .total-card {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .total-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s ease;
    }

    .total-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
    }

    .total-card:hover::before {
        left: 100%;
    }

    .total-icon {
        transition: all 0.3s ease;
    }

    .total-card:hover .total-icon {
        transform: scale(1.1) rotate(5deg);
    }

    /* Enhanced total input styling */
    .total-card input[type="number"]:focus {
        transform: scale(1.02);
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25) !important;
        border-color: #007bff !important;
    }

    .total-card input[readonly]:focus {
        box-shadow: 0 0 0 3px rgba(123, 31, 162, 0.25) !important;
        border-color: #7b1fa2 !important;
    }

    /* Hover effects for badges */
    .badge:hover {
        transform: scale(1.05);
        transition: all 0.2s ease;
    }

    /* Custom scrollbar for table - Golf Theme */
    .table-responsive::-webkit-scrollbar {
        height: 6px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 6px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #2F4A3C 0%, #5E7C4C 100%);
        border-radius: 6px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #5E7C4C 0%, #8DA66E 100%);
    }

    /* Card animations */
    .card {
        transition: all 0.2s ease;
    }

    .card:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08) !important;
    }

    /* Compact table styling */
    .score-table td,
    .score-table th {
        padding: 0.4rem 0.3rem !important;
    }

    /* Clear button animations */
    #clearScoresBtn:hover,
    #clearAllBtn:hover {
        transform: scale(1.05);
        transition: all 0.2s ease;
    }

    /* Column highlighting - Golf Green Theme */
    .column-highlighted {
        background: linear-gradient(135deg, rgba(141, 166, 110, 0.15) 0%, rgba(94, 124, 76, 0.15) 100%) !important;
        border-left: 3px solid #2F4A3C !important;
        border-right: 3px solid #2F4A3C !important;
        box-shadow: inset 0 0 10px rgba(47, 74, 60, 0.1);
    }

    .column-header.column-highlighted {
        background: linear-gradient(135deg, #2F4A3C 0%, #5E7C4C 100%) !important;
        color: #FFFFFF !important;
        transform: scale(1.05);
        box-shadow: 0 2px 8px rgba(47, 74, 60, 0.4);
        border: 1px solid #8DA66E;
    }

    /* Enhanced alert styling */
    .alert-info {
        border-left: 4px solid #17a2b8;
    }

    /* Total input number spinner removal */
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        appearance: textfield;
        -moz-appearance: textfield;
    }

    /* Pulse animation for computed total when updating */
    @keyframes pulse-update {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }

        100% {
            transform: scale(1);
        }
    }

    .total-updating {
        animation: pulse-update 0.3s ease-in-out;
    }

    /* Skeleton loader for table */
    .skeleton-loader {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 4px;
    }

    .skeleton-row {
        opacity: 0.7;
    }

    .skeleton-cell {
        height: 20px;
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 4px;
        margin: 2px 0;
    }

    .skeleton-yardage {
        height: 16px;
        width: 30px;
        margin: 0 auto;
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 8px;
    }

    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }

        100% {
            background-position: 200% 0;
        }
    }

    /* Ensure yardage spans maintain consistent size */
    .yardage-span {
        min-width: 30px;
        display: inline-block;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
    }

    /* Animation removed - simplified scorecard display */

    @keyframes pulse-success {
        0% {
            background-color: rgba(40, 167, 69, 0.2);
            transform: scale(1);
        }

        50% {
            background-color: rgba(40, 167, 69, 0.4);
            transform: scale(1.05);
        }

        100% {
            background-color: transparent;
            transform: scale(1);
        }
    }

    /* Hidden class for original tbody during loading */
    .tbody-hidden {
        display: none;
    }

    /* Keyboard shortcuts styling */
    kbd {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 3px;
        box-shadow: 0 1px 0 rgba(0, 0, 0, 0.2), inset 0 0 0 2px #fff;
        color: #495057;
        display: inline-block;
        font-family: monospace;
        font-size: 0.75rem;
        font-weight: 600;
        line-height: 1;
        padding: 2px 4px;
        white-space: nowrap;
    }

    .keyboard-help {
        opacity: 0.8;
        transition: opacity 0.2s ease;
    }

    .keyboard-help:hover {
        opacity: 1;
    }

    /* Player search dropdown styling */
    #player_dropdown {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        background: white;
    }

    .player-option:hover {
        background-color: #f8f9fa;
    }

    .player-option:active {
        background-color: #e9ecef;
    }

    .player-option .fas {
        color: #007bff !important;
    }

    /* Player search validation */
    #player_search.is-valid {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    #player_search.is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    /* Simulation data indicator styling */
    .text-warning .fas {
        color: #ffc107 !important;
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }

    /* Golf Theme Colors */
    .golf-primary {
        background: linear-gradient(135deg, #2f4a3c 0%, #1f2f26 100%) !important;
    }

    .golf-secondary {
        background: linear-gradient(135deg, #8da66e 0%, #6d8552 100%) !important;
    }

    .golf-success {
        background: linear-gradient(135deg, #5e7c4c 0%, #4a6139 100%) !important;
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 15px;
    }

    .modal-header {
        border-radius: 15px 15px 0 0;
    }

    #adjustedTotalModal .modal-header {
        background: linear-gradient(135deg, #2f4a3c 0%, #1f2f26 100%);
    }

    #grossTotalModal .modal-header {
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
    }

    #adjustedTotalInput,
    #grossTotalInput {
        font-size: 1.25rem;
        font-weight: 600;
        border: 2px solid #dee2e6;
        transition: all 0.3s ease;
    }

    #adjustedTotalInput:focus {
        border-color: #2f4a3c;
        box-shadow: 0 0 0 0.25rem rgba(47, 74, 60, 0.25);
    }

    #grossTotalInput:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
    }

    #adjustedTotalInput.is-invalid,
    #grossTotalInput.is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
    }

    /* Golf themed button styling */
    .btn.golf-primary {
        background: linear-gradient(135deg, #2f4a3c 0%, #1f2f26 100%);
        border-color: #2f4a3c;
        color: white;
    }

    .btn.golf-primary:hover {
        background: linear-gradient(135deg, #3d5d4d 0%, #2a3d30 100%);
        border-color: #3d5d4d;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(47, 74, 60, 0.3);
    }

    .btn.golf-primary:active {
        background: linear-gradient(135deg, #1f2f26 0%, #0f1813 100%);
        border-color: #1f2f26;
        transform: translateY(0);
    }

    /* Success alert animation */
    .alert.fade.show {
        animation: slideInRight 0.5s ease-out;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form submit event handler
        const form = document.querySelector('form.needs-validation');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent default browser form submission
                submitForm(); // Use our custom submission function
            });
        }

        // Player search functionality
        const playerSearch = document.getElementById('player_search');
        const playerDropdown = document.getElementById('player_dropdown');
        const playerIdInput = document.getElementById('player_id');
        let searchTimeout;

        if (playerSearch) {
            playerSearch.addEventListener('input', function() {
                const searchTerm = this.value.trim();

                // Clear previous timeout
                clearTimeout(searchTimeout);

                // Clear player ID when search changes
                if (playerIdInput.value && this.value !== this.getAttribute('data-selected-name')) {
                    playerIdInput.value = '';
                    this.removeAttribute('data-selected-name');
                    clearSelectedPlayerInfo();
                    window.selectedPlayerGender = null;
                }

                if (searchTerm.length < 2) {
                    playerDropdown.classList.add('d-none');
                    return;
                }

                // Debounce search
                searchTimeout = setTimeout(() => {
                    searchPlayers(searchTerm);
                }, 300);
            });

            // Handle keyboard navigation in search
            playerSearch.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const firstOption = document.querySelector('.player-option');
                    if (firstOption) {
                        firstOption.click();
                    }
                } else if (e.key === 'Escape') {
                    playerDropdown.classList.add('d-none');
                }
            });

            // Hide dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!playerSearch.contains(e.target) && !playerDropdown.contains(e.target)) {
                    playerDropdown.classList.add('d-none');
                }
            });
        }

        function searchPlayers(searchTerm) {
            console.log('Searching players:', searchTerm);

            // Show loading state
            playerDropdown.innerHTML = '<div class="p-2 text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Searching players...</div>';
            playerDropdown.classList.remove('d-none');

            // Call the API directly
            fetch(`${BASE_URL}/admin/players/search?q=${encodeURIComponent(searchTerm)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    displayPlayerResults(data);
                })
                .catch(error => {
                    console.error('API Error:', error);

                    // Show error message instead of fallback data
                    playerDropdown.innerHTML = `
                        <div class="p-2 text-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Error searching players. Please try again.
                        </div>
                    `;
                });
        }

        function displayPlayerResults(data) {
            if (data.success && data.players && data.players.length > 0) {
                let html = '';

                data.players.forEach(player => {
                    const genderIcon = player.gender === 'F' ? 'fas fa-female text-pink' : 'fas fa-male text-blue';
                    const genderLabel = player.gender === 'F' ? 'Female' : 'Male';

                    html += `
                        <div class="dropdown-item p-2 border-bottom cursor-pointer player-option" 
                             data-player-id="${player.id}" 
                             data-player-name="${player.first_name} ${player.last_name}"
                             data-player-gender="${player.gender || 'M'}"
                             data-player-whs="${player.whs_no || ''}"
                             data-player-account="${player.account_no || ''}"
                             style="cursor: pointer;">
                            <div class="d-flex align-items-center">
                                <div class="me-2">
                                    <i class="fas fa-user-circle text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">${player.first_name} ${player.last_name}</div>
                                    <small class="text-muted">${player.email || 'No email'}</small>
                                    ${player.whs_no || player.account_no ? 
                                        `<div class="d-flex gap-1 mt-1">
                                            ${player.whs_no ? `<small class="badge badge-sm bg-secondary">WHS: ${player.whs_no}</small>` : ''}
                                            ${player.account_no ? `<small class="badge badge-sm bg-info">ACC: ${player.account_no}</small>` : ''}
                                        </div>` : ''}
                                </div>
                                <div class="ms-2">
                                    <small class="badge bg-light text-dark">
                                        <i class="${genderIcon} me-1"></i>${genderLabel}
                                    </small>
                                </div>
                            </div>
                        </div>
                    `;
                });

                playerDropdown.innerHTML = html;

                // Add click handlers for player options
                document.querySelectorAll('.player-option').forEach(option => {
                    option.addEventListener('click', function() {
                        const playerId = this.getAttribute('data-player-id');
                        const playerName = this.getAttribute('data-player-name');
                        const playerGender = this.getAttribute('data-player-gender') || 'M';
                        const playerWhs = this.getAttribute('data-player-whs') || '';
                        const playerAccount = this.getAttribute('data-player-account') || '';

                        // Set selected player
                        playerSearch.value = playerName;
                        playerSearch.setAttribute('data-selected-name', playerName);
                        playerIdInput.value = playerId;
                        playerDropdown.classList.add('d-none');

                        // Store selected player's gender
                        window.selectedPlayerGender = playerGender;

                        // Display selected player information
                        displaySelectedPlayerInfo(playerName, playerWhs, playerAccount, playerGender, playerId);

                        // Automatically update handicap display based on player's gender
                        if (window.courseHandicapData) {
                            updateHandicapDisplay(playerGender);

                            // Show feedback about gender-based handicap update
                            showSuccessMessage({
                                title: 'Player Selected',
                                message: `${playerName} selected - showing ${playerGender === 'M' ? 'Male' : 'Female'} handicaps`,
                                icon: 'fas fa-user-check'
                            });
                        } else {
                            // Show feedback that player is selected but handicaps will update when course is selected
                            showSuccessMessage({
                                title: 'Player Selected',
                                message: `${playerName} selected - handicaps will display when course is selected`,
                                icon: 'fas fa-user-check'
                            });

                            console.log(`Player ${playerName} (${playerGender === 'M' ? 'Male' : 'Female'}) selected - waiting for course data to update handicaps`);
                        } // Add visual feedback for selection
                        playerSearch.classList.add('is-valid');

                        console.log('Selected player:', playerName, 'ID:', playerId, 'Gender:', playerGender);
                    });
                });
            } else {
                playerDropdown.innerHTML = '<div class="p-2 text-muted"><i class="fas fa-user-slash me-2"></i>No players found</div>';
            }
        }

        function displaySelectedPlayerInfo(name, whsNo, accountNo, gender, playerId) {
            const selectedPlayerInfo = document.getElementById('selected_player_info');
            const playerNameDisplay = document.getElementById('player_name_display');
            const playerWhsDisplay = document.getElementById('player_whs_display');
            const playerAccountDisplay = document.getElementById('player_account_display');
            const playerGenderDisplay = document.getElementById('player_gender_display');

            // Update display elements
            if (playerNameDisplay) playerNameDisplay.textContent = name;
            if (playerWhsDisplay) {
                playerWhsDisplay.innerHTML = `<i class="fas fa-id-card me-1"></i>WHS: ${whsNo || 'N/A'}`;
            }
            if (playerAccountDisplay) {
                playerAccountDisplay.innerHTML = `<i class="fas fa-hashtag me-1"></i>ACC: ${accountNo || 'N/A'}`;
            }
            if (playerGenderDisplay) {
                const genderIcon = gender === 'F' ? 'fas fa-female' : 'fas fa-male';
                const genderText = gender === 'F' ? 'Female' : 'Male';
                playerGenderDisplay.innerHTML = `<i class="${genderIcon} me-1"></i>${genderText}`;
            }

            // Show the player info display
            if (selectedPlayerInfo) {
                selectedPlayerInfo.classList.remove('d-none');
            }

            // Fetch and display recent scores
            if (playerId) {
                fetchRecentScores(playerId);
            }
        }

        function fetchRecentScores(playerId) {
            const recentScoresSection = document.getElementById('recent_scores_section');
            const recentScoresList = document.getElementById('recent_scores_list');
            const scoresCount = document.getElementById('scores_count');

            // Show loading state
            if (recentScoresList) {
                recentScoresList.innerHTML = '<div class="text-muted"><i class="fas fa-spinner fa-spin me-1"></i>Loading recent scores...</div>';
            }

            if (recentScoresSection) {
                recentScoresSection.classList.remove('d-none');
            }

            // Fetch recent scores from API
            fetch(`${BASE_URL}/admin/players/${playerId}/recent-scores`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    displayRecentScores(data);
                })
                .catch(error => {
                    console.error('Error fetching recent scores:', error);

                    if (recentScoresList) {
                        recentScoresList.innerHTML = '<div class="text-danger small">Error loading recent scores</div>';
                    }
                    if (scoresCount) {
                        scoresCount.textContent = 'Error';
                    }
                });
        }

        function displayRecentScores(data) {
            const recentScoresList = document.getElementById('recent_scores_list');
            const scoresCount = document.getElementById('scores_count');

            if (data.success && data.scores && data.scores.length > 0) {
                let html = '';

                data.scores.forEach(score => {
                    html += `
                        <div class="d-flex justify-content-between align-items-center border-bottom py-1" style="font-size: 0.8rem;">
                            <div class="flex-grow-1">
                                <div class="fw-semibold text-dark">${score.tournament_name}</div>
                                <div class="text-muted">${score.course_name} • ${score.entry_date}</div>
                            </div>
                            <div class="text-end ms-2">
                                <div class="d-flex gap-2 small">
                                    <span class="badge bg-secondary" title="Gross Score">G: ${score.gross_score}</span>
                                    <span class="badge bg-info" title="Adjusted Score">A: ${score.adjusted_score}</span>
                                    <span class="badge bg-success" title="Handicap">H: ${score.handicap}</span>
                                </div>
                            </div>
                        </div>
                    `;
                });

                if (recentScoresList) {
                    recentScoresList.innerHTML = html;
                }

                if (scoresCount) {
                    scoresCount.textContent = `${data.count} recent ${data.count === 1 ? 'entry' : 'entries'}`;
                }
            } else {
                if (recentScoresList) {
                    recentScoresList.innerHTML = '<div class="text-muted small text-center py-2">No recent scores found</div>';
                }
                if (scoresCount) {
                    scoresCount.textContent = '0 entries';
                }
            }
        }

        function clearSelectedPlayerInfo() {
            const selectedPlayerInfo = document.getElementById('selected_player_info');
            const recentScoresSection = document.getElementById('recent_scores_section');

            if (selectedPlayerInfo) {
                selectedPlayerInfo.classList.add('d-none');
            }
            if (recentScoresSection) {
                recentScoresSection.classList.add('d-none');
            }
        }

        // Column highlighting functionality
        const scoreInputs = document.querySelectorAll('.score-input');

        scoreInputs.forEach(input => {
            input.addEventListener('focus', function() {
                // Clear any existing highlights
                clearColumnHighlights();

                // Get the hole number for this input
                const holeNumber = this.getAttribute('data-hole');

                // Highlight the entire column
                highlightColumn(holeNumber);

                // Select all text in the input for easy replacement
                this.select();
            });

            input.addEventListener('blur', function() {
                // Remove highlights when input loses focus
                clearColumnHighlights();
            });
        });

        function highlightColumn(holeNumber) {
            // Highlight header
            const headerCell = document.querySelector(`.column-header[data-column="${holeNumber}"]`);
            if (headerCell) {
                headerCell.classList.add('column-highlighted');
            }

            // Highlight all cells in the column
            const columnCells = document.querySelectorAll(`.column-cell[data-column="${holeNumber}"]`);
            columnCells.forEach(cell => {
                cell.classList.add('column-highlighted');
            });
        }

        function clearColumnHighlights() {
            // Remove all column highlights
            const highlightedElements = document.querySelectorAll('.column-highlighted');
            highlightedElements.forEach(element => {
                element.classList.remove('column-highlighted');
            });
        }



        // Add select-on-focus functionality to all number inputs
        const numberInputs = document.querySelectorAll('input[type="number"]');
        numberInputs.forEach(input => {
            input.addEventListener('focus', function() {
                // Select all text in the input for easy replacement
                this.select();
            });
        });

        // Add enhanced keyboard navigation
        scoreInputs.forEach((input, index) => {
            input.addEventListener('keydown', function(e) {
                // Enter key acts like Tab (move to next field)
                if (e.key === 'Enter') {
                    e.preventDefault();
                    if (index < scoreInputs.length - 1) {
                        scoreInputs[index + 1].focus();
                        scoreInputs[index + 1].select();
                    } else {
                        // If on last hole, focus first input again
                        scoreInputs[0].focus();
                        scoreInputs[0].select();
                    }
                }
                // Arrow key navigation
                else if (e.key === 'ArrowRight' && index < scoreInputs.length - 1) {
                    e.preventDefault();
                    scoreInputs[index + 1].focus();
                    scoreInputs[index + 1].select();
                } else if (e.key === 'ArrowLeft' && index > 0) {
                    e.preventDefault();
                    scoreInputs[index - 1].focus();
                    scoreInputs[index - 1].select();
                }
                // Ctrl+S to submit form
                else if (e.ctrlKey && e.key === 's') {
                    e.preventDefault();
                    submitForm();
                }
            });
        });

        // Global Ctrl+S handler for form submission
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                submitForm();
            }
        });

        // Form submission function
        function submitForm() {
            const form = document.querySelector('form.needs-validation');
            if (!form) {
                console.error('Form not found');
                return;
            }

            // Collect form data first
            const formData = {
                player_id: document.getElementById('player_id')?.value,
                tournament_id: document.getElementById('tournament_id')?.value,
                course_id: document.getElementById('course_id')?.value,
                tee_id: document.getElementById('tee_id')?.value,
                score_mode: document.getElementById('score_mode')?.value,
                _token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                    document.querySelector('input[name="_token"]')?.value
            };

            // Validate required fields with user-friendly names
            const requiredFields = [{
                    field: 'player_id',
                    name: 'Player',
                    element: document.getElementById('player_search')
                },
                {
                    field: 'tournament_id',
                    name: 'Tournament',
                    element: document.getElementById('tournament_id')
                },
                {
                    field: 'course_id',
                    name: 'Course',
                    element: document.getElementById('course_id')
                },
                {
                    field: 'tee_id',
                    name: 'Tee',
                    element: document.getElementById('tee_id')
                },
                {
                    field: 'score_mode',
                    name: 'Score Mode',
                    element: document.getElementById('score_mode')
                }
            ];

            const missingFields = [];
            requiredFields.forEach(({
                field,
                name,
                element
            }) => {
                if (!formData[field] || formData[field].trim() === '') {
                    missingFields.push(name);
                    // Add visual indication for missing field
                    if (element) {
                        element.classList.add('is-invalid');
                        element.focus();
                    }
                } else {
                    // Remove invalid class if field is filled
                    if (element) {
                        element.classList.remove('is-invalid');
                    }
                }
            });

            if (missingFields.length > 0) {
                showNotification(`Please fill in the following required fields: ${missingFields.join(', ')}`, 'error');
                return;
            }

            // Validate that scorecard data is available
            const scorecardHoles = document.querySelectorAll('.score-input');
            if (scorecardHoles.length === 0) {
                showNotification('Scorecard not loaded. Please select a valid course and tee combination.', 'error');
                return;
            }

            // Collect all score inputs with their hole data
            const scores = {};
            const frontNineScores = {};
            const backNineScores = {};
            let hasFrontScores = false;
            let hasBackScores = false;

            scorecardHoles.forEach(input => {
                const holeNumber = parseInt(input.getAttribute('data-hole'));
                const scoreValue = input.value.trim();

                if (scoreValue && scoreValue !== '' && !isNaN(scoreValue)) {
                    const score = parseInt(scoreValue);
                    if (score > 0 && score <= 20) { // Reasonable score range
                        scores[holeNumber] = score;

                        // Categorize holes into front nine (1-9) and back nine (10-18)
                        if (holeNumber >= 1 && holeNumber <= 9) {
                            frontNineScores[holeNumber] = score;
                            hasFrontScores = true;
                        } else if (holeNumber >= 10 && holeNumber <= 18) {
                            backNineScores[holeNumber] = score;
                            hasBackScores = true;
                        }
                    }
                }
            });

            // Validate hole completion based on requirements
            let validationErrors = [];
            const scoreMode = document.getElementById('score_mode')?.value;

            // Check if at least one score is entered OR if adjusted/gross total is available
            const hasAdjustedTotal = window.adjustedTotal && scoreMode === 'adjusted_score';
            const hasGrossTotal = window.grossTotal;

            if (!hasFrontScores && !hasBackScores && !hasAdjustedTotal && !hasGrossTotal) {
                if (scoreMode === 'adjusted_score') {
                    showNotification('Please enter an adjusted total score or hole-by-hole scores before submitting.', 'error');
                } else {
                    showNotification('Please enter at least one valid score (1-20) before submitting.', 'error');
                }
                const firstScoreInput = document.querySelector('.score-input');
                if (firstScoreInput) {
                    firstScoreInput.focus();
                }
                return;
            }

            // Skip hole validation if using adjusted score mode with total
            if (!(hasAdjustedTotal || hasGrossTotal)) {

                // Validate front nine: if any front nine score is entered, all front nine holes must be completed
                if (hasFrontScores) {
                    const missingFrontHoles = [];
                    for (let hole = 1; hole <= 9; hole++) {
                        if (!frontNineScores[hole]) {
                            missingFrontHoles.push(hole);
                        }
                    }
                    if (missingFrontHoles.length > 0) {
                        validationErrors.push(`Front nine incomplete. Please enter scores for holes: ${missingFrontHoles.join(', ')}`);
                    }
                }

                // Validate back nine: if any back nine score is entered, all back nine holes must be completed
                if (hasBackScores) {
                    const missingBackHoles = [];
                    for (let hole = 10; hole <= 18; hole++) {
                        if (!backNineScores[hole]) {
                            missingBackHoles.push(hole);
                        }
                    }
                    if (missingBackHoles.length > 0) {
                        validationErrors.push(`Back nine incomplete. Please enter scores for holes: ${missingBackHoles.join(', ')}`);
                    }
                }

                // Show validation errors if any
                if (validationErrors.length > 0) {
                    showNotification(validationErrors.join('<br>'), 'error');

                    // Focus on first missing hole input
                    const allMissingHoles = [];
                    if (hasFrontScores) {
                        for (let hole = 1; hole <= 9; hole++) {
                            if (!frontNineScores[hole]) allMissingHoles.push(hole);
                        }
                    }
                    if (hasBackScores) {
                        for (let hole = 10; hole <= 18; hole++) {
                            if (!backNineScores[hole]) allMissingHoles.push(hole);
                        }
                    }

                    if (allMissingHoles.length > 0) {
                        const firstMissingInput = document.querySelector(`[data-hole="${allMissingHoles[0]}"]`);
                        if (firstMissingInput) {
                            firstMissingInput.focus();
                            firstMissingInput.classList.add('is-invalid');
                        }
                    }
                    return;
                }
            } // Close the hole validation skip section

            // Add scores to form data
            formData.scores = scores;

            // Add adjusted or gross totals if available
            if (window.adjustedTotal) {
                formData.adjusted_total = window.adjustedTotal;
            }
            if (window.grossTotal) {
                formData.gross_total = window.grossTotal;
            }

            // Trigger form validation
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                showNotification('Please check all form fields for errors.', 'error');
                return;
            }

            // Show submission feedback
            const submitBtn = document.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';
                submitBtn.disabled = true;

                // Submit to backend
                console.log('Submitting form data to /admin/scores:', formData);

                fetch('/admin/scores', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': formData._token,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            return response.text().then(text => {
                                throw new Error(`HTTP ${response.status}: ${text}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Success feedback
                            submitBtn.innerHTML = '<i class="fas fa-check me-1"></i>Saved!';
                            submitBtn.classList.remove('btn-primary');
                            submitBtn.classList.add('btn-success');

                            // Show success message
                            showNotification('Score saved successfully!', 'success');

                            // Reset form after delay
                            setTimeout(() => {
                                if (confirm('Score saved successfully! Would you like to enter another score?')) {
                                    resetForm();
                                } else {
                                    window.location.href = '/admin/scores';
                                }
                            }, 1500);
                        } else {
                            throw new Error(data.message || 'Failed to save score');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('btn-success');
                        submitBtn.classList.add('btn-primary');

                        // Show error message
                        showNotification(`Error saving score: ${error.message}`, 'error');
                    });
            }

            console.log('Submitting form data:', formData);
        }

        // Helper function to show notifications
        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `alert alert-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info'} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            notification.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(notification);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }

        // Helper function to reset form
        function resetForm() {
            // Reset all score inputs
            document.querySelectorAll('.score-input').forEach(input => {
                input.value = '';
            });

            // Reset computed totals
            document.querySelectorAll('.front-score-total, .back-score-total, .total-score-total').forEach(input => {
                input.value = '';
            });

            // Reset display inputs
            document.querySelectorAll('.score-input-display').forEach(input => {
                input.value = '';
            });

            // Reset selects (except tournament as it might be reused)
            document.getElementById('course_id').selectedIndex = 0;
            document.getElementById('tee_id').selectedIndex = 0;
            document.getElementById('score_mode').selectedIndex = 0;

            // Reset button
            const submitBtn = document.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Save Score';
                submitBtn.disabled = false;
                submitBtn.classList.remove('btn-success');
                submitBtn.classList.add('btn-primary');
            }

            // Focus first input
            const firstInput = document.querySelector('.score-input');
            if (firstInput) {
                firstInput.focus();
            }
        }

        // Enhanced keyboard navigation for dropdown selects
        const selectElements = [
            document.getElementById('tournament_id'),
            document.getElementById('course_id'),
            document.getElementById('tee_id')
        ].filter(el => el !== null);

        selectElements.forEach((select, index) => {
            select.addEventListener('keydown', function(e) {
                // Enter key moves to next select or first score input
                if (e.key === 'Enter') {
                    e.preventDefault();
                    if (index < selectElements.length - 1) {
                        selectElements[index + 1].focus();
                    } else {
                        // Move to first score input after last select
                        const firstScoreInput = document.querySelector('.score-input');
                        if (firstScoreInput) {
                            firstScoreInput.focus();
                            firstScoreInput.select();
                        }
                    }
                }
                // Ctrl+S to submit form
                else if (e.ctrlKey && e.key === 's') {
                    e.preventDefault();
                    submitForm();
                }
            });
        });

        // Clear individual scores button
        const clearScoresBtn = document.getElementById('clearScoresBtn');
        if (clearScoresBtn) {
            clearScoresBtn.addEventListener('click', function() {
                // Clear only score inputs, keep computed values
                document.querySelectorAll('.score-input').forEach(input => {
                    input.value = '';
                    input.classList.remove('is-valid', 'is-invalid');
                });

                // Clear any column highlights
                clearColumnHighlights();

                // Focus first input
                const firstInput = document.querySelector('.score-input');
                if (firstInput) firstInput.focus();

                // Show success feedback
                clearScoresBtn.innerHTML = '<i class="fas fa-check me-1"></i>Cleared';
                clearScoresBtn.classList.remove('btn-outline-light');
                clearScoresBtn.classList.add('btn-success');

                setTimeout(() => {
                    clearScoresBtn.innerHTML = '<i class="fas fa-eraser me-1"></i>Clear';
                    clearScoresBtn.classList.remove('btn-success');
                    clearScoresBtn.classList.add('btn-outline-light');
                }, 1000);
            });
        }

        // Clear all button
        const clearAllBtn = document.getElementById('clearAllBtn');
        if (clearAllBtn) {
            clearAllBtn.addEventListener('click', function() {
                if (confirm('Clear all scores and computed values? This cannot be undone.')) {
                    // Clear all inputs
                    document.querySelectorAll('.score-input, .score-input-display').forEach(input => {
                        input.value = '';
                        input.classList.remove('is-valid', 'is-invalid');
                    });

                    // Clear any column highlights
                    clearColumnHighlights();

                    // Focus first input
                    const firstInput = document.querySelector('.score-input');
                    if (firstInput) firstInput.focus();

                    // Show success feedback
                    clearAllBtn.innerHTML = '<i class="fas fa-check me-1"></i>Cleared';
                    clearAllBtn.classList.remove('btn-outline-danger');
                    clearAllBtn.classList.add('btn-success');

                    setTimeout(() => {
                        clearAllBtn.innerHTML = '<i class="fas fa-trash me-1"></i>Clear All';
                        clearAllBtn.classList.remove('btn-success');
                        clearAllBtn.classList.add('btn-outline-danger');
                    }, 1000);
                }
            });

            // Tournament-Course dynamic loading handlers
            console.log('Setting up tournament/course/tee handlers');

            const tournamentSelect = document.getElementById('tournament_id');
            const courseSelect = document.getElementById('course_id');
            const teeSelect = document.getElementById('tee_id');

            if (tournamentSelect && courseSelect && teeSelect) {

                tournamentSelect.addEventListener('change', function() {
                    console.log('Tournament changed:', this.value);
                    const tournamentId = this.value;

                    // Clear and reset course selection
                    courseSelect.innerHTML = '<option value="">Select Course</option>';
                    courseSelect.value = '';
                    courseSelect.disabled = true;

                    // Clear and reset tee selection
                    teeSelect.innerHTML = '<option value="">Select Tee</option>';
                    teeSelect.value = '';
                    teeSelect.disabled = true;

                    // Disable score mode when tee is cleared (tournament change)
                    const scoreModeSelect = document.getElementById('score_mode');
                    if (scoreModeSelect) {
                        scoreModeSelect.disabled = true;
                        scoreModeSelect.value = '';
                        console.log('Score mode disabled - tournament changed');
                    }

                    if (tournamentId) {
                        // Enable course select and show loading state
                        courseSelect.disabled = false;
                        courseSelect.innerHTML = '<option value="">Loading courses...</option>';

                        // Fetch courses from API
                        console.log('Fetching courses for tournament:', tournamentId);

                        fetch(`${BASE_URL}/admin/tournaments/${tournamentId}/courses`)
                            .then(response => {
                                console.log('API Response status:', response.status);
                                if (!response.ok) {
                                    throw new Error(`HTTP error! status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(data => {
                                console.log('API Response data:', data);
                                courseSelect.innerHTML = '<option value="">Select Course</option>';

                                if (data.success && data.courses && data.courses.length > 0) {
                                    data.courses.forEach(courseData => {
                                        const option = document.createElement('option');
                                        option.value = courseData.tournament_course_id;
                                        option.textContent = courseData.course.course_name;
                                        courseSelect.appendChild(option);
                                        console.log('Added course option:', courseData.course.course_name, 'with value:', courseData.tournament_course_id);
                                    });
                                    console.log(`Loaded ${data.courses.length} courses from API`);
                                    courseSelect.disabled = false; // Ensure it's enabled
                                } else {
                                    courseSelect.innerHTML = '<option value="">No courses available</option>';
                                    courseSelect.disabled = true;
                                    console.log('No courses found in API response');
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching courses from API:', error);

                                // Fallback to static data if API fails
                                console.log('Using fallback courses due to API error');
                                courseSelect.innerHTML = '<option value="">Select Course</option>';
                                courseSelect.disabled = false; // Enable for fallback options

                                const fallbackCourses = [{
                                        course_id: 1,
                                        course_name: 'North Course'
                                    },
                                    {
                                        course_id: 2,
                                        course_name: 'South Course'
                                    },
                                    {
                                        course_id: 3,
                                        course_name: 'East Course'
                                    },
                                    {
                                        course_id: 4,
                                        course_name: 'West Course'
                                    }
                                ];

                                fallbackCourses.forEach(course => {
                                    const option = document.createElement('option');
                                    option.value = course.course_id;
                                    option.textContent = course.course_name;
                                    courseSelect.appendChild(option);
                                });
                                console.log('Fallback courses loaded after API error');
                            });
                    }
                });

                // Course-Tee dynamic loading
                courseSelect.addEventListener('change', function() {
                    console.log('Course changed:', this.value);
                    const courseId = this.value;

                    // Clear and reset tee selection
                    teeSelect.innerHTML = '<option value="">Select Tee</option>';
                    teeSelect.value = '';
                    teeSelect.disabled = true;

                    // Disable score mode when tee is cleared (course change)
                    const scoreModeSelect = document.getElementById('score_mode');
                    if (scoreModeSelect) {
                        scoreModeSelect.disabled = true;
                        scoreModeSelect.value = '';
                        console.log('Score mode disabled - course changed');
                    }

                    // Clear scorecard values when course changes
                    clearScorecardValues();

                    if (courseId) {
                        // Enable tee select and show loading state
                        teeSelect.disabled = false;
                        teeSelect.innerHTML = '<option value="">Loading tees...</option>';

                        // Fetch tees from API
                        console.log('Fetching tees for course:', courseId);
                        fetch(`${BASE_URL}/admin/courses/${courseId}/tees`)
                            .then(response => {
                                console.log('Tees API Response status:', response.status);
                                if (!response.ok) {
                                    throw new Error(`HTTP error! status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(data => {
                                console.log('Tees API Response data:', data);
                                teeSelect.innerHTML = '<option value="">Select Tee</option>';

                                if (data.success) {
                                    if (data.tees && data.tees.length > 0) {
                                        data.tees.forEach(tee => {
                                            const option = document.createElement('option');
                                            option.value = tee.tee_id;
                                            option.textContent = `${tee.tee_code} (${tee.tee_name})`;
                                            teeSelect.appendChild(option);
                                        });
                                        console.log(`Loaded ${data.tees.length} tees from API`);
                                    } else {
                                        teeSelect.innerHTML = '<option value="">No tees available</option>';
                                        teeSelect.disabled = true;
                                        console.log('No tees found in API response');
                                    }




                                    if (data.holes && data.holes.length > 0) {
                                        console.log(`Course has ${data.holes.length} holes as per API response`);



                                        // Store handicap data for both genders
                                        const handicapData = {};

                                        data.holes.forEach(holeData => {
                                            const hole = holeData.hole;
                                            const par = holeData.par;

                                            // Store handicap data by gender
                                            if (holeData.handicap_hole) {
                                                const gender = holeData.handicap_hole.gender || 'M'; // Default to Male
                                                const handicapValue = holeData.handicap_hole.handicap_hole || '-';

                                                if (!handicapData[hole]) {
                                                    handicapData[hole] = {};
                                                }
                                                handicapData[hole][gender] = handicapValue;
                                            }

                                            // Find the par span for this hole
                                            const parSpan = document.querySelector(`.par-span[data-hole="${hole}"]`);
                                            if (parSpan) {
                                                parSpan.textContent = par;
                                                parSpan.setAttribute('data-par', par);
                                                console.log(`Updated hole ${hole} par to ${par}`);
                                            }
                                        });

                                        // Store handicap data globally for gender switching
                                        window.courseHandicapData = handicapData;

                                        // Update handicap display based on selected player's gender or default to Male
                                        const currentGender = window.selectedPlayerGender || 'M';
                                        updateHandicapDisplay(currentGender);

                                        // Show feedback if player is selected
                                        if (window.selectedPlayerGender) {
                                            console.log(`Handicap display updated for ${currentGender === 'M' ? 'Male' : 'Female'} player after course change`);
                                        }

                                        // Calculate and update PAR totals
                                        let frontPar = 0,
                                            backPar = 0,
                                            totalPar = 0;
                                        let frontHandicap = 0,
                                            backHandicap = 0,
                                            totalHandicap = 0;

                                        data.holes.forEach(holeData => {
                                            const par = parseInt(holeData.par) || 0;
                                            // Use current gender selection for handicap calculation
                                            const currentGender = getCurrentGenderSelection();
                                            const handicap = (handicapData[holeData.hole] && handicapData[holeData.hole][currentGender]) ?
                                                parseInt(handicapData[holeData.hole][currentGender]) || 0 :
                                                0;

                                            if (holeData.hole <= 9) {
                                                frontPar += par;
                                                frontHandicap += handicap;
                                            } else {
                                                backPar += par;
                                                backHandicap += handicap;
                                            }
                                            totalPar += par;
                                            totalHandicap += handicap;
                                        });

                                        // Update PAR total displays
                                        const frontParTotal = document.querySelector('.front-par-total');
                                        const backParTotal = document.querySelector('.back-par-total');
                                        const totalParTotal = document.querySelector('.total-par-total');

                                        if (frontParTotal) frontParTotal.textContent = frontPar;
                                        if (backParTotal) backParTotal.textContent = backPar;
                                        if (totalParTotal) totalParTotal.textContent = totalPar;

                                        // Update Handicap total displays
                                        const frontHandicapTotal = document.querySelector('.front-handicap-total');
                                        const backHandicapTotal = document.querySelector('.back-handicap-total');
                                        const totalHandicapTotal = document.querySelector('.total-handicap-total');

                                        if (frontHandicapTotal) frontHandicapTotal.textContent = frontHandicap > 0 ? frontHandicap : '-';
                                        if (backHandicapTotal) backHandicapTotal.textContent = backHandicap > 0 ? backHandicap : '-';
                                        if (totalHandicapTotal) totalHandicapTotal.textContent = totalHandicap > 0 ? totalHandicap : '-';

                                        console.log(`Updated PAR totals - Front: ${frontPar}, Back: ${backPar}, Total: ${totalPar}`);
                                        console.log(`Updated Handicap totals - Front: ${frontHandicap}, Back: ${backHandicap}, Total: ${totalHandicap}`);

                                        // Recalculate score totals after pars and handicaps are updated
                                        calculateTotals();



                                    } else {
                                        console.log('No holes data found in tees API response');

                                        console.log('No pars found in API response');
                                        window.showErrorModal && window.showErrorModal({
                                            message: 'No pars found for the selected course.',
                                            details: 'Please check the course selection or try again later.',
                                            primaryText: 'OK'
                                        });

                                    }






                                } else {
                                    teeSelect.innerHTML = '<option value="">No tees available</option>';
                                    teeSelect.disabled = true;
                                    console.log('No tees found in API response');
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching tees from API:', error);

                                // Fallback to static data if API fails
                                console.log('Using fallback tees due to API error');
                                teeSelect.innerHTML = '<option value="">Select Tee</option>';
                                const fallbackTees = [{
                                        tee_id: 1,
                                        tee_name: 'Championship Tees',
                                        tee_color: 'Black'
                                    },
                                    {
                                        tee_id: 2,
                                        tee_name: 'Tournament Tees',
                                        tee_color: 'Blue'
                                    },
                                    {
                                        tee_id: 3,
                                        tee_name: 'Regular Tees',
                                        tee_color: 'White'
                                    },
                                    {
                                        tee_id: 4,
                                        tee_name: 'Forward Tees',
                                        tee_color: 'Red'
                                    },
                                    {
                                        tee_id: 5,
                                        tee_name: 'Senior Tees',
                                        tee_color: 'Gold'
                                    }
                                ];

                                fallbackTees.forEach(tee => {
                                    const option = document.createElement('option');
                                    option.value = tee.tee_id;
                                    option.textContent = `${tee.tee_name} (${tee.tee_color})`;
                                    teeSelect.appendChild(option);
                                });
                                console.log('Fallback tees loaded after API error');
                            });
                    }
                });

                // Tee-Yardages dynamic loading with skeleton loader
                teeSelect.addEventListener('change', function() {
                    console.log('Tee changed:', this.value);
                    const teeId = this.value;
                    const scoreModeSelect = document.getElementById('score_mode');

                    if (teeId) {
                        // Enable score mode selection when tee is selected
                        if (scoreModeSelect) {
                            scoreModeSelect.disabled = false;
                            scoreModeSelect.value = 'hole_by_hole'; // Set default selection
                            console.log('Score mode enabled - default set to hole_by_hole');
                        }
                        console.log('Fetching yardages for tee:', teeId);

                        // Show skeleton loader by replacing tbody content
                        showSkeletonLoader();

                        // Fetch yardages from API
                        fetch(`${BASE_URL}/admin/tees/${teeId}/yardages`)
                            .then(response => {
                                console.log('Yardages API Response status:', response.status);
                                if (!response.ok) {
                                    throw new Error(`HTTP error! status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(data => {
                                console.log('Yardages API Response data:', data);

                                // Hide skeleton and show original content
                                hideSkeletonLoader();

                                if (data.success && data.yardages && data.yardages.length > 0) {
                                    // Update yardages in the scorecard table
                                    data.yardages.forEach(yardageData => {
                                        const hole = yardageData.hole;
                                        const yardage = yardageData.yardage;

                                        // Find the yardage span for this hole
                                        const yardageSpan = document.querySelector(`.yardage-span[data-hole="${hole}"]`);
                                        if (yardageSpan) {
                                            yardageSpan.textContent = yardage.toLocaleString();
                                            yardageSpan.setAttribute('data-yardage', yardage);
                                            console.log(`Updated hole ${hole} yardage to ${yardage}`);
                                        }
                                    });

                                    console.log(`Updated ${data.yardages.length} hole yardages from API`);

                                    // Recalculate totals after yardages are updated
                                    calculateTotals();
                                } else {
                                    console.log('No yardages found in API response');
                                    window.showErrorModal && window.showErrorModal({
                                        message: 'No yardages found for the selected tee.',
                                        details: 'Please check the tee selection or try again later.',
                                        primaryText: 'OK'
                                    });

                                    // Reset yardages to default if no data
                                    const yardageSpans = document.querySelectorAll('.yardage-span');
                                    yardageSpans.forEach(span => {
                                        span.textContent = '-';
                                        span.classList.add('text-primary');
                                        span.setAttribute('data-yardage', '-');
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching yardages from API:', error);

                                // Hide skeleton and show original content
                                hideSkeletonLoader();

                                // Use fallback yardages if API fails
                                console.log('Using fallback yardages due to API error');
                                const fallbackYardages = [{
                                        hole: 1,
                                        yardage: 380
                                    }, {
                                        hole: 2,
                                        yardage: 425
                                    }, {
                                        hole: 3,
                                        yardage: 190
                                    },
                                    {
                                        hole: 4,
                                        yardage: 510
                                    }, {
                                        hole: 5,
                                        yardage: 340
                                    }, {
                                        hole: 6,
                                        yardage: 415
                                    },
                                    {
                                        hole: 7,
                                        yardage: 175
                                    }, {
                                        hole: 8,
                                        yardage: 395
                                    }, {
                                        hole: 9,
                                        yardage: 450
                                    },
                                    {
                                        hole: 10,
                                        yardage: 360
                                    }, {
                                        hole: 11,
                                        yardage: 480
                                    }, {
                                        hole: 12,
                                        yardage: 205
                                    },
                                    {
                                        hole: 13,
                                        yardage: 385
                                    }, {
                                        hole: 14,
                                        yardage: 420
                                    }, {
                                        hole: 15,
                                        yardage: 165
                                    },
                                    {
                                        hole: 16,
                                        yardage: 375
                                    }, {
                                        hole: 17,
                                        yardage: 440
                                    }, {
                                        hole: 18,
                                        yardage: 525
                                    }
                                ];

                                fallbackYardages.forEach(yardageData => {
                                    const hole = yardageData.hole;
                                    const yardage = yardageData.yardage;

                                    const yardageSpan = document.querySelector(`.yardage-span[data-hole="${hole}"]`);
                                    if (yardageSpan) {
                                        yardageSpan.textContent = yardage.toLocaleString();
                                        yardageSpan.classList.add('text-primary');
                                        yardageSpan.setAttribute('data-yardage', yardage);
                                    }
                                });

                                console.log('Fallback yardages loaded after API error');

                                // Recalculate totals after fallback yardages are loaded
                                calculateTotals();
                            });
                    } else {
                        // Clear yardages when no tee is selected
                        hideSkeletonLoader();

                        // Disable score mode when no tee is selected
                        const scoreModeSelect = document.getElementById('score_mode');
                        if (scoreModeSelect) {
                            scoreModeSelect.disabled = true;
                            scoreModeSelect.value = '';
                            console.log('Score mode disabled - no tee selected');
                        }

                        const yardageSpans = document.querySelectorAll('.yardage-span');
                        yardageSpans.forEach(span => {
                            span.textContent = '-';
                            span.classList.remove('text-muted');
                            span.classList.add('text-primary');
                            span.setAttribute('data-yardage', '-');
                        });
                    }
                });

                // Skeleton loader functions
                function showSkeletonLoader() {
                    const table = document.querySelector('.score-table');
                    const originalTbody = table.querySelector('tbody');

                    // Hide original tbody
                    originalTbody.classList.add('tbody-hidden');

                    // Create skeleton tbody
                    const skeletonTbody = document.createElement('tbody');
                    skeletonTbody.id = 'skeleton-tbody';
                    skeletonTbody.style.background = '#ffffff';

                    // Create skeleton rows
                    const skeletonRows = [{
                            label: 'PAR',
                            style: 'background: linear-gradient(90deg, #f8f9fa 0%, #ffffff 100%);'
                        },
                        {
                            label: 'Yards',
                            style: 'background: linear-gradient(90deg, #e9ecef 0%, #f8f9fa 100%);'
                        },
                        {
                            label: 'Score',
                            style: 'background: #ffffff;'
                        },
                        {
                            label: '',
                            style: 'background: linear-gradient(90deg, #f1f3f4 0%, #e9ecef 100%);'
                        }
                    ];

                    skeletonRows.forEach(rowData => {
                        const row = document.createElement('tr');
                        row.style.cssText = rowData.style;
                        row.classList.add('skeleton-row');

                        // First cell with label
                        const labelCell = document.createElement('td');
                        labelCell.className = 'text-start ps-3 fw-bold text-primary py-2 small';
                        labelCell.textContent = rowData.label;
                        row.appendChild(labelCell);

                        // 18 skeleton cells for holes
                        for (let i = 1; i <= 18; i++) {
                            const cell = document.createElement('td');
                            cell.className = 'py-2 column-cell';

                            if (rowData.label === 'Yards') {
                                // Special skeleton for yardage cells
                                const skeletonDiv = document.createElement('div');
                                skeletonDiv.className = 'skeleton-yardage';
                                cell.appendChild(skeletonDiv);
                            } else if (rowData.label === 'Score') {
                                // Skeleton for score input
                                const skeletonDiv = document.createElement('div');
                                skeletonDiv.className = 'skeleton-cell';
                                skeletonDiv.style.width = '40px';
                                skeletonDiv.style.height = '35px';
                                skeletonDiv.style.margin = '0 auto';
                                cell.appendChild(skeletonDiv);
                            } else if (rowData.label === '') {
                                // Skeleton for computed score
                                const skeletonDiv = document.createElement('div');
                                skeletonDiv.className = 'skeleton-cell';
                                skeletonDiv.style.width = '40px';
                                skeletonDiv.style.height = '28px';
                                skeletonDiv.style.margin = '0 auto';
                                cell.appendChild(skeletonDiv);
                            } else {
                                // Skeleton for PAR
                                const skeletonDiv = document.createElement('div');
                                skeletonDiv.className = 'skeleton-cell';
                                skeletonDiv.style.width = '20px';
                                skeletonDiv.style.height = '20px';
                                skeletonDiv.style.margin = '0 auto';
                                skeletonDiv.style.borderRadius = '50%';
                                cell.appendChild(skeletonDiv);
                            }

                            row.appendChild(cell);

                            // Add front nine total after hole 9
                            if (i === 9) {
                                const totalCell = document.createElement('td');
                                totalCell.className = 'py-2 fw-bold border-start border-2 border-light';
                                const totalSkeleton = document.createElement('div');
                                totalSkeleton.className = 'skeleton-cell';
                                totalSkeleton.style.width = '40px';
                                totalSkeleton.style.height = rowData.label === 'Score' ? '35px' : (rowData.label === '' ? '28px' : '20px');
                                totalSkeleton.style.margin = '0 auto';
                                if (rowData.label === 'PAR') totalSkeleton.style.borderRadius = '50%';
                                totalCell.appendChild(totalSkeleton);
                                row.appendChild(totalCell);
                            }
                        }

                        // Add back nine total
                        const backTotalCell = document.createElement('td');
                        backTotalCell.className = 'py-2 fw-bold border-start border-2 border-light';
                        const backSkeleton = document.createElement('div');
                        backSkeleton.className = 'skeleton-cell';
                        backSkeleton.style.width = '40px';
                        backSkeleton.style.height = rowData.label === 'Score' ? '35px' : (rowData.label === '' ? '28px' : '20px');
                        backSkeleton.style.margin = '0 auto';
                        if (rowData.label === 'PAR') backSkeleton.style.borderRadius = '50%';
                        backTotalCell.appendChild(backSkeleton);
                        row.appendChild(backTotalCell);

                        // Add grand total
                        const grandTotalCell = document.createElement('td');
                        grandTotalCell.className = 'py-2 fw-bold border-start border-2 border-light';
                        const grandSkeleton = document.createElement('div');
                        grandSkeleton.className = 'skeleton-cell';
                        grandSkeleton.style.width = '40px';
                        grandSkeleton.style.height = rowData.label === 'Score' ? '35px' : (rowData.label === '' ? '28px' : '20px');
                        grandSkeleton.style.margin = '0 auto';
                        if (rowData.label === 'PAR') grandSkeleton.style.borderRadius = '50%';
                        grandTotalCell.appendChild(grandSkeleton);
                        row.appendChild(grandTotalCell);
                        skeletonTbody.appendChild(row);
                    });

                    // Insert skeleton tbody after original
                    originalTbody.parentNode.insertBefore(skeletonTbody, originalTbody.nextSibling);
                }

                function hideSkeletonLoader() {
                    const originalTbody = document.querySelector('.score-table tbody:not(#skeleton-tbody)');
                    const skeletonTbody = document.getElementById('skeleton-tbody');

                    // Show original tbody
                    if (originalTbody) {
                        originalTbody.classList.remove('tbody-hidden');
                    }

                    // Remove skeleton tbody
                    if (skeletonTbody) {
                        skeletonTbody.remove();
                    }
                }

                // Clear scorecard values function
                function clearScorecardValues() {
                    // Clear PAR values
                    document.querySelectorAll('.par-span').forEach(span => {
                        span.textContent = '-';
                        span.setAttribute('data-par', '');
                    });

                    // Clear Yards values
                    document.querySelectorAll('.yardage-span').forEach(span => {
                        span.textContent = '-';
                        span.setAttribute('data-yardage', '');
                    });

                    // Clear Handicap values
                    document.querySelectorAll('.handicap-span').forEach(span => {
                        span.textContent = '-';
                        span.setAttribute('data-handicap', '');
                        span.removeAttribute('data-gender');
                    });

                    // Clear totals
                    document.querySelectorAll('.front-par-total, .back-par-total, .total-par-total').forEach(span => {
                        span.textContent = '-';
                    });

                    document.querySelectorAll('.front-yards-total, .back-yards-total, .total-yards-total').forEach(span => {
                        span.textContent = '-';
                    });

                    document.querySelectorAll('.front-handicap-total, .back-handicap-total, .total-handicap-total').forEach(span => {
                        span.textContent = '-';
                    });

                    // Clear global handicap data when course changes
                    window.courseHandicapData = null;

                    console.log('Scorecard values cleared - waiting for tee selection');
                }

                // Total calculation functions
                function calculateTotals() {
                    let frontScore = 0,
                        backScore = 0,
                        totalScore = 0;
                    let frontYards = 0,
                        backYards = 0,
                        totalYards = 0;
                    let frontComputed = 0,
                        backComputed = 0,
                        totalComputed = 0;

                    // Calculate scores
                    for (let i = 1; i <= 18; i++) {
                        const scoreInput = document.querySelector(`input[name="score[${i}]"]`);
                        const scoreValue = scoreInput ? parseInt(scoreInput.value) || 0 : 0;

                        if (i <= 9) {
                            frontScore += scoreValue;
                        } else {
                            backScore += scoreValue;
                        }
                        totalScore += scoreValue;
                    }

                    // Calculate yardages
                    for (let i = 1; i <= 18; i++) {
                        const yardageSpan = document.querySelector(`.yardage-span[data-hole="${i}"]`);
                        const yardageValue = yardageSpan ? parseInt(yardageSpan.getAttribute('data-yardage')) || 0 : 0;

                        if (i <= 9) {
                            frontYards += yardageValue;
                        } else {
                            backYards += yardageValue;
                        }
                        totalYards += yardageValue;
                    }

                    // Calculate computed scores (if any)
                    for (let i = 1; i <= 18; i++) {
                        const computedInput = document.querySelector(`input[data-score-input-display="${i}"]`);
                        const computedValue = computedInput ? parseInt(computedInput.value) || 0 : 0;

                        if (i <= 9) {
                            frontComputed += computedValue;
                        } else {
                            backComputed += computedValue;
                        }
                        totalComputed += computedValue;
                    }

                    // Update score totals
                    const frontScoreTotal = document.querySelector('.front-score-total');
                    const backScoreTotal = document.querySelector('.back-score-total');
                    const totalScoreTotal = document.querySelector('.total-score-total');

                    if (frontScoreTotal) frontScoreTotal.value = frontScore || '';
                    if (backScoreTotal) backScoreTotal.value = backScore || '';
                    if (totalScoreTotal) totalScoreTotal.value = totalScore || '';

                    // Update yardage totals
                    const frontYardsTotal = document.querySelector('.front-yards-total');
                    const backYardsTotal = document.querySelector('.back-yards-total');
                    const totalYardsTotal = document.querySelector('.total-yards-total');

                    if (frontYardsTotal) frontYardsTotal.textContent = frontYards > 0 ? frontYards.toLocaleString() : '-';
                    if (backYardsTotal) backYardsTotal.textContent = backYards > 0 ? backYards.toLocaleString() : '-';
                    if (totalYardsTotal) totalYardsTotal.textContent = totalYards > 0 ? totalYards.toLocaleString() : '-';

                    // Update computed totals
                    const frontComputedTotal = document.querySelector('.front-computed-total');
                    const backComputedTotal = document.querySelector('.back-computed-total');
                    const totalComputedTotal = document.querySelector('.total-computed-total');

                    if (frontComputedTotal) frontComputedTotal.value = frontComputed || '';
                    if (backComputedTotal) backComputedTotal.value = backComputed || '';
                    if (totalComputedTotal) totalComputedTotal.value = totalComputed || '';
                }

                // Gender-based handicap functions
                function updateHandicapDisplay(gender) {
                    if (!window.courseHandicapData) return;

                    console.log('Updating handicap display for gender:', gender);

                    let frontHandicap = 0,
                        backHandicap = 0,
                        totalHandicap = 0;

                    for (let hole = 1; hole <= 18; hole++) {
                        const handicapSpan = document.querySelector(`.handicap-span[data-hole="${hole}"]`);
                        if (handicapSpan && window.courseHandicapData[hole]) {
                            const handicapValue = window.courseHandicapData[hole][gender] || '-';
                            handicapSpan.textContent = handicapValue;
                            handicapSpan.setAttribute('data-handicap', handicapValue);
                            handicapSpan.setAttribute('data-gender', gender);

                            // Calculate totals
                            const numericValue = parseInt(handicapValue) || 0;
                            if (hole <= 9) {
                                frontHandicap += numericValue;
                            } else {
                                backHandicap += numericValue;
                            }
                            totalHandicap += numericValue;

                            console.log(`Updated hole ${hole} handicap to ${handicapValue} for ${gender}`);
                        }
                    }

                    // Update handicap totals
                    const frontHandicapTotal = document.querySelector('.front-handicap-total');
                    const backHandicapTotal = document.querySelector('.back-handicap-total');
                    const totalHandicapTotal = document.querySelector('.total-handicap-total');

                    if (frontHandicapTotal) frontHandicapTotal.textContent = frontHandicap > 0 ? frontHandicap : '-';
                    if (backHandicapTotal) backHandicapTotal.textContent = backHandicap > 0 ? backHandicap : '-';
                    if (totalHandicapTotal) totalHandicapTotal.textContent = totalHandicap > 0 ? totalHandicap : '-';

                    // Store current gender selection
                    window.currentGender = gender;
                }

                function getCurrentGenderSelection() {
                    // Use selected player's gender if available, otherwise default to Male
                    return window.selectedPlayerGender || window.currentGender || 'M';
                }

                // Add event listeners to score inputs for real-time total calculation
                const scoreInputs = document.querySelectorAll('.score-input');
                scoreInputs.forEach(input => {
                    input.addEventListener('input', calculateTotals);
                    input.addEventListener('change', calculateTotals);
                });

                // Initial calculation
                calculateTotals();

                // Score Mode Handler
                const scoreModeSelect = document.getElementById('score_mode');
                if (scoreModeSelect) {
                    scoreModeSelect.addEventListener('change', function() {
                        console.log('Score mode changed:', this.value);

                        if (this.value === 'hole_by_hole') {
                            showAdjustedTotalDialog();
                        } else if (this.value === 'adjusted_score') {
                            showAdjustedTotalDialog();
                        } else if (this.value === 'gross_total') {
                            showGrossTotalDialog();
                        }
                    });
                }

                // Adjusted Total Dialog Functions
                function showAdjustedTotalDialog() {
                    const input = document.getElementById('adjustedTotalInput');
                    const scoreModeSelect = document.getElementById('score_mode');
                    const selectedMode = scoreModeSelect ? scoreModeSelect.value : '';

                    // Clear previous input and validation
                    input.value = '';
                    input.classList.remove('is-invalid');

                    // Update modal title and description based on selected mode
                    const modalTitle = document.querySelector('#adjustedTotalModalLabel');
                    const modalDescription = document.querySelector('#adjustedTotalModal .text-muted');

                    if (selectedMode === 'adjusted_score') {
                        if (modalTitle) modalTitle.innerHTML = '<i class="fas fa-calculator me-2"></i>Adjusted Score Entry';
                        if (modalDescription) modalDescription.textContent = 'Enter the adjusted total score for this round';
                    } else {
                        if (modalTitle) modalTitle.innerHTML = '<i class="fas fa-calculator me-2"></i>Adjusted Total Score';
                        if (modalDescription) modalDescription.textContent = 'Enter the adjusted total score for hole-by-hole entry';
                    }

                    // Show modal
                    showModal('adjustedTotalModal');

                    // Focus input immediately since there's no animation
                    setTimeout(() => {
                        input.focus();
                        input.select();
                    }, 50);

                    console.log('Showing adjusted total dialog for mode:', selectedMode);
                }

                // Gross Total Dialog Functions
                function showGrossTotalDialog() {
                    const input = document.getElementById('grossTotalInput');

                    // Clear previous input and validation
                    input.value = '';
                    input.classList.remove('is-invalid');

                    // Show modal
                    showModal('grossTotalModal');

                    // Focus input immediately since there's no animation
                    setTimeout(() => {
                        input.focus();
                        input.select();
                    }, 50);

                    // Focus input after modal is shown
                    setTimeout(() => {
                        input.focus();
                    }, 150);

                    console.log('Showing gross total dialog for gross total mode');
                }

                // Modal utility functions
                function showModal(modalId) {
                    const modalElement = document.getElementById(modalId);
                    if (!modalElement) return;

                    // Check if modal has fade animation
                    const hasAnimation = modalElement.classList.contains('fade');

                    // Use Bootstrap's data attributes if available
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        const modal = new bootstrap.Modal(modalElement);
                        modal.show();
                    } else if (typeof $ !== 'undefined') {
                        // Use jQuery Bootstrap
                        $(modalElement).modal('show');
                    } else {
                        // Pure CSS/vanilla approach
                        modalElement.classList.add('show');
                        modalElement.style.display = 'block';
                        modalElement.setAttribute('aria-modal', 'true');
                        modalElement.removeAttribute('aria-hidden');
                        document.body.classList.add('modal-open');

                        // Add backdrop (no fade for gross total modal)
                        let backdrop = document.querySelector('.modal-backdrop');
                        if (!backdrop) {
                            backdrop = document.createElement('div');
                            if (hasAnimation) {
                                backdrop.className = 'modal-backdrop fade show';
                            } else {
                                backdrop.className = 'modal-backdrop show';
                            }
                            document.body.appendChild(backdrop);
                        }
                    }
                }

                function hideModal(modalId) {
                    const modalElement = document.getElementById(modalId);
                    if (!modalElement) return;

                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        const modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) modal.hide();
                    } else if (typeof $ !== 'undefined') {
                        $(modalElement).modal('hide');
                    } else {
                        modalElement.classList.remove('show');
                        modalElement.style.display = 'none';
                        modalElement.setAttribute('aria-hidden', 'true');
                        modalElement.removeAttribute('aria-modal');
                        document.body.classList.remove('modal-open');

                        // Remove backdrop
                        const backdrop = document.querySelector('.modal-backdrop');
                        if (backdrop) backdrop.remove();
                    }
                }

                // Handle confirm button for adjusted total dialog
                const confirmAdjustedButton = document.getElementById('confirmAdjustedTotal');
                if (confirmAdjustedButton) {
                    confirmAdjustedButton.addEventListener('click', function() {
                        const input = document.getElementById('adjustedTotalInput');
                        const adjustedTotal = parseInt(input.value);

                        if (adjustedTotal && adjustedTotal >= 30 && adjustedTotal <= 200) {
                            // Store the adjusted total for later use
                            window.adjustedTotal = adjustedTotal;

                            // Hide the modal
                            hideModal('adjustedTotalModal');

                            // Show success message
                            showAdjustedTotalConfirmation(adjustedTotal);

                            console.log('Adjusted total set to:', adjustedTotal);

                            // Trigger form submission after setting adjusted total
                            setTimeout(() => {
                                submitForm();
                            }, 500);
                        } else {
                            // Show validation error
                            input.classList.add('is-invalid');
                            showAdjustedTotalError('Please enter a valid score between 30 and 200');
                        }
                    });
                }

                // Handle confirm button for gross total dialog
                const confirmGrossButton = document.getElementById('confirmGrossTotal');
                if (confirmGrossButton) {
                    confirmGrossButton.addEventListener('click', function() {
                        const input = document.getElementById('grossTotalInput');
                        const grossTotal = parseInt(input.value);

                        if (grossTotal && grossTotal >= 30 && grossTotal <= 200) {
                            // Store the gross total for later use
                            window.grossTotal = grossTotal;

                            // Hide the modal
                            hideModal('grossTotalModal');

                            // Show success message
                            showGrossTotalConfirmation(grossTotal);

                            console.log('Gross total set to:', grossTotal);

                            // Trigger form submission after setting gross total
                            setTimeout(() => {
                                submitForm();
                            }, 500);
                        } else {
                            // Show validation error
                            input.classList.add('is-invalid');
                            showGrossTotalError('Please enter a valid score between 30 and 200');
                        }
                    });
                }

                // Handle Enter key and validation for adjusted total input
                const adjustedTotalInput = document.getElementById('adjustedTotalInput');
                if (adjustedTotalInput) {
                    adjustedTotalInput.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            document.getElementById('confirmAdjustedTotal').click();
                        } else if (e.key === 'Escape') {
                            hideModal('adjustedTotalModal');
                        }
                    });

                    // Remove validation error on input
                    adjustedTotalInput.addEventListener('input', function() {
                        this.classList.remove('is-invalid');
                    });
                }

                // Handle Enter key and validation for gross total input
                const grossTotalInput = document.getElementById('grossTotalInput');
                if (grossTotalInput) {
                    grossTotalInput.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            document.getElementById('confirmGrossTotal').click();
                        } else if (e.key === 'Escape') {
                            hideModal('grossTotalModal');
                        }
                    });

                    // Remove validation error on input
                    grossTotalInput.addEventListener('input', function() {
                        this.classList.remove('is-invalid');
                    });
                }

                function showAdjustedTotalConfirmation(total) {
                    const scoreModeSelect = document.getElementById('score_mode');
                    const selectedMode = scoreModeSelect ? scoreModeSelect.value : '';

                    let title, message;

                    if (selectedMode === 'adjusted_score') {
                        title = 'Adjusted Score Set!';
                        message = `Adjusted score: ${total} for this round`;
                    } else {
                        title = 'Adjusted Total Set!';
                        message = `Target score: ${total} for hole-by-hole entry`;
                    }

                    showSuccessMessage({
                        title: title,
                        message: message,
                        icon: 'fas fa-calculator'
                    });
                }

                function showGrossTotalConfirmation(total) {
                    showSuccessMessage({
                        title: 'Gross Total Set!',
                        message: `Total score: ${total} for this round`,
                        icon: 'fas fa-golf-ball'
                    });
                }

                function showSuccessMessage({
                    title,
                    message,
                    icon
                }) {
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed';
                    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; animation: slideInRight 0.5s ease-out;';
                    alertDiv.innerHTML = `
                        <div class="d-flex align-items-center">
                            <i class="${icon} me-2"></i>
                            <div>
                                <strong>${title}</strong><br>
                                <small>${message}</small>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;

                    document.body.appendChild(alertDiv);

                    // Auto remove after 4 seconds
                    setTimeout(() => {
                        if (alertDiv.parentNode) {
                            alertDiv.remove();
                        }
                    }, 4000);
                }

                function showAdjustedTotalError(message) {
                    showInputError('adjustedTotalInput', message || 'Please enter a valid score between 30 and 200');
                }

                function showGrossTotalError(message) {
                    showInputError('grossTotalInput', message || 'Please enter a valid score between 30 and 200');
                }

                function showInputError(inputId, message) {
                    const input = document.getElementById(inputId);

                    // Create error message if not exists
                    let errorDiv = document.querySelector(`#${inputId} + .invalid-feedback`);
                    if (!errorDiv) {
                        errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        input.parentNode.appendChild(errorDiv);
                    }

                    errorDiv.textContent = message;
                }

            } else {
                console.error('Tournament, course, or tee select elements not found');
            }
        };
    });
</script>

@endsection