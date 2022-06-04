@extends('dashboard.base')

@php
foreach ($test as $key => $value) {
    if (is_null($value)) {
        $test[$key] = '';
    }
}
@endphp

@section('dashboard-proper')
    <header class="dashboard-header">
        <h1 class="title">
            {{ $test['divisi'] }}
        </h1>
    </header>
    <main class="dashboard-table">
        <div class="dashboard-table-container table-container">
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
                    @php
                        $subkegiatan = $test['sub_kegiatan'];
                    @endphp
                    @foreach ($subkegiatan as $test_item)
                        <tr class="table-view">
                            <td class="activity">
                                {{ $test_item['sub_kegiatan'] }}
                            </td>
                            <td class="physical">
                                @isset($test_item['fisik'])
                                    {{ $test_item['fisik'] }}%
                                @endisset
                                @php
                                    if (is_null($test_item['fisik'])) {
                                        echo '-';
                                    }
                                @endphp
                            </td>
                            <td class="finance">
                                @isset($test_item['keuangan'])
                                    {{ $test_item['keuangan'] }}%
                                @endisset
                                @php
                                    if (is_null($test_item['keuangan'])) {
                                        echo '-';
                                    }
                                @endphp
                            </td>
                        </tr>
                        <tr class="table-fold is-hidden">
                            <td>
                                <div class="dashboard-nested-table-container">
                                    <table align="center">
                                        <thead class="division-table-nested-head">
                                            <tr>
                                                <th rowspan="2">
                                                    Rekening
                                                </th>
                                                <th rowspan="2">
                                                    Jumlah Fisik / Anggaran
                                                </th>
                                                <th rowspan="2">
                                                    Target Realisasi
                                                </th>
                                                <th rowspan="1" colspan="12">
                                                    Jumlah Kebutuhan Dana
                                                </th>
                                            </tr>
                                            <tr>
                                                <th rowspan="1">Januari</th>
                                                <th rowspan="1">Febuari</th>
                                                <th rowspan="1">Maret</th>
                                                <th rowspan="1">April</th>
                                                <th rowspan="1">Mei</th>
                                                <th rowspan="1">Juni</th>
                                                <th rowspan="1">Juli</th>
                                                <th rowspan="1">Agustus</th>
                                                <th rowspan="1">September</th>
                                                <th rowspan="1">Oktober</th>
                                                <th rowspan="1">November</th>
                                                <th rowspan="1">December</th>
                                                <th rowspan="1">Jumlah</th>
                                                <th rowspan="1">
                                                    Persentase Realisasi Fisik / Anggaran
                                                </th>
                                                <th rowspan="1">
                                                    Sisa Fisik / Anggaran yang belum digunakan
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="sekretariat-table-nested-body">
                                            <?php
                                            $details = $test_item['detail'];
                                            if (is_null($details)) {
                                                $details = [];
                                            }
                                            ?>
                                            @foreach ($details as $details_item)
                                                <tr>
                                                    <td class="account">
                                                        {{ $details_item['rekening'] }}
                                                    </td>
                                                    <td>
                                                        @currency($details_item['jumlah_fisik_anggaran'])
                                                    </td>
                                                    <td>
                                                        &nbsp;
                                                    </td>

                                                    <?php $monthly_finance = $details_item['jumlah_Kebutuhan_dana']; ?>
                                                    @foreach ((array) $monthly_finance as $monthly_finance => $monthly_finance_item)
                                                        <td>
                                                            @currency($monthly_finance_item['total'])
                                                        </td>
                                                    @endforeach

                                                    <td>
                                                        @currency($details_item['jumlah_fisik_anggaran'])
                                                    </td>
                                                    <td>
                                                        &nbsp;
                                                    </td>
                                                    <td>
                                                        &nbsp;
                                                    </td>
                                                </tr>

                                                <?php $expenses = $details_item['biaya']; ?>
                                                @foreach ((array) $expenses as $expenses => $expenses_item)
                                                    <?php $physical_expenses = $expenses_item['fisik']; ?>
                                                    <?php $finance_expenses = $expenses_item['keuangan']; ?>

                                                    <tr>
                                                        <td class="first-row fixed fixed-row-one " rowspan="2">
                                                            {{ $expenses_item['biaya'] }}
                                                            <br>
                                                            -
                                                        </td>
                                                        <td class="first-row fixed fixed-row-two" rowspan="2">
                                                            {{ $physical_expenses['jumlah_fisik'] }}
                                                        </td>
                                                        <td class="first-row fixed fixed-row-three">
                                                            Target
                                                        </td>
                                                        <?php $monthly_physical_expenses = $physical_expenses['jumlah_Kebutuhan_dana']; ?>

                                                        @foreach ((array) $monthly_physical_expenses as $monthly_physical_expenses => $monthly_physical_expenses_item)
                                                            <td class="first-row">
                                                                @isset($monthly_physical_expenses_item['target']['total'])
                                                                    {{ $monthly_physical_expenses_item['target']['total'] }}
                                                                @endisset
                                                                @php
                                                                    if (is_null($monthly_physical_expenses_item['target']['total'])) {
                                                                        echo '-';
                                                                    }
                                                                @endphp
                                                            </td>
                                                        @endforeach

                                                        <?php $monthly_physical_expenses = $physical_expenses['jumlah_Kebutuhan_dana']; ?>
                                                        <td class="first-row">
                                                            {{ array_sum(array_column(array_column($monthly_physical_expenses, 'target'), 'total')) }}
                                                        </td>
                                                        <td class="first-row" rowspan="2">
                                                            {{ round(
                                                                divnum(
                                                                    array_sum(array_column(array_column($monthly_physical_expenses, 'realisasi'), 'total')),
                                                                    array_sum(array_column(array_column($monthly_physical_expenses, 'target'), 'total')),
                                                                ) * 100,
                                                            ) }}%
                                                        </td>
                                                        <td class="first-row" rowspan="2">
                                                            {{ array_sum(array_column(array_column($monthly_physical_expenses, 'target'), 'total')) -
                                                                array_sum(array_column(array_column($monthly_physical_expenses, 'realisasi'), 'total')) }}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="first-row fixed">
                                                            Realisasi
                                                        </td>

                                                        <?php $monthly_physical_expenses = $physical_expenses['jumlah_Kebutuhan_dana']; ?>
                                                        @foreach ((array) $monthly_physical_expenses as $monthly_physical_expenses => $monthly_physical_expenses_item)
                                                            <td class="first-row">
                                                                @isset($monthly_physical_expenses_item['realisasi']['total'])
                                                                    {{ $monthly_physical_expenses_item['realisasi']['total'] }}
                                                                @endisset
                                                                @php
                                                                    if (is_null($monthly_physical_expenses_item['realisasi']['total'])) {
                                                                        echo '-';
                                                                    }
                                                                @endphp
                                                            </td>
                                                        @endforeach

                                                        <?php $monthly_physical_expenses = $physical_expenses['jumlah_Kebutuhan_dana']; ?>
                                                        <td class="first-row">
                                                            {{ array_sum(array_column(array_column($monthly_physical_expenses, 'realisasi'), 'total')) }}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="second-row fixed" rowspan="2">
                                                            {{ $expenses_item['biaya'] }}
                                                            <br>
                                                            -
                                                        </td>
                                                        <td class="second-row fixed" rowspan="2">
                                                            {{ $finance_expenses['jumlah_anggaran'] }}
                                                        </td>

                                                        <td class="second-row fixed">Target</td>

                                                        <?php $monthly_finance_expenses = $finance_expenses['jumlah_Kebutuhan_dana']; ?>
                                                        @foreach ((array) $monthly_finance_expenses as $monthly_finance_expenses => $monthly_finance_expenses_item)
                                                            <td class="second-row">
                                                                @isset($monthly_physical_expenses_item['target']['total'])
                                                                    @currency($monthly_physical_expenses_item['target']['total'])
                                                                @endisset
                                                                @php
                                                                    if (is_null($monthly_physical_expenses_item['target']['total'])) {
                                                                        echo '-';
                                                                    }
                                                                @endphp
                                                            </td>
                                                        @endforeach

                                                        <?php $monthly_finance_expenses = $finance_expenses['jumlah_Kebutuhan_dana']; ?>
                                                        <td class="second-row">
                                                            @currency(array_sum(array_column(array_column($monthly_physical_expenses, 'target'), 'total')))
                                                        </td>
                                                        <td class="second-row" rowspan="2">
                                                            {{ round(
                                                                divnum(
                                                                    array_sum(array_column(array_column($monthly_physical_expenses, 'realisasi'), 'total')),
                                                                    array_sum(array_column(array_column($monthly_physical_expenses, 'target'), 'total')),
                                                                ) * 100,
                                                            ) }}%
                                                        </td>
                                                        <td class="second-row" rowspan="2">
                                                            @php
                                                                $result = round(array_sum(array_column(array_column($monthly_physical_expenses, 'target'), 'total'))) - array_sum(array_column(array_column($monthly_physical_expenses, 'realisasi'), 'total'));
                                                            @endphp
                                                            @currency($result)
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="second-row fixed">Realisasi</td>

                                                        <?php $monthly_finance_expenses = $finance_expenses['jumlah_Kebutuhan_dana']; ?>
                                                        @foreach ((array) $monthly_finance_expenses as $monthly_finance_expenses => $monthly_finance_expenses_item)
                                                            <td class="second-row">
                                                                @isset($monthly_physical_expenses_item['realisasi']['total'])
                                                                    @currency($monthly_physical_expenses_item['realisasi']['total'])
                                                                @endisset
                                                                @php
                                                                    if (is_null($monthly_physical_expenses_item['realisasi']['total'])) {
                                                                        echo '-';
                                                                    }
                                                                @endphp
                                                            </td>
                                                        @endforeach
                                                        <?php $monthly_finance_expenses = $finance_expenses['jumlah_Kebutuhan_dana']; ?>
                                                        <td class="second-row">
                                                            @currency(array_sum(array_column(array_column($monthly_physical_expenses, 'realisasi'), 'total')))
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                        <tfoot class="sekretariat-table-nested-foot">
                                            <tr>
                                                <th>
                                                    Jumlah Realisasi Fisik
                                                </th>
                                                <th>
                                                    &nbsp;
                                                </th>
                                                <?php $details = $test_item['detail']; ?>

                                                <?php $jumlahRealisasiKeuangan = []; ?>
                                                <?php $jumlahRealisasiJanuari = []; ?>
                                                <?php $jumlahRealisasiFebuari = []; ?>
                                                <?php $jumlahRealisasiMaret = []; ?>
                                                <?php $jumlahRealisasiApril = []; ?>
                                                <?php $jumlahRealisasiMei = []; ?>
                                                <?php $jumlahRealisasiJuni = []; ?>
                                                <?php $jumlahRealisasiJuli = []; ?>
                                                <?php $jumlahRealisasiAgustus = []; ?>
                                                <?php $jumlahRealisasiSeptember = []; ?>
                                                <?php $jumlahRealisasiOktober = []; ?>
                                                <?php $jumlahRealisasiNovember = []; ?>
                                                <?php $jumlahRealisasiDecember = []; ?>

                                                @foreach ((array) $details as $details => $details_item)
                                                    <?php $expenses = $details_item['biaya']; ?>

                                                    @foreach ((array) $expenses as $expenses => $expenses_item)
                                                        <?php $physical_expenses = $expenses_item['fisik']; ?>

                                                        <?php $monthly_physical_expenses = $physical_expenses['jumlah_Kebutuhan_dana']; ?>

                                                        <?php $jumlahRealisasiKeuangan[] = array_sum(array_column(array_column($monthly_physical_expenses, 'target'), 'total')); ?>

                                                        <?php $jumlahRealisasiJanuari[] = array_slice($monthly_physical_expenses[0], 0, 10); ?>
                                                        <?php $jumlahRealisasiFebuari[] = array_slice($monthly_physical_expenses[1], 0, 10); ?>
                                                        <?php $jumlahRealisasiMaret[] = array_slice($monthly_physical_expenses[2], 0, 10); ?>
                                                        <?php $jumlahRealisasiApril[] = array_slice($monthly_physical_expenses[3], 0, 10); ?>
                                                        <?php $jumlahRealisasiMei[] = array_slice($monthly_physical_expenses[4], 0, 10); ?>
                                                        <?php $jumlahRealisasiJuni[] = array_slice($monthly_physical_expenses[5], 0, 10); ?>
                                                        <?php $jumlahRealisasiJuli[] = array_slice($monthly_physical_expenses[6], 0, 10); ?>
                                                        <?php $jumlahRealisasiAgustus[] = array_slice($monthly_physical_expenses[7], 0, 10); ?>
                                                        <?php $jumlahRealisasiSeptember[] = array_slice($monthly_physical_expenses[8], 0, 10); ?>
                                                        <?php $jumlahRealisasiOktober[] = array_slice($monthly_physical_expenses[9], 0, 10); ?>
                                                        <?php $jumlahRealisasiNovember[] = array_slice($monthly_physical_expenses[10], 0, 10); ?>
                                                        <?php $jumlahRealisasiDecember[] = array_slice($monthly_physical_expenses[11], 0, 10); ?>
                                                    @endforeach
                                                @endforeach

                                                <th>
                                                    @currency(array_sum($jumlahRealisasiKeuangan))
                                                </th>
                                                {{-- <th>
                                                    &nbsp;
                                                </th> --}}
                                                <th>
                                                    @php
                                                        $januari = array_sum(array_column(array_column($jumlahRealisasiJanuari, 'target'), 'total'));
                                                    @endphp
                                                    @currency($januari)
                                                </th>
                                                <th>
                                                    @php
                                                        $febuari = array_sum(array_column(array_column($jumlahRealisasiFebuari, 'target'), 'total'));
                                                    @endphp
                                                    @currency($febuari)
                                                </th>
                                                <th>
                                                    @php
                                                        $maret = array_sum(array_column(array_column($jumlahRealisasiMaret, 'target'), 'total'));
                                                    @endphp
                                                    @currency($maret)
                                                </th>
                                                <th>
                                                    @php
                                                        $april = array_sum(array_column(array_column($jumlahRealisasiApril, 'target'), 'total'));
                                                    @endphp
                                                    @currency($april)
                                                </th>
                                                <th>
                                                    @php
                                                        $mei = array_sum(array_column(array_column($jumlahRealisasiMei, 'target'), 'total'));
                                                    @endphp
                                                    @currency($mei)

                                                </th>
                                                <th>
                                                    @php
                                                        $juni = array_sum(array_column(array_column($jumlahRealisasiJuni, 'target'), 'total'));
                                                    @endphp
                                                    @currency($juni)
                                                </th>
                                                <th>
                                                    @php
                                                        $juli = array_sum(array_column(array_column($jumlahRealisasiJuli, 'target'), 'total'));
                                                    @endphp
                                                    @currency($juli)
                                                </th>
                                                <th>
                                                    @php
                                                        $agustus = array_sum(array_column(array_column($jumlahRealisasiAgustus, 'target'), 'total'));
                                                    @endphp
                                                    @currency($agustus)
                                                </th>
                                                <th>
                                                    @php
                                                        $september = array_sum(array_column(array_column($jumlahRealisasiSeptember, 'target'), 'total'));
                                                    @endphp
                                                    @currency($september)
                                                </th>
                                                <th>
                                                    @php
                                                        $oktober = array_sum(array_column(array_column($jumlahRealisasiOktober, 'target'), 'total'));
                                                    @endphp
                                                    @currency($oktober)
                                                </th>
                                                <th>
                                                    @php
                                                        $november = array_sum(array_column(array_column($jumlahRealisasiNovember, 'target'), 'total'));
                                                    @endphp
                                                    @currency($november)

                                                </th>
                                                <th>
                                                    @php
                                                        $desember = array_sum(array_column(array_column($jumlahRealisasiDecember, 'target'), 'total'));
                                                    @endphp
                                                    @currency($desember)
                                                </th>
                                                <th>
                                                    @php
                                                        $all_months = $januari + $febuari + $maret + $april + $mei + $juni + $juli + $agustus + $september + $oktober + $november + $desember;
                                                    @endphp
                                                    @currency($all_months)
                                                </th>
                                                <th>
                                                    @php
                                                        $allMonthsPercent = divnum($all_months, array_sum($jumlahRealisasiKeuangan)) * 100;
                                                    @endphp
                                                    {{ round($allMonthsPercent) }}%
                                                </th>
                                                <th>
                                                    @php
                                                        $new = abs($allMonthsPercent - 100);
                                                    @endphp
                                                    {{ round($new) }}%
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>
                                                    Jumlah Realisasi Keuangan
                                                </th>
                                                <?php $details = $test_item['detail']; ?>

                                                <?php $jumlahRealisasiKeuangan = []; ?>

                                                <?php $jumlahRealisasiJanuari = []; ?>
                                                <?php $jumlahRealisasiFebuari = []; ?>
                                                <?php $jumlahRealisasiMaret = []; ?>
                                                <?php $jumlahRealisasiApril = []; ?>
                                                <?php $jumlahRealisasiMei = []; ?>
                                                <?php $jumlahRealisasiJuni = []; ?>
                                                <?php $jumlahRealisasiJuli = []; ?>
                                                <?php $jumlahRealisasiAgustus = []; ?>
                                                <?php $jumlahRealisasiSeptember = []; ?>
                                                <?php $jumlahRealisasiOktober = []; ?>
                                                <?php $jumlahRealisasiNovember = []; ?>
                                                <?php $jumlahRealisasiDecember = []; ?>

                                                @foreach ((array) $details as $details => $details_item)
                                                    <?php $jumlahRealisasiKeuangan[] = $details_item['jumlah_fisik_anggaran']; ?>

                                                    <?php $expenses = $details_item['biaya']; ?>
                                                    @foreach ((array) $expenses as $expenses => $expenses_item)
                                                        <?php $finance_expenses = $expenses_item['keuangan']; ?>

                                                        <?php $monthly_finance_expenses = $finance_expenses['jumlah_Kebutuhan_dana']; ?>

                                                        <?php $jumlahRealisasiJanuari[] = array_slice($monthly_finance_expenses[0], 0, 10); ?>
                                                        <?php $jumlahRealisasiFebuari[] = array_slice($monthly_finance_expenses[1], 1, 10); ?>
                                                        <?php $jumlahRealisasiMaret[] = array_slice($monthly_finance_expenses[2], 1, 10); ?>
                                                        <?php $jumlahRealisasiApril[] = array_slice($monthly_finance_expenses[3], 1, 10); ?>
                                                        <?php $jumlahRealisasiMei[] = array_slice($monthly_finance_expenses[4], 1, 10); ?>
                                                        <?php $jumlahRealisasiJuni[] = array_slice($monthly_finance_expenses[5], 1, 10); ?>
                                                        <?php $jumlahRealisasiJuli[] = array_slice($monthly_finance_expenses[6], 1, 10); ?>
                                                        <?php $jumlahRealisasiAgustus[] = array_slice($monthly_finance_expenses[7], 1, 10); ?>
                                                        <?php $jumlahRealisasiSeptember[] = array_slice($monthly_finance_expenses[8], 1, 10); ?>
                                                        <?php $jumlahRealisasiOktober[] = array_slice($monthly_finance_expenses[9], 1, 10); ?>
                                                        <?php $jumlahRealisasiNovember[] = array_slice($monthly_finance_expenses[10], 1, 10); ?>
                                                        <?php $jumlahRealisasiDecember[] = array_slice($monthly_finance_expenses[11], 1, 10); ?>
                                                    @endforeach
                                                @endforeach
                                                <th>
                                                    &nbsp;
                                                </th>

                                                <th>
                                                    @currency(array_sum($jumlahRealisasiKeuangan))
                                                </th>

                                                <th>
                                                    @php
                                                        $januari = array_sum(array_column(array_column($jumlahRealisasiJanuari, 'target'), 'total'));
                                                    @endphp
                                                    @currency($januari)
                                                </th>
                                                <th>
                                                    @php
                                                        $febuari = array_sum(array_column(array_column($jumlahRealisasiFebuari, 'target'), 'total'));
                                                    @endphp
                                                    @currency($febuari)
                                                </th>
                                                <th>
                                                    @php
                                                        $maret = array_sum(array_column(array_column($jumlahRealisasiMaret, 'target'), 'total'));
                                                    @endphp
                                                    @currency($maret)
                                                </th>
                                                <th>
                                                    @php
                                                        $april = array_sum(array_column(array_column($jumlahRealisasiApril, 'target'), 'total'));
                                                    @endphp
                                                    @currency($april)
                                                </th>
                                                <th>
                                                    @php
                                                        $mei = array_sum(array_column(array_column($jumlahRealisasiMei, 'target'), 'total'));
                                                    @endphp
                                                    @currency($mei)
                                                </th>
                                                <th>
                                                    @php
                                                        $juni = array_sum(array_column(array_column($jumlahRealisasiJuni, 'target'), 'total'));
                                                    @endphp
                                                    @currency($juni)
                                                </th>
                                                <th>
                                                    @php
                                                        $juli = array_sum(array_column(array_column($jumlahRealisasiJuli, 'target'), 'total'));
                                                    @endphp
                                                    @currency($juli)
                                                </th>
                                                <th>
                                                    @php
                                                        $agustus = array_sum(array_column(array_column($jumlahRealisasiAgustus, 'target'), 'total'));
                                                    @endphp
                                                    @currency($agustus)
                                                </th>
                                                <th>
                                                    @php
                                                        $september = array_sum(array_column(array_column($jumlahRealisasiSeptember, 'target'), 'total'));
                                                    @endphp
                                                    @currency($september)
                                                </th>
                                                <th>
                                                    @php
                                                        $oktober = array_sum(array_column(array_column($jumlahRealisasiOktober, 'target'), 'total'));
                                                    @endphp
                                                    @currency($oktober)
                                                </th>
                                                <th>
                                                    @php
                                                        $november = array_sum(array_column(array_column($jumlahRealisasiNovember, 'target'), 'total'));
                                                    @endphp
                                                    @currency($november)
                                                </th>
                                                <th>
                                                    @php
                                                        $desember = array_sum(array_column(array_column($jumlahRealisasiDecember, 'target'), 'total'));
                                                    @endphp
                                                    @currency($desember)
                                                </th>
                                                <th>
                                                    @php
                                                        $all_months = $januari + $febuari + $maret + $april + $mei + $juni + $juli + $agustus + $september + $oktober + $november + $desember;
                                                    @endphp
                                                    @currency($all_months)
                                                </th>
                                                <th>
                                                    @php
                                                        $allMonthsPercent = divnum($all_months, array_sum($jumlahRealisasiKeuangan)) * 100;
                                                    @endphp
                                                    {{ round($allMonthsPercent) }}%
                                                </th>
                                                <th>
                                                    @php
                                                        $new = abs($allMonthsPercent - 100);
                                                    @endphp
                                                    {{ round($new) }}%
                                                </th>

                                                
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
    <footer class="footer">
        <p class="subtitle">
            <?php if ($test['divisi'] == "Sekretariat") { ?>
            <a class="call-to-action call-to-action--accent" wire:click='export("Sekretariat", "pdf")'>Download Sekretariat PDF</a>
            <a class="call-to-action call-to-action--accent" wire:click='export("Sekretariat", "xlsx")'>Download Sekretariat XLSX</a>
            <?php } ?>
            <?php if ($test['divisi'] == "Penta") { ?>
                <a class="call-to-action call-to-action--accent" wire:click='export("Penta", "pdf")'>Download Penta PDF</a>
                <a class="call-to-action call-to-action--accent" wire:click='export("Penta", "xlsx")'>Download Penta XLSX</a>
                <?php } ?>
                <?php if ($test['divisi'] == "Lattas") { ?>
                    <a class="call-to-action call-to-action--accent" wire:click='export("Lattas", "pdf")'>Download Lattas PDF</a>
                    <a class="call-to-action call-to-action--accent" wire:click='export("Lattas", "xlsx")'>Download Lattas XLSX</a>
                    <?php } ?>
                    <?php if ($test['divisi'] == "HI") { ?>
                        <a class="call-to-action call-to-action--accent" wire:click='export("HI", "pdf")'>Download HI PDF</a>
                        <a class="call-to-action call-to-action--accent" wire:click='export("HI", "xlsx")'>Download HI XLSX</a>
            <?php } ?>

        </p>
        <p class="subtitle">
            Dikembangkan oleh <strong>imperatoria</strong> <br> <strong>Yehezkiel Dio</strong> & <strong>Rizky
                Irswanda</strong>
        </p>
    </footer>
@endsection
