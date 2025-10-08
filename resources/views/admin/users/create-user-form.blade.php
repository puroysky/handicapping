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
                        Add New User
                    </h3>
                    <p class="text-muted text-center mb-1">Create a new user account in the system</p>

                    <form class="needs-validation" novalidate id="userForm">
                        @csrf
                        <!-- Personal Information Section -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-user"></i>
                                Personal Information
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" required maxlength="50">
                                        <label for="first_name">First Name *</label>
                                        <div class="invalid-feedback">
                                            Please provide a valid first name.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="middle_name" name="middle_name" placeholder="Middle Name" maxlength="50">
                                        <label for="middle_name">Middle Name</label>
                                        <div class="invalid-feedback">
                                            Middle name cannot exceed 50 characters.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" required maxlength="50">
                                        <label for="last_name">Last Name *</label>
                                        <div class="invalid-feedback">
                                            Please provide a valid last name.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required>
                                        <label for="email">Email Address *</label>
                                        <div class="invalid-feedback">
                                            Please provide a valid email address.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="Phone Number" maxlength="15">
                                        <label for="phone">Phone Number</label>
                                        <div class="invalid-feedback">
                                            Please provide a valid phone number.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" id="birth_date" name="birth_date" placeholder="Birth Date" required>
                                        <label for="birth_date">Birth Date *</label>
                                        <div class="invalid-feedback">
                                            Please provide a valid birth date.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="sex" name="sex" required>
                                            <option value="">Choose...</option>
                                            <option value="MALE">Male</option>
                                            <option value="FEMALE">Female</option>
                                        </select>
                                        <label for="sex">Sex *</label>
                                        <div class="invalid-feedback">
                                            Please select a sex.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" id="address" name="address" placeholder="Address" style="height: 100px;"></textarea>
                                        <label for="address">Address</label>
                                        <div class="invalid-feedback">
                                            Please provide a valid address.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Settings Section -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-cog"></i>
                                Account Settings
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="role" name="role" required>
                                            <option value="">Choose...</option>
                                            <option value="user" selected>User</option>
                                            <option value="admin">Administrator</option>
                                        </select>
                                        <label for="role">User Role *</label>
                                        <div class="invalid-feedback">
                                            Please select a user role.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="user_desc" name="user_desc" placeholder="Description" maxlength="100">
                                        <label for="user_desc">User Description</label>
                                        <div class="invalid-feedback">
                                            Description cannot exceed 100 characters.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- User-specific fields (shown only when role is 'user') -->
                            <div class="row" id="userFields" style="display: none;">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="account_no" name="account_no" placeholder="Account Number" maxlength="20">
                                        <label for="account_no">Account Number *</label>
                                        <div class="invalid-feedback">
                                            Please provide a valid account number.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="whs_no" name="whs_no" placeholder="WHS Number" maxlength="20">
                                        <label for="whs_no">WHS Number *</label>
                                        <div class="invalid-feedback">
                                            Please provide a valid WHS number.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-check-modern">
                                <input class="form-check-input" type="checkbox" id="active" name="active" checked>
                                <label class="form-check-label" for="active">
                                    <strong>Active User</strong>
                                    <br>
                                    <small class="text-muted">Check if this user account is currently active</small>
                                </label>
                            </div>
                        </div>



                        <!-- Additional Remarks Section -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-sticky-note"></i>
                                Additional Remarks
                            </div>

                            <div class="form-floating">
                                <textarea class="form-control" id="remarks" name="remarks" placeholder="Enter any additional remarks about this user..." style="height: 120px;"></textarea>
                                <label for="remarks">Remarks (Optional)</label>
                                <div class="invalid-feedback">
                                    Please provide valid remarks.
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
                                        Create User
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

        let isSubmitting = false; // Flag to prevent multiple submissions

        // Function to disable/enable form
        function toggleFormState(disabled) {
            const form = document.getElementById('userForm');
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
        $(document).on('submit', '#userForm', function(e) {
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



            // Prepare form data manually to ensure all fields are captured
            const formData = new FormData();

            // Add CSRF token first
            const csrfToken = $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val();
            formData.append('_token', csrfToken);

            // Manually collect all form values using jQuery
            const formValues = {
                'first_name': $('#first_name').val(),
                'middle_name': $('#middle_name').val(),
                'last_name': $('#last_name').val(),
                'email': $('#email').val(),
                'phone': $('#phone').val(),
                'birth_date': $('#birth_date').val(),
                'sex': $('#sex').val(),
                'address': $('#address').val(),
                'role': $('#role').val(),
                'user_desc': $('#user_desc').val(),
                'account_no': $('#account_no').val(),
                'whs_no': $('#whs_no').val(),
                'remarks': $('#remarks').val(),
                'active': $('#active').is(':checked') ? '1' : '0'
            };

            // Append each field to FormData
            Object.keys(formValues).forEach(key => {
                if (formValues[key] !== null && formValues[key] !== undefined) {
                    formData.append(key, formValues[key]);
                }
            });

            // Set submitting flag and disable form
            isSubmitting = true;
            toggleFormState(true);





            // Make AJAX request
            $.ajax({
                url: '{{ route("admin.users.store") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                timeout: 30000, // 30 seconds timeout
                success: function(response) {
                    // Success handling
                    if (response.success) {
                        // Show success message

                        showModal('success', 'Success', 'User created successfully!');
                        // Reset form
                        form.reset();
                        form.classList.remove('was-validated');

                        // Remove validation classes
                        $(form).find('.is-valid, .is-invalid').removeClass('is-valid is-invalid');

                        // Redirect after delay
                        setTimeout(function() {
                            window.location.href = response.redirect || '/admin/users';
                        }, 2000);
                    } else {
                        showModal('error', 'Error', response.message || 'An error occurred while creating the user.');
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
        const inputs = document.querySelectorAll('#userForm input, #userForm select, #userForm textarea');

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

        // Email validation
        const emailInput = document.getElementById('email');
        if (emailInput) {
            emailInput.addEventListener('input', function() {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (this.value.length > 0) {
                    if (emailPattern.test(this.value)) {
                        this.setCustomValidity('');
                    } else {
                        this.setCustomValidity('Please enter a valid email address');
                    }
                }
            });
        }

        // Phone number formatting
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function() {
                // Remove all non-digit characters
                let value = this.value.replace(/\D/g, '');

                // Limit to 15 characters
                if (value.length > 15) {
                    value = value.substring(0, 15);
                }

                this.value = value;
            });
        }

        // Role-based field visibility and validation
        const roleSelect = document.getElementById('role');
        const userFields = document.getElementById('userFields');
        const accountNoInput = document.getElementById('account_no');
        const whsNoInput = document.getElementById('whs_no');

        function toggleUserFields() {
            const selectedRole = roleSelect.value;

            if (selectedRole === 'user') {
                userFields.style.display = 'block';
                // Make fields required
                accountNoInput.setAttribute('required', 'required');
                whsNoInput.setAttribute('required', 'required');
            } else {
                userFields.style.display = 'none';
                // Remove required attribute and clear values
                accountNoInput.removeAttribute('required');
                whsNoInput.removeAttribute('required');
                accountNoInput.value = '';
                whsNoInput.value = '';
                // Remove validation classes
                accountNoInput.classList.remove('is-valid', 'is-invalid');
                whsNoInput.classList.remove('is-valid', 'is-invalid');
            }
        }

        // Initialize on page load
        toggleUserFields();

        // Add event listener for role changes
        if (roleSelect) {
            roleSelect.addEventListener('change', toggleUserFields);
        }

        // Cancel button functionality
        $('#cancelBtn').on('click', function() {
            if (!isSubmitting && confirm('Are you sure you want to cancel? Any unsaved changes will be lost.')) {
                window.location.href = '/admin/users';
            }
        });
    });
</script>
@endsection