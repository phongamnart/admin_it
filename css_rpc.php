<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sync Sharepoint to Database: CSS</title>
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
                            <h1 class="text-center mt-4">Sync Sharepoint to Database: CSS RPC</h1><br>
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
            // ฟังก์ชันสำหรับการซิงค์ข้อมูล
            function syncData() {
                $('#status').text('Syncing data...');
                // $('#responseOutput').text('');

                $.ajax({
                    url: 'https://prod-46.southeastasia.logic.azure.com:443/workflows/2a5e4224e3e443ea980602c45498a293/triggers/manual/paths/invoke?api-version=2016-06-01&sp=%2Ftriggers%2Fmanual%2Frun&sv=1.0&sig=cQHO2Ss7Pwe4PuJQ5EMHf4gYVwnEo1jhEPh0c7y-kbw',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        key1: 'value1',
                        key2: 'value2'
                    }),
                    success: function(response) {
                        $('#status').text('Data sync completed successfully!');
                        // $('#responseOutput').text(JSON.stringify(response, null, 2));

                        $.ajax({
                            url: 'https://app.italthaiengineering.com/admin_it/css_rpc_json.php',
                            method: 'POST',
                            contentType: 'application/json',
                            data: JSON.stringify(response),
                            success: function(data) {
                                console.log('Data sync successfully:', data);
                            },
                            error: function(xhr, status, error) {
                                console.error('Error syncing data:', error);
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        $('#status').text('Error: ' + error);
                        // $('#responseOutput').text(xhr.responseText);
                    }
                });
            }

            // เมื่อกดปุ่มจะเรียกฟังก์ชัน syncData
            $('#syncData').on('click', function() {
                syncData();
            });

            // เรียก syncData อัตโนมัติทุก 1 ชั่วโมง (3600000 มิลลิวินาที = 1 ชั่วโมง)
            setInterval(function() {
                syncData();
            }, 3600000); // 1 ชั่วโมง = 3600000 milliseconds
        });
    </script>

</body>

</html>