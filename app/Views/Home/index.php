<!DOCTYPE html>
<html>
<head>
    <title>Friends List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/ladda-themeless.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/libs/toastr/build/toastr.min.css">
    <style>
        .error{
            margin-left: 5px;
            color: #cc5965;
            font-size: 13px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="card">
        <div class="card-body">
        <h5 class="card-title">Input Data Teman</h5>
        <form method="post" action="">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" class="form-control" id="name" name="name">
        </div>
        <div class="form-group">
            <label for="gender">Jenis Kelamin</label>
            <select class="form-control" id="gender" name="gender">
                <?php foreach($groupGender as $listGender):?>
                <option value="<?=$listGender->id?>"><?=$listGender->name?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="form-group">
            <label for="age">Usia</label>
            <input type="number" class="form-control" id="age" name="age">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
        <h5 class="card-title">Data Teman</h5>
            <button class="btn btn-primary mt-3 mb-3" onclick="exportToPdf()">Export to PDF</button>
        <div class="row">
                <div class="col-md-12">
                        <table id="friendsTable" class="table table-striped table-bordered mt-2">
                        <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Usia</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($friends as $friend): ?>
                            <?php $param = "'". $friend->idfriend . "', '" . $friend->fullname . "', '" . $friend->jenisKelamin . "', '". $friend->age . "'"; ?>
                            <tr>
                                <td><?= $friend->fullname ?></td>
                                <td><?= $friend->jenisKelamin ?></td>
                                <td><?= $friend->age ?></td>
                                <td>
                                <button class="btn btn-info" onclick="editFriend(<?=$param?>)">Edit</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4">
                <canvas id="genderChart" width="255" height="255"></canvas>
                </div>
                <div class="cold-md-4">
                <canvas id="ageChart" width="255" height="255"></canvas>
                </div>
        </div>
        </div>
    </div>
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" id="editForm" action="/postEdit">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <input type="hidden" value="" id="idTeman" name="idFriend">
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="gender">Jenis Kelamin</label>
                        <select class="form-control" id="gender" name="gender">
                            <?php foreach($groupGender as $listGender):?>
                            <option value="<?=$listGender->id?>"><?=$listGender->name?></option>
                            <?php endforeach?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="age">Usia</label>
                        <input type="number" class="form-control" id="age" name="age">
                    </div>
               
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary ladda-button ladda-button-submit" data-style="slide-up">Submit</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/spin.min.js"></script>
<script src="/libs/toastr/build/toastr.min.js"></script>
<script src="/js/toastr/toastr.init.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/ladda.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
<script src="https://unpkg.com/jspdf-autotable@3.5.28/dist/jspdf.plugin.autotable.js"></script>
<script>
    window.jsPDF = window.jspdf.jsPDF;
    var genderCtx = document.getElementById('genderChart').getContext('2d');
    var ageCtx = document.getElementById('ageChart').getContext('2d');

    var totalGender = <?= $male_count ?> + <?= $female_count ?>;
    var totalAge = <?= $below_19_count ?> + <?= $above_20_count ?>;
    var preMale = (<?= $male_count ?>/totalGender*100).toFixed(2);
    var preFem = (<?= $female_count ?>/totalGender*100).toFixed(2);
    var preBelow19 = (<?= $below_19_count ?>/totalAge*100).toFixed(2);
    var preAbove20 = (<?= $above_20_count ?>/totalAge*100).toFixed(2);
    function exportToPdf() {
        var doc = new jsPDF();
        var tableData = [];
        doc.setFont('helvetica');
        doc.setFontSize(20);
        doc.text(75, 10, 'Ringkasan Laporan');
        $('#friendsTable tr').each(function (rowIndex, row) {
            var rowData = [];
            $(row).find('td:not(:last-child)').each(function (colIndex, cell) {
                rowData.push($(cell).text());
            });
            tableData.push(rowData);
        });
        tableData.shift();
        doc.autoTable({
            head: [$('#friendsTable th:not(:last-child)').map(function () { return $(this).text(); }).get()],
            body: tableData
        });
        doc.setFontSize(16);
        doc.text(10, 150, 'Statistik Data');
        doc.setFontSize(10);
        var preTextGen = 'Presentase Jenis Kelamin Laki-laki: ' + preMale + '% | Perempuan : ' + preFem + '%';
        var preTextAge = 'Presentase 19 tahun kebawah : ' + preBelow19 + '% | 20 tahun keatas : ' + preAbove20 + '%';
        doc.text(10, 160, preTextGen)
        doc.text(10, 170, preTextAge)
        var genderData = genderChart.toBase64Image();
        var ageData = ageChart.toBase64Image();
        var chartWidth = 100;
        var chartHeight = (100 * genderChart.canvas.height) / genderChart.canvas.width;

        doc.addImage(genderData, 'PNG', 10, 180, chartWidth, chartHeight);
        doc.addImage(ageData, 'PNG', 100, 190, chartWidth, chartHeight);

        doc.save('talenta.pdf');
    }
    function editFriend(id, name, jenisKelamin, usia) {
        $("#idTeman").val(id);
        $("#editForm #name").val(name);
        $("#editForm #age").val(usia);
        $('#editModal').modal('show');
    }

    $('#editForm').validate({
        rules: {
            name: {
                required: true,
                maxlength: 50
            },
            gender: {
                required: true
            },
            age: {
                required: true,
                number: true
            }
        },
        messages: {
            name: {
                required: "Nama harus diisi.",
                maxlength: "Nama tidak boleh lebih dari 50 karakter."
            },
            gender: {
                required: "Jenis kelamin harus diisi."
            },
            age: {
                required: "Usia harus diisi.",
                number: "Usia harus berupa angka."
            }
        },
        submitHandler: function (form) {
            var l = Ladda.create(document.querySelector('.ladda-button'));
            l.start();
            $.ajax({
                type: 'post',
                url: '<?=base_url('postEdit')?>',
                data: $(form).serialize(),
                success: function (data) {
                    var parseData = JSON.parse(data);
                    console.log(parseData);
                    if(parseData['status'] == 200){
                       l.stop();
                       $('#editModal').modal('hide');
                       toastr.success('Success update data.', '', { timeOut: 1000 });
                        setTimeout(function() {
                        window.location.reload();
                        }, 1000);
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
            return false;
        }
    });

// chart
    var genderData = {
        labels: ['Male', 'Female'],
        datasets: [{
            label: 'Presentase',
            data: [preMale, preFem],
            backgroundColor: [
                'rgba(75, 192, 192, 0.2)',
                'rgba(255, 99, 132, 0.2)'
            ],
            borderColor: [
                'rgba(75, 192, 192, 1)',
                'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 1
        }]
    };
    var ageData = {
        labels: ['19 tahun kebawah', '20 tahun kebawah'],
        datasets: [{
            label: 'Presentase',
            data: [preBelow19, preAbove20],
            backgroundColor: [
                'rgba(255, 206, 86, 0.2)',
                'rgba(153, 102, 255, 0.2)'
            ],
            borderColor: [
                'rgba(255, 206, 86, 1)',
                'rgba(153, 102, 255, 1)'
            ],
            borderWidth: 1
        }]
    };

    var genderChart = new Chart(genderCtx, {
        type: 'pie',
        data: genderData,
    });

    var ageChart = new Chart(ageCtx, {
        type: 'pie',
        data: ageData
    });
</script>
<?php if(session()->getFlashdata('validation')):?>
        <script>
            toastr.error('<?= session()->getFlashData("validation"); ?>', '', {
                "timeOut": "5000",
                "escapeHtml": false,
                "closeButton": true,
                "positionClass": "toast-top-right"
            });
        </script>
    <?php endif?>
</body>
</html>