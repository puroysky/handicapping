@extends('layouts.app')

@section('content')


<div class="container-fluid py-4 px-4">
    <div class="row">
        <div class="col-12">
            <div class="form-container fade-in">
                <!-- Form Body -->
                <div class="form-body">
                    <h3 class="mb-2 mt-0 text-center text-primary">
                        <i class="fas fa-user-plus me-2 text-primary"></i>
                        Add New Tournament Player
                    </h3>
                    <p class="text-muted text-center mb-1">Add a player to the tournament</p>

                    <form class="needs-validation" novalidate id="mainForm">
                        @csrf
                        <!-- Player Basic Information Section -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-user"></i>
                                Player Information
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="tournament_display_name" name="tournament_display_name" placeholder="Tournament Name" value="{{ $tournament->tournament_name ?? '' }}" required readonly>

                                        <input type="hidden" name="tournament_id" value="{{ $tournament->tournament_id ?? '' }}">
                                        <label for="tournament_display_name">Tournament *</label>
                                        <div class="invalid-feedback">
                                            Please provide a tournament name.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="player_profile_id" name="player_profile_id" required>
                                            <option value="">Select Player...</option>
                                            @if(isset($players))
                                                @foreach($players as $player)
                                                    <option value="{{ $player->player_profile_id }}">Account No: {{ $player->whs_no }} - {{ $player->userProfile->first_name }} {{ $player->userProfile->last_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <label for="player_profile_id">Player *</label>
                                        <div class="invalid-feedback">
                                            Please select a player.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Handicap Information Section -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-calculator"></i>
                                Handicap Information
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" id="whs_handicap_index" name="whs_handicap_index" placeholder="WHS Handicap Index" min="0" max="54" step="0.1">
                                        <label for="whs_handicap_index">WHS Handicap Index</label>
                                        <div class="invalid-feedback">
                                            Please provide a valid WHS handicap index (0-54).
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tees Section -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-golf-ball"></i>
                                Tee Selection
                            </div>

                            <div class="row">
                                @foreach ($tournament->tournamentCourses as $tournamentCourse)
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select class="form-select" id="tournament_course_tee_{{ $tournamentCourse->course_id }}" name="tournament_course_tee[{{ $tournamentCourse->course_id }}]" required>
                                                <option value="">Select Tee...</option>
                                                @foreach ($tournamentCourse->course->tees as $tee)
                                                    <option value="{{ $tee->tee_id }}">
                                                        {{ $tee->tee_code }} ({{ $tee->tee_name ?? 'N/A' }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label for="tournament_course_tee_{{ $tournamentCourse->course_id }}">{{ $tournamentCourse->course->course_name }} Tee *</label>
                                            <div class="invalid-feedback">
                                                Please select a tee for {{ $tournamentCourse->course->course_name }}.
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Additional Information Section -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-sticky-note"></i>
                                Additional Remarks
                            </div>

                            <div class="form-floating">
                                <textarea class="form-control" id="remarks" name="remarks" placeholder="Enter any additional remarks about this player..." maxlength="1000" style="height: 120px;"></textarea>
                                <label for="remarks">Player Remarks (Optional)</label>
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
                                        <i class="fas fa-user-plus me-2"></i>
                                        Add Player
                                    </span>
                                    <span class="btn-loading d-none">
                                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                        Adding...
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

        // AJAX form submission
        $(document).on('submit', '#mainForm', function(e) {
            e.preventDefault();


            // Prevent multiple submissions
            if (isSubmitting) {
                return false;
            }

            const form = this;

            // Validate form
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return false;
            }



            const formData = $('#mainForm').serializeArray();

            // Set submitting flag and disable form
            isSubmitting = true;
            toggleFormState(true);





            // Make AJAX request
            $.ajax({
                url: '{{ route("admin.participants.store") }}',
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
                        showModal('success', 'Success!', 'Tournament player added successfully!');

                        // Reset form
                        form.reset();
                        form.classList.remove('was-validated');

                        // Remove validation classes
                        $(form).find('.is-valid, .is-invalid').removeClass('is-valid is-invalid');

                        // Redirect after delay
                        setTimeout(function() {
                            window.location.href = response.redirect || '/admin/tournament-players';
                        }, 2000);
                    } else {
                        showModal('error', 'Error', response.message || 'An error occurred while adding the player.');
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

        // Bootstrap form validation (removed default behavior)
        const forms = document.querySelectorAll('.needs-validation');

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

        // Custom validation for handicap values
        const handicapInput = document.querySelector('#whs_handicap_index');
        if (handicapInput) {
            handicapInput.addEventListener('input', function() {
                const value = parseFloat(this.value);
                
                if (this.value && (isNaN(value) || value < 0 || value > 54)) {
                    this.setCustomValidity('WHS Handicap index must be between 0 and 54');
                } else {
                    this.setCustomValidity('');
                }
            });
        }

        // Cancel button functionality
        $('#cancelBtn').on('click', function() {
            if (!isSubmitting && confirm('Are you sure you want to cancel? Any unsaved changes will be lost.')) {
                window.location.href = '/admin/tournament-players';
            }
        });
    });
</script>
@endsection