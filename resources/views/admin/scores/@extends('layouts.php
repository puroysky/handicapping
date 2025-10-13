@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Modern Card Design -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">


                <!-- Compact Card Body -->
                <div class="card-body p-3" style="background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);">
                    <form class="needs-validation" novalidate>
                        <!-- Course Selection Section -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="form-floating bg-white rounded-2 border border-light shadow-sm">
                                    <select name="player_id" id="player_id" class="form-select form-select-sm border-0 bg-light" required>
                                        <option value="hole_by_hole" disabled selected>Hole by Hole</option>
                                        <option value="1">Gross Total</option>
                                    </select>
                                    <label for="player_id" class="fw-semibold text-dark small">
                                        <i class="fas fa-user-golf text-primary me-1"></i>Mode
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
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

                            <div class="col-md-3">
                                <div class="form-floating bg-white rounded-2 border border-light shadow-sm">
                                    <select name="course_id" id="course_id" class="form-select form-select-sm border-0 bg-light" required disabled>
                                        <option value="">Select Course</option>
                                    </select>
                                    <label for="course_id" class="fw-semibold text-dark small">
                                        <i class="fas fa-golf-ball text-primary me-1"></i>Course
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-floating bg-white rounded-2 border border-light shadow-sm">
                                    <select name="tee_id" id="tee_id" class="form-select form-select-sm border-0 bg-light" required>

                                    </select>
                                    <label for="tee_id" class="fw-semibold text-dark small">
                                        <i class="fas fa-flag text-primary me-1"></i>Tee
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
                                            @foreach($scorecard->scorecardHoles as $hole)
                                            <th class="text-primary fw-bold py-2 column-header text-white" style="font-size: 0.875rem; font-weight: 700;" data-column="{{ $hole->hole }}">{{ $hole->hole }}</th>
                                            @if($hole->hole == 9)
                                            <th class="text-white fw-bold py-2 bg-success border-start border-2 border-light" style="font-size: 0.875rem; font-weight: 700;">OUT</th>
                                            @endif
                                            @endforeach
                                            <th class="text-white fw-bold py-2 bg-success border-start border-2 border-light" style="font-size: 0.875rem; font-weight: 700;">IN</th>
                                            <th class="text-white fw-bold py-2 bg-primary border-start border-2 border-light" style="font-size: 0.875rem; font-weight: 700;">TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody style="background: #ffffff;">
                                        <tr style="background: linear-gradient(90deg, #f8f9fa 0%, #ffffff 100%);">
                                            <td class="text-start ps-3 fw-bold text-primary py-2" style="font-size: 0.875rem; font-weight: 700;">PAR</td>
                                            @php $frontPar = 0; $backPar = 0; @endphp
                                            @foreach($scorecard->scorecardHoles as $hole)
                                            @php
                                            if($hole->hole <= 9) $frontPar +=$hole->par;
                                                else $backPar += $hole->par;
                                                @endphp
                                                <td class="py-2 column-cell" data-column="{{ $hole->hole }}">
                                                    <span class="rounded-pill fw-bold" style="padding: 4px 8px; font-size: 0.875rem; font-weight: 700;" data-hole="{{ $hole->hole }}" data-par-value="{{ $hole->par }}">{{ $hole->par }}</span>
                                                </td>
                                                @if($hole->hole == 9)
                                                <td class="py-2 fw-bold text-success bg-success bg-opacity-10 border-start border-2 border-light">
                                                    <span class="rounded-pill bg-success text-white px-2 py-1 fw-bold" style="font-size: 0.875rem; font-weight: 700;">{{ $frontPar }}</span>
                                                </td>
                                                @endif
                                                @endforeach
                                                <td class="py-2 fw-bold text-success bg-success bg-opacity-10 border-start border-2 border-light">
                                                    <span class="rounded-pill bg-success text-white px-2 py-1 fw-bold" style="font-size: 0.875rem; font-weight: 700;">{{ $backPar }}</span>
                                                </td>
                                                <td class="py-2 fw-bold text-primary bg-primary bg-opacity-10 border-start border-2 border-light">
                                                    <span class="rounded-pill bg-primary text-white px-2 py-1 fw-bold" style="font-size: 0.875rem; font-weight: 700;">{{ $frontPar + $backPar }}</span>
                                                </td>
                                        </tr>

                                        <tr style="background: linear-gradient(90deg, #e9ecef 0%, #f8f9fa 100%);">
                                            <td class="text-start ps-3 fw-bold text-info py-2" style="font-size: 0.875rem; font-weight: 700;">Yards</td>
                                            @foreach($scorecard->scorecardHoles as $hole)
                                            <td class="py-2 column-cell" data-column="{{ $hole->hole }}">
                                                <span class="rounded-pill text-primary px-2 py-1 yardage-span fw-semibold" style="font-size: 0.75rem; font-weight: 600;" data-hole="{{ $hole->hole }}" data-yardage="{{ $hole->yardage->yardage }}">-</span>
                                            </td>
                                            @if($hole->hole == 9)
                                            <td class="py-2 fw-bold text-info bg-info bg-opacity-10 border-start border-2 border-light">
                                                <span class="rounded-pill bg-info text-white px-2 py-1 front-yards-total fw-bold" style="font-size: 0.8rem; font-weight: 700;">-</span>
                                            </td>
                                            @endif
                                            @endforeach
                                            <td class="py-2 fw-bold text-info bg-info bg-opacity-10 border-start border-2 border-light">
                                                <span class="rounded-pill bg-info text-white px-2 py-1 back-yards-total fw-bold" style="font-size: 0.8rem; font-weight: 700;">-</span>
                                            </td>
                                            <td class="py-2 fw-bold text-info bg-info bg-opacity-10 border-start border-2 border-light">
                                                <span class="rounded-pill bg-info text-white px-2 py-1 total-yards-total fw-bold" style="font-size: 0.8rem; font-weight: 700;">-</span>
                                            </td>
                                        </tr>

                                        <tr style="background: #ffffff;">
                                            <td class="text-start ps-3 fw-bold text-dark py-2" style="font-size: 0.875rem; font-weight: 700;">Score</td>
                                            @foreach ($scorecard->scorecardHoles as $hole)
                                            <td class="py-2 column-cell" data-column="{{ $hole->hole }}">
                                                <input type="text" name="score[{{ $hole->hole }}]"
                                                    class="form-control form-control-sm text-center score-input border-1 border-primary"
                                                    placeholder="–"
                                                    aria-label="Score hole {{ $hole->hole }}"
                                                    data-hole="{{ $hole->hole }}"
                                                    style="min-width: 40px; height: 35px; font-weight: 700; font-size: 1.1rem; border-radius: 6px; transition: all 0.2s ease;">
                                            </td>
                                            @if($hole->hole == 9)
                                            <td class="py-2 fw-bold text-warning bg-warning bg-opacity-10 border-start border-2 border-light">
                                                <input type="text" class="form-control form-control-sm text-center fw-bold border-0 bg-warning bg-opacity-20 front-score-total"
                                                    placeholder="0" readonly style="min-width: 40px; height: 35px; font-size: 1rem; color: #856404;">
                                            </td>
                                            @endif
                                            @endforeach
                                            <td class="py-2 fw-bold text-warning bg-warning bg-opacity-10 border-start border-2 border-light">
                                                <input type="text" class="form-control form-control-sm text-center fw-bold border-0 bg-warning bg-opacity-20 back-score-total"
                                                    placeholder="0" readonly style="min-width: 40px; height: 35px; font-size: 1rem; color: #856404;">
                                            </td>
                                            <td class="py-2 fw-bold text-primary bg-primary bg-opacity-10 border-start border-2 border-light">
                                                <input type="text" class="form-control form-control-sm text-center fw-bold border-0 bg-primary bg-opacity-20 total-score-total"
                                                    placeholder="0" readonly style="min-width: 40px; height: 35px; font-size: 1rem; color: #0d47a1;">
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
                                                <td class="py-2 fw-bold text-muted bg-secondary bg-opacity-10 border-start border-2 border-light">
                                                    <input type="text" class="form-control form-control-sm text-center fw-bold border-0 bg-secondary bg-opacity-20 front-computed-total"
                                                        placeholder="0" readonly style="min-width: 40px; height: 28px; font-size: 0.9rem; font-weight: 600; color: #6c757d;">
                                                </td>
                                                @endif
                                                @endfor
                                                <td class="py-2 fw-bold text-muted bg-secondary bg-opacity-10 border-start border-2 border-light">
                                                    <input type="text" class="form-control form-control-sm text-center fw-bold border-0 bg-secondary bg-opacity-20 back-computed-total"
                                                        placeholder="0" readonly style="min-width: 40px; height: 28px; font-size: 0.9rem; font-weight: 600; color: #6c757d;">
                                                </td>
                                                <td class="py-2 fw-bold text-muted bg-secondary bg-opacity-10 border-start border-2 border-light">
                                                    <input type="text" class="form-control form-control-sm text-center fw-bold border-0 bg-secondary bg-opacity-20 total-computed-total"
                                                        placeholder="0" readonly style="min-width: 40px; height: 28px; font-size: 0.9rem; font-weight: 600; color: #6c757d;">
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
        </div>
    </div>
</div>

<style>
    /* Enhanced styling for score inputs */
    .score-input:focus {
        border-color: #2F4A3C !important;
        box-shadow: 0 0 0 2px rgba(47, 74, 60, 0.2) !important;
        transform: scale(1.02);
        background: rgba(255, 255, 255, 0.95);
    }

    .score-input:valid {
        border-color: #5E7C4C !important;
        background: rgba(141, 166, 110, 0.05);
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

    /* Pulse effect for updated yardages */
    .yardage-updated {
        animation: pulse-success 0.6s ease-in-out;
    }

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
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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
            if (form) {
                // Trigger form validation
                if (form.checkValidity()) {
                    // Show submission feedback
                    const submitBtn = document.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        const originalText = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';
                        submitBtn.disabled = true;

                        // Simulate form submission (replace with actual submission logic)
                        setTimeout(() => {
                            submitBtn.innerHTML = '<i class="fas fa-check me-1"></i>Saved!';
                            setTimeout(() => {
                                submitBtn.innerHTML = originalText;
                                submitBtn.disabled = false;
                            }, 1500);
                        }, 1000);
                    }

                    console.log('Form submitted via Ctrl+S');
                    // Add actual form submission logic here
                } else {
                    // Show validation errors
                    form.classList.add('was-validated');
                    console.log('Form validation failed');
                }
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

                                if (data.success && data.tees && data.tees.length > 0) {
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

                    if (teeId) {
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
                                            // Update content with success animation
                                            yardageSpan.textContent = yardage.toLocaleString();
                                            yardageSpan.classList.add('text-primary', 'yardage-updated');
                                            yardageSpan.setAttribute('data-yardage', yardage);

                                            // Remove success animation after it completes
                                            setTimeout(() => {
                                                yardageSpan.classList.remove('yardage-updated');
                                            }, 600);

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

                // Add event listeners to score inputs for real-time total calculation
                const scoreInputs = document.querySelectorAll('.score-input');
                scoreInputs.forEach(input => {
                    input.addEventListener('input', calculateTotals);
                    input.addEventListener('change', calculateTotals);
                });

                // Initial calculation
                calculateTotals();
            } else {
                console.error('Tournament, course, or tee select elements not found');
            }
        };
    });
</script>

@endsection