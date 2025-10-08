<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Valley Golf Handicapping System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>

    </script>

    <script src="{{ asset('js/handicapping.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/handicapping.css') }}">
</head>

<body class="pt-5">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-golf-ball me-2"></i>Handicapping System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">My Scores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Handicap</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Settings</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Reports
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Score History</a></li>
                            <li><a class="dropdown-item" href="#">Handicap Trends</a></li>
                            <li><a class="dropdown-item" href="#">Course Scorecards</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Tournament Results</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>Account
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-3">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 d-none">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Valley Golf Handicapping System</h5>
                    <p class="mb-0">Your trusted partner for golf handicap management.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; {{ date('Y') }} Valley Golf. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scorecard Management Modal -->
    <div class="modal fade" id="scorecardModal" tabindex="-1" aria-labelledby="scorecardModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scorecardModalLabel">
                        <i class="fas fa-map me-2"></i>Scorecard Management
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Course Selection/Creation -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <h6 class="text-primary">Select or Create Course</h6>
                            <select class="form-select" id="courseSelect">
                                <option value="">Select a course...</option>
                                <option value="1">Pebble Beach Golf Links</option>
                                <option value="2">Augusta National Golf Club</option>
                                <option value="3">St. Andrews Old Course</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-primary">&nbsp;</h6>
                            <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#newCourseModal">
                                <i class="fas fa-plus me-2"></i>New Course
                            </button>
                        </div>
                    </div>

                    <!-- Course Information Display -->
                    <div id="courseInfo" class="card mb-4" style="display: none;">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">Course Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Course Name:</strong> <span id="courseName">-</span><br>
                                    <strong>Location:</strong> <span id="courseLocation">-</span><br>
                                    <strong>Architect:</strong> <span id="courseArchitect">-</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Year Built:</strong> <span id="courseYear">-</span><br>
                                    <strong>Type:</strong> <span id="courseType">-</span><br>
                                    <strong>Holes:</strong> <span id="courseHoles">18</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tee Information -->
                    <div id="teeSection" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-primary mb-0">Tee Information</h6>
                            <button class="btn btn-sm btn-outline-primary" id="addTeeBtn">
                                <i class="fas fa-plus me-1"></i>Add Tee
                            </button>
                        </div>

                        <!-- Tee Cards Container -->
                        <div id="teeCards" class="row g-3 mb-4">
                            <!-- Tee cards will be dynamically added here -->
                        </div>

                        <!-- Scorecard Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="scorecardTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Hole</th>
                                        <th>Par</th>
                                        <th class="tee-yardage" data-tee="championship" style="display: none;">Championship<br><small>Yardage</small></th>
                                        <th class="tee-yardage" data-tee="mens" style="display: none;">Men's<br><small>Yardage</small></th>
                                        <th class="tee-yardage" data-tee="womens" style="display: none;">Women's<br><small>Yardage</small></th>
                                        <th class="tee-yardage" data-tee="senior" style="display: none;">Senior<br><small>Yardage</small></th>
                                        <th>Handicap</th>
                                    </tr>
                                </thead>
                                <tbody id="scorecardBody">
                                    <!-- Scorecard rows will be generated here -->
                                </tbody>
                                <tfoot class="table-secondary">
                                    <tr>
                                        <th>Total</th>
                                        <th id="totalPar">72</th>
                                        <th class="tee-total" data-tee="championship" style="display: none;">0</th>
                                        <th class="tee-total" data-tee="mens" style="display: none;">0</th>
                                        <th class="tee-total" data-tee="womens" style="display: none;">0</th>
                                        <th class="tee-total" data-tee="senior" style="display: none;">0</th>
                                        <th>-</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveScorecard">
                        <i class="fas fa-save me-2"></i>Save Scorecard
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- New Course Modal -->
    <div class="modal fade" id="newCourseModal" tabindex="-1" aria-labelledby="newCourseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newCourseModalLabel">
                        <i class="fas fa-plus me-2"></i>Create New Course
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="newCourseForm">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="newCourseName" class="form-label">Course Name *</label>
                                    <input type="text" class="form-control" id="newCourseName" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="newCourseHoles" class="form-label">Number of Holes</label>
                                    <select class="form-select" id="newCourseHoles">
                                        <option value="9">9 Holes</option>
                                        <option value="18" selected>18 Holes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="newCourseLocation" class="form-label">Location</label>
                                    <input type="text" class="form-control" id="newCourseLocation" placeholder="City, State">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="newCourseType" class="form-label">Course Type</label>
                                    <select class="form-select" id="newCourseType">
                                        <option value="Public">Public</option>
                                        <option value="Private">Private</option>
                                        <option value="Semi-Private">Semi-Private</option>
                                        <option value="Resort">Resort</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="newCourseArchitect" class="form-label">Architect</label>
                                    <input type="text" class="form-control" id="newCourseArchitect">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="newCourseYear" class="form-label">Year Built</label>
                                    <input type="number" class="form-control" id="newCourseYear" min="1800" max="2025">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="createCourse">
                        <i class="fas fa-plus me-2"></i>Create Course
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tee Information Modal -->
    <div class="modal fade" id="teeModal" tabindex="-1" aria-labelledby="teeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="teeModalLabel">
                        <i class="fas fa-flag me-2"></i>Tee Information
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="teeForm">
                        <div class="mb-3">
                            <label for="teeName" class="form-label">Tee Name *</label>
                            <input type="text" class="form-control" id="teeName" placeholder="e.g., Championship, Men's, Women's" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="teeColor" class="form-label">Tee Color</label>
                                    <select class="form-select" id="teeColor">
                                        <option value="Black">Black</option>
                                        <option value="Blue">Blue</option>
                                        <option value="White">White</option>
                                        <option value="Yellow">Yellow</option>
                                        <option value="Red">Red</option>
                                        <option value="Green">Green</option>
                                        <option value="Gold">Gold</option>
                                        <option value="Silver">Silver</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="teeGender" class="form-label">Typical Gender</label>
                                    <select class="form-select" id="teeGender">
                                        <option value="Men">Men</option>
                                        <option value="Women">Women</option>
                                        <option value="Mixed">Mixed</option>
                                        <option value="Senior">Senior</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="courseRating" class="form-label">Course Rating *</label>
                                    <input type="number" class="form-control" id="courseRating" step="0.1" min="60" max="85" required>
                                    <div class="form-text">Expected score for a scratch golfer</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slopeRating" class="form-label">Slope Rating *</label>
                                    <input type="number" class="form-control" id="slopeRating" min="55" max="155" required>
                                    <div class="form-text">Course difficulty (55-155, 113 = average)</div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveTee">
                        <i class="fas fa-save me-2"></i>Save Tee
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Compact Bootstrap Modal for Messages -->
    <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm compact">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h6 class="modal-title" id="messageModalLabel">
                        <i class="fas fa-info-circle me-2" id="modalIcon"></i>
                        <span id="modalTitle">Notification</span>
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <p class="mb-0" id="modalMessage">Message content here</p>
                    <div id="errorList" class="mt-2" style="display: none;">
                        <small class="text-muted">Validation errors:</small>
                        <ul class="list-unstyled mt-1" id="errorItems"></ul>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
    <script>
        // Function to show modal messages
        function showModal(type, title, message, errors = null) {
            const modal = $("#messageModal");
            const modalContent = modal.find(".modal-content");
            const modalIcon = $("#modalIcon");
            const modalTitle = $("#modalTitle");
            const modalMessage = $("#modalMessage");
            const errorList = $("#errorList");
            const errorItems = $("#errorItems");

            // Reset modal classes
            modalContent.removeClass(
                "modal-success modal-error modal-warning modal-info"
            );

            // Set modal type and icon
            switch (type) {
                case "success":
                    modalContent.addClass("modal-success");
                    modalIcon.removeClass().addClass("fas fa-check-circle me-2");
                    break;
                case "error":
                    modalContent.addClass("modal-error");
                    modalIcon
                        .removeClass()
                        .addClass("fas fa-exclamation-circle me-2");
                    break;
                case "warning":
                    modalContent.addClass("modal-warning");
                    modalIcon
                        .removeClass()
                        .addClass("fas fa-exclamation-triangle me-2");
                    break;
                case "info":
                    modalContent.addClass("modal-info");
                    modalIcon.removeClass().addClass("fas fa-info-circle me-2");
                    break;
                default:
                    modalContent.addClass("modal-info");
                    modalIcon.removeClass().addClass("fas fa-info-circle me-2");
            }

            // Set content
            modalTitle.text(title);
            modalMessage.text(message);

            // Handle validation errors
            if (errors && Object.keys(errors).length > 0) {
                errorItems.empty();
                $.each(errors, function(field, messages) {
                    if (Array.isArray(messages)) {
                        messages.forEach((msg) => {
                            errorItems.append(`<li>${msg}</li>`);
                        });
                    } else {
                        errorItems.append(`<li>${messages}</li>`);
                    }
                });
                errorList.show();
            } else {
                errorList.hide();
            }

            // Show modal
            modal.modal("show");
        }
    </script>

    @include('partials.error-modal')




</body>

</html>