<x-app-layout>
    <div class="container">
        <h4>Settings</h4>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('settings.update') }}">
            @csrf

            <div class="form-group">
                <label for="maintenance_mode">Maintenance Mode:</label>
                <select name="maintenance_mode" id="maintenance_mode" class="form-control">
                    <option value="1" {{ $settings['maintenance_mode'] ? 'selected' : '' }}>Enabled</option>
                    <option value="0" {{ !$settings['maintenance_mode'] ? 'selected' : '' }}>Disabled</option>
                </select>
            </div>

            <div class="form-group">
                <label for="system_version">System Version:</label>
                <input type="text" name="system_version" id="system_version" class="form-control" value="{{ $settings['system_version'] }}">
            </div>

            <!-- Add other settings fields as needed -->

            <button type="submit" class="btn btn-primary">Update System</button>
        </form>

        <!-- <h3>Administrative Tasks</h3>
        <form method="POST" action="{{ route('settings.performTask') }}">
            @csrf

            <button type="submit" name="clear_cache" class="btn btn-warning">Clear Cache</button>
            <button type="submit" name="update_system" class="btn btn-danger">Update System</button>
        </form> -->
    </div>

    <style>
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .btn {
            margin-right: 10px;
        }
    </style>
</x-app-layout>
