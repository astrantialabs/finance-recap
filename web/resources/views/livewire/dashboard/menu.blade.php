<aside class="dashboard-menu menu">
    <div class="logotype">
        <i class="logotype-logo fas fa-abacus"></i>
        <h1 class="logotype-text title">
            Finance Recap
        </h1>
    </div>
    <div class="menu-list">
        <ul>
            <li class="menu-item-container menu-item-dashboard">
                <a class="menu-item @if ($isActive == 'dashboard') menu-item-active @endif" href="/">Dashboard</a>
            </li>
            <li class="menu-item-activity">
                Activity
            </li>
            <ul>
                <li class="menu-item-container">
                    <a class="menu-item @if ($isActive == 'sekretariat') menu-item-active @endif" href="/sekretariat">Sekretariat</a>
                </li>
                <li class="menu-item-container">
                    <a class="menu-item @if ($isActive == 'penta') menu-item-active @endif" href="/penta">Penta</a>
                </li>
                <li class="menu-item-container">
                    <a class="menu-item @if ($isActive == 'lattas') menu-item-active @endif" href="/lattas">Lattas</a>
                </li>
                <li class="menu-item-container">
                    <a class="menu-item @if ($isActive == 'hi') menu-item-active @endif" href="/hi">Hubungan Industrial</a>
                </li>
            </ul>
        </ul>
    </div>
    {{-- <ul class="menu-list">
        <li>
            <a>Dashboard</a>
        </li>
        <li>
            <a>active</a>
            <ul>
                <li>
                    <a href="/sekretariat">Sekretariat</a>
                    <a href="/penta">Penta</a>
                    <a href="/lattas">Lattas</a>
                    <a href="/hi">Hubungan Industrial</a>
                </li>
            </ul>
        </li>
    </ul> --}}
</aside>