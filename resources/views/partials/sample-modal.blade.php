{{-- 
    Modern Modal Component - Clean & Simple Design
    Usage: @include('partials.sample-modal')
    Trigger: onclick="showSampleModal()"
--}}

<div class="modal fade modern-modal" id="modernModal" tabindex="-1" aria-hidden="true" data-position="right">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            
            {{-- HEADER --}}
            <div class="modal-header bg-primary-gradient">
                <h5 class="modal-title text-white fw-semibold">
                    <i class="fas fa-clipboard-list me-2"></i>Player Profile Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            {{-- BODY --}}
            <div class="modal-body p-2">
                
                {{-- Section 1: Input Fields --}}
                <div class="modern-modal-section-box mb-2">
                    <div class="modern-modal-section-header">
                        <i class="fas fa-user-circle me-1"></i>
                        <span>Personal Information</span>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control form-control-sm" value="John Michael Doe" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control form-control-sm" value="john.doe@golf.com" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" class="form-control form-control-sm" value="+1 (555) 123-4567" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Member ID</label>
                            <input type="text" class="form-control form-control-sm" value="MEM-2024-1234" readonly>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Dropdowns & Date --}}
                <div class="modern-modal-section-box mb-2">
                    <div class="modern-modal-section-header">
                        <i class="fas fa-cog me-1"></i>
                        <span>Membership Settings</span>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label">Membership Type</label>
                            <select class="form-select form-select-sm">
                                <option>Standard</option>
                                <option selected>Premium</option>
                                <option>VIP Elite</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Home Course</label>
                            <select class="form-select form-select-sm">
                                <option selected>North Course</option>
                                <option>South Course</option>
                                <option>East Course</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Account Status</label>
                            <select class="form-select form-select-sm">
                                <option selected>Active</option>
                                <option>Inactive</option>
                                <option>Suspended</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Join Date</label>
                            <input type="date" class="form-control form-control-sm" value="2024-01-15">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Renewal Date</label>
                            <input type="date" class="form-control form-control-sm" value="2025-01-15">
                        </div>
                    </div>
                </div>

                {{-- Section 3: Checkboxes, Radio & Toggles --}}
                <div class="modern-modal-section-box mb-2">
                    <div class="modern-modal-section-header">
                        <i class="fas fa-bell me-1"></i>
                        <span>Preferences & Options</span>
                    </div>
                    
                    {{-- Toggle Switches --}}
                    <div class="mb-2">
                        <small class="text-muted fw-semibold d-block mb-1">Notifications</small>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" id="emailNotif" checked>
                                    <label class="form-check-label" for="emailNotif">
                                        <i class="fas fa-envelope text-primary me-1"></i>Email
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" id="smsNotif">
                                    <label class="form-check-label" for="smsNotif">
                                        <i class="fas fa-sms text-success me-1"></i>SMS
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" id="publicProfile" checked>
                                    <label class="form-check-label" for="publicProfile">
                                        <i class="fas fa-globe text-info me-1"></i>Public
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Radio Buttons --}}
                    <div class="mb-2">
                        <small class="text-muted fw-semibold d-block mb-1">Preferred Contact Method</small>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="contactMethod" id="contactEmail" checked>
                                <label class="form-check-label" for="contactEmail">Email</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="contactMethod" id="contactPhone">
                                <label class="form-check-label" for="contactPhone">Phone</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="contactMethod" id="contactBoth">
                                <label class="form-check-label" for="contactBoth">Both</label>
                            </div>
                        </div>
                    </div>

                    {{-- Standard Checkboxes --}}
                    <div>
                        <small class="text-muted fw-semibold d-block mb-1">Additional Options</small>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="newsletter" checked>
                                <label class="form-check-label" for="newsletter">Newsletter</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="events">
                                <label class="form-check-label" for="events">Event Invites</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="promotions" checked>
                                <label class="form-check-label" for="promotions">Promotions</label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 4: Number, Range & Textarea --}}
                <div class="modern-modal-section-box mb-2">
                    <div class="modern-modal-section-header">
                        <i class="fas fa-sliders-h me-1"></i>
                        <span>Additional Fields</span>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label">Handicap Index</label>
                            <input type="number" class="form-control form-control-sm" value="15.2" step="0.1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Age</label>
                            <input type="number" class="form-control form-control-sm" value="45" min="0" max="120">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Years Playing</label>
                            <input type="number" class="form-control form-control-sm" value="12" min="0">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Skill Level (1-10): <span class="text-primary fw-bold" id="skillValue">7</span></label>
                            <input type="range" class="form-range" min="1" max="10" value="7" id="skillRange" oninput="document.getElementById('skillValue').textContent = this.value">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Notes / Comments</label>
                            <textarea class="form-control form-control-sm" rows="2" placeholder="Add any additional notes here...">Prefers early morning tee times. Regular participant in club tournaments.</textarea>
                        </div>
                    </div>
                </div>

                {{-- Section 5: Stats Cards --}}
                <div class="modern-modal-section-box mb-2">
                    <div class="modern-modal-section-header">
                        <i class="fas fa-chart-bar me-1"></i>
                        <span>Performance Statistics</span>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-3">
                            <div class="modern-modal-stat-card">
                                <div class="modern-modal-stat-icon">
                                    <i class="fas fa-golf-ball"></i>
                                </div>
                                <div class="modern-modal-stat-value">47</div>
                                <div class="modern-modal-stat-label">Rounds</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="modern-modal-stat-card">
                                <div class="modern-modal-stat-icon text-success">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="modern-modal-stat-value text-success">15.2</div>
                                <div class="modern-modal-stat-label">Handicap</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="modern-modal-stat-card">
                                <div class="modern-modal-stat-icon text-warning">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="modern-modal-stat-value text-warning">82</div>
                                <div class="modern-modal-stat-label">Avg Score</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="modern-modal-stat-card">
                                <div class="modern-modal-stat-icon text-danger">
                                    <i class="fas fa-flag"></i>
                                </div>
                                <div class="modern-modal-stat-value text-danger">76</div>
                                <div class="modern-modal-stat-label">Best</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 6: Data Table --}}
                <div class="modern-modal-section-box mb-0">
                    <div class="modern-modal-section-header">
                        <i class="fas fa-table me-1"></i>
                        <span>Recent Rounds</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">Date</th>
                                    <th class="border-0">Course</th>
                                    <th class="border-0 text-center">Score</th>
                                    <th class="border-0 text-center">Diff</th>
                                    <th class="border-0 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="fw-semibold">Nov 1, 2025</div>
                                        <small class="text-muted d-block lh-1">Morning</small>
                                    </td>
                                    <td>North Course</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">78</span>
                                    </td>
                                    <td class="text-center">12.5</td>
                                    <td class="text-center">
                                        <span class="badge bg-success">Posted</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="fw-semibold">Oct 28, 2025</div>
                                        <small class="text-muted d-block lh-1">Afternoon</small>
                                    </td>
                                    <td>South Course</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">82</span>
                                    </td>
                                    <td class="text-center">14.2</td>
                                    <td class="text-center">
                                        <span class="badge bg-success">Posted</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="fw-semibold">Oct 20, 2025</div>
                                        <small class="text-muted d-block lh-1">Morning</small>
                                    </td>
                                    <td>North Course</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">85</span>
                                    </td>
                                    <td class="text-center">15.8</td>
                                    <td class="text-center">
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Section 7: Recent Activity / Audit Trail --}}
                <div class="modern-modal-section-box mb-0">
                    <div class="modern-modal-section-header">
                        <i class="fas fa-history me-1"></i>
                        <span>Recent Activity & Audit Trail</span>
                    </div>
                    <div class="modern-modal-activity-timeline">
                        {{-- Activity Item 1 --}}
                        <div class="modern-modal-activity-item">
                            <div class="modern-modal-activity-icon bg-success">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="modern-modal-activity-content">
                                <div class="modern-modal-activity-title">Score Posted</div>
                                <div class="modern-modal-activity-description">
                                    Round score of 78 submitted for North Course
                                </div>
                                <div class="modern-modal-activity-meta">
                                    <span><i class="fas fa-user me-1"></i>John Doe</span>
                                    <span><i class="fas fa-clock me-1"></i>2 hours ago</span>
                                    <span><i class="fas fa-desktop me-1"></i>Web Portal</span>
                                </div>
                            </div>
                        </div>

                        {{-- Activity Item 2 --}}
                        <div class="modern-modal-activity-item">
                            <div class="modern-modal-activity-icon bg-primary">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div class="modern-modal-activity-content">
                                <div class="modern-modal-activity-title">Profile Updated</div>
                                <div class="modern-modal-activity-description">
                                    Email address changed from old@email.com to john.doe@golf.com
                                </div>
                                <div class="modern-modal-activity-meta">
                                    <span><i class="fas fa-user me-1"></i>John Doe</span>
                                    <span><i class="fas fa-clock me-1"></i>1 day ago</span>
                                    <span><i class="fas fa-mobile-alt me-1"></i>Mobile App</span>
                                </div>
                            </div>
                        </div>

                        {{-- Activity Item 3 --}}
                        <div class="modern-modal-activity-item">
                            <div class="modern-modal-activity-icon bg-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="modern-modal-activity-content">
                                <div class="modern-modal-activity-title">Handicap Adjusted</div>
                                <div class="modern-modal-activity-description">
                                    Handicap index changed from 15.8 to 15.2 based on recent rounds
                                </div>
                                <div class="modern-modal-activity-meta">
                                    <span><i class="fas fa-user me-1"></i>System Auto</span>
                                    <span><i class="fas fa-clock me-1"></i>3 days ago</span>
                                    <span><i class="fas fa-cog me-1"></i>Automated</span>
                                </div>
                            </div>
                        </div>

                        {{-- Activity Item 4 --}}
                        <div class="modern-modal-activity-item">
                            <div class="modern-modal-activity-icon bg-info">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="modern-modal-activity-content">
                                <div class="modern-modal-activity-title">Tournament Registration</div>
                                <div class="modern-modal-activity-description">
                                    Registered for "Summer Classic 2025" tournament
                                </div>
                                <div class="modern-modal-activity-meta">
                                    <span><i class="fas fa-user me-1"></i>John Doe</span>
                                    <span><i class="fas fa-clock me-1"></i>5 days ago</span>
                                    <span><i class="fas fa-desktop me-1"></i>Web Portal</span>
                                </div>
                            </div>
                        </div>

                        {{-- Activity Item 5 --}}
                        <div class="modern-modal-activity-item">
                            <div class="modern-modal-activity-icon bg-danger">
                                <i class="fas fa-times"></i>
                            </div>
                            <div class="modern-modal-activity-content">
                                <div class="modern-modal-activity-title">Score Rejected</div>
                                <div class="modern-modal-activity-description">
                                    Score submission rejected - invalid scorecard data
                                </div>
                                <div class="modern-modal-activity-meta">
                                    <span><i class="fas fa-user me-1"></i>Admin User</span>
                                    <span><i class="fas fa-clock me-1"></i>1 week ago</span>
                                    <span><i class="fas fa-shield-alt me-1"></i>Admin Panel</span>
                                </div>
                            </div>
                        </div>

                        {{-- Activity Item 6 --}}
                        <div class="modern-modal-activity-item mb-0">
                            <div class="modern-modal-activity-icon bg-secondary">
                                <i class="fas fa-sign-in-alt"></i>
                            </div>
                            <div class="modern-modal-activity-content">
                                <div class="modern-modal-activity-title">Account Login</div>
                                <div class="modern-modal-activity-description">
                                    Successful login from IP: 192.168.1.100
                                </div>
                                <div class="modern-modal-activity-meta">
                                    <span><i class="fas fa-user me-1"></i>John Doe</span>
                                    <span><i class="fas fa-clock me-1"></i>2 weeks ago</span>
                                    <span><i class="fas fa-mobile-alt me-1"></i>Mobile App</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 8: Golf Scorecard --}}
                <div class="modern-modal-section-box mb-0">
                    <div class="modern-modal-section-header">
                        <i class="fas fa-golf-ball me-1"></i>
                        <span>Scorecard - North Course</span>
                    </div>
                    
                    <div class="modern-modal-scorecard">
                        {{-- Course Info --}}
                        <div class="modern-modal-scorecard-info mb-2">
                            <div class="row g-2">
                                <div class="col-6 col-md-3">
                                    <small class="text-muted d-block">Date</small>
                                    <strong>Nov 1, 2025</strong>
                                </div>
                                <div class="col-6 col-md-3">
                                    <small class="text-muted d-block">Tee</small>
                                    <strong>Blue Tees</strong>
                                </div>
                                <div class="col-6 col-md-3">
                                    <small class="text-muted d-block">Handicap Index</small>
                                    <strong class="text-primary">15.2</strong>
                                </div>
                                <div class="col-6 col-md-3">
                                    <small class="text-muted d-block">Course Handicap</small>
                                    <strong class="text-success">18</strong>
                                </div>
                            </div>
                        </div>

                        {{-- Front 9 --}}
                        <div class="table-responsive mb-2">
                            <table class="modern-modal-scorecard-table">
                                <thead>
                                    <tr class="table-light">
                                        <th class="text-start">Hole</th>
                                        <th>1</th>
                                        <th>2</th>
                                        <th>3</th>
                                        <th>4</th>
                                        <th>5</th>
                                        <th>6</th>
                                        <th>7</th>
                                        <th>8</th>
                                        <th>9</th>
                                        <th class="modern-modal-total-col">OUT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-start fw-semibold">Par</td>
                                        <td>4</td>
                                        <td>3</td>
                                        <td>5</td>
                                        <td>4</td>
                                        <td>4</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>5</td>
                                        <td>4</td>
                                        <td class="modern-modal-total-col fw-bold">36</td>
                                    </tr>
                                    <tr>
                                        <td class="text-start fw-semibold">Score</td>
                                        <td class="modern-modal-score-cell">5</td>
                                        <td class="modern-modal-score-cell modern-modal-birdie">2</td>
                                        <td class="modern-modal-score-cell">5</td>
                                        <td class="modern-modal-score-cell modern-modal-bogey">5</td>
                                        <td class="modern-modal-score-cell">4</td>
                                        <td class="modern-modal-score-cell">3</td>
                                        <td class="modern-modal-score-cell modern-modal-bogey">5</td>
                                        <td class="modern-modal-score-cell modern-modal-par">5</td>
                                        <td class="modern-modal-score-cell">4</td>
                                        <td class="modern-modal-total-col fw-bold text-primary">38</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Back 9 --}}
                        <div class="table-responsive mb-2">
                            <table class="modern-modal-scorecard-table">
                                <thead>
                                    <tr class="table-light">
                                        <th class="text-start">Hole</th>
                                        <th>10</th>
                                        <th>11</th>
                                        <th>12</th>
                                        <th>13</th>
                                        <th>14</th>
                                        <th>15</th>
                                        <th>16</th>
                                        <th>17</th>
                                        <th>18</th>
                                        <th class="modern-modal-total-col">IN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-start fw-semibold">Par</td>
                                        <td>4</td>
                                        <td>4</td>
                                        <td>3</td>
                                        <td>5</td>
                                        <td>4</td>
                                        <td>4</td>
                                        <td>3</td>
                                        <td>5</td>
                                        <td>4</td>
                                        <td class="modern-modal-total-col fw-bold">36</td>
                                    </tr>
                                    <tr>
                                        <td class="text-start fw-semibold">Score</td>
                                        <td class="modern-modal-score-cell">4</td>
                                        <td class="modern-modal-score-cell modern-modal-bogey">5</td>
                                        <td class="modern-modal-score-cell">3</td>
                                        <td class="modern-modal-score-cell modern-modal-par">5</td>
                                        <td class="modern-modal-score-cell">4</td>
                                        <td class="modern-modal-score-cell modern-modal-bogey">5</td>
                                        <td class="modern-modal-score-cell modern-modal-birdie">2</td>
                                        <td class="modern-modal-score-cell">5</td>
                                        <td class="modern-modal-score-cell">4</td>
                                        <td class="modern-modal-total-col fw-bold text-primary">40</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Score Summary --}}
                        <div class="modern-modal-score-summary">
                            <div class="row g-2">
                                <div class="col-6 col-md-3">
                                    <div class="modern-modal-summary-item">
                                        <div class="modern-modal-summary-label">Front 9</div>
                                        <div class="modern-modal-summary-value">38</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="modern-modal-summary-item">
                                        <div class="modern-modal-summary-label">Back 9</div>
                                        <div class="modern-modal-summary-value">40</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="modern-modal-summary-item modern-modal-summary-highlight">
                                        <div class="modern-modal-summary-label">Gross Total</div>
                                        <div class="modern-modal-summary-value">78</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="modern-modal-summary-item">
                                        <div class="modern-modal-summary-label">Adjusted Gross</div>
                                        <div class="modern-modal-summary-value">78</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-12">
                                    <div class="modern-modal-summary-item modern-modal-summary-net">
                                        <div class="modern-modal-summary-label">Competition Net Score</div>
                                        <div class="modern-modal-summary-value">63</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Score Legend --}}
                        <div class="modern-modal-score-legend">
                            <small class="text-muted">
                                <span class="modern-modal-legend-item">
                                    <span class="modern-modal-legend-dot modern-modal-birdie"></span>
                                    Birdie or Better
                                </span>
                                <span class="modern-modal-legend-item">
                                    <span class="modern-modal-legend-dot modern-modal-par"></span>
                                    Par
                                </span>
                                <span class="modern-modal-legend-item">
                                    <span class="modern-modal-legend-dot modern-modal-bogey"></span>
                                    Bogey or Worse
                                </span>
                            </small>
                        </div>
                    </div>
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Close
                </button>
                <button type="button" class="btn btn-outline-primary">
                    <i class="fas fa-print me-2"></i>Print
                </button>
                <button type="button" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Save Changes
                </button>
            </div>
            
        </div>
    </div>
</div>

<style>
    
</style>

<script>
    function showSampleModal() {
        const modal = document.getElementById('modernModal');
        if (typeof bootstrap !== 'undefined') {
            new bootstrap.Modal(modal).show();
        } else if (typeof $ !== 'undefined') {
            $('#modernModal').modal('show');
        }
    }
</script>
