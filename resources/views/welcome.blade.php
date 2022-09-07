<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />


        <title>Todo</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="container">
            <div class="row">
                <div class="flex">
                    <h3 class="text-left pt-5"> Todo List</h3>
                    <a href="" class="btn btn-success btn-sm float-end" data-bs-toggle="modal" data-bs-target="#exampleModal">ADD</a>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Todo</th>
                        <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tr">
                    </tbody>
                </table>
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Todo</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="mb-3">
                                        <label for="exampleInputPassword1" class="form-label">Todo</label>
                                        <input type="text" class="form-control" name="todo" id="_todo" />
                                    </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary submit">Save changes</button>
                                    </div>
                                </form>    
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <script>
            var editID = '';
            $(document).ready(function() {
                getData();
                $(".submit").click(function(e){
                    e.preventDefault();
                    var todo = $('#_todo').val();
                    $.ajax({
                        url: 'todo',
                        type: "POST",
                        data: {
                            todo: todo,
                            id:editID,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function() {
                            $('.submit').button('loading');
                        },
                        complete: function() {
                            $('.submit').button('reset');
                            $('#exampleModal').modal('hide');
                            getData();
                            editID = '';
                            $('#_todo').val('');
                        },
                        success: function(json) {
                            console.log({json});
                            getData();
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            console.log({xhr, ajaxOptions, thrownError});
                        }
                    });
                });
 
            });
            function editData(id){
                editID =  id;
                var todo = $('#'+id).attr("data-todo");
                $('#_todo').val(todo);
                $('#exampleModal').modal('show');
            }
            function deleteData(id){
                $.ajax({
                    url: 'delete/'+id,
                    type: "GET",
                    success: function(json) {
                        getData();
                        editID = ''
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.log({xhr, ajaxOptions, thrownError});
                    }
                });
            }
            function getData(){
                    var data = '';
                    $.ajax({
                        url: 'todo',
                        type: "GET",
                        success: function(json) {
                            data = json.todos;
                            $("#tr").empty().append('');
                            data.map((val,index)=>{
                            var key = index+parseInt(1);
                            var id = val.id;
                            var todo = val.todo;
                            $("#tr").append(`
                                <tr>
                                    <td class="text-left">`+key+`</td>
                                    <td class="text-left">`+todo+`</td>
                                    <td class="text-left">
                                        <button class="btn btn-sm btn-primary" data-id="`+id+`" id="`+id+`" data-todo="`+todo+`" onclick="return editData('`+id+`')">Edit</button>
                                        <button class="btn btn-sm btn-danger" onclick="return deleteData('`+id+`')">Delete</button>
                                    </td>
                                </tr>
                            `);
                            });
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            console.log({xhr, ajaxOptions, thrownError});
                        }
                    });
                    
                
                }

        </script>
    </body>
</html>
