<!DOCTYPE html>
<html>
<head>
    <title>Projects and Tasks</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
@include('header')
<div class="container mt-5">
    <h2>Projects and Tasks</h2>
    <div>
        <a href="/project/new"><b>New Project</b></a>
    </div>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Project Title</th>
            <th>Project Description</th>
            <th>Project Status</th>
            <th>Project Duration</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody>
        @foreach($projects as $project)
            <tr>
                <td><a href="/tasks/{{ $project->id }}">{{ $project->id }}</a></td>
                <td><a href="/tasks/{{ $project->id }}">{{ $project->title }}</a></td>
                <td>{{ $project->description }}</td>
                <td>{{ $project->status }}</td>
                <td>{{ $project->duration }}</td>
                <td><a href="/project/edit/{{ $project->id }}">Edit</a></td>
                <td>
                    <form action="/project/delete/{{ $project->id }}" method="POST" style="display:inline;">
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
