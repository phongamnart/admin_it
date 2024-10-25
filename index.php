<?php include('_check_session.php') ?>
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
                            <h1 class="text-center mt-4">Admin IT</h1><br>
                            <div class="d-flex justify-content-center">
                                <button class="btn btn-primary btn-custom col-2" onclick="window.location.href='extodb.php'">Convert AD to Database</button>
                                <button class="btn btn-primary btn-custom col-2 ml-4" onclick="window.location.href='data_sync.php'">DB Management: Data</button>
                                <button class="btn btn-primary btn-custom col-2 ml-4" onclick="window.location.href='css_sync.php'">DB management: CSS</button>
                                <button class="btn btn-primary btn-custom col-2 ml-4" onclick="window.location.href='css_contact.php'">DB management: CSS tb_contact</button>
                                <button class="btn btn-primary btn-custom col-2 ml-4" onclick="window.location.href='css_consult.php'">DB management: CSS tb_consult</button>
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