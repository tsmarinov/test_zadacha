#### Create a simple task management.

- You have to create REST API for creating projects and tasks. 
- Each project and task have to have title, description, status and duration. 
- Each project can have many tasks
- The api always have to return HTTP status 200 and return errors and success in your json response.

For example:<br>
```json
{
code: 0 //success, -1 error
data: … //data to be return
validation_errors : [] // if any
}
```

- All of the fields are required.
- The project duration and status are dependent on each task of the project status and duration.
- Authenticated clients have to be able to create, list update and delete projects and tasks.
- Make soft deletion for project and tasks. 
- Create web part that consumes your API CRUD abilities using php only.
- You may use any php framework (Symfony, Laravel etc.).
- Create a script that populates your database (fixtures etc.).
