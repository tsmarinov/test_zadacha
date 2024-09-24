@php use App\Models\Project; @endphp
    <!-- resources/views/projects/edit.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Edit Project</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
@include('header')
<div class="container mt-5">
    <h2>Edit Project</h2>

    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('project/edit/' . $project->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="title">Project Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ $project->title }}">
        </div>
        <div class="form-group">
            <label for="description">Project Description</label>
            <textarea class="form-control" id="description" name="description">{{ $project->description }}</textarea>
        </div>
        <div class="form-group">
            <label for="status">Project Status</label>
            <select class="form-control" id="status" name="status">
                @foreach([
                    Project::STATUS_PENDING => 'Pending',
                    Project::STATUS_IN_PROGRESS => 'In Progress',
                    Project::STATUS_COMPLETED => 'Completed'
                ] as $value => $label)
                    <option
                        value="{{ $value }}" {{ $project->status == $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>
</body>
</html>
