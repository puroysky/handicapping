@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-12">




        </div>
    </div>
</div>

<div class="container-fluid py-4 px-4">
    <div class="row">
        <div class="col-12">
            <div class="form-container fade-in">
                <!-- Form Body -->
                <div class="form-body">
                    <h3 class="mb-2 mt-0 text-center text-primary">
                        <i class="fas fa-golf-ball me-2 text-primary"></i>
                        Golf Score Entry
                    </h3>
                    <p class="text-muted text-center mb-1">Record your round and track your handicap progress</p>

                    <form>
                        <!-- Course Information Section -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-map-marker-alt"></i>
                                Course Information
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="course">
                                            <option value="">Choose...</option>
                                            <option value="1">Pebble Beach Golf Links</option>
                                            <option value="2">Augusta National Golf Club</option>
                                            <option value="3">St. Andrews Old Course</option>
                                            <option value="4">Cypress Point Club</option>
                                            <option value="5">Bethpage Black</option>
                                            <option value="6">Pinehurst No. 2</option>
                                            <option value="7">TPC Sawgrass</option>
                                            <option value="8">Whistling Straits</option>
                                        </select>
                                        <label for="course">Golf Course</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="tee">
                                            <option value="">Choose...</option>
                                            <option value="black">Championship (Black)</option>
                                            <option value="blue">Blue Tees</option>
                                            <option value="white">White Tees</option>
                                            <option value="red">Red Tees</option>
                                        </select>
                                        <label for="tee">Tee Color</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">

                                    <div class="form-floating">
                                        <input type="date" class="form-control" id="date_played">
                                        <label for="date_played">Date Played</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" id="total_score" placeholder="85" min="50" max="150">
                                        <label for="total_score">Total Score</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Round Details Section -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-cloud-sun"></i>
                                Round Details
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="conditions">
                                            <option value="">Choose...</option>
                                            <option selected value="excellent">Excellent</option>
                                            <option value="good">Good</option>
                                            <option value="fair">Fair</option>
                                            <option value="poor">Poor (Wind/Rain)</option>
                                        </select>
                                        <label for="conditions">Playing Conditions</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" id="putts" placeholder="32" min="18" max="50">
                                        <label for="putts">Total Putts</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-check-modern">
                                <input class="form-check-input" type="checkbox" id="tournament">
                                <label class="form-check-label" for="tournament">
                                    <strong>Tournament Round</strong>
                                    <br>
                                    <small class="text-muted">Check if this was an official tournament score</small>
                                </label>
                            </div>
                        </div>



                        <!-- Notes Section -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-sticky-note"></i>
                                Additional Notes
                            </div>

                            <div class="form-floating">
                                <textarea class="form-control" id="notes" placeholder="Enter any additional notes about your round..."></textarea>
                                <label for="notes">Round Notes (Optional)</label>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end flex-wrap gap-3">

                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-modern">
                                    <i class="fas fa-times me-2"></i>
                                    Cancel
                                </button>
                                <button type="submit" class="btn btn-primary-modern text-white">
                                    <i class="fas fa-save me-2"></i>
                                    Submit
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
    .form-floating.has-value>label {
        opacity: .65;
        transform: scale(.85) translateY(-0.75rem) translateX(0.15rem);
    }




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

    $(document).ready(function() {

        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%',


        });


        $('.select2').on('select2:open select2:close change', function() {
            let parent = $(this).closest('.form-floating');
            if ($(this).val() && $(this).val().length > 0) {
                parent.addClass('has-value');
            } else {
                parent.removeClass('has-value');
            }
        });
    });
</script>
@endsection