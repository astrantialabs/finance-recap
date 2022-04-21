@php
foreach ($payload as $key => $value) {
    if (is_null($value)) {
        $payload[$key] = '';
    }
}
@endphp
<div class="division-container">
    <header class="division-header">
        <h1 class="title">
            <span class="title">
                {{ $payload[0]->name }}
            </span>
        </h1>
    </header>
    <section class="division-section">
        <div class="division-table-container table-container">
            <table class="division-table table is-bordered is-fulwidth fold-table">
                <thead class="division-table-head">
                    <tr>
                        <th class="sub-kegiatan" colspan="1" rowspan="2">
                            Sub Kegiatan
                        </th>
                        <th class="realisasi" colspan="2" rowspan="1">
                            Realisasi
                        </th>
                    </tr>
                    <tr>
                        <th>
                            Pisikal
                        </th>
                        <th>
                            Keuangan
                        </th>
                    </tr>
                </thead>
                <tbody class="division-table-body">
                    @foreach ($payload as $payload_item)
                        @php
                            $activities = $payload_item['activity'];
                        @endphp
                        @foreach ((array) $activities as $activities => $activities_item)
                            <tr class="view">
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
                            <tr class="fold">
                                <th>
                                    <div class="division-table-fold-container table-container">
                                        <table class="sekretariat-table-nested table is-bordered is-fullwidth">
                                            <thead class="sekretariat-table-nested-head">
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
                                                $details = $activities_item["detail"];
                                                if (is_null($details)) {
                                                    $details = [];
                                                }
                                                ?>
                                                {{-- Sekretariat Details --}}
                                                @foreach ((array) $details as $details => $details_item)
                                                    <tr>
                                                        <th>
                                                            {{ $details_item['account'] }}
                                                        </th>
                                                        <th>
                                                            @currency($details_item['total_finance'])
                                                        </th>
                                                        <th>
                                                            &nbsp;
                                                        </th>

                                                        {{-- Sekretariat Monthly Finance --}}
                                                        <?php $monthly_finance = $details_item["monthly_finance"]; ?>
                                                        @foreach ((array) $monthly_finance as $monthly_finance => $monthly_finance_item)
                                                            <th>
                                                                @currency($monthly_finance_item)
                                                            </th>
                                                        @endforeach

                                                        <th>
                                                            @currency($details_item['total_finance'])
                                                        </th>
                                                        <th>
                                                            &nbsp;
                                                        </th>
                                                        <th>
                                                            &nbsp;
                                                        </th>
                                                    </tr>

                                                    {{-- Sekretariat Target Expenses --}}
                                                    <?php $expenses = $details_item["expenses"]; ?>
                                                    @foreach ((array) $expenses as $expenses => $expenses_item)
                                                        <?php $physical_expenses = $expenses_item["physical"]; ?>
                                                        <tr>
                                                            <td rowspan="2">
                                                                {{ $expenses_item['name'] }}
                                                            </td>
                                                            <td rowspan="2">
                                                                {{ $physical_expenses['total'] }}
                                                            </td>
                                                            <td>
                                                                Target
                                                            </td>

                                                            {{-- Sekretariat Monthly Physical Expenses --}}
                                                            <?php $monthly_physical_expenses =
                                                                $physical_expenses["monthly"]; ?>

                                                            @foreach ((array) $monthly_physical_expenses as $monthly_physical_expenses => $monthly_physical_expenses_item)
                                                                <td>
                                                                    @isset($monthly_physical_expenses_item[0])
                                                                        {{ $monthly_physical_expenses_item[0] }}
                                                                    @endisset
                                                                    @php
                                                                        if (is_null($monthly_physical_expenses_item[0])) {
                                                                            echo '-';
                                                                        }
                                                                    @endphp
                                                                </td>
                                                            @endforeach

                                                            <?php $monthly_physical_expenses =
                                                                $physical_expenses["monthly"]; ?>
                                                            <td>
                                                                {{ array_sum(array_column($monthly_physical_expenses, 0)) }}
                                                            </td>
                                                            <td rowspan="2">
                                                                {{ divnum(
                                                                    array_sum(array_column($monthly_physical_expenses, 1)),
                                                                    array_sum(array_column($monthly_physical_expenses, 0)),
                                                                ) * 100 }}%
                                                            </td>
                                                            <td rowspan="2">
                                                                {{ array_sum(array_column($monthly_physical_expenses, 0)) -
                                                                    array_sum(array_column($monthly_physical_expenses, 1)) }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Realisasi
                                                            </td>

                                                            {{-- Sekretariat Monthly Realisasi Expenses --}}
                                                            <?php $monthly_physical_expenses =
                                                                $physical_expenses["monthly"]; ?>
                                                            @foreach ((array) $monthly_physical_expenses as $monthly_physical_expenses => $monthly_physical_expenses_item)
                                                                <td>
                                                                    @isset($monthly_physical_expenses_item[1])
                                                                        {{ $monthly_physical_expenses_item[1] }}
                                                                    @endisset
                                                                    @php
                                                                        if (is_null($monthly_physical_expenses_item[1])) {
                                                                            echo '-';
                                                                        }
                                                                    @endphp
                                                                </td>
                                                            @endforeach

                                                            <?php $monthly_physical_expenses =
                                                                $physical_expenses["monthly"]; ?>
                                                            <td>
                                                                {{ array_sum(array_column($monthly_physical_expenses, 1)) }}
                                                            </td>
                                                        </tr>

                                                        {{-- Sekretariat Target Expenses --}}
                                                        <?php $expenses = $details_item["expenses"]; ?>
                                                        @foreach ((array) $expenses as $expenses => $expenses_item)
                                                            <?php $finance_expenses = $expenses_item["finance"]; ?>

                                                            <tr>
                                                                <td rowspan="2">
                                                                    {{ $expenses_item['name'] }}
                                                                </td>
                                                                <td rowspan="2">
                                                                    {{ $finance_expenses['total'] }}
                                                                </td>
                                                                <td>Target</td>

                                                                {{-- Sekretariat Monthly Finance Expenses --}}
                                                                <?php $monthly_finance_expenses =
                                                                    $finance_expenses["monthly"]; ?>
                                                                @foreach ((array) $monthly_finance_expenses as $monthly_finance_expenses => $monthly_finance_expenses_item)
                                                                    <td>
                                                                        @isset($monthly_finance_expenses_item[0])
                                                                            @currency($monthly_finance_expenses_item[0])
                                                                        @endisset
                                                                        @php
                                                                            if (is_null($monthly_finance_expenses_item[1])) {
                                                                                echo '-';
                                                                            }
                                                                        @endphp
                                                                    </td>
                                                                @endforeach
                                                                <?php $monthly_finance_expenses =
                                                                    $finance_expenses["monthly"]; ?>
                                                                <td>
                                                                    @currency(array_sum(array_column($monthly_finance_expenses,
                                                                    0)))
                                                                </td>
                                                                <td rowspan="2">
                                                                    {{ round(
                                                                        divnum(
                                                                            array_sum(array_column($monthly_finance_expenses, 1)),
                                                                            array_sum(array_column($monthly_finance_expenses, 0)),
                                                                        ) * 100,
                                                                    ) }}%
                                                                </td>
                                                                <td rowspan="2">
                                                                    @php
                                                                        $result = round(array_sum(array_column($monthly_finance_expenses, 0)) - array_sum(array_column($monthly_finance_expenses, 1)));
                                                                    @endphp
                                                                    @currency($result)
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td>Realisasi</td>

                                                                <?php $monthly_finance_expenses =
                                                                    $finance_expenses["monthly"]; ?>
                                                                @foreach ((array) $monthly_finance_expenses as $monthly_finance_expenses => $monthly_finance_expenses_item)
                                                                    <td>
                                                                        @isset($monthly_finance_expenses_item[1])
                                                                            @currency($monthly_finance_expenses_item[1])
                                                                        @endisset
                                                                        @php
                                                                            if (is_null($monthly_finance_expenses_item[1])) {
                                                                                echo '-';
                                                                            }
                                                                        @endphp
                                                                    </td>
                                                                @endforeach
                                                                <?php $monthly_finance_expenses =
                                                                    $finance_expenses["monthly"]; ?>
                                                                <td>
                                                                    @currency(array_sum(array_column($monthly_finance_expenses,
                                                                    1)))
                                                                </td>
                                                            </tr>
                                                        @endforeach
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
                                                    <?php $details = $activities_item["detail"]; ?>

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
                                                        <?php $expenses = $details_item["expenses"]; ?>
                                                        @foreach ((array) $expenses as $expenses => $expenses_item)
                                                            <?php $physical_expenses = $expenses_item["physical"]; ?>

                                                            <?php $monthly_physical_expenses =
                                                                $physical_expenses["monthly"]; ?>

                                                            <?php $jumlahRealisasiKeuangan[] = array_sum(
                                                                array_column($monthly_physical_expenses, 0)
                                                            ); ?>

                                                            <?php $jumlahRealisasiJanuari[] = array_slice(
                                                                $monthly_physical_expenses[0],
                                                                0,
                                                                10
                                                            ); ?>
                                                            <?php $jumlahRealisasiFebuari[] = array_slice(
                                                                $monthly_physical_expenses[1],
                                                                1,
                                                                10
                                                            ); ?>
                                                            <?php $jumlahRealisasiMaret[] = array_slice(
                                                                $monthly_physical_expenses[2],
                                                                1,
                                                                10
                                                            ); ?>
                                                            <?php $jumlahRealisasiApril[] = array_slice(
                                                                $monthly_physical_expenses[3],
                                                                1,
                                                                10
                                                            ); ?>
                                                            <?php $jumlahRealisasiMei[] = array_slice(
                                                                $monthly_physical_expenses[4],
                                                                1,
                                                                10
                                                            ); ?>
                                                            <?php $jumlahRealisasiJuni[] = array_slice(
                                                                $monthly_physical_expenses[5],
                                                                1,
                                                                10
                                                            ); ?>
                                                            <?php $jumlahRealisasiJuli[] = array_slice(
                                                                $monthly_physical_expenses[6],
                                                                1,
                                                                10
                                                            ); ?>
                                                            <?php $jumlahRealisasiAgustus[] = array_slice(
                                                                $monthly_physical_expenses[7],
                                                                1,
                                                                10
                                                            ); ?>
                                                            <?php $jumlahRealisasiSeptember[] = array_slice(
                                                                $monthly_physical_expenses[8],
                                                                1,
                                                                10
                                                            ); ?>
                                                            <?php $jumlahRealisasiOktober[] = array_slice(
                                                                $monthly_physical_expenses[9],
                                                                1,
                                                                10
                                                            ); ?>
                                                            <?php $jumlahRealisasiNovember[] = array_slice(
                                                                $monthly_physical_expenses[10],
                                                                1,
                                                                10
                                                            ); ?>
                                                            <?php $jumlahRealisasiDecember[] = array_slice(
                                                                $monthly_physical_expenses[11],
                                                                1,
                                                                10
                                                            ); ?>
                                                        @endforeach
                                                    @endforeach

                                                    <th>
                                                        &nbsp;
                                                    </th>
                                                    <th>
                                                        @php
                                                            $januari = array_map(function ($item) {
                                                                return $item[1];
                                                            }, $jumlahRealisasiJanuari);
                                                            $januari = array_sum($januari);
                                                        @endphp
                                                        @currency($januari)
                                                    </th>

                                                    {{-- 1 --}}
                                                    <th>
                                                        @php
                                                            $febuari = array_map(function ($item) {
                                                                return $item[0];
                                                            }, $jumlahRealisasiFebuari);
                                                            $febuari = array_sum($febuari);
                                                        @endphp
                                                        @currency($febuari)
                                                    </th>

                                                    {{-- 2 --}}
                                                    <th>
                                                        @php
                                                            $maret = array_map(function ($item) {
                                                                return $item[0];
                                                            }, $jumlahRealisasiMaret);
                                                            $maret = array_sum($maret);
                                                        @endphp
                                                        @currency($maret)
                                                    </th>

                                                    {{-- 3 --}}
                                                    <th>
                                                        @php
                                                            $april = array_map(function ($item) {
                                                                return $item[0];
                                                            }, $jumlahRealisasiApril);
                                                            $april = array_sum($april);
                                                        @endphp
                                                        @currency($april)
                                                    </th>

                                                    {{-- 4 --}}
                                                    <th>
                                                        @php
                                                            $mei = array_map(function ($item) {
                                                                return $item[0];
                                                            }, $jumlahRealisasiMei);
                                                            $mei = array_sum($mei);
                                                        @endphp
                                                        @currency($mei)
                                                    </th>

                                                    {{-- 6 --}}
                                                    <th>
                                                        @php
                                                            $juni = array_map(function ($item) {
                                                                return $item[0];
                                                            }, $jumlahRealisasiJuni);
                                                            $juni = array_sum($juni);
                                                        @endphp
                                                        @currency($juni)
                                                    </th>

                                                    {{-- 7 --}}
                                                    <th>
                                                        @php
                                                            $juli = array_map(function ($item) {
                                                                return $item[0];
                                                            }, $jumlahRealisasiJuli);
                                                            $juli = array_sum($juli);
                                                        @endphp
                                                        @currency($juli)
                                                    </th>

                                                    {{-- 8 --}}
                                                    <th>
                                                        @php
                                                            $agustus = array_map(function ($item) {
                                                                return $item[0];
                                                            }, $jumlahRealisasiAgustus);
                                                            $agustus = array_sum($agustus);
                                                        @endphp
                                                        @currency($agustus)
                                                    </th>

                                                    {{-- 9 --}}
                                                    <th>
                                                        @php
                                                            $september = array_map(function ($item) {
                                                                return $item[0];
                                                            }, $jumlahRealisasiSeptember);
                                                            $september = array_sum($september);
                                                        @endphp
                                                        @currency($september)
                                                    </th>

                                                    {{-- 10 --}}
                                                    <th>
                                                        @php
                                                            $oktober = array_map(function ($item) {
                                                                return $item[0];
                                                            }, $jumlahRealisasiOktober);
                                                            $oktober = array_sum($oktober);
                                                        @endphp
                                                        @currency($oktober)
                                                    </th>

                                                    {{-- 11 --}}
                                                    <th>
                                                        @php
                                                            $november = array_map(function ($item) {
                                                                return $item[0];
                                                            }, $jumlahRealisasiNovember);
                                                            $november = array_sum($november);
                                                        @endphp
                                                        @currency($november)
                                                    </th>

                                                    {{-- 12 --}}
                                                    <th>
                                                        @php
                                                            $december = array_map(function ($item) {
                                                                return $item[0];
                                                            }, $jumlahRealisasiDecember);
                                                            $december = array_sum($december);
                                                        @endphp
                                                        @currency($december)
                                                    </th>
                                                    <th>
                                                        @php
                                                            $all_months = $januari + $febuari + $maret + $april + $mei + $juni + $juli + $agustus + $september + $oktober + $november + $december;
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
                                                    <?php $details = $activities_item["detail"]; ?>

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
                                                        <?php $jumlahRealisasiKeuangan[] =
                                                            $details_item["total_finance"]; ?>

                                                        <?php $expenses = $details_item["expenses"]; ?>
                                                        @foreach ((array) $expenses as $expenses => $expenses_item)
                                                            <?php $finance_expenses = $expenses_item["finance"]; ?>

                                                            <?php $monthly_finance_expenses =
                                                                $finance_expenses["monthly"]; ?>

                                                            <?php $jumlahRealisasiJanuari[] = array_slice(
                                                                $monthly_finance_expenses[0],
                                                                0,
                                                                10
                                                            ); ?>
                                                            <?php $jumlahRealisasiFebuari[] = array_slice(
                                                                $monthly_finance_expenses[1],
                                                                1,
                                                                10
                                                            ); ?>
                                                            <?php $jumlahRealisasiMaret[] = array_slice(
                                                                $monthly_finance_expenses[2],
                                                                1,
                                                                10
                                                            ); ?>
                                                            <?php $jumlahRealisasiApril[] = array_slice(
                                                                $monthly_finance_expenses[3],
                                                                1,
                                                                10
                                                            ); ?>
                                                            <?php $jumlahRealisasiMei[] = array_slice(
                                                                $monthly_finance_expenses[4],
                                                                1,
                                                                10
                                                            ); ?>
                                                            <?php $jumlahRealisasiJuni[] = array_slice(
                                                                $monthly_finance_expenses[5],
                                                                1,
                                                                10
                                                            ); ?>
                                                            <?php $jumlahRealisasiJuli[] = array_slice(
                                                                $monthly_finance_expenses[6],
                                                                1,
                                                                10
                                                            ); ?>
                                                            <?php $jumlahRealisasiAgustus[] = array_slice(
                                                                $monthly_finance_expenses[7],
                                                                1,
                                                                10
                                                            ); ?>
                                                            <?php $jumlahRealisasiSeptember[] = array_slice(
                                                                $monthly_finance_expenses[8],
                                                                1,
                                                                10
                                                            ); ?>
                                                            <?php $jumlahRealisasiOktober[] = array_slice(
                                                                $monthly_finance_expenses[9],
                                                                1,
                                                                10
                                                            ); ?>
                                                            <?php $jumlahRealisasiNovember[] = array_slice(
                                                                $monthly_finance_expenses[10],
                                                                1,
                                                                10
                                                            ); ?>
                                                            <?php $jumlahRealisasiDecember[] = array_slice(
                                                                $monthly_finance_expenses[11],
                                                                1,
                                                                10
                                                            ); ?>
                                                        @endforeach
                                                    @endforeach

                                                    <th>
                                                        @currency(array_sum($jumlahRealisasiKeuangan))
                                                    </th>
                                                    <th>
                                                        &nbsp;
                                                    </th>
                                                    <th>
                                                        @php
                                                            $januari = array_map(function ($item) {
                                                                return $item[1];
                                                            }, $jumlahRealisasiJanuari);
                                                            $januari = array_sum($januari);
                                                        @endphp
                                                        @currency($januari)
                                                    </th>

                                                    {{-- 1 --}}
                                                    <th>
                                                        @php
                                                            $febuari = array_map(function ($item) {
                                                                return $item[0];
                                                            }, $jumlahRealisasiFebuari);
                                                            $febuari = array_sum($febuari);
                                                        @endphp
                                                        @currency($febuari)
                                                    </th>

                                                    {{-- 2 --}}
                                                    <th>
                                                        @php
                                                            $maret = array_map(function ($item) {
                                                                return $item[0];
                                                            }, $jumlahRealisasiMaret);
                                                            $maret = array_sum($maret);
                                                        @endphp
                                                        @currency($maret)
                                                    </th>

                                                    {{-- 3 --}}
                                                    <th>
                                                        @php
                                                            $april = array_map(function ($item) {
                                                                return $item[0];
                                                            }, $jumlahRealisasiApril);
                                                            $april = array_sum($april);
                                                        @endphp
                                                        @currency($april)
                                                    </th>

                                                    {{-- 4 --}}
                                                    <th>
                                                        @php
                                                            $mei = array_map(function ($item) {
                                                                return $item[0];
                                                            }, $jumlahRealisasiMei);
                                                            $mei = array_sum($mei);
                                                        @endphp
                                                        @currency($mei)
                                                    </th>

                                                    {{-- 6 --}}
                                                    <th>
                                                        @php
                                                            $juni = array_map(function ($item) {
                                                                return $item[0];
                                                            }, $jumlahRealisasiJuni);
                                                            $juni = array_sum($juni);
                                                        @endphp
                                                        @currency($juni)
                                                    </th>

                                                    {{-- 7 --}}
                                                    <th>
                                                        @php
                                                            $juli = array_map(function ($item) {
                                                                return $item[0];
                                                            }, $jumlahRealisasiJuli);
                                                            $juli = array_sum($juli);
                                                        @endphp
                                                        @currency($juli)
                                                    </th>

                                                    {{-- 8 --}}
                                                    <th>
                                                        @php
                                                            $agustus = array_map(function ($item) {
                                                                return $item[0];
                                                            }, $jumlahRealisasiAgustus);
                                                            $agustus = array_sum($agustus);
                                                        @endphp
                                                        @currency($agustus)
                                                    </th>

                                                    {{-- 9 --}}
                                                    <th>
                                                        @php
                                                            $september = array_map(function ($item) {
                                                                return $item[0];
                                                            }, $jumlahRealisasiSeptember);
                                                            $september = array_sum($september);
                                                        @endphp
                                                        @currency($september)
                                                    </th>

                                                    {{-- 10 --}}
                                                    <th>
                                                        @php
                                                            $oktober = array_map(function ($item) {
                                                                return $item[0];
                                                            }, $jumlahRealisasiOktober);
                                                            $oktober = array_sum($oktober);
                                                        @endphp
                                                        @currency($oktober)
                                                    </th>

                                                    {{-- 11 --}}
                                                    <th>
                                                        @php
                                                            $november = array_map(function ($item) {
                                                                return $item[0];
                                                            }, $jumlahRealisasiNovember);
                                                            $november = array_sum($november);
                                                        @endphp
                                                        @currency($november)
                                                    </th>

                                                    {{-- 12 --}}
                                                    <th>
                                                        @php
                                                            $december = array_map(function ($item) {
                                                                return $item[0];
                                                            }, $jumlahRealisasiDecember);
                                                            $december = array_sum($december);
                                                        @endphp
                                                        @currency($december)
                                                    </th>
                                                    <th>
                                                        @php
                                                            $all_months = $januari + $febuari + $maret + $april + $mei + $juni + $juli + $agustus + $september + $oktober + $november + $december;
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
                                </th>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
