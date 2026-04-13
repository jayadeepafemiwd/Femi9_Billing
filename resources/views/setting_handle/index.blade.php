<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setting Handle - JSON Column</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between">
                <h4>JSON Column Configurations</h4>
                <a href="{{ route('setting_handle.create') }}" class="btn btn-light">+ New</a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Id</th>
                            <th>Process Name</th>
                            <th>Configuration (JSON)</th>
                            <th>Created-at</th>
                            <th>Deleted-at</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($settings as $setting)
                        <tr>
                            <td>{{ $setting->id }}</td>
                            <td>{{ $setting->process }}</td>
                            <td>
                                @if($setting->config)
                                    <pre class="mb-0 bg-light p-2 rounded" style="max-height: 100px; overflow: auto;">{{ json_encode($setting->Config, JSON_PRETTY_PRINT) }}</pre>
                                @else
                                    <span class="text-muted">No config</span>
                                @endif
                            </td>
                            <td>{{ $setting->created_at ? $setting->created_at->format('Y-m-d H:i:s') : '' }}</td>
                            <td>{{ $setting->deleted_at ? $setting->deleted_at->format('Y-m-d H:i:s') : '' }}</td>
                            <td>
                                <a href="{{ route('setting_handle.edit', $setting->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('setting_handle.destroy', $setting->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $settings->links() }}
            </div>
        </div>
    </div>
</body>
</html>