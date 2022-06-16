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
                                                <th rowspan="2">Jumlah</th>
                                                <th rowspan="2">
                                                    Persentase Realisasi Fisik / Anggaran
                                                </th>
                                                <th rowspan="2">
                                                    Sisa Fisik / Anggaran yang belum digunakan
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
                                                    @foreach ((array) $monthly_finance as $monthly_finance_array => $monthly_finance_item)
                                                        <td>
                                                            @currency($monthly_finance_item['total'])
                                                        </td>
                                                    @endforeach

                                                    <td>
                                                        @currency(array_sum(array_column($monthly_finance, 'total')))
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
                                                        </td>
                                                        <td class="second-row fixed" rowspan="2">
                                                            {{ $finance_expenses['jumlah_anggaran'] }}
                                                        </td>

                                                        <td class="second-row fixed">Target</td>

                                                        <?php $monthly_finance_expenses = $finance_expenses['jumlah_Kebutuhan_dana']; ?>
                                                        @foreach ((array) $monthly_finance_expenses as $monthly_finance_expenses => $monthly_finance_expenses_item)
                                                            <td class="second-row">
                                                                @isset($monthly_finance_expenses_item['target']['total'])
                                                                    @currency($monthly_finance_expenses_item['target']['total'])
                                                                @endisset
                                                                @php
                                                                    if (is_null($monthly_finance_expenses_item['target']['total'])) {
                                                                        echo '-';
                                                                    }
                                                                @endphp
                                                            </td>
                                                        @endforeach

                                                        <?php $monthly_finance_expenses = $finance_expenses['jumlah_Kebutuhan_dana']; ?>
                                                        <td class="second-row">
                                                            @currency(array_sum(array_column(array_column($monthly_finance_expenses, 'target'), 'total')))
                                                        </td>
                                                        <td class="second-row" rowspan="2">
                                                            {{ round(
                                                                divnum(
                                                                    array_sum(array_column(array_column($monthly_finance_expenses, 'realisasi'), 'total')),
                                                                    array_sum(array_column(array_column($monthly_finance_expenses, 'target'), 'total')),
                                                                ) * 100,
                                                            ) }}%
                                                        </td>
                                                        <td class="second-row" rowspan="2">
                                                            @php
                                                                $result = round(
                                                                    array_sum(array_column(array_column($monthly_finance_expenses, 'target'), 'total')) -
                                                                    array_sum(array_column(array_column($monthly_finance_expenses, 'realisasi'), 'total'))
                                                                );
                                                            @endphp
                                                            @currency($result)
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="second-row fixed">Realisasi</td>

                                                        <?php $monthly_finance_expenses = $finance_expenses['jumlah_Kebutuhan_dana']; ?>
                                                        @foreach ((array) $monthly_finance_expenses as $monthly_finance_expenses => $monthly_finance_expenses_item)
                                                            <td class="second-row">
                                                                @isset($monthly_finance_expenses_item['realisasi']['total'])
                                                                    @currency($monthly_finance_expenses_item['realisasi']['total'])
                                                                @endisset
                                                                @php
                                                                    if (is_null($monthly_finance_expenses_item['realisasi']['total'])) {
                                                                        echo '-';
                                                                    }
                                                                @endphp
                                                            </td>
                                                        @endforeach
                                                        <?php $monthly_finance_expenses = $finance_expenses['jumlah_Kebutuhan_dana']; ?>
                                                        <td class="second-row">
                                                            @currency(array_sum(array_column(array_column($monthly_finance_expenses, 'realisasi'), 'total')))
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                        <tfoot class="sekretariat-table-nested-foot">
                                            <tr>
                                                <?php
                                                    $details = $test_item['detail'];
                                                    $persentase_realisasi_fisik_array = [];
                                                    
                                                    foreach ($details as $detail => $detail_item) {
                                                        $expenses = $detail_item['biaya'];
                                                        

                                                        foreach ($expenses as $expense => $expense_item) {
                                                            $physical_expenses = $expense_item['fisik'];

                                                            $monthly_physical_expenses = $physical_expenses['jumlah_Kebutuhan_dana'];

                                                            $persentase_realisasi_fisik = (array_sum(array_column(array_column($monthly_physical_expenses, 'realisasi'), 'total')) / array_sum(array_column(array_column($monthly_physical_expenses, 'target'), 'total'))) * 100;
                                                        
                                                            array_push($persentase_realisasi_fisik_array, $persentase_realisasi_fisik);
                                                        }
                                                    }

                                                    if (array_sum($persentase_realisasi_fisik_array) != 0 && count($persentase_realisasi_fisik_array) != 0) {
                                                        $persentase_realisasi_fisik_footer = array_sum($persentase_realisasi_fisik_array) / count($persentase_realisasi_fisik_array);
                                                    } else if (array_sum($persentase_realisasi_fisik_array) == 0 || count($persentase_realisasi_fisik_array) == 0) {
                                                        $persentase_realisasi_fisik_footer = 0;
                                                    }

                                                    $sisa_persentase_realisasi_fisik_footer = 100 - $persentase_realisasi_fisik_footer;
                                                ?>

                                                <th>
                                                    Jumlah Realisasi Fisik
                                                </th>
                                                <th>
                                                    &nbsp;
                                                </th>
                                                <th>
                                                    &nbsp;
                                                </th>
                                                <th>
                                                    &nbsp;
                                                </th>
                                                <th>
                                                    &nbsp;
                                                </th>
                                                <th>
                                                    &nbsp;
                                                </th>
                                                <th>
                                                    &nbsp;
                                                </th>
                                                <th>
                                                    &nbsp;
                                                </th>
                                                <th>
                                                    &nbsp;
                                                </th>
                                                <th>
                                                    &nbsp;
                                                </th>
                                                <th>
                                                    &nbsp;
                                                </th>
                                                <th>
                                                    &nbsp;
                                                </th>
                                                <th>
                                                    &nbsp;
                                                </th>
                                                <th>
                                                    &nbsp;
                                                </th>
                                                <th>
                                                    &nbsp;
                                                </th>
                                                <th>
                                                    &nbsp;
                                                </th>
                                                <th>
                                                    {{ round($persentase_realisasi_fisik_footer) }}%
                                                </th>
                                                <th>
                                                    {{ round($sisa_persentase_realisasi_fisik_footer) }}%
                                                </th>
                                            </tr>
                                            <tr>
                                                <?php
                                                    $monthly_realization_finance_object = [
                                                        1 => [],
                                                        2 => [],
                                                        3 => [],
                                                        4 => [],
                                                        5 => [],
                                                        6 => [],
                                                        7 => [],
                                                        8 => [],
                                                        9 => [],
                                                        10 => [],
                                                        11 => [],
                                                        12 => [],
                                                    ];

                                                    $details = $test_item['detail'];

                                                    $jumlah_fisik_anggaran_sum = array_sum(array_column($details, 'jumlah_fisik_anggaran'));

                                                    foreach ($details as $detail => $detail_item) {
                                                        $expenses = $detail_item['biaya'];

                                                        foreach ($expenses as $expense => $expense_item) {
                                                            $finance_expenses = $expense_item['keuangan'];

                                                            $monthly_finance_expenses = $finance_expenses['jumlah_Kebutuhan_dana'];

                                                            foreach ($monthly_finance_expenses as $monthly_finance_expense => $monthly_finance_expense_item) {
                                                                array_push($monthly_realization_finance_object[$monthly_finance_expense_item['id']], $monthly_finance_expense_item['realisasi']['total']);
                                                            }
                                                        }
                                                    };

                                                    $monthly_realization_finance_summary = 0;

                                                    foreach (array_keys($monthly_realization_finance_object) as $monthly_realization_finance_object_key => $key) {
                                                        $monthly_realization_finance_object[$key] = array_sum($monthly_realization_finance_object[$key]);

                                                        $monthly_realization_finance_summary += $monthly_realization_finance_object[$key];
                                                    };
                                                ?>

                                                <th>
                                                    Jumlah Realisasi Keuangan
                                                </th>
                                                <th>
                                                    @currency($jumlah_fisik_anggaran_sum)
                                                </th>

                                                <th>
                                                    &nbsp;
                                                </th>
                                                <th>
                                                    @currency($monthly_realization_finance_object[1])
                                                </th>
                                                <th>
                                                    @currency($monthly_realization_finance_object[2])
                                                </th>
                                                <th>
                                                    @currency($monthly_realization_finance_object[3])
                                                </th>
                                                <th>
                                                    @currency($monthly_realization_finance_object[4])
                                                </th>
                                                <th>
                                                    @currency($monthly_realization_finance_object[5])
                                                </th>
                                                <th>
                                                    @currency($monthly_realization_finance_object[6])
                                                </th>
                                                <th>
                                                    @currency($monthly_realization_finance_object[7])
                                                </th>
                                                <th>
                                                    @currency($monthly_realization_finance_object[8])
                                                </th>
                                                <th>
                                                    @currency($monthly_realization_finance_object[9])
                                                </th>
                                                <th>
                                                    @currency($monthly_realization_finance_object[10])
                                                </th>
                                                <th>
                                                    @currency($monthly_realization_finance_object[11])
                                                </th>
                                                <th>
                                                    @currency($monthly_realization_finance_object[12])
                                                </th>
                                                <th>
                                                    @currency($monthly_realization_finance_summary)
                                                </th>
                                                <th>
                                                    <?php try { ?>
                                                        {{ round(($monthly_realization_finance_summary / $jumlah_fisik_anggaran_sum) * 100) }}%
                                                    <?php } catch(error) { ?>
                                                        0%
                                                    <?php } ?>

                                                </th>
                                                <th>
                                                    @currency($jumlah_fisik_anggaran_sum - $monthly_realization_finance_summary)
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
