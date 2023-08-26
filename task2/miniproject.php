<!DOCTYPE html>
<html>
<head>
    <title>PDF Upload</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add these lines to the <head> section of your HTML -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">File Upload</h2>
        <div class="statusMsg"></div>
        <div class="card">
            <div class="card-body">
                <form id="fupForm" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="pdfFile" class="form-label">Select a PDF file to upload:</label>
                        <input type="file" id="file" name="file" required  class="form-control" accept=".pdf" >
                    </div>
                    <button type="button" id="submitFile" class="btn btn-primary" name="submit">Upload PDF</button>
                </form>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="input-group">
                    <input type="text" id="searchFile" class="form-control" placeholder="Search...">
                    <div class="input-group-append">
                        <button class="btn btn-primary" id="searchBtn" type="button">Search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3" id="tbl">
            
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).on('click','#submitFile',function(e){
            e.preventDefault();
                
            var file = $('#file').prop("files")[0];
            var form = new FormData();
            form.append("pdfFile", file);
            $.ajax({
                url: 'upload.php', // Your PHP processing script
                type: 'POST',
                data: form,
                // dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(res){
                    var responseObject = JSON.parse(res)
                    $("#file").val(null);
                    $('.statusMsg').html('');
                    if(responseObject.status == 1){
                        $('#fupForm')[0].reset();
                        $('.statusMsg').html(
                            `
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                ${responseObject.message}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            `
                        );
                    }else{
                        $('.statusMsg').html(
                            `
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                ${responseObject.message}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            `
                        );
                    }
                }
            });
        });
        /** Search operation */
        $(document).on('keyup','#searchFile',function(e){
            e.preventDefault();
            var fileData = $(this).val();
            searchContent(fileData);
        });
        $(document).on('click','#searchBtn',function(e){
            e.preventDefault();
            var fileData = $('#searchFile').val();
            searchContent(fileData);
        });
        function searchContent(data){
            if (data !== '') {
                $.ajax({
                    url: 'search.php', // Your PHP processing script
                    type: 'POST',
                    data: {data: data},
                    success: function(res){
                        var resObject = JSON.parse(res)
                        if(resObject.status == 1){
                            $('#tbl').html(resObject.data);
                        } else {
                            $('#tbl').html(
                                `
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    ${resObject.message}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                `
                            );
                        }
                    }
                });
            } else {
                $('#tbl').html('');
            }
        }

    </script>

</body>
</html>
