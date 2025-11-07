@extends('layouts.app')

@section('content')
<style>
    .scorecard-container {
        background: linear-gradient(135deg, #f8fafc 0%, #f0f5f0 100%);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(47, 74, 60, 0.1);
        border: 1px solid rgba(141, 166, 110, 0.2);
    }

    .scorecard-header {
        background: linear-gradient(135deg, #2F4A3C 0%, #3d5a4a 50%, #4a7054 100%);
        color: #fff;
        padding: 0.75rem 1rem;
        position: relative;
        overflow: hidden;
        border-bottom: 3px solid #8DA66E;
    }

    .scorecard-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
        border-radius: 50%;
        transform: translate(50px, -50px);
    }

    .scorecard-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(141, 166, 110, 0.2) 0%, rgba(141, 166, 110, 0) 70%);
        border-radius: 50%;
        transform: translate(-30px, 30px);
    }

    .scorecard-header-content {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .scorecard-title {
        font-size: 1.25rem;
        font-weight: 800;
        margin-bottom: 0;
        letter-spacing: -0.5px;
        flex: 1;
    }

    .scorecard-badges-container {
        display: flex;
        gap: 0.35rem;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .scorecard-badge {
        display: inline-block;
        background: rgba(141, 166, 110, 0.9);
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .scorecard-meta {
        font-size: 0.75rem;
        opacity: 0.9;
        margin-top: 0.3rem;
        line-height: 1.4;
    }

    .scorecard-meta i {
        color: #8DA66E;
        margin-right: 0.5rem;
    }

    .scorecard-body {
        padding: 1rem;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .detail-card {
        background: #fff;
        padding: 0.75rem;
        border-radius: 12px;
        border-left: 4px solid #8DA66E;
        box-shadow: 0 2px 8px rgba(47, 74, 60, 0.08);
        transition: all 0.3s ease;
    }

    .detail-card:hover {
        box-shadow: 0 4px 16px rgba(47, 74, 60, 0.12);
        transform: translateY(-2px);
    }

    .detail-card.type-regular {
        border-left-color: #2F4A3C;
    }

    .detail-card.type-tournament {
        border-left-color: #5E7C4C;
    }

    .detail-card.type-formula {
        border-left-color: #8DA66E;
    }

    .detail-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #999;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .detail-value {
        font-size: 0.95rem;
        font-weight: 700;
        color: #2F4A3C;
    }

    .detail-value.formula-code {
        font-family: 'Courier New', monospace;
        background: #f5f5f5;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        display: inline-block;
        font-size: 0.8rem;
    }

    .detail-value.not-set {
        color: #e74c3c;
        font-style: italic;
    }

    .ratings-section {
        background: #fff;
        border-radius: 12px;
        padding: 0.75rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(47, 74, 60, 0.08);
    }

    .ratings-header {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #2F4A3C;
        font-weight: 700;
        margin-bottom: 0.5rem;
        padding-bottom: 0.25rem;
        border-bottom: 2px solid #8DA66E;
    }

    .ratings-table {
        width: 100%;
    }

    .ratings-table th {
        background: linear-gradient(135deg, #f0f5f0 0%, #e8ebe8 100%);
        color: #2F4A3C;
        font-weight: 600;
        padding: 0.5rem;
        text-align: center;
        border: 1px solid rgba(141, 166, 110, 0.1);
    }

    .ratings-table td {
        padding: 0.5rem;
        text-align: center;
        border: 1px solid rgba(141, 166, 110, 0.1);
        font-size: smaller;
    }

    .tee-name {
        color: #2F4A3C;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .course-slope {
        color: #5E7C4C;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .rating-value {
        color: #5E7C4C;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .scorecard-table {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(47, 74, 60, 0.08);
    }

    .scorecard-table thead {
        background: linear-gradient(135deg, #2F4A3C 0%, #3d5a4a 100%) !important;
        color: #fff !important;
    }

    .scorecard-table th {
        font-weight: 600;
        padding: 0.5rem 0.25rem !important;
        font-size: 0.8rem;
        border: none !important;
    }

    .scorecard-table td {
        padding: 0.4rem 0.25rem !important;
        border: 1px solid rgba(141, 166, 110, 0.1) !important;
        font-size: smaller;
    }

    .scorecard-table tbody tr:hover {
        background-color: rgba(141, 166, 110, 0.05) !important;
    }

    .scorecard-table th:nth-child(11),
    .scorecard-table td:nth-child(11) {
        background-color: #8DA66E !important;
        color: #fff !important;
        font-weight: bold !important;
        border-left: 2px solid #2F4A3C !important;
    }

    .scorecard-table th:nth-child(22),
    .scorecard-table td:nth-child(22) {
        background-color: #2F4A3C !important;
        color: #8DA66E !important;
        font-weight: bold !important;
        border-left: 2px solid #8DA66E !important;
    }

    .scorecard-table th:nth-child(21),
    .scorecard-table td:nth-child(21) {
        background-color: #5E7C4C !important;
        color: #fff !important;
        font-weight: bold !important;
    }

    .row-par {
        background-color: rgba(141, 166, 110, 0.15) !important;
        font-weight: 600;
    }

    .row-handicap {
        background-color: rgba(47, 74, 60, 0.08) !important;
    }

    .row-handicap-label {
        color: #2F4A3C;
        font-weight: 600;
    }

    .table-responsive {
        border-radius: 12px;
        overflow: hidden;
    }
</style>

<div class="scorecard-container">
    {{-- Header --}}
    <div class="scorecard-header">
        <div class="scorecard-header-content">
            <div>
                <h2 class="scorecard-title">{{ $scorecard->scorecard_name ?? 'Scorecard' }}</h2>

                @if(isset($scorecard->scorecard_desc))
                <div class="scorecard-meta">{{ $scorecard->scorecard_desc }}</div>
                @endif

                @if(isset($scorecard->course->course_name))
                <div class="scorecard-meta">
                    <i class="fas fa-golf-ball"></i>
                    <strong>{{ $scorecard->course->course_name }}</strong>
                </div>
                @endif
            </div>

            <div class="scorecard-badges-container">
                @if(isset($scorecard->scorecard_code))
                <span class="scorecard-badge">{{ $scorecard->scorecard_code }}</span>
                @endif

                @if(isset($scorecard->scorecard_type))
                <span class="scorecard-badge">{{ ucfirst($scorecard->scorecard_type) }}</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Body --}}
    <div class="scorecard-body">

        {{-- Scorecard Details --}}
        <div class="details-grid">
            <div class="detail-card type-regular">
                <div class="detail-label">Scorecard Type</div>
                <div class="detail-value">{{ ucfirst($scorecard->scorecard_type ?? 'N/A') }}</div>
            </div>
            <div class="detail-card type-tournament">
                <div class="detail-label">X Value</div>
                <div class="detail-value">{{ str_replace('_', ' ', $scorecard->x_value ?? 'N/A') }}</div>
            </div>
            <div class="detail-card type-formula">
                <div class="detail-label">Adjusted Gross Formula</div>
                <div class="detail-value formula-code">
                    @if($scorecard->adjustedGrossScoreFormula)
                    {{ $scorecard->adjustedGrossScoreFormula->formula_expression ?? 'N/A' }}
                    @else
                    <span class="not-set">Not Set</span>
                    @endif
                </div>
            </div>
            <div class="detail-card type-regular">
                <div class="detail-label">Score Differential Formula</div>
                <div class="detail-value formula-code">
                    @if($scorecard->scoreDifferentialFormula)
                    {{ $scorecard->scoreDifferentialFormula->formula_expression ?? 'N/A' }}
                    @else
                    <span class="not-set">Not Set</span>
                    @endif
                </div>
            </div>
            <div class="detail-card type-tournament">
                <div class="detail-label">Course Handicap Formula</div>
                <div class="detail-value formula-code">
                    @if($scorecard->courseHandicapFormula)
                    {{ $scorecard->courseHandicapFormula->formula_expression ?? 'N/A' }}
                    @else
                    <span class="not-set">Not Set</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Course & Slope Ratings --}}
        @if($scorecard->ratings->isNotEmpty())
        <div class="ratings-section">
            <div class="ratings-header">
                <i class="fas fa-chart-bar me-2"></i>Course & Slope Ratings
            </div>
            <div class="table-responsive">
                <table class="ratings-table">
                    <thead>
                        <tr>
                            <th colspan="3" style="background: #e8ebe8;">Overall Ratings (Course / Slope)</th>
                            @foreach($scorecard->ratings as $rating)
                            <th style="border-right: 2px solid #8DA66E;">
                                <div class="tee-name">{{ $rating->tee->tee_name }}</div>
                                <div class="course-slope">{{ $rating->course_rating }} / {{ $rating->slope_rating }}</div>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3" style="font-weight: 600; background: #f0fdf4; color: #5E7C4C; border-right: 2px solid #8DA66E;">Front 9 Ratings</td>
                            @foreach($scorecard->ratings as $rating)
                            <td style="border-right: 2px solid #8DA66E; background: #f9fdf8;">
                                @php
                                $frontCR = $rating->f9_course_rating ?? 'N/A';
                                $frontSR = $rating->f9_slope_rating ?? 'N/A';
                                @endphp
                                <div class="rating-value">{{ $frontCR }} / {{ $frontSR }}</div>
                            </td>
                            @endforeach
                        </tr>
                        <tr>
                            <td colspan="3" style="font-weight: 600; background: #fdf8f0; color: #5E7C4C; border-right: 2px solid #8DA66E;">Back 9 Ratings</td>
                            @foreach($scorecard->ratings as $rating)
                            <td style="border-right: 2px solid #8DA66E; background: #fdfcf9;">
                                @php
                                $backCR = $rating->b9_course_rating ?? 'N/A';
                                $backSR = $rating->b9_slope_rating ?? 'N/A';
                                @endphp
                                <div class="rating-value">{{ $backCR }} / {{ $backSR }}</div>
                            </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Scorecard Table --}}
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center mb-0 table-hover scorecard-table" style="min-width: 1100px;">
                <thead style="background: #2F4A3C; color: #fff;">
                    <tr>
                        <th class="fw-bold">Hole</th>
                        @php
                        $holes = $scorecard->scorecardHoles->pluck('hole')->toArray();
                        $frontNine = array_slice($holes, 0, 9);
                        $backNine = array_slice($holes, 9, 9);
                        @endphp
                        @foreach($frontNine as $hole)
                        <th>{{ $hole }}</th>
                        @endforeach
                        <th>OUT</th>
                        @foreach($backNine as $hole)
                        <th>{{ $hole }}</th>
                        @endforeach
                        <th>IN</th>
                        <th>TOTAL</th>
                    </tr>
                </thead>

                <tbody>
                    {{-- Tee Rows --}}

                    @foreach($scorecard->course->tees as $tee)

                    <tr>
                        <td class="fw-bold">{{ $tee->tee_name }}</td>

                        @foreach ($yardages[$tee->tee_id] as $hole => $yardage)
                        <td style="color: #333;">{{ $yardage }}</td>
                        @if ($hole == 9)
                        <td>
                            {{ array_sum(array_slice($yardages[$tee->tee_id], 0, 9)) }}
                        </td>
                        @endif

                        @if ($hole == 18)

                        @endif
                        @endforeach

                        <td>
                            {{ array_sum(array_slice($yardages[$tee->tee_id], 9, 9)) }}
                        </td>
                        <td>
                            {{ array_sum($yardages[$tee->tee_id]) }}
                        </td>
                    </tr>
                    @endforeach

                    {{-- Par Row --}}
                    <tr class="row-par">
                        <td class="row-handicap-label">PAR</td>
                        @foreach ($scorecard->scorecardHoles as $hole)
                        <td style="color: #333;">{{ $hole->par }}</td>
                        @if ($hole->hole == 9)
                        <td>
                            {{ $scorecard->scorecardHoles->pluck('par')->take(9)->sum() }}
                        </td>
                        @endif
                        @endforeach
                        <td>
                            {{ $scorecard->scorecardHoles->pluck('par')->skip(9)->sum() }}
                        </td>
                        <td>
                            {{ $scorecard->scorecardHoles->pluck('par')->sum() }}
                        </td>

                    </tr>

                    {{-- Men's Handicap --}}
                    <tr class="row-handicap">
                        <td class="row-handicap-label">Handicap</td>
                        @foreach ($scorecard->scorecardHoles as $hole)

                        <td style="color: #333;">{{ $hole->men_stroke_index }}</td>
                        @if ($hole->hole == 9)
                        <td></td>
                        @endif


                        @endforeach
                        <td></td>
                        <td></td>

                    </tr>

                    {{-- Ladies' Handicap --}}
                    <tr class="row-handicap">
                        <td class="row-handicap-label">Ladies HDC</td>
                        @foreach ($scorecard->scorecardHoles as $hole)

                        <td style="color: #333;">{{ $hole->ladies_stroke_index }}</td>
                        @if ($hole->hole == 9)
                        <td></td>
                        @endif

                        @endforeach
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection