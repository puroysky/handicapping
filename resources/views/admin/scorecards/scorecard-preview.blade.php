@extends('layouts.app')

@section('content')
<style>
    .scorecard-table th:nth-child(11),
    .scorecard-table td:nth-child(11) {
        background-color: #8DA66E !important;
        color: #fff !important;
        font-weight: bold !important;
        border-left: 1px solid #2F4A3C !important;
      
    }

    .scorecard-table th:nth-child(11) {
        font-size: 1.1rem;
    }

    .scorecard-table tbody td:nth-child(11) {
        font-size: 1.05rem;
    }

    .scorecard-table th:nth-child(22),
    .scorecard-table td:nth-child(22) {
        background-color: #2F4A3C !important;
        color: #8DA66E !important;
        font-weight: bold !important;
        border: 2px solid #8DA66E !important;
    }

    .scorecard-table tbody td:nth-child(22) {
        font-size: 1.05rem;
    }

    .scorecard-table th:nth-child(21),
    .scorecard-table td:nth-child(21) {
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
                    <tr style="background-color: #e8e8e8; font-weight: bold;">
                        <td style="color: #2F4A3C; background-color: #d0d0d0;">PAR</td>
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
                    <tr style="background-color: #f8fafc;">
                        <td class="fw-bold">Handicap</td>
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
                    <tr style="background-color: #f8fafc;">
                        <td class="fw-bold">Ladies HDC</td>
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