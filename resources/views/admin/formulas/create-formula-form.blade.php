@extends('layouts.app')

@section('content')


<div class="container-fluid py-4 px-4">
    <div class="row">
        <div class="col-12">
            <div class="form-container fade-in">
                <!-- Form Body -->
                <div class="form-body">
                    <h3 class="mb-2 mt-0 text-center text-primary">
                        <i class="fas fa-plus-circle me-2 text-primary"></i>
                        Add New Golf Formula
                    </h3>
                    <p class="text-muted text-center mb-1">Create a new golf formula in the system</p>

                    <form class="needs-validation" novalidate id="mainForm">
                        @csrf
                        <!-- Formula Basic Information Section -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-info-circle"></i>
                                Formula Information
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="formula_name" name="formula_name" placeholder="Formula Name" required>
                                        <label for="formula_name">Formula Name *</label>
                                        <div class="invalid-feedback">
                                            Please provide a valid formula name.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="formula_ver" name="formula_code" placeholder="Formula Code" required minlength="2" maxlength="10">
                                        <label for="formula_code">Formula Code *</label>
                                        <div class="invalid-feedback">
                                            Please provide a valid formula code (2-10 characters).
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" id="formula_desc" name="formula_desc" placeholder="Formula Description" style="height: 120px;" maxlength="500"></textarea>
                                        <label for="formula_desc">Formula Description</label>
                                        <div class="invalid-feedback">
                                            Formula description cannot exceed 500 characters.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Formula Settings Section -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-cog"></i>
                                Formula Settings
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="formula_type_id" name="formula_type_id" required>
                                            <option value="">Choose...</option>
                                            @foreach($formulaTypes as $type)
                                            <option value="{{ $type->formula_type_id }}">{{ $type->formula_type_name }}</option>
                                            @endforeach

                                        </select>
                                        <label for="formula_type_id">Formula Type *</label>
                                        <div class="invalid-feedback">
                                            Please select a formula type.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="course_id" name="course_id" required>
                                            <option value="">Choose...</option>
                                            @foreach($courses as $course)
                                            <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
                                            @endforeach

                                        </select>
                                        <label for="course_id">Course *</label>
                                        <div class="invalid-feedback">
                                            Please select a course.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-check-modern">
                                <input class="form-check-input" type="checkbox" id="status" name="status" checked>
                                <label class="form-check-label" for="status">
                                    <strong>Active Formula</strong>
                                    <br>
                                    <small class="text-muted">Check if this formula is currently active for scoring</small>
                                </label>
                            </div>
                        </div>



                        <!-- Additional Remarks Section -->
                        <!-- Variables Section -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-list"></i>
                                Variables
                            </div>
                            <div id="variables-list">
                                <div class="row mb-2 variable-row">
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" name="variables[0][name]" placeholder="Variable Name" required>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" name="variables[0][value]" placeholder="Value" required>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger remove-variable">Remove</button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success" id="add-variable">Add Variable</button>
                        </div>

                        <!-- Expression Section -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-calculator"></i>
                                Expression
                            </div>
                            <div class="form-floating">
                                <textarea class="form-control" id="formula_expression" name="formula_expression" placeholder="e.g. (score - par) * slope" style="height: 80px;" required></textarea>
                                <label for="formula_expression">Expression *</label>
                                <div class="invalid-feedback">
                                    Please provide a valid formula expression.
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
                                <textarea class="form-control" id="remarks" name="remarks" placeholder="Enter any additional remarks about this formula..." maxlength="1000"></textarea>
                                <label for="remarks">Formula Remarks (Optional)</label>
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
                                        <i class="fas fa-plus me-2"></i>
                                        Create Formula
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

</style>

<script>
    $(document).ready(function() {
        // Dynamic variable add/remove
        let variableIndex = 1;
        document.getElementById('add-variable').addEventListener('click', function() {
            const list = document.getElementById('variables-list');
            const row = document.createElement('div');
            row.className = 'row mb-2 variable-row';
            row.innerHTML = `<div class=\"col-md-5\">\n<input type=\"text\" class=\"form-control\" name=\"variables[${variableIndex}][name]\" placeholder=\"Variable Name\" required>\n</div>\n<div class=\"col-md-5\">\n<input type=\"text\" class=\"form-control\" name=\"variables[${variableIndex}][value]\" placeholder=\"Value\" required>\n</div>\n<div class=\"col-md-2\">\n<button type=\"button\" class=\"btn btn-danger remove-variable\">Remove</button>\n</div>`;
            list.appendChild(row);
            variableIndex++;
        });
        document.getElementById('variables-list').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-variable')) {
                e.target.closest('.variable-row').remove();
            }
        });

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
                url: '{{ route("admin.formulas.store") }}',
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
                        showModal('success', 'Success!', 'Formula created successfully!');

                        // Reset form
                        form.reset();
                        form.classList.remove('was-validated');

                        // Remove validation classes
                        $(form).find('.is-valid, .is-invalid').removeClass('is-valid is-invalid');

                        // Redirect after delay
                        setTimeout(function() {
                            window.location.href = response.redirect || '/admin/formulas';
                        }, 2000);
                    } else {
                        showModal('error', 'Error', response.message || 'An error occurred while creating the formula.');
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

        // Custom validation for formula code (uppercase and alphanumeric)
        const formulaCodeInput = document.getElementById('formula_code');
        if (formulaCodeInput) {
            formulaCodeInput.addEventListener('input', function() {
                // Convert to uppercase
                this.value = this.value.toUpperCase();

                // Check if alphanumeric only
                const isValid = /^[A-Z0-9]+$/.test(this.value) && this.value.length >= 2;

                if (this.value.length > 0) {
                    if (isValid) {
                        this.setCustomValidity('');
                    } else {
                        this.setCustomValidity('Formula code must be alphanumeric (letters and numbers only)');
                    }
                }
            });
        }

        // Cancel button functionality
        $('#cancelBtn').on('click', function() {
            if (!isSubmitting && confirm('Are you sure you want to cancel? Any unsaved changes will be lost.')) {
                window.location.href = '/admin/formulas';
            }
        });
    });
</script>
@endsection