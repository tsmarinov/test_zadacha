@php use App\Models\Project; @endphp
<!DOCTYPE html>
<html>
<head>
    <title>New Task</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
@include('header')
<div class="container mt-5">
    <h2>New Task</h2>

    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('task/new') }}" method="POST">
        @csrf
        <input type="hidden" name="project_id" value="{{ $projectId }}">
        <div class="form-group">
            <label for="title">Task Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}">
        </div>
        <div class="form-group">
            <label for="description">Task Description</label>
            <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
        </div>
        <div class="form-group">
            <label for="status">Task Status</label>
            <select class="form-control" id="status" name="status">
                @foreach([
                    Project::STATUS_PENDING => 'Pending',
                    Project::STATUS_IN_PROGRESS => 'In Progress',
                    Project::STATUS_COMPLETED => 'Completed'
                ] as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="duration">Task Duration (minutes)</label>
            <input type="number" class="form-control" id="duration" name="duration" value="{{ old('duration') }}">
        </div>
        <button type="submit" class="btn btn-primary">Create Task</button>
    </form>
</div>
</body>
</html>
