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
                    <p class="text-muted text-center mb-1">Create a new golf tournament in the system</p>

                    <form class="needs-validation" novalidate id="mainForm">
                        @csrf
                        <!-- Tournament Basic Information Section -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-info-circle"></i>
                                Tournament Information
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
                        </div>

                        <!-- Tournament Schedule Section -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-calendar-alt"></i>
                                Tournament Schedule
                            </div>

                            <div class="row">
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

                        <!-- Course Selection Section -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-golf-ball"></i>
                                Course Settings
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

                        <!-- Scorecard Configuration Section -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-table"></i>
                                Scorecard Configuration
                            </div>

                            <div class="alert alert-info" id="scorecard-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Please select courses first to configure scorecards for each course.
                            </div>

                            <div id="scorecard-selections" class="row g-3" style="display: none;">
                                <!-- Dynamic scorecard selections will be added here -->
                            </div>
                        </div>

                        <!-- Additional Remarks Section -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-sticky-note"></i>
                                Additional Remarks
                            </div>

                            <div class="form-floating">
                                <textarea class="form-control" id="remarks" name="remarks" placeholder="Enter any additional remarks about this tournament..." maxlength="1000"></textarea>
                                <label for="remarks">Remarks (Optional)</label>
                                <div class="invalid-feedback">
                                    Remarks cannot exceed 1000 characters.
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
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
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

    @media (max-width: 768px) {
        .course-scorecard-selection {
            margin-bottom: 1rem;
        }
    }
</style>

<script>
    $(document).ready(function() {

        let isSubmitting = false; // Flag to prevent multiple submissions

        // Function to disable/enable form
        function toggleFormState(disabled) {
            const form = document.getElementById('mainForm');
            const submitBtn = document.getElementById('submitBtn');
            const cancelBtn = document.getElementById('cancelBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');

            // Disable/enable all form inputs
            const inputs = form.querySelectorAll('input, select, textarea, button');
            inputs.forEach(input => {
                input.disabled = disabled;
            });

            // Toggle button states
            if (disabled) {
                btnText.classList.add('d-none');
                btnLoading.classList.remove('d-none');
                submitBtn.disabled = true;
                cancelBtn.disabled = true;
            } else {
                btnText.classList.remove('d-none');
                btnLoading.classList.add('d-none');
                submitBtn.disabled = false;
                cancelBtn.disabled = false;
            }
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
                // Update visual state
                const courseOption = this.closest('.course-option');
                if (this.checked) {
                    courseOption.classList.add('selected');
                } else {
                    courseOption.classList.remove('selected');
                }

                // Validate selection and update scorecard options
                validateCourseSelection();
                updateScorecardSelections();
            });
        });

        // Function to update scorecard selections based on selected courses
        function updateScorecardSelections() {
            const selectedCourses = Array.from(document.querySelectorAll('input[name="course_ids[]"]:checked'));
            const scorecardContainer = document.getElementById('scorecard-selections');
            const scorecardInfo = document.getElementById('scorecard-info');

            // Clear existing scorecard selections
            scorecardContainer.innerHTML = '';

            if (selectedCourses.length === 0) {
                scorecardContainer.style.display = 'none';
                scorecardInfo.style.display = 'block';
                scorecardInfo.innerHTML = '<i class="fas fa-info-circle me-2"></i>Please select courses first to configure scorecards for each course.';
                return;
            }

            scorecardContainer.style.display = 'block';
            scorecardInfo.style.display = 'none';

            // Create scorecard selection for each selected course
            selectedCourses.forEach(courseCheckbox => {
                const courseId = courseCheckbox.value;
                const courseName = courseCheckbox.closest('.course-option').querySelector('strong').textContent;

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
                                        <option value="">Select Scorecard for ${courseName}</option>
                                        @if(isset($scorecards))
                                            @foreach($scorecards as $scorecard)
                                                <option value="{{ $scorecard->scorecard_id }}">{{ $scorecard->scorecard_name }}</option>
                                            @endforeach
                                        @else
                                            <option value="1">Standard Scorecard</option>
                                            <option value="2">Tournament Scorecard</option>
                                            <option value="3">Championship Scorecard</option>
                                            <option value="4">Executive Scorecard</option>
                                        @endif
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

            // Add event listeners to new scorecard selects
            document.querySelectorAll('.course-scorecard-select').forEach(select => {
                select.addEventListener('change', validateScorecardSelection);
            });
        }

        // Custom validation for scorecard selection
        function validateScorecardSelection() {
            const scorecardSelects = document.querySelectorAll('.course-scorecard-select');
            let isValid = true;

            if (scorecardSelects.length === 0) {
                return true; // No courses selected, skip scorecard validation
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

        // Custom validation for date range
        function validateDateRange() {
            const startDate = new Date(document.getElementById('tournament_start').value);
            const endDate = new Date(document.getElementById('tournament_end').value);
            const startInput = document.getElementById('tournament_start');
            const endInput = document.getElementById('tournament_end');

            if (startDate && endDate) {
                if (endDate < startDate) {
                    endInput.setCustomValidity('End date must be equal to or after start date');
                    return false;
                } else {
                    endInput.setCustomValidity('');
                    return true;
                }
            }
            return true;
        }

        // Date validation event listeners
        document.getElementById('tournament_start').addEventListener('change', validateDateRange);
        document.getElementById('tournament_end').addEventListener('change', validateDateRange);

        // AJAX form submission
        $(document).on('submit', '#mainForm', function(e) {
            e.preventDefault();

            // Prevent multiple submissions
            if (isSubmitting) {
                return false;
            }

            const form = this;

            // Validate form including custom validations
            if (!form.checkValidity() || !validateDateRange() || !validateCourseSelection() || !validateScorecardSelection()) {
                form.classList.add('was-validated');
                return false;
            }

            const formData = $('#mainForm').serializeArray();

            // Set submitting flag and disable form
            isSubmitting = true;
            toggleFormState(true);

            // Make AJAX request
            $.ajax({
                url: '{{ route("admin.tournaments.store") }}',
                type: 'POST',
                data: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                timeout: 30000, // 30 seconds timeout
                success: function(response) {
                    // Success handling
                    if (response.success) {
                        // Show success message
                        showModal('success', 'Success!', 'Tournament created successfully!');

                        // Reset form
                        form.reset();
                        form.classList.remove('was-validated');

                        // Remove validation classes
                        $(form).find('.is-valid, .is-invalid').removeClass('is-valid is-invalid');

                        // Redirect after delay
                        setTimeout(function() {
                            window.location.href = response.redirect || '/admin/tournaments';
                        }, 2000);
                    } else {
                        showModal('error', 'Error', response.message || 'An error occurred while creating the tournament.');
                    }
                },
                error: function(xhr, status, error) {
                    // Error handling
                    if (xhr.status === 422) {
                        // Validation errors
                        const errors = xhr.responseJSON.errors;

                        // Clear previous validation states
                        $(form).find('.is-invalid').removeClass('is-invalid');

                        // Show validation errors
                        $.each(errors, function(field, messages) {
                            const input = $(`[name="${field}"]`);
                            if (input.length) {
                                input.addClass('is-invalid');

                                // Update error message if custom feedback exists
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
                    // Re-enable form and reset submitting flag
                    isSubmitting = false;
                    toggleFormState(false);
                }
            });
        });

        // Real-time validation for better UX
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

        // Set minimum date to today for tournament start
        const today = new Date();
        const todayString = today.toISOString().slice(0, 10); // Format: YYYY-MM-DD
        document.getElementById('tournament_start').min = todayString;

        // Cancel button functionality
        $('#cancelBtn').on('click', function() {
            if (!isSubmitting && confirm('Are you sure you want to cancel? Any unsaved changes will be lost.')) {
                window.location.href = '/admin/tournaments';
            }
        });
    });
</script>
@endsection