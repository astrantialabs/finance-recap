<section class="section">
    <div class="logout">
        <livewire:auth.logout />
    </div>
    <div class="container is-fullhd">
        {{-- {{ $summary }} --}}
        <div class="sekretariat">
            <h1 class="sekretariat-title title">
                Sekretariat.
            </h1>
            <hr>
            <div class="sekretariat-table-container table-container" id="table-container">
                <table class="sekretariat-table table is-bordered is-fullwidth fold-table">
                    <thead class="sekretariat-table-head">
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
                    <tbody class="sekretariat-table-body">
                        {{-- Sekretariat Index --}}
                        @foreach ($sekretariat as $sekretariat_item)
                        <?php $activities = $sekretariat_item['activity']; ?>

                        {{-- Sekretariat Activities --}}
                        @foreach ($activities as $activities => $activities_item)
                        <tr class="view">
                            <th class="activity-name">
                                {{ $activities_item['activity'] }}
                            </th>
                            <td class="activity-physical">
                                @isset($activities_item['physical'])
                                {{ $activities_item['physical'] }}%
                                @endisset
                                @php
                                if (is_null($activities_item['physical'])) {
                                echo "-";
                                }
                                @endphp
                            </td>
                            <td class="activity-finance">
                                @isset($activities_item['finance'])
                                {{ $activities_item['finance'] }}%
                                @endisset
                                @php
                                if (is_null($activities_item['finance'])) {
                                echo "-";
                                }
                                @endphp
                            </td>
                        </tr>
                        <tr class="fold">
                            <td colspan="100">
                                <div class="fold-content">
                                    <div class="sekretariat-table-fold-container table-container" id="table-container">
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
                                                $details = $activities_item['detail'];
                                                if (is_null($details)) {
                                                    break;
                                                }
                                                ?>
                                                {{-- Sekretariat Details --}}
                                                @foreach ($details as $details => $details_item)
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
                                                    <?php $monthly_finance = $details_item['monthly_finance']; ?>
                                                    @foreach ($monthly_finance as $monthly_finance =>
                                                    $monthly_finance_item)
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
                                                <?php $expenses = $details_item['expenses'] ?>
                                                @foreach ($expenses as $expenses => $expenses_item)
                                                <?php  $physical_expenses = $expenses_item['physical']; ?>

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
                                                    <?php  $monthly_physical_expenses = $physical_expenses['monthly']; ?>

                                                    @foreach ($monthly_physical_expenses as $monthly_physical_expenses
                                                    => $monthly_physical_expenses_item)
                                                    <td>
                                                        @isset($monthly_physical_expenses_item[0])
                                                        {{ $monthly_physical_expenses_item[0] }}
                                                        @endisset
                                                        @php
                                                        if (is_null($monthly_physical_expenses_item[0])) {
                                                        echo "-";
                                                        }
                                                        @endphp
                                                    </td>
                                                    @endforeach

                                                    <?php  $monthly_physical_expenses = $physical_expenses['monthly']; ?>
                                                    <td>
                                                        {{array_sum(array_column($monthly_physical_expenses, 0)) }}
                                                    </td>
                                                    <td rowspan="2">
                                                        {{ array_sum(array_column($monthly_physical_expenses, 1)) /
                                                        array_sum(array_column($monthly_physical_expenses,0)) * 100 }}%
                                                    </td>
                                                    <td rowspan="2">
                                                        {{ array_sum(array_column($monthly_physical_expenses, 0)) -
                                                        array_sum(array_column($monthly_physical_expenses,1)) }}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        Realisasi
                                                    </td>

                                                    {{-- Sekretariat Monthly Realisasi Expenses --}}
                                                    <?php  $monthly_physical_expenses = $physical_expenses['monthly']; ?>
                                                    @foreach ($monthly_physical_expenses as
                                                    $monthly_physical_expenses =>
                                                    $monthly_physical_expenses_item)
                                                    <td>
                                                        @isset($monthly_physical_expenses_item[1])
                                                        {{ $monthly_physical_expenses_item[1] }}
                                                        @endisset
                                                        @php
                                                        if (is_null($monthly_physical_expenses_item[1])) {
                                                        echo "-";
                                                        }
                                                        @endphp
                                                    </td>
                                                    @endforeach

                                                    <?php  $monthly_physical_expenses = $physical_expenses['monthly']; ?>
                                                    <td>
                                                        {{array_sum(array_column($monthly_physical_expenses, 1)) }}
                                                    </td>
                                                </tr>

                                                {{-- Sekretariat Target Expenses --}}
                                                <?php $expenses = $details_item['expenses'] ?>
                                                @foreach ($expenses as $expenses =>
                                                $expenses_item)
                                                <?php  $finance_expenses = $expenses_item['finance']; ?>

                                                <tr>
                                                    <td rowspan="2">
                                                        {{ $expenses_item['name'] }}
                                                    </td>
                                                    <td rowspan="2">
                                                        {{ $finance_expenses['total'] }}
                                                    </td>
                                                    <td>Target</td>

                                                    {{-- Sekretariat Monthly Finance Expenses --}}
                                                    <?php  $monthly_finance_expenses = $finance_expenses['monthly']; ?>
                                                    @foreach ($monthly_finance_expenses as
                                                    $monthly_finance_expenses =>
                                                    $monthly_finance_expenses_item)
                                                    <td>
                                                        @isset($monthly_finance_expenses_item[0])
                                                        @currency($monthly_finance_expenses_item[0])
                                                        @endisset
                                                        @php
                                                        if (is_null($monthly_finance_expenses_item[1])) {
                                                        echo "-";
                                                        }
                                                        @endphp
                                                    </td>
                                                    @endforeach
                                                    <?php  $monthly_finance_expenses = $finance_expenses['monthly']; ?>
                                                    <td>
                                                        @currency(array_sum(array_column($monthly_finance_expenses,
                                                        0)))
                                                    </td>
                                                    <td rowspan="2">
                                                        {{
                                                        round(array_sum(array_column($monthly_finance_expenses,
                                                        1)) /
                                                        array_sum(array_column($monthly_finance_expenses,
                                                        0)) * 100) }}%
                                                    </td>
                                                    <td rowspan="2">
                                                        @php
                                                        $result =
                                                        round(array_sum(array_column($monthly_finance_expenses,0)) -
                                                        array_sum(array_column($monthly_finance_expenses, 1)));
                                                        @endphp
                                                        @currency($result)
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Realisasi</td>

                                                    <?php  $monthly_finance_expenses = $finance_expenses['monthly']; ?>
                                                    @foreach ($monthly_finance_expenses as
                                                    $monthly_finance_expenses =>
                                                    $monthly_finance_expenses_item)
                                                    <td>
                                                        @isset($monthly_finance_expenses_item[1])
                                                        @currency($monthly_finance_expenses_item[1])
                                                        @endisset
                                                        @php
                                                        if (is_null($monthly_finance_expenses_item[1])) {
                                                        echo "-";
                                                        }
                                                        @endphp
                                                    </td>
                                                    @endforeach
                                                    <?php  $monthly_finance_expenses = $finance_expenses['monthly']; ?>
                                                    <td>
                                                        @currency(array_sum(array_column($monthly_finance_expenses, 1)))
                                                    </td>
                                                </tr>
                                                @endforeach

                                                @endforeach
                                                @endforeach
                                            </tbody>
                                            <tfoot class="sekretariat-table-nested-foot">
                                                <tr>
                                                    <th>
                                                        Jumlah Realisasi Keuangan
                                                    </th>
                                                    <?php $details = $activities_item['detail'];
                                                    if (is_null($details)) {
                                                        break;
                                                    }
                                                    ?>

                                                    <?php $jumlahRealisasiKeuangan = array(); ?>
                                                    <?php $jumlahRealisasiJanuari = array(); ?>

                                                    @foreach ($details as $details => $details_item)
                                                    <?php $jumlahRealisasiKeuangan[] = $details_item['total_finance'] ?>

                                                    <?php $expenses = $details_item['expenses'] ?>
                                                    @foreach ($expenses as $expenses =>
                                                    $expenses_item)
                                                    <?php  $finance_expenses = $expenses_item['finance']; ?>

                                                    <?php  $monthly_finance_expenses = $finance_expenses['monthly']; ?>
                                                    @foreach ($monthly_finance_expenses as
                                                    $monthly_finance_expenses =>
                                                    $monthly_finance_expenses_item)
                                                    <?php $jumlahRealisasiJanuari[] = $monthly_finance_expenses_item[1]; ?>
                                                    @endforeach

                                                    @endforeach
                                                    @endforeach

                                                    <th>
                                                        @currency(array_sum($jumlahRealisasiKeuangan))
                                                    </th>
                                                    <th>
                                                        &nbsp;
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>