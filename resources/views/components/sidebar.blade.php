<!-- sidebar -->
<nav class="sidebar">
    <div class="menu_content">
        <ul class="menu_items">
            <div class="menu_title menu_dashboard">
                <a href="{{ route('dashboard') }}" class="nav_link sublink {{ Request::routeIs('dashboard') ? 'active' : '' }}" style="text-decoration: none;">
                    <span class="material-symbols-outlined"></span>
                    <i class="bx bx-dashboard"></i>
                    Dashboard
                </a>
            </div>

            <!-- Inventory Section -->
            <li class="item">
                <div class="nav_link submenu_item {{ Request::is('asset_categories*') || Request::is('assets*') || Request::is('asset_assignments*') ? 'active' : '' }}">
                    <span class="navlink_icon">
                        <i class="bx bx-package"></i> <!-- Inventory icon -->
                    </span>
                    <span class="navlink">Inventory</span>
                    <i class="bx bx-chevron-right arrow-left"></i>
                </div>
                <ul class="menu_items submenu">
                    @can('access', [App\Models\AssetCategory::class])
                    <li>
                        <a href="{{ route('asset_categories.index') }}" class="nav_link sublink {{ Request::routeIs('asset_categories.index') ? 'active' : '' }}">
                            <span class="navlink_icon"><i class="bx bx-category"></i></span> <!-- Asset Category icon -->
                            Category
                        </a>
                    </li>
                    @endcan

                    @can('access', [App\Models\Asset::class])
                    <li>
                        <a href="{{ route('assets.index') }}" class="nav_link sublink {{ Request::routeIs('assets.index') ? 'active' : '' }}">
                            <span class="navlink_icon"><i class="bx bx-cube"></i></span> <!-- Assets icon -->
                            Assets
                        </a>
                    </li>
                    @endcan
                    
                    

                    @can('access', [App\Models\AssetHistory::class])
                    <li>
                        <a href="{{ route('asset-assignments.index') }}" class="nav_link sublink {{ Request::routeIs('asset-assignments.index') ? 'active' : '' }}">
                            <span class="navlink_icon"><i class="bx bx-list-ul"></i></span> <!-- Asset Summary icon -->
                            Asset Summary
                        </a>
                    </li>
                    @endcan 
                </ul>
            </li>

            <!-- Overview Section -->
            
            <li class="item">
                <div class="nav_link submenu_item {{ Request::is('departments*') || Request::is('regions*') || Request::is('locations*') || Request::is('vendors*') || Request::is('insurances*') ||Request::is('invoices*') ? 'active' : '' }}">
                    <span class="navlink_icon">
                        <i class="bx bx-grid-alt"></i> <!-- Overview icon -->
                    </span>
                    <span class="navlink">Overview</span>
                    <i class="bx bx-chevron-right arrow-left"></i>
                </div>
                <ul class="menu_items submenu">

                    @can('access', App\Models\Department::class)
                    <li>
                        <a href="{{ route('departments.index') }}" class="nav_link sublink {{ Request::routeIs('departments.index') ? 'active' : '' }}">
                            <span class="navlink_icon"><i class="bx bx-building"></i></span> <!-- Department icon -->
                            Department
                        </a>
                    </li>
                    @endcan
                    <!-- @can('access', App\Models\Department::class) -->
                    <li>
                        <a href="{{ route('facilities.index') }}" class="nav_link sublink {{ Request::routeIs('facilities.index') ? 'active' : '' }}">
                            <span class="navlink_icon"><i class="bx bx-building"></i></span> <!-- Department icon -->
                            Facility Space
                        </a>
                    </li>
                    <!-- @endcan -->
                    @can('access', App\Models\Region::class)
                    <li>
                        <a href="{{ route('regions.index') }}" class="nav_link sublink {{ Request::routeIs('regions.index') ? 'active' : '' }}">
                            <span class="navlink_icon"><i class="bx bx-world"></i></span> 
                            Regions
                        </a>
                    </li>
                    @endcan
                    <li>
                        <a href="{{ route('audit-trails.index') }}" class="nav_link sublink {{ Request::routeIs('audit-trails.index') ? 'active' : '' }}">
                            <span class="navlink_icon"><i class="bx bx-world"></i></span> 
                            View Audit Trails
                        </a>
                    </li>
              
                    @can('access', App\Models\Location::class)
                    <li>
                        <a href="{{ route('locations.index') }}" class="nav_link sublink {{ Request::routeIs('locations.index') ? 'active' : '' }}">
                            <span class="navlink_icon"><i class="bx bx-map"></i></span> <!- Location icon -->
                            Location
                        </a>
                    </li>
                    @endcan  
                    @can('access', App\Models\Vendor::class)
                    <li>
                        <a href="{{ route('vendors.index') }}" class="nav_link sublink {{ Request::routeIs('vendors.index') ? 'active' : '' }}">
                            <span class="navlink_icon"><i class="bx bx-store"></i></span>
                            Vendor
                        </a>
                    </li>
                    @endcan 
                    <!-- @can('access', App\Models\Invoice::class)
                    <li>
                        <a href="{{ route('invoices.index') }}" class="nav_link sublink {{ Request::routeIs('invoices.index') ? 'active' : '' }}">
                            <span class="navlink_icon"><i class="bx bx-file"></i></span>
                            Invoice
                        </a>
                    </li>
                    @endcan -->
                    @can('access', App\Models\Insurance::class)
                    <li>
                        <a href="{{ route('insurances.index') }}" class="nav_link sublink {{ Request::routeIs('insurances.index') ? 'active' : '' }}">
                            <span class="navlink_icon"><i class="bx bx-shield"></i></span> <!-- Insurance Claim icon -->
                            Insurance Claim
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>

            <ul class="menu_items">
                <!-- Editors Section -->
                
                <li class="item">
                    <div class="nav_link submenu_item {{ Request::is('reports*') ? 'active' : '' }}">
                        <span class="navlink_icon">
                            <i class="bx bxs-report"></i> <!-- Reports icon -->
                        </span>
                        <span class="navlink">Reports</span>
                        <i class="bx bx-chevron-right arrow-left"></i>
                    </div>
                    <ul class="menu_items submenu">
                        <li>
                            <a href="{{ route('reports.index') }}" class="nav_link sublink {{ Request::routeIs('reports.index') ? 'active' : '' }}">
                                <span class="navlink_icon"><i class="bx bxs-bar-chart"></i></span> <!-- Asset Reports icon -->
                                Asset Reports
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('insurance.report') }}" class="nav_link sublink {{ Request::routeIs('insurance.report') ? 'active' : '' }}">
                                <span class="navlink_icon"><i class="bx bxs-bar-chart"></i></span> <!-- Asset Reports icon -->
                                Insurance Reports
                            </a>
                        </li>
                    </ul>
                </li>
               
            </ul>

            <ul class="menu_items">
                <div class="menu_title menu_settings"></div>
                @can('access', App\Models\Role::class)
                <li class="item">
                    <a href="{{ route('roles_permissions.index') }}" class="nav_link {{ Request::routeIs('roles_permissions.index') ? 'active' : '' }}">
                        <span class="navlink_icon">
                            <i class="bx bx-shield"></i> <!-- Roles and Permissions icon -->
                        </span>
                        <span class="navlink">Roles and Permissions</span>
                    </a>
                </li>
                @endcan
                @can('access', App\Models\User::class)
                <li class="item">
                    <a href="{{ route('users.index') }}" class="nav_link {{ Request::routeIs('users.index') ? 'active' : '' }}">
                        <span class="navlink_icon">
                            <i class="bx bx-users"></i> <!-- User Management icon -->
                        </span>
                        <span class="navlink">User Management</span>
                    </a>
                </li>
                @endcan
                
                <li class="item">
                    <a href="{{ route('settings.index') }}" class="nav_link {{ Request::routeIs('settings.index') ? 'active' : '' }}">
                        <span class="navlink_icon">
                            <i class="bx bx-cog"></i> <!-- Settings icon -->
                        </span>
                        <span class="navlink">Settings</span>
                    </a>
                </li>
                
            </ul>
            

            <!-- Sidebar Open / Close -->
            <div class="bottom_content">
                <div class="bottom expand_sidebar">
                    <span> Expand</span>
                    <i class='bx bx-log-in'></i>
                </div>
                <div class="bottom collapse_sidebar">
                    <span> Collapse</span>
                    <i class='bx bx-log-out'></i>
                </div>
            </div>
        </div>
    </div>
</nav>
