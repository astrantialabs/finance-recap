<section class="section">
    <div class="container is-fullhd">
        <div class="sekretariat">
            <h1 class="title">
                Sekretariat
            </h1>
            <div class="table-container" id="table-container">
                <table class="table is-bordered is-narrow fold-table">
                    <thead>
                        <tr>
                            <th colspan="1" rowspan="2">
                                Sub Kegiatan
                            </th>
                            <th colspan="2" rowspan="1">
                                Realisasi
                            </th>
                        <tr>
                            <th>
                                Pisikal
                            </th>
                            <th>
                                Keuangan
                            </th>
                        </tr>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sekretariat as $sekretariat => $sekretariatItem)
                        <?php $activities = $sekretariatItem['activity']; ?>
                        @foreach ($activities as $activities => $activityItem)
                        <tr class="view">
                            <td>
                                {{ $activityItem['activity'] }}
                            </td>
                            <td>
                                @isset($record)
                                {{ $activityItem['physical'] }}
                                @endisset
                            </td>
                            <td>
                                {{ $activityItem['finance'] }}
                            </td>
                        </tr>
                        <tr class="fold">
                            <td colspan="7">
                                <div class="fold-content">
                                    <div class="table-container" id="table-container">
                                        <table class="table fold-table">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2">Rekening</th>
                                                    <th rowspan="2">Jumlah Fisik/Anggaran</th>
                                                    <th rowspan="1" colspan="12">
                                                        Jumlah Kebutuhan Dana
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>Januari</th>
                                                    <th>Febuari</th>
                                                    <th>Maret</th>
                                                    <th>April</th>
                                                    <th>Mei</th>
                                                    <th>Juni</th>
                                                    <th>Juli</th>
                                                    <th>Agustus</th>
                                                    <th>September</th>
                                                    <th>Oktober</th>
                                                    <th>November</th>
                                                    <th>December</th>
                                                    <th colspan="2" rowspan="1">Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $details = $activityItem['detail'] ?>
                                                <?php
                                                    if (is_null($details)) {
                                                        break;
                                                    }
                                                    ?>
                                                @foreach ($details as $details => $detailsItem)
                                                <tr class="view">
                                                    <td>
                                                        {{ $detailsItem['account'] }}
                                                    </td>
                                                    <td>
                                                        @currency($detailsItem['total_finance'])
                                                    </td>
                                                    <?php $monthlyFinance = $detailsItem['monthly_finance'] ?>
                                                    @foreach ($monthlyFinance as $monthlyFinance => $monthlyFinanceItem)
                                                    <td>
                                                        @currency($monthlyFinanceItem)
                                                    </td>
                                                    @endforeach
                                                    <td>
                                                        @currency($detailsItem['total_finance'])
                                                    </td>
                                                </tr>
                                                <tr class="fold">
                                                    <td colspan="7">
                                                        <div class="fold-content">
                                                            <div class="table-container" id="table-container">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th rowspan="2">Rekening</th>
                                                                            <th rowspan="2">Jumlah Fisik/Anggaran</th>
                                                                            <th rowspan="2">
                                                                                Target Realisasi
                                                                            </th>
                                                                            <th rowspan="1" colspan="12">
                                                                                Jumlah Kebutuhan Dana
                                                                            </th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Januari</th>
                                                                            <th>Febuari</th>
                                                                            <th>Maret</th>
                                                                            <th>April</th>
                                                                            <th>Mei</th>
                                                                            <th>Juni</th>
                                                                            <th>Juli</th>
                                                                            <th>Agustus</th>
                                                                            <th>September</th>
                                                                            <th>Oktober</th>
                                                                            <th>November</th>
                                                                            <th>December</th>
                                                                            <th colspan="1" rowspan="1">Jumlah</th>
                                                                            <th>Persentase Realisasi Fisik /Anggaran</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php $expenses = $detailsItem['expenses'] ?>
                                                                        @foreach ($expenses as $expenses =>
                                                                        $expensesItem)
                                                                        <?php  $physicalExpenses = $expensesItem['physical']; ?>
                                                                        <tr class="view">
                                                                            <td rowspan="2">
                                                                                {{ $expensesItem['name'] }}
                                                                            </td>
                                                                            <td rowspan="2">
                                                                                {{ $physicalExpenses['total'] }}
                                                                            </td>
                                                                            <td>
                                                                                Target
                                                                            </td>
                                                                            <?php  $monthlyPhysicalExpenses = $physicalExpenses['monthly']; ?>
                                                                            @foreach ($monthlyPhysicalExpenses as $monthlyPhysicalExpenses => $monthlyPhysicalExpensesItem)
                                                                                <td>
                                                                                    @isset($monthlyPhysicalExpensesItem[0])
                                                                                        {{ $monthlyPhysicalExpensesItem[0] }}
                                                                                    @endisset
                                                                                    @empty($monthlyPhysicalExpensesItem[0])
                                                                                        -
                                                                                    @endempty
                                                                                </td>
                                                                            @endforeach
                                                                            <?php  $monthlyPhysicalExpenses = $physicalExpenses['monthly']; ?>
                                                                            <td>
                                                                                {{ array_sum(array_column($monthlyPhysicalExpenses, 0)) }}
                                                                            </td>
                                                                            <td rowspan="2">
                                                                                {{ array_sum(array_column($monthlyPhysicalExpenses, 1)) / array_sum(array_column($monthlyPhysicalExpenses, 0)) * 100 }}%
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Realisasi</td>
                                                                            <?php  $monthlyPhysicalExpenses = $physicalExpenses['monthly']; ?>
                                                                            @foreach ($monthlyPhysicalExpenses as $monthlyPhysicalExpenses => $monthlyPhysicalExpensesItem)
                                                                                <td>
                                                                                    @isset($monthlyPhysicalExpensesItem[1])
                                                                                        {{ $monthlyPhysicalExpensesItem[1] }}
                                                                                    @endisset
                                                                                    @empty($monthlyPhysicalExpensesItem[1])
                                                                                        -
                                                                                    @endempty
                                                                                </td>
                                                                            @endforeach
                                                                            <?php  $monthlyPhysicalExpenses = $physicalExpenses['monthly']; ?>
                                                                            <td>
                                                                                {{ array_sum(array_column($monthlyPhysicalExpenses, 1)) }}
                                                                            </td>
                                                                            
                                                                        </tr>
                                                                        @endforeach

                                                                        <?php $expenses = $detailsItem['expenses'] ?>
                                                                        @foreach ($expenses as $expenses =>
                                                                        $expensesItem)
                                                                        <?php  $financeExpenses = $expensesItem['finance']; ?>
                                                                        <tr class="view">
                                                                            <td rowspan="2">
                                                                                {{ $expensesItem['name'] }}
                                                                            </td>
                                                                            <td rowspan="2">
                                                                                {{ $financeExpenses['total'] }}
                                                                            </td>
                                                                            <td>
                                                                                Target
                                                                            </td>
                                                                            <?php  $monthlyFinanceExpenses = $financeExpenses['monthly']; ?>
                                                                            @foreach ($monthlyFinanceExpenses as $monthlyFinanceExpenses => $monthlyFinanceExpensesItem)
                                                                                <td>
                                                                                    @isset($monthlyFinanceExpensesItem[0])
                                                                                        @currency($monthlyFinanceExpensesItem[0])
                                                                                    @endisset
                                                                                    @empty($monthlyFinanceExpensesItem[0])
                                                                                        -
                                                                                    @endempty
                                                                                </td>
                                                                            @endforeach
                                                                            <?php  $monthlyFinanceExpenses = $financeExpenses['monthly']; ?>
                                                                            <td>
                                                                                {{ array_sum(array_column($monthlyFinanceExpenses, 0)) }}
                                                                            </td>
                                                                            <td rowspan="2">
                                                                                {{ round(array_sum(array_column($monthlyFinanceExpenses, 1)) / array_sum(array_column($monthlyFinanceExpenses, 0)) * 100) }}%
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Realisasi</td>
                                                                            <?php  $monthlyFinanceExpenses = $financeExpenses['monthly']; ?>
                                                                            @foreach ($monthlyFinanceExpenses as $monthlyFinanceExpenses => $monthlyFinanceExpensesItem)
                                                                                <td>
                                                                                    @isset($monthlyFinanceExpensesItem[1])
                                                                                    @currency($monthlyFinanceExpensesItem[1])
                                                                                    @endisset
                                                                                    @empty($monthlyFinanceExpensesItem[1])
                                                                                        -
                                                                                    @endempty
                                                                                </td>
                                                                            @endforeach
                                                                            <?php  $monthlyFinanceExpenses = $financeExpenses['monthly']; ?>
                                                                            <td>
                                                                                {{ array_sum(array_column($monthlyFinanceExpenses, 1)) }}
                                                                            </td>
                                                                            
                                                                        </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @endforeach


                        <tr class="view">
                            <td>Company Name</td>
                            <td class="pcs">457</td>
                            <td class="pcs">457</td>
                        </tr>
                        <tr class="fold">
                            <td colspan="7">
                                <div class="fold-content">
                                    <h3>Company Name</h3>
                                    <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac
                                        turpis
                                        egestas.</p>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Company name</th>
                                                <th>Customer no</th>
                                                <th>Customer name</th>
                                                <th>Insurance no</th>
                                                <th>Strategy</th>
                                                <th>Start</th>
                                                <th>Current</th>
                                                <th>Diff</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Sony</td>
                                                <td>13245</td>
                                                <td>John Doe</td>
                                                <td>064578</td>
                                                <td>A, 100%</td>
                                                <td class="cur">20000</td>
                                                <td class="cur">33000</td>
                                                <td class="cur">13000</td>
                                            </tr>
                                            <tr>
                                                <td>Sony</td>
                                                <td>13288</td>
                                                <td>Claire Bennet</td>
                                                <td>064877</td>
                                                <td>B, 100%</td>
                                                <td class="cur">28000</td>
                                                <td class="cur">48000</td>
                                                <td class="cur">20000</td>
                                            </tr>
                                            <tr>
                                                <td>Sony</td>
                                                <td>12341</td>
                                                <td>Barry White</td>
                                                <td>064123</td>
                                                <td>A, 100%</td>
                                                <td class="cur">10000</td>
                                                <td class="cur">22000</td>
                                                <td class="cur">12000</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <br>
        <livewire:auth.logout />
    </div>
</section>


<script>

</script>