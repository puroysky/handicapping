@extends('layouts.app')

@section('content')

<!-- Hero Section -->
<section class="hero-section bg-gradient text-white py-5">
    <div class="container">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3 text-primary mt-4">Welcome to Valley Golf</h1>
                <p class="lead mb-4 text-secondary">Track your golf scores, calculate your handicap, and improve your game with our comprehensive handicapping system.</p>
                <div class="d-flex flex-column flex-sm-row gap-3">
                    <a href="#" class="btn btn-light btn-lg px-4">
                        <i class="fas fa-plus me-2"></i>Enter New Score
                    </a>
                    <a href="#" class="btn btn-outline-light btn-lg px-4">
                        <i class="fas fa-chart-line me-2"></i>View Handicap
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="hero-icon">
                    <i class="fas fa-golf-ball display-1 opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Actions -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Quick Actions</h2>
        <div class="row g-4">
            <!-- User Actions -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm hover-card">
                    <div class="card-body text-center">
                        <div class="feature-icon bg-primary text-white rounded-circle mb-3 mx-auto">
                            <i class="fas fa-golf-ball"></i>
                        </div>
                        <h5 class="card-title">Enter Score</h5>
                        <p class="card-text">Record your latest round and update your handicap automatically.</p>
                        <a href="{{ asset('admin/scores/create') }}" class="btn btn-primary">Add Score</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm hover-card">
                    <div class="card-body text-center">
                        <div class="feature-icon bg-secondary text-white rounded-circle mb-3 mx-auto">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <h5 class="card-title">Handicap Calculator</h5>
                        <p class="card-text">View your current handicap and track your progress over time.</p>
                        <a href="#" class="btn btn-secondary">Calculate</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm hover-card">
                    <div class="card-body text-center">
                        <div class="feature-icon bg-success text-white rounded-circle mb-3 mx-auto">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h5 class="card-title">Score History</h5>
                        <p class="card-text">Review your past rounds and analyze your performance trends.</p>
                        <a href="#" class="btn btn-success">View History</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm hover-card">
                    <div class="card-body text-center">
                        <div class="feature-icon bg-info text-white rounded-circle mb-3 mx-auto">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h5 class="card-title">Tournaments</h5>
                        <p class="card-text">Join upcoming tournaments and view competition results.</p>
                        <a href="#" class="btn btn-info">View Events</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Admin Panel (Show only for admin users) -->
<section class="py-5 bg-light" id="admin-section">
    <div class="container">
        <div class="row align-items-center mb-4">
            <div class="col">
                <h2 class="text-primary">
                    <i class="fas fa-shield-alt me-2"></i>Administrator Panel
                </h2>
            </div>
            <div class="col-auto">
                <span class="badge bg-warning text-dark">Admin Access</span>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card border-primary h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="feature-icon bg-primary text-white rounded me-3">
                                <i class="fas fa-users"></i>
                            </div>
                            <h5 class="card-title mb-0">Manage Players</h5>
                        </div>
                        <p class="card-text">Add, edit, or remove players from the system.</p>
                        <a href="{{ route('admin.players.index') }}" class="btn btn-outline-primary">Manage Players</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card border-warning h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="feature-icon bg-warning text-dark rounded me-3">
                                <i class="fas fa-map"></i>
                            </div>
                            <h5 class="card-title mb-0">Scorecard Management</h5>
                        </div>
                        <p class="card-text">Manage golf courses, tees, ratings, and hole information.</p>
                        <a href="#" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#scorecardModal">Manage Scorecards</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card border-secondary h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="feature-icon bg-secondary text-white rounded me-3">
                                <i class="fas fa-cog"></i>
                            </div>
                            <h5 class="card-title mb-0">System Settings</h5>
                        </div>
                        <p class="card-text">Configure handicap rules and system preferences.</p>
                        <a href="#" class="btn btn-outline-secondary">Settings</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card border-success h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="feature-icon bg-success text-white rounded me-3">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <h5 class="card-title mb-0">Reports</h5>
                        </div>
                        <p class="card-text">Generate comprehensive reports and analytics.</p>
                        <a href="#" class="btn btn-outline-success">View Reports</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Recent Activity -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Recent Activity</h2>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Latest Score Recorded</h6>
                                    <p class="mb-1 text-muted">Shot 82 at Pebble Beach Golf Course</p>
                                    <small class="text-muted">2 hours ago</small>
                                </div>
                                <span class="badge bg-primary rounded-pill">New</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Handicap Updated</h6>
                                    <p class="mb-1 text-muted">Your handicap is now 12.5</p>
                                    <small class="text-muted">1 day ago</small>
                                </div>
                                <span class="badge bg-success rounded-pill">Updated</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Tournament Registration</h6>
                                    <p class="mb-1 text-muted">Registered for Spring Championship</p>
                                    <small class="text-muted">3 days ago</small>
                                </div>
                                <span class="badge bg-info rounded-pill">Event</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection