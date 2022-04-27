@extends("dashboard.base")

@php
foreach ($payload as $key => $value) {
    if (is_null($value)) {
        $payload[$key] = '';
    }
}
@endphp

@section('dashboard-proper')
    <header class="dashboard-header">
        <h1 class="title">
            {{ $payload[0]->name }}
        </h1>
    </header>
    <main class="dashboard-table">
        <div class="dashboard-table-container">
            <table class="fold-table" align="center">
                <thead>
                    <tr>
                        <th colspan="1" rowspan="2">Sub Kegiatan</th>
                        <th colspan="2" rowspan="1">Realisasi</th>
                    </tr>
                    <tr>
                        <th>Pisikal</th>
                        <th>Realisasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payload as $payload_item)
                        @php
                            $activities = $payload_item['activity'];
                        @endphp
                        @foreach ((array) $activities as $activities => $activities_item)
                        <tr class="table-view">
                            <td class="activity">
                                {{ $activities_item['activity'] }}
                            </td>
                            <td class="physical">
                                @isset($activities_item['physical'])
                                    {{ $activities_item['physical'] }}%
                                @endisset
                                @php
                                    if (is_null($activities_item['physical'])) {
                                        echo '-';
                                    }
                                @endphp
                            </td>
                            <td class="finance">
                                @isset($activities_item['finance'])
                                    {{ $activities_item['finance'] }}%
                                @endisset
                                @php
                                    if (is_null($activities_item['finance'])) {
                                        echo '-';
                                    }
                                @endphp
                            </td>
                        </tr>
                        <tr class="table-fold">

                        </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
    <footer class="footer">
        <p class="subtitle">
            Dikembangkan oleh <strong>Kodikas</strong> <br> <strong>Yehezkiel Dio</strong> & <strong>Rizky Irswanda</strong>
        </p>
    </footer>
@endsection
