<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sync SHE Daily to Employees</title>
    <?php include('_head.php') ?>
</head>

<body>
    <?php include('_navbar.php') ?>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card mx-3 my-3">
                        <div class="card-body">
                            <h1 class="text-center mt-4">Sync SHE Daily to Employees</h1><br>
                            <div class="d-flex flex-column align-items-center">
                                <button class="btn btn-primary btn-custom" id="syncData">Sync Data Now</button>
                                <div id="status" class="mt-2" style="font-size: 24px;"></div>
                                <!-- <pre id="responseOutput"></pre> -->
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
    </section>

    <?php include('_script.php') ?>

    <script>
        $(document).ready(function() {
            function syncData() {
                $('#status').text('Syncing data...');
                $('#responseOutput').text('');

                $.ajax({
                    url: 'daily_emp.php',
                    method: 'POST',
                    success: function(response) {
                        $('#status').text('Data sync completed successfully!');
                        $('#responseOutput').text(response);
                    },
                    error: function(xhr, status, error) {
                        $('#status').text('Error occurred while syncing data.');
                        $('#responseOutput').text('Error: ' + error);
                    }
                });
            }

            $('#syncData').on('click', function() {
                syncData();
            });

            setInterval(function() {
                console.log('Auto sync triggered');
                syncData();
            }, 43200000);
        });
    </script>


</body>

</html>