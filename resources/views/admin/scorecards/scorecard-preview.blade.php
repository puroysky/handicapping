@extends('layouts.app')

@section('content')
<style>
    .scorecard-table th:nth-child(11),
    .scorecard-table td:nth-child(11) {
        background-color: #8DA66E !important;
        color: #fff !important;
        font-weight: bold !important;
        border: 2px solid #2F4A3C !important;
    }

    .scorecard-table th:nth-child(11) {
        font-size: 1.1rem;
    }

    .scorecard-table tbody td:nth-child(11) {
        font-size: 1.05rem;
    }

    .scorecard-table th:nth-child(21),
    .scorecard-table td:nth-child(21) {
        background-color: #2F4A3C !important;
        color: #8DA66E !important;
        font-weight: bold !important;
        border: 2px solid #8DA66E !important;
    }

    .scorecard-table tbody td:nth-child(21) {
        font-size: 1.05rem;
    }

    .scorecard-table th:nth-child(20),
    .scorecard-table td:nth-child(20) {
        background-color: #5E7C4C !important;
        color: #fff !important;
        font-weight: bold !important;
    }
</style>

<div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4" style="background-color: #f8fafc; border: 2px solid #8DA66E;">
    <div class="card-header py-3 px-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between" style="background: linear-gradient(90deg, #2F4A3C 0%, #5E7C4C 50%, #8DA66E 100%); color: #fff;">
        <div>
            <h3 class="mb-0 fw-bold" style="color: #fff;">{{ $scorecard->scorecard_name ?? 'Scorecard' }}</h3>

            @if(isset($scorecard->scorecard_code))
            <span class="badge" style="background-color: #8DA66E; color: #fff;">{{ $scorecard->scorecard_code }}</span>
            @endif
            @if(isset($scorecard->scorecard_desc))
            <div class="mt-1 text-light" style="font-size: 1rem;">{{ $scorecard->scorecard_desc }}</div>
            @endif
            @if(isset($scorecard->course->course_name))
            <div class="mt-1 text-light" style="font-size: 1rem;">Course: <span style="color: #8DA66E;">{{ $scorecard->course->course_name }}</span></div>
            @endif
        </div>
        <div class="mt-2 mt-md-0">
            <span class="fw-semibold" style="color: #8DA66E;">Scorecard Preview</span>
        </div>
    </div>
    <div class="card-body p-4">


        {{-- Course & Slope Ratings --}}
        @if($scorecard->ratings->isNotEmpty())
        <div class="table-responsive mb-3">
            <table class="table table-borderless align-middle text-center mb-0">
                <thead style="background-color: #e8e8e8;">
                    <tr>
                        <th class="text-uppercase text-muted small" style="color: #2F4A3C;">Course / Slope Rating</th>
                        @foreach($scorecard->ratings as $rating)
                        <th>
                            <div class="fw-semibold" style="color: #2F4A3C;">{{ $rating->tee->tee_name }}</div>
                            <div style="color: #5E7C4C; font-size: 0.9rem;">{{ $rating->course_rating }} / {{ $rating->slope_rating }}</div>
                        </th>
                        @endforeach
                    </tr>
                </thead>
            </table>
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
                        <td class="fw-bold" style="color: #2F4A3C; background-color: #e8e8e8;">{{ $tee->tee_name }}</td>

                        @php
                            $yardage = $hole->yardages->firstWhere('tee_id', $tee->tee_id)->yardage
                        @endphp

                    </tr>
                    @endforeach

                    {{-- Par Row --}}
                    <tr style="background-color: #e8e8e8; font-weight: bold;">
                        <td style="color: #2F4A3C; background-color: #d0d0d0;">PAR</td>
                        @php $parOut = 0; $parIn = 0; @endphp
                        @foreach($scorecard->scorecardHoles as $hole)
                        @php
                        $par = $hole->par;
                        if ($hole->hole <= 9) $parOut +=$par;
                            else $parIn +=$par;
                            @endphp
                            <td style="color: #2F4A3C;">{{ $par }}</td>
                            @endforeach
                            <td>{{ $parOut }}</td>
                            <td>{{ $parIn }}</td>
                            <td>{{ $parOut + $parIn }}</td>
                    </tr>

                    {{-- Men's Handicap --}}
                    <tr style="background-color: #f8fafc;">
                        <td class="fw-bold" style="color: #2F4A3C; background-color: #e8e8e8;">Handicap</td>
                        @php $hcpOut = 0; $hcpIn = 0; $menStrokes = $scorecard->strokeIndexes->where('sex', 'M'); @endphp
                        @foreach($scorecard->scorecardHoles as $hole)
                        @php
                        $strokeIndex = $menStrokes->firstWhere('hole', $hole->hole)->stroke_index ?? 0;
                        if ($hole->hole <= 9) $hcpOut +=$strokeIndex;
                            else $hcpIn +=$strokeIndex;
                            @endphp
                            <td style="color: #333;">{{ $strokeIndex }}</td>
                            @endforeach
                            <td>{{ $hcpOut }}</td>
                            <td>{{ $hcpIn }}</td>
                            <td>{{ $hcpOut + $hcpIn }}</td>
                    </tr>

                    {{-- Ladies' Handicap --}}
                    <tr style="background-color: #f8fafc;">
                        <td class="fw-bold" style="color: #2F4A3C; background-color: #e8e8e8;">Ladies HCP</td>
                        @php $ladiesOut = 0; $ladiesIn = 0; $womenStrokes = $scorecard->strokeIndexes->where('sex', 'F'); @endphp
                        @foreach($scorecard->scorecardHoles as $hole)
                        @php
                        $womenIndex = $womenStrokes->firstWhere('hole', $hole->hole)->stroke_index ?? 0;
                        if ($hole->hole <= 9) $ladiesOut +=$womenIndex;
                            else $ladiesIn +=$womenIndex;
                            @endphp
                            <td style="color: #333;">{{ $womenIndex }}</td>
                            @endforeach
                            <td>{{ $ladiesOut }}</td>
                            <td>{{ $ladiesIn }}</td>
                            <td>{{ $ladiesOut + $ladiesIn }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection