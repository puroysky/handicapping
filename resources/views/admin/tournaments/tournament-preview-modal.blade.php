<!-- resources/views/admin/tournaments/tournament-preview-modal.blade.php -->

<div class="modal fade" id="tournamentPreviewModal" tabindex="-1" aria-labelledby="tournamentPreviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tournamentPreviewLabel">Tournament Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card mb-4">
                                <div class="card-body text-center">
                                    <i class="fas fa-trophy fa-6x text-warning mb-3"></i>
                                    <h5 class="my-3" id="previewTournamentName"></h5>
                                    <p class="text-muted mb-1" id="previewDesc"></p>
                                </div>
                            </div>
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p class="mb-0">Start Date</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="text-muted mb-0" id="previewStartDateValue"></p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p class="mb-0">End Date</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="text-muted mb-0" id="previewEndDateValue"></p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p class="mb-0">Duration</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="text-muted mb-0" id="previewDurationValue"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Courses & Scorecards</h5>
                                    <div id="previewCourses" class="mb-3"></div>
                                    <div id="previewScorecards"></div>
                                </div>
                            </div>
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Handicap Configuration</h5>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p class="mb-1"><strong>Recent Scores:</strong> <span id="previewRecentScoresCountValue"></span></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1"><strong>Selection Type:</strong> <span id="previewScoreSelectionTypeValue"></span></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1"><strong>Scores to Average:</strong> <span id="previewScoresToAverageValue"></span></p>
                                        </div>
                                    </div>
                                    <hr>
                                    <p class="mb-1"><strong>Date Range:</strong> <span id="previewDateRangeValue"></span></p>
                                    <hr>
                                    <p class="mb-1"><strong>Formula:</strong></p>
                                    <code id="previewFormula" class="d-block bg-light p-2 rounded"></code>
                                </div>
                            </div>
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Remarks</h5>
                                    <p id="previewRemarks"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="editTournamentBtn"><i class="fas fa-edit me-1"></i>Edit</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showTournamentPreview(tournament) {
        document.getElementById('previewTournamentName').textContent = tournament.tournament_name || 'N/A';
        document.getElementById('previewDesc').textContent = tournament.tournament_desc || 'No description provided';
        document.getElementById('previewRemarks').textContent = tournament.remarks || 'No remarks provided';

        const startDate = new Date(tournament.tournament_start);
        const endDate = new Date(tournament.tournament_end);

        document.getElementById('previewStartDateValue').textContent = startDate.toLocaleDateString('en-US', { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' });
        document.getElementById('previewEndDateValue').textContent = endDate.toLocaleDateString('en-US', { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' });

        const diffTime = Math.abs(endDate - startDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        document.getElementById('previewDurationValue').textContent = `${diffDays} day${diffDays > 1 ? 's' : ''}`;

        const coursesHtml = tournament.courses && tournament.courses.length > 0
            ? tournament.courses.map(course => `<span class="badge bg-primary me-1">${course.course_name}</span>`).join('')
            : '<span class="badge bg-light text-dark">No courses selected</span>';
        document.getElementById('previewCourses').innerHTML = coursesHtml;

        const scorecardsHtml = tournament.scorecards && tournament.scorecards.length > 0
            ? tournament.scorecards.map(sc => `<div><strong>${sc.course_name}:</strong> <small>${sc.scorecard_name}</small></div>`).join('')
            : '<span class="text-muted">No scorecards assigned</span>';
        document.getElementById('previewScorecards').innerHTML = scorecardsHtml;

        document.getElementById('previewRecentScoresCountValue').textContent = tournament.recent_scores_count || '-';
        
        const selectionType = tournament.score_selection_type === 'HIGHEST' ? 'Highest Scores' : 'Lowest Scores';
        const selectionTypeClass = `badge ${tournament.score_selection_type === 'HIGHEST' ? 'bg-danger' : 'bg-success'}`;
        document.getElementById('previewScoreSelectionTypeValue').innerHTML = `<span class="${selectionTypeClass}">${selectionType}</span>`;

        document.getElementById('previewScoresToAverageValue').textContent = tournament.scores_to_average || '-';

        const startDateStr = tournament.score_diff_start_date ? new Date(tournament.score_diff_start_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A';
        const endDateStr = tournament.score_diff_end_date ? new Date(tournament.score_diff_end_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A';
        document.getElementById('previewDateRangeValue').textContent = `${startDateStr} to ${endDateStr}`;

        document.getElementById('previewFormula').textContent = tournament.handicap_formula_expression || '-';

        const modal = new bootstrap.Modal(document.getElementById('tournamentPreviewModal'));
        modal.show();

        document.getElementById('editTournamentBtn').onclick = () => {
            if (tournament.tournament_id) {
                window.location.href = `/admin/tournaments/${tournament.tournament_id}/edit`;
            }
        };
    }
</script>