<?php $current_page = "Convert AD to SQL"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                            <h1 class="text-center">Upload CSV File</h1>
                            <div class="form-container my-5">
                                <form action="services/upload.php" method="post" enctype="multipart/form-data">
                                    <label class="label-custom">Select CSV file to upload:</label>
                                    <input type="file" name="csv_file" accept=".csv" required>
                                    <input type="submit" value="Upload CSV" class="btn-custom btn btn-success">
                                </form>
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
</body>

</html>