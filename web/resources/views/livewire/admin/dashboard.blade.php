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
                                {{ $activityItem['physical'] }}
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
                                                        {{ $detailsItem['total_finance'] }}
                                                    </td>
                                                    <?php $monthlyFinance = $detailsItem['monthly_finance'] ?>
                                                    @foreach ($monthlyFinance as $monthlyFinance => $monthlyFinanceItem)
                                                    <td>
                                                        {{ $monthlyFinanceItem }}
                                                    </td>
                                                    @endforeach
                                                    <td>
                                                        {{ $detailsItem['total_finance'] }}
                                                    </td>
                                                </tr>
                                                <tr class="fold">
                                                    <td colspan="7">
                                                        <div class="fold-content">
                                                            <div class="table-container" id="table-container">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Rekening</th>
                                                                            <th>Jumlah Fisik/Anggaran</th>
                                                                            <th>
                                                                                Target Realisasi
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php $expenses = $detailsItem['expenses'] ?>
                                                                        @foreach ($expenses as $expenses =>
                                                                        $expensesItem)
                                                                        <?php  $physicalExpenses = $expensesItem['physical']; ?>
                                                                        <tr class="view">
                                                                            <td>
                                                                                {{ $expensesItem['name'] }}
                                                                            </td>
                                                                            <td>
                                                                                {{ $physicalExpenses['total'] }}
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
        <div class="penta">
            <h1 class="title">
                Penta
            </h1>
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
                    @foreach ($penta as $penta => $pentaItem)
                    <?php $activities = $pentaItem['activity']; ?>
                    @foreach ($activities as $activities => $item)
                    <tr class="view">
                        <td>
                            {{ $item['activity'] }}
                        </td>
                        <td>
                            {{ $item['physical'] }}
                        </td>
                        <td>
                            {{ $item['finance'] }}
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
                                <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis
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
        <br>
        <livewire:auth.logout />
    </div>
</section>
<script>

</script>