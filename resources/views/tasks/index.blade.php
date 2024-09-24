<!DOCTYPE html>
<html>
<head>
    <title>Project Tasks</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
@include('header')
<div class="container mt-5">
    <h2>Project Details</h2>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Status</th>
            <th>Duration</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{ $project->id }}</td>
            <td>{{ $project->title }}</td>
            <td>{{ $project->description }}</td>
            <td>{{ $project->status }}</td>
            <td>{{ $project->duration }}</td>
        </tr>
        </tbody>
    </table>

    <h2>Tasks</h2>
    <div>
        <a href="/task/new/{{ $project->id }}"><b>New Task</b></a>
    </div>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Task Title</th>
            <th>Task Description</th>
            <th>Task Status</th>
            <th>Task Duration</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody>
        @foreach($tasks as $task)
            <tr>
                <td>{{ $task->id }}</td>
                <td>{{ $task->title }}</td>
                <td>{{ $task->description }}</td>
                <td>{{ $task->status }}</td>
                <td>{{ $task->duration }}</td>
                <td><a href="/task/edit/{{ $task->id }}">Edit</a></td>
                <td>
                    <form action="/task/delete/{{ $task->id }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link" style="padding:0; border:none; background:none;">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
