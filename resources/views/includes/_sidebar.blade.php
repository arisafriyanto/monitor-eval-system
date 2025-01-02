<aside id="sidebar" class="js-sidebar">
    <div class="h-100">
        <div class="sidebar-logo">
            <a href="#">MonitorEval System</a>
        </div>
        <ul class="sidebar-nav">
            <li class="sidebar-header">
                <h6>Menu</h6>
            </li>

            @if (auth()->user()->role === 'admin')
                <li class="sidebar-item">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
                        <i class="fa-solid fa-list pe-2"></i>
                        Dashboard
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('admin.reports.index') }}" class="sidebar-link">
                        <i class="fa-solid fa-file-lines pe-2"></i>
                        Laporan
                    </a>
                </li>
            @else
                <li class="sidebar-item">
                    <a href="{{ route('reports.index') }}" class="sidebar-link">
                        <i class="fa-solid fa-file-lines pe-2"></i>
                        Laporan
                    </a>
                </li>
            @endif
        </ul>
    </div>
</aside>
