<x-app-layout>
<div class="main">

    <div class="content">
        <style>
            .total-assets { background-color: #4CAF50; }
            .in-storage { background-color: #2196F3; }
            .broken { background-color: #FF5722; }
            .written-off { background-color: #9C27B0; }
            .new-asset { background-color: #FFEB3B; }
            .old-asset { background-color: #795548; }
        </style>

        <div class="metrics">
            <a href="javascript:void(0)" class="card" onclick="showTotalAssetsTable()" background-color: #795548;>
                <p id="totalAssetsCount">{{ $totalAssets }}</p>
                <h8>Total Assets</h8>
            </a>
            <!-- <a href="javascript:void(0)" class="card" onclick="showAssetsInUseTable()">
                <p id="inUseCount">{{ $In_UseCount }}</p>
                <h8>Total assets in use</h8>
            </a> -->
            <a href="javascript:void(0)" class="card" onclick="showAssetsInStorageTable()">
                <p id="inStorageCount">{{ $In_StorageCount }}</p>
                <h8>Total assets available in storage</h8>
            </a>
            <a href="javascript:void(0)" class="card" onclick="showAssetsBrokenTable()">
                <p id="brokenCount">{{ $BrokenCount }}</p>
                <h8>Total assets broken</h8>
            </a>
            <a href="javascript:void(0)" class="card" onclick="showAssetsWrittenOffTable()">
                <p id="writtenOffCount">{{ $WrittenOffCount }}</p>
                <h8>Total assets Written Off</h8>
            </a>
            <!-- New Asset Card -->
            <a href="javascript:void(0)" class="card" onclick="showNewAssetsTable()">
                <p id="newAssetCount">{{ $NewAssetCount }}</p>
                <h8>Total New Assets</h8>
            </a>
            <!-- Old Asset Card -->
            <a href="javascript:void(0)" class="card" onclick="showOldAssetsTable()">
                <p id="oldAssetCount">{{ $OldAssetCount }}</p>
                <h8>Total Old Assets</h8>
            </a>
        </div>
        
        @if ($user?->is_admin)
            <div class="admin-section">
                <h4>Admin Section</h4>
                <p>Full access to the dashboard and management features.</p>
                <!-- Add admin-specific content here -->
            </div>
        @endif
    </div>
</div>
<br>
<br>

<table id="totalAssetsTable" class="table table-striped table-bordered" style="display: none;">
    <thead>
        <tr>
            <th>Make</th>
            <th>Model</th>
            <th>Serial Number</th>
            <th>Asset Number</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($assets as $asset)
            <tr>
                <td>{{ $asset->make }}</td>
                <td>{{ $asset->model }}</td>
                <td>{{ $asset->serial_number }}</td>
                <td>{{ $asset->asset_number }}</td>
                <td>{{ $asset->status}}</td>
                <td>
                    <a href="{{ route('assets.show', $asset->id) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i> 
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<table id="assetsInUseTable" class="table table-striped table-bordered" style="display: none;">
    <thead>
        <tr>
            <th>Make</th>
            <th>Model</th>
            <th>Serial Number</th>
            <th>Asset Number</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody id="assetsInUseTableBody">
        @forelse($assets->where('status', 'In use') as $asset)
            <tr>
                <td>{{ $asset->make }}</td>
                <td>{{ $asset->model }}</td>
                <td>{{ $asset->serial_number }}</td>
                <td>{{ $asset->asset_number }}</td>
                <td>{{ $asset->status}}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No assets in use</td>
            </tr>
        @endforelse
    </tbody>
</table>

<table id="assetsInStorageTable" class="table table-striped table-bordered" style="display: none;">
    <thead>
        <tr>
            <th>Make</th>
            <th>Model</th>
            <th>Serial Number</th>
            <th>Asset Number</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($assets as $asset)
            @if($asset->status === 'In_Storage')
                <tr>
                    <td>{{ $asset->make }}</td>
                    <td>{{ $asset->model }}</td>
                    <td>{{ $asset->serial_number }}</td>
                    <td>{{ $asset->asset_number }}</td>
                    <td>{{ $asset->status}}</td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>

<table id="newAssetsTable" class="table table-striped table-bordered" style="display: none;">
    <thead>
        <tr>
            <th>Make</th>
            <th>Model</th>
            <th>Serial Number</th>
            <th>Asset Number</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($assets->where('status', 'New') as $asset)
            <tr>
                <td>{{ $asset->make }}</td>
                <td>{{ $asset->model }}</td>
                <td>{{ $asset->serial_number }}</td>
                <td>{{ $asset->asset_number }}</td>
                <td>{{ $asset->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<table id="oldAssetsTable" class="table table-striped table-bordered" style="display: none;">
    <thead>
        <tr>
            <th>Make</th>
            <th>Model</th>
            <th>Serial Number</th>
            <th>Asset Number</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($assets->where('status', 'Old') as $asset)
            <tr>
                <td>{{ $asset->make }}</td>
                <td>{{ $asset->model }}</td>
                <td>{{ $asset->serial_number }}</td>
                <td>{{ $asset->asset_number }}</td>
                <td>{{ $asset->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<table id="assetsWrittenOffTable" class="table table-striped table-bordered" style="display: none;">
    <thead>
        <tr>
            <th>Make</th>
            <th>Model</th>
            <th>Serial Number</th>
            <th>Asset Number</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($assets->where('status', 'WrittenOff') as $asset)
            <tr>
                <td>{{ $asset->make }}</td>
                <td>{{ $asset->model }}</td>
                <td>{{ $asset->serial_number }}</td>
                <td>{{ $asset->asset_number }}</td>
                <td>{{ $asset->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>


<br><br><br><br><br>

<script>
    function showTotalAssetsTable() {
        document.getElementById('totalAssetsTable').style.display = 'table';
        document.getElementById('assetsInUseTable').style.display = 'none';
        document.getElementById('assetsInStorageTable').style.display = 'none';
        document.getElementById('newAssetsTable').style.display = 'none';
        document.getElementById('oldAssetsTable').style.display = 'none';
    }

    function showAssetsInUseTable() {
        document.getElementById('totalAssetsTable').style.display = 'none';
        document.getElementById('assetsInUseTable').style.display = 'table';
        document.getElementById('assetsInStorageTable').style.display = 'none';
        document.getElementById('newAssetsTable').style.display = 'none';
        document.getElementById('oldAssetsTable').style.display = 'none';
        document.getElementById('assetsWrittenOffTable').style.display = 'none';
    }

    function showAssetsInStorageTable() {
        document.getElementById('totalAssetsTable').style.display = 'none';
        document.getElementById('assetsInUseTable').style.display = 'none';
        document.getElementById('assetsInStorageTable').style.display = 'table';
        document.getElementById('newAssetsTable').style.display = 'none';
        document.getElementById('oldAssetsTable').style.display = 'none';
        document.getElementById('assetsWrittenOffTable').style.display = 'none';
    }

    function showAssetsBrokenTable() {
        filterAssetsInUseTable(['Broken']);
        document.getElementById('totalAssetsTable').style.display = 'none';
        document.getElementById('assetsInUseTable').style.display = 'table';
        document.getElementById('assetsInStorageTable').style.display = 'none';
        document.getElementById('newAssetsTable').style.display = 'none';
        document.getElementById('oldAssetsTable').style.display = 'none';
        document.getElementById('assetsWrittenOffTable').style.display = 'none';
    }

    function showAssetsWrittenOffTable() {
        document.getElementById('totalAssetsTable').style.display = 'none';
        document.getElementById('assetsInUseTable').style.display = 'none';
        document.getElementById('assetsInStorageTable').style.display = 'none';
        document.getElementById('newAssetsTable').style.display = 'none';
        document.getElementById('oldAssetsTable').style.display = 'none';
        document.getElementById('assetsWrittenOffTable').style.display = 'table';
    }


    function showNewAssetsTable() {
        document.getElementById('totalAssetsTable').style.display = 'none';
        document.getElementById('assetsInUseTable').style.display = 'none';
        document.getElementById('assetsInStorageTable').style.display = 'none';
        document.getElementById('newAssetsTable').style.display = 'table';
        document.getElementById('oldAssetsTable').style.display = 'none';
        document.getElementById('assetsWrittenOffTable').style.display = 'none';
    }

    function showOldAssetsTable() {
        document.getElementById('totalAssetsTable').style.display = 'none';
        document.getElementById('assetsInUseTable').style.display = 'none';
        document.getElementById('assetsInStorageTable').style.display = 'none';
        document.getElementById('newAssetsTable').style.display = 'none';
        document.getElementById('oldAssetsTable').style.display = 'table';
        document.getElementById('assetsWrittenOffTable').style.display = 'none';
    }

    function filterAssetsInUseTable(statuses) {
        const tbody = document.getElementById('assetsInUseTableBody');
        tbody.innerHTML = '';
        @foreach($assets as $asset)
            if (statuses.includes('{{ $asset->status }}')) {
                const row = `<tr>
                    <td>{{ $asset->make }}</td>
                    <td>{{ $asset->model }}</td>
                    <td>{{ $asset->serial_number }}</td>
                    <td>{{ $asset->asset_number }}</td>
                    <td>{{ $asset->status }}</td>
                </tr>`;
                tbody.insertAdjacentHTML('beforeend', row);
            }
        @endforeach
    }
</script>
</x-app-layout>
