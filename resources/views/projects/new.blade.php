@php use App\Models\Project; @endphp
    <!-- resources/views/projects/new.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>New Project</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
@include('header')
<div class="container mt-5">
    <h2>New Project</h2>

    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('project/new') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="title">Project Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}">
        </div>
        <div class="form-group">
            <label for="description">Project Description</label>
            <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
        </div>
        <div class="form-group">
            <label for="status">Project Status</label>
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
        <button type="submit" class="btn btn-primary">Create Project</button>
    </form>
</div>
</body>
</html>
