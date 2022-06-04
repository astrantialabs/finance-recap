@extends("dashboard.base")

@section('dashboard-proper')
<h1 class="title">
    Dashboard
</h1>
{{-- <pre>
    @php
    var_dump($summary)
    @endphp
</pre> --}}

@foreach ($summary as $summary_item)
    {{ var_dump() }}    
@endforeach


{{-- <a href="{{ asset("storage/ProSekretariat"); }}">test</a> --}}
{{-- <a href="{{ asset("storage/files/ProSekretariat.pdf"); }}" download="">test</a> --}}
@endsection
