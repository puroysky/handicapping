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
        background: linear-gradient(135deg, #2F4A3C 0%, #5E7C4C 50%, #8DA66E 100%);
        color: #fff;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    .scorecard-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .scorecard-header-content {
        position: relative;
        z-index: 1;
    }

    .scorecard-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        letter-spacing: -0.5px;
    }

    .scorecard-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .scorecard-meta {
        font-size: 0.95rem;
        opacity: 0.95;
        margin-top: 0.5rem;
    }

    .scorecard-body {
        padding: 2rem;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .detail-card {
        background: #fff;
        padding: 1.5rem;
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
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #999;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .detail-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: #2F4A3C;
    }

    .detail-value.formula-code {
        font-family: 'Courier New', monospace;
        background: #f5f5f5;
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        display: inline-block;
        font-size: 0.95rem;
    }

    .detail-value.not-set {
        color: #e74c3c;
        font-style: italic;
    }

    .ratings-section {
        background: #fff;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(47, 74, 60, 0.08);
    }

    .ratings-header {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #2F4A3C;
        font-weight: 700;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #8DA66E;
    }

    .ratings-table {
        width: 100%;
    }

    .ratings-table th {
        background: linear-gradient(135deg, #f0f5f0 0%, #e8ebe8 100%);
        color: #2F4A3C;
        font-weight: 600;
        padding: 1rem;
        text-align: center;
        border: 1px solid rgba(141, 166, 110, 0.1);
    }

    .ratings-table td {
        padding: 1rem;
        text-align: center;
        border: 1px solid rgba(141, 166, 110, 0.1);
    }

    .tee-name {
        color: #2F4A3C;
        font-weight: 600;
    }

    .course-slope {
        color: #5E7C4C;
        font-size: 0.9rem;
        font-weight: 500;
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
        padding: 1rem 0.5rem !important;
        font-size: 0.9rem;
        border: none !important;
    }

    .scorecard-table td {
        padding: 0.75rem 0.5rem !important;
        border: 1px solid rgba(141, 166, 110, 0.1) !important;
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
            <h2 class="scorecard-title mb-2">{{ $scorecard->scorecard_name ?? 'Scorecard' }}</h2>
            
            <div>
                @if(isset($scorecard->scorecard_code))
                <span class="scorecard-badge">{{ $scorecard->scorecard_code }}</span>
                @endif
                
                @if(isset($scorecard->scorecard_type))
                <span class="scorecard-badge">{{ ucfirst($scorecard->scorecard_type) }}</span>
                @endif
            </div>

            @if(isset($scorecard->scorecard_desc))
            <div class="scorecard-meta mt-2">{{ $scorecard->scorecard_desc }}</div>
            @endif

            @if(isset($scorecard->course->course_name))
            <div class="scorecard-meta mt-1">
                <i class="fas fa-map-marker-alt me-1"></i>
                Course: <strong>{{ $scorecard->course->course_name }}</strong>
            </div>
            @endif
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
                    @if($scorecard->adjusted_gross_score_formula)
                        {{ $scorecard->adjusted_gross_score_formula->formula_code ?? 'N/A' }}
                    @else
                        <span class="not-set">Not Set</span>
                    @endif
                </div>
            </div>
            <div class="detail-card type-regular">
                <div class="detail-label">Score Differential Formula</div>
                <div class="detail-value formula-code">
                    @if($scorecard->score_differential_formula)
                        {{ $scorecard->score_differential_formula->formula_code ?? 'N/A' }}
                    @else
                        <span class="not-set">Not Set</span>
                    @endif
                </div>
            </div>
            <div class="detail-card type-tournament">
                <div class="detail-label">Handicap Index Formula</div>
                <div class="detail-value formula-code">
                    @if($scorecard->handicap_index_formula)
                        {{ $scorecard->handicap_index_formula->formula_code ?? 'N/A' }}
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
                            <th>TEE</th>
                            @foreach($scorecard->ratings as $rating)
                            <th>
                                <div class="tee-name">{{ $rating->tee->tee_name }}</div>
                                <div class="course-slope">{{ $rating->course_rating }} / {{ $rating->slope_rating }}</div>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
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
                               <td style="">
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
                        @foreach ($scorecard->strokeIndexes as $index)
                            @if ($index->sex == 'M')
                                <td style="color: #333;">{{ $index->stroke_index }}</td>
                                  @if ($index->hole == 9)
                                        <td></td>
                                    @endif
                            @endif
                          
                        @endforeach
                       <td></td>
                        <td></td>
                      
                    </tr>

                    {{-- Ladies' Handicap --}}
                    <tr class="row-handicap">
                        <td class="row-handicap-label">Ladies HDC</td>
                        @foreach ($scorecard->strokeIndexes as $index)
                            @if ($index->sex == 'F')
                                <td style="color: #333;">{{ $index->stroke_index }}</td>
                                  @if ($index->hole == 9)
                                        <td></td>
                                    @endif
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