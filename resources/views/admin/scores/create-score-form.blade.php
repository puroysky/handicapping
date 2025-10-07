@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Modern Card Design -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <!-- Compact Header -->
                <div class="card-header bg-gradient border-0 py-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="bg-white bg-opacity-20 rounded-2 p-1 me-2">
                                <i class="fas fa-golf-ball text-white"></i>
                            </div>
                            <div>
                                <h1 class="text-primary mb-0 h5 fw-bold">Score Entry</h1>
                                <p class="text-success-50 mb-0 small">Hole by hole scoring</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-light btn-sm rounded-pill px-3" id="clearScoresBtn">
                                <i class="fas fa-eraser me-1"></i>Clear
                            </button>
                            <button type="button" class="btn btn-outline-light btn-sm rounded-pill px-3" onclick="window.showErrorModal && window.showErrorModal({ message: 'Example error', details: 'This is a demo of the compact error modal.' })">
                                <i class="fas fa-bug me-1"></i>Test
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Compact Card Body -->
                <div class="card-body p-3" style="background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);">
                    <form class="needs-validation" novalidate>
                        <!-- Course Selection Section -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="bg-white rounded-2 p-2 border border-light shadow-sm">
                                    <label for="player_id" class="form-label fw-semibold text-dark mb-1 small">
                                        <i class="fas fa-user-golf text-primary me-1"></i>Mode
                                    </label>
                                    <select name="player_id" id="player_id" class="form-select form-select-sm border-0 bg-light" required>
                                        <option value="hole_by_hole" disabled selected>Hole by Hole</option>
                                        <option value="1">Gross Total</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <?php
                        // Demo PAR data (replace with real PARs later)
                        $parArray = [];
                        for ($i = 0; $i < 18; $i++) {
                            $parArray[] = rand(3, 5);
                        }
                        ?>

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
                                    <thead style="background: linear-gradient(135deg, #495057 0%, #343a40 100%);">
                                        <tr>
                                            <th class="text-start ps-3 text-dark fw-semibold py-2 small">Hole</th>
                                            @foreach($scorecard->scorecardDetails as $detail)
                                            <th class="{{ $detail->hole == 10 ? 'border-start border-2 border-warning' : '' }} text-primary fw-semibold py-2 small column-header" data-column="{{ $detail->hole }}">{{ $detail->hole }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody style="background: #ffffff;">
                                        <tr style="background: linear-gradient(90deg, #f8f9fa 0%, #ffffff 100%);">
                                            <td class="text-start ps-3 fw-bold text-primary py-2 small">PAR</td>
                                            @foreach($scorecard->scorecardDetails as $detail)
                                            <td class="{{ $detail->hole == 10 ? 'border-start border-2 border-warning' : '' }} py-2 column-cell" data-column="{{ $detail->hole }}">
                                                <span class="badge rounded-pill" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; padding: 4px 8px; font-size: 0.75rem;" data-hole="{{ $detail->hole }}" data-par-value="{{ $detail->par }}">{{ $detail->par }}</span>
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
                                                <i class="fas fa-calculator me-1"></i>Calc
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
                        </div>

                        <!-- Compact Summary Section -->
                        <div class="bg-white rounded-3 p-2 border border-light shadow-sm mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-success bg-opacity-10 rounded-2 p-1 me-2">
                                    <i class="fas fa-calculator text-success"></i>
                                </div>
                                <h6 class="mb-0 fw-bold text-dark">Summary</h6>
                            </div>

                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="bg-gradient rounded-2 p-2" style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-edit text-primary me-1"></i>
                                            <label class="form-label mb-0 fw-semibold text-primary small">Entered Total</label>
                                        </div>
                                        <input type="number" name="score[total]" min="18" max="180"
                                            class="form-control text-center fw-bold border-1 border-primary"
                                            placeholder="0"
                                            style="background: rgba(255,255,255,0.9); font-size: 1.25rem; border-radius: 6px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="bg-gradient rounded-2 p-2" style="background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-robot me-1" style="color: #7b1fa2;"></i>
                                            <label class="form-label mb-0 fw-semibold small" style="color: #7b1fa2;">Computed Total</label>
                                        </div>
                                        <input type="number"
                                            class="form-control text-center fw-bold score-input-display-total border-0"
                                            placeholder="0" readonly
                                            style="background: rgba(255,255,255,0.9); font-size: 1.25rem; border-radius: 6px; color: #7b1fa2;">
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info border-0 mt-2 mb-0 py-2 rounded-2" style="background: linear-gradient(135deg, #e8f4fd 0%, #d1ecf1 100%);">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-lightbulb text-info me-2"></i>
                                    <small class="text-info mb-0">
                                        <strong>Tip:</strong> Enter numbers or 'x' for penalty (auto-calculated).
                                    </small>
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
        /* transition: all 0.3s ease !important; */
        box-shadow: inset 0 0 10px rgba(47, 74, 60, 0.1);
    }

    .column-header.column-highlighted {
        background: linear-gradient(135deg, #2F4A3C 0%, #5E7C4C 100%) !important;
        color: #FFFFFF !important;
        transform: scale(1.05);
        box-shadow: 0 2px 8px rgba(47, 74, 60, 0.4);
        border: 1px solid #8DA66E;
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
@endsection