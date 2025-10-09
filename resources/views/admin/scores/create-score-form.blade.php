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
                            <div class="col-md-4">
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
                            <div class="col-md-4">
                                <div class="form-floating bg-white rounded-2 border border-light shadow-sm">
                                    <select name="player_id" id="player_id" class="form-select form-select-sm border-0 bg-light" required>
                                        @foreach($tournaments as $tournament)
                                        <option value="{{ $tournament->id }}">{{ $tournament->tournament->tournament_name }} - {{ $tournament->course->course_name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="player_id" class="fw-semibold text-dark small">
                                        <i class="fas fa-user-golf text-primary me-1"></i>Tournament
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-floating bg-white rounded-2 border border-light shadow-sm">
                                    <select name="player_id" id="player_id" class="form-select form-select-sm border-0 bg-light" required>
                                        @foreach($tournaments as $tournament)
                                        <option value="{{ $tournament->id }}">{{ $tournament->tournament->tournament_name }} - {{ $tournament->course->course_name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="player_id" class="fw-semibold text-dark small">
                                        <i class="fas fa-user-golf text-primary me-1"></i>Tee
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
                                <h5 class="mb-0 fw-bold text-dark">Scorecard</h5>
                            </div>

                            <div class="table-responsive border-0 rounded-3 overflow-hidden" style="box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                                <table class="table table-sm align-middle text-center mb-0 score-table">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th class="text-start ps-3 text-white fw-semibold py-2 small">Hole</th>
                                            @foreach($scorecard->scorecardDetails as $detail)
                                            <th class="{{ $detail->hole == 10 ? 'border-start border-2 border-warning' : '' }} text-primary fw-semibold py-2 small column-header text-white" data-column="{{ $detail->hole }}">{{ $detail->hole }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody style="background: #ffffff;">
                                        <tr style="background: linear-gradient(90deg, #f8f9fa 0%, #ffffff 100%);">
                                            <td class="text-start ps-3 fw-bold text-primary py-2 small">PAR</td>
                                            @foreach($scorecard->scorecardDetails as $detail)
                                            <td class="{{ $detail->hole == 10 ? 'border-start border-2 border-warning' : '' }} py-2 column-cell" data-column="{{ $detail->hole }}">
                                                <span class=" rounded-pill" style="padding: 4px 8px; font-size: 0.75rem;" data-hole="{{ $detail->hole }}" data-par-value="{{ $detail->par }}">{{ $detail->par }}</span>
                                            </td>
                                            @endforeach
                                        </tr>

                                        <tr style="background: linear-gradient(90deg, #e9ecef 0%, #f8f9fa 100%);">
                                            <td class="text-start ps-3 fw-bold text-info py-2 small">Yards</td>
                                            @foreach($scorecard->scorecardDetails as $detail)
                                            <td class="{{ $detail->hole == 10 ? 'border-start border-2 border-warning' : '' }} py-2 column-cell" data-column="{{ $detail->hole }}">
                                                <span class="rounded-pill text-primary px-2 py-1" style="font-size: 0.65rem;" data-hole="{{ $detail->hole }}" data-yardage="{{ $detail->yardage }}">{{ number_format($detail->yardage, 0) }}</span>
                                            </td>
                                            @endforeach
                                        </tr>

                                        <tr style="background: #ffffff;">
                                            <td class="text-start ps-3 fw-bold text-dark py-2 small">Score</td>
                                            @for ($i = 1; $i <= 18; $i++)
                                                <td class="{{ $i == 10 ? 'border-start border-2 border-warning' : '' }} py-2 column-cell" data-column="{{ $i }}">
                                                <input type="text" name="score[{{ $i }}]"
                                                    class="form-control form-control-sm text-center score-input border-1 border-primary"
                                                    placeholder="–"
                                                    aria-label="Score hole {{ $i }}"
                                                    data-hole="{{ $i }}"
                                                    style="min-width: 40px; height: 35px; font-weight: 600; font-size: 1rem; border-radius: 6px; transition: all 0.2s ease;">
                                                </td>
                                                @endfor
                                        </tr>

                                        <tr style="background: linear-gradient(90deg, #f1f3f4 0%, #e9ecef 100%);">
                                            <td class="text-start ps-3 fw-bold text-muted py-2 small">

                                            </td>
                                            @for ($i = 1; $i <= 18; $i++)
                                                <td class="{{ $i == 10 ? 'border-start border-2 border-warning' : '' }} py-2 column-cell" data-column="{{ $i }}">
                                                <input type="text"
                                                    class="form-control form-control-sm text-center score-input-display bg-light text-muted border-0"
                                                    data-score-input-display="{{ $i }}"
                                                    placeholder="–"
                                                    aria-label="Computed score hole {{ $i }} (read-only)"
                                                    style="min-width: 40px; height: 28px; cursor: default; font-weight: 500; border-radius: 4px; font-size: 0.875rem;"
                                                    readonly disabled tabindex="-1" aria-disabled="true">
                                                </td>
                                                @endfor
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Enhanced Total Section -->
                            <div class="row g-3 mt-3 mb-2">
                                <div class="col-md-6">
                                    <div class="total-card bg-gradient rounded-3 p-3 border-0 shadow-sm" style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="total-icon bg-primary bg-opacity-20 rounded-circle p-2 me-3">
                                                <i class="fas fa-edit text-primary fs-5"></i>
                                            </div>
                                            <div>
                                                <label for="manualTotal" class="form-label fw-bold text-primary mb-0">Entered Total</label>
                                                <small class="d-block text-primary opacity-75">Manual score entry</small>
                                            </div>
                                        </div>
                                        <input type="number" name="score[total]" id="manualTotal" min="18" max="180"
                                            class="form-control form-control-lg text-center fw-bold border-2 border-primary shadow-sm"
                                            placeholder="0"
                                            style="background: rgba(255,255,255,0.95); font-size: 1.5rem; border-radius: 12px; height: 60px; transition: all 0.3s ease;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="total-card bg-gradient rounded-3 p-3 border-0 shadow-sm" style="background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="total-icon bg-purple bg-opacity-20 rounded-circle p-2 me-3" style="background-color: rgba(123, 31, 162, 0.2) !important;">
                                                <i class="fas fa-calculator fs-5" style="color: #7b1fa2;"></i>
                                            </div>
                                            <div>
                                                <label class="form-label fw-bold mb-0" style="">Computed Total</label>
                                                <small class="d-block opacity-75" style="">Auto-calculated from holes</small>
                                            </div>
                                        </div>
                                        <input type="number" id="computedTotal"
                                            class="form-control form-control-lg text-center fw-bold border-0 shadow-sm score-input-display-total"
                                            placeholder="0" readonly
                                            style="background: rgba(255,255,255,0.95); font-size: 1.5rem; border-radius: 12px; color: #7b1fa2; height: 60px; cursor: default;">
                                    </div>
                                </div>
                            </div>

                            <!-- Score Summary Info -->
                            <div class="alert alert-info border-0 mt-2 mb-0 py-3 rounded-3" style="background: linear-gradient(135deg, #e8f4fd 0%, #d1ecf1 100%);">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-lightbulb text-info fs-4"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-info mb-1">Scoring Tips</div>
                                        <small class="text-info mb-0">
                                            Enter numbers (1-9) or 'x' for penalty strokes. Computed total updates automatically as you enter hole scores.
                                        </small>
                                    </div>
                                </div>
                            </div>
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
                                <button type="submit" class="btn rounded-pill px-4 text-white fw-bold" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border: none; box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);">
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

        // Add keyboard navigation enhancement
        scoreInputs.forEach((input, index) => {
            input.addEventListener('keydown', function(e) {
                // Arrow key navigation
                if (e.key === 'ArrowRight' && index < scoreInputs.length - 1) {
                    e.preventDefault();
                    scoreInputs[index + 1].focus();
                } else if (e.key === 'ArrowLeft' && index > 0) {
                    e.preventDefault();
                    scoreInputs[index - 1].focus();
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

                // Clear totals
                const totalInput = document.querySelector('input[name="score[total]"]');
                if (totalInput) totalInput.value = '';
                const computedTotal = document.querySelector('.score-input-display-total');
                if (computedTotal) computedTotal.value = '';

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
                    document.querySelectorAll('.score-input, .score-input-display, .score-input-display-total').forEach(input => {
                        input.value = '';
                        input.classList.remove('is-valid', 'is-invalid');
                    });

                    // Clear total input
                    const totalInput = document.querySelector('input[name="score[total]"]');
                    if (totalInput) totalInput.value = '';

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
        }
    });
</script>

<!-- Include handicapping calculation script -->
<script src="{{ asset('js/handicapping.js') }}"></script>
@endsection