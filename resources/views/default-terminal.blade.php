@if ($report->isCummulative && $report->term_id===3)
@include('cummulativeScores')
@else
@include('noneCummulativeScore')
@endif
