<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div><!-- /.modal-header -->
            <div class="modal-body">Apakah Anda Yakin Ingin Logout?</div><!-- /.modal-body -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">Ya</button>
                </form>
            </div><!-- /.modal-footer -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Footer -->
<footer class="footer">
    <span id="year"></span> © Seminari Tinggi St. Paulus Kentungan
</footer>
<!-- End Footer -->

</div>
<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->

</div>
<!-- END wrapper -->


<!-- Apex Chart -->
<script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/apexchart/apexcharts.min.js') }}"></script>

<!-- Date Picker -->
<script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}">
</script>

<!-- Required datatable js -->
<script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/datatables/dataTables.select.min.js') }}"></script>
<script type="text/javascript"
    src="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/datatables/dataTables.checkboxes.min.js') }}"></script>

<!-- Buttons examples -->
<script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/datatables/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/datatables/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/datatables/jszip.min.js') }}"></script>
<script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/datatables/pdfmake.min.js') }}"></script>
<script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/datatables/vfs_fonts.js') }}"></script>
<script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/datatables/buttons.html5.min.js') }}"></script>
<script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/datatables/buttons.print.min.js') }}"></script>
<script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/datatables/buttons.colVis.min.js') }}"></script>

<!-- Responsive examples -->
<script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/datatables/responsive.bootstrap4.min.js') }}"></script>

<!-- DataTables init js -->
<script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/pages/datatables.init.js') }}"></script>

<!-- Bootstrap inputmask js -->
<script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js') }}"></script>

<!-- App js -->
<script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/js/app.js') }}"></script>
<!---->

<script>
    document.getElementById('year').textContent = new Date().getFullYear();

    $(".alert").delay(5000).fadeOut(400);

    $(document).ready(function() {
        $('select2').select2({
            theme: 'bootstrap4',
        });

        $('#subyek').select2();

        $('.pengarang-select').select2({
            minimumInputLength: 3,
            allowClear: true,
            placeholder: 'Pilih Pengarang',
            theme: 'bootstrap4',
            ajax: {
                dataType: 'json',
                url: '/buku/pengarang',
                delay: 5,
                cache: true,
                data: function(params) {
                    return {
                        search: params.term
                    }
                },
                processResults: function(data, page) {
                    return {
                        results: data
                    };
                },
            }
        });

        var selectedPengarangs = {!! json_encode($bukuData['pengarangs'] ?? []) !!};

        selectedPengarangs.forEach(function(pengarangData, index) {
            var pengarangId = pengarangData['pengarang']['pengarang_id'];
            var pengarangNama = pengarangData['pengarang']['pengarang_nama'];
            var kategoriNama = pengarangData['pengarang']['kategori']['kategori_nama'];

            var optionText = pengarangNama + ' - ' + kategoriNama;
            var option = new Option(optionText, pengarangId, true, true);

            $('select[name="pengarangs[' + index + '][pengarang_id]"]').append(option).trigger(
                'change');
        });
        var selectedPenerbit = {!! json_encode($bukuData['buku']['buku_penerbit'] ?? []) !!};
        var penerbitNama = {!! json_encode($bukuData['buku']['penerbit']['penerbit_nama'] ?? []) !!};

        var optionText = penerbitNama;
        var option = new Option(optionText, selectedPenerbit, true, true);

        $('.penerbit-select').append(option).trigger('change');

        $('.penerbit-select').select2({
            minimumInputLength: 3,
            allowClear: true,
            placeholder: 'Pilih Penerbit',
            theme: 'bootstrap4',
            ajax: {
                dataType: 'json',
                url: '/buku/penerbit',
                delay: 5,
                cache: true,
                data: function(params) {
                    return {
                        search: params.term
                    }
                },
                processResults: function(data, page) {
                    return {
                        results: data
                    };
                },
            }
        });

        var memberDashboard_table = $('#memberDashboard_table').DataTable({
            'columnDefs': [{
                'targets': 3,
                'orderable': false,
            }],
            'order': [
                [2, 'asc']
            ],
            'pageLength': 5,
            'lengthChange': false,
        });

        var user_table = $('#user_table').DataTable({
            'columnDefs': [{
                'targets': 0,
                'orderable': false,
                'width': '10px',
            }, {
                'targets': 6,
                'orderable': false,
                'width': '50px',
            }],
            'order': [
                [2, 'asc']
            ],
        });

        var role_table = $('#role_table').DataTable({
            'columnDefs': [{
                'targets': 0,
                'orderable': false,
                'width': '10px',
            }, {
                'targets': 2,
                'orderable': false,
                'width': '50px',
            }],
            'order': [
                [1, 'asc']
            ],
        });

        var member_table = $('#member_table').DataTable({
            'columnDefs': [{
                'targets': 0,
                'orderable': false,
                'width': '10px',
            }, {
                'targets': 8,
                'orderable': false,
                'width': '50px',
            }],
            'order': [
                [1, 'asc']
            ],
        });

        var member_exp_table = $('#member_exp_table').DataTable({
            'columnDefs': [{
                'targets': 0,
                'orderable': false,
                'width': '10px',
            }, {
                'targets': 8,
                'orderable': false,
                'width': '50px',
            }],
            'order': [
                [1, 'asc']
            ],
        });

        var kategori_table = $('#kategori_table').DataTable({
            'columnDefs': [{
                'targets': 0,
                'orderable': false,
                'width': '10px',
            }, {
                'targets': 2,
                'orderable': false,
                'width': '50px',
            }],
            'order': [
                [1, 'asc']
            ],
        });
        var peran_table = $('#peran_table').DataTable({
            'columnDefs': [{
                'targets': 0,
                'orderable': false,
                'width': '10px',
            }, {
                'targets': 2,
                'orderable': false,
                'width': '50px',
            }],
            'order': [
                [1, 'asc']
            ],
        });

        var pengarangPeran_table = $('#pengarangPeran_table').DataTable({
            'columnDefs': [{
                'targets': 0,
                'width': '70%',
            }, {
                'targets': 2,
                'orderable': false,
                'width': '100px',
            }],
        });

        var riwayatBuku_table = $('#riwayatBuku_table').DataTable({
            'columnDefs': [{
                'targets': 0,
                'width': '20px',
            }, {
                'targets': 1,
                'width': '20px',
            }, {
                'targets': 2,
                'width': '30%',
            }, {
                'targets': 5,
                'width': '30px',
            }, {
                'targets': 6,
                'orderable': false,
                'width': '100px',
            }],
        });

        var riwayatBukuMember_table = $('#riwayatBukuMember_table').DataTable({
            'columnDefs': [{
                'targets': 1,
                'width': '40%',
            }, {
                'targets': 5,
                'orderable': false,
            }],
        });

        var klasifikasi_table = $('#klasifikasi_table').DataTable({
            'columnDefs': [{
                'targets': 0,
                'orderable': false,
                'width': '10px'
            }, {
                'targets': 3,
                'orderable': false,
                'width': '50px'
            }],
            'order': [
                [1, 'asc']
            ],
        });

        var subyek_table = $('#subyek_table').DataTable({
            'columnDefs': [{
                'targets': 0,
                'orderable': false,
                'width': '10px'
            }, {
                'targets': 2,
                'orderable': false,
                'width': '50px'
            }],
            'order': [
                [1, 'asc']
            ],
        });

        var bahasa_table = $('#bahasa_table').DataTable({
            'columnDefs': [{
                'targets': 0,
                'orderable': false,
                'width': '10px'
            }, {
                'targets': 2,
                'orderable': false,
                'width': '50px'
            }],
            'order': [
                [1, 'asc']
            ],
        });

        var peminjamanRiwayat_table = $('#peminjamanRiwayat_table').DataTable({
            'columnDefs': [{
                'targets': 4,
                'orderable': false,
                'width': '10px',
            }],
            'order': [
                [0, 'asc']
            ],
        });

        var pengembalianBuku_table = $('#pengembalianBuku_table').DataTable({
            'columnDefs': [{
                'targets': 5,
                'orderable': false,
                'width': '10px',
            }],
            'order': [
                [0, 'asc']
            ],
        });

        var pengembalianBukuDashboard_table = $('#pengembalianBukuDashboard_table').DataTable({
            'columnDefs': [{
                'targets': 0,
                'width': '20%',
            }, {
                'targets': 2,
                'width': '30%',
            }, {
                'targets': 6,
                'orderable': false,
                'width': '10%',
            }],
            'order': [
                [0, 'asc']
            ],
        });

        var pemesananBuku_table = $('#pemesananBuku_table').DataTable({
            'columnDefs': [{
                'targets': 2,
                'width': '150px',
            }, {
                'targets': 3,
                'width': '150px',
            }, {
                'targets': 4,
                'width': '100px',
            }, {
                'targets': 5,
                'orderable': false,
                'width': '65px',
            }],
        });

        // Handle the "select all" checkbox
        $('#select-all').on('change', function() {
            $('.checkbox').prop('checked', $(this).prop('checked'));
        });

        // Handle individual checkboxes
        $('.checkbox').on('change', function() {
            if ($('.checkbox:checked').length === $('.checkbox').length) {
                $('#select_all').prop('checked', true);
            } else {
                $('#select_all').prop('checked', false);
            }
        });

        // Handle the "Hapus Data Terpilih" button click dynamically
        $(document).on('click', '[data-toggle="modal"][data-target="#hapusModal"]', function() {
            var selectedIds = [];
            var tableId = $(this).data('table-id');

            var table = $('#' + tableId).DataTable();

            var selectedRowsData = table.rows({
                selected: true
            }).data().toArray();

            selectedIds = selectedRowsData.map(function(rowData) {
                return rowData[0];
            });

            $('#' + tableId + ' .checkbox:checked').each(function() {
                selectedIds.push($(this).data("id"));
            });

            $('#selected-ids').val(JSON.stringify(selectedIds));

            var dataIds = $('#' + tableId + ' .checkbox:checked').map(function() {
                return $(this).data('id');
            }).get();

            console.log('Idss yang dipilih di ' + tableId + ':', dataIds);
        });

        $(document).on('click', '[data-toggle="modal"][data-target="#cetakModal"]', function() {
            var selectedIds = [];
            var tableId = $(this).data('table-id');

            var table = $('#' + tableId).DataTable();

            var selectedRowsData = table.rows({
                selected: true
            }).data().toArray();

            selectedIds = selectedRowsData.map(function(rowData) {
                return rowData[0];
            });

            $('#' + tableId + ' .checkbox:checked').each(function() {
                selectedIds.push($(this).data("id"));
            });

            $('#selected-cetak-ids').val(JSON.stringify(selectedIds));

            var dataIds = $('#' + tableId + ' .checkbox:checked').map(function() {
                return $(this).data('id');
            }).get();

            console.log('Ids yang dipilih di ' + tableId + ':', dataIds);
        });

    });

    document.addEventListener("DOMContentLoaded", function() {
        const tableCells = document.querySelectorAll("td[data-id]");
        const memberForm = document.getElementById("memberForm");
        const selectedMemberId = document.getElementById("selectedMemberId");

        tableCells.forEach(function(cell) {
            cell.addEventListener("click", function() {
                const memberId = cell.getAttribute("data-id");
                selectedMemberId.value = memberId;
                memberForm.submit();
            });
        });
    });
    // document.addEventListener("DOMContentLoaded", function() {
    //     const tableCells = document.querySelectorAll("td[data-item-id]");

    //     tableCells.forEach(function(cell) {
    //         cell.addEventListener("click", function() {
    //             const itemId = cell.getAttribute("data-item-id");
    //             console.log("item_id:", itemId);
    //         });
    //     });
    // });
</script>

</body>

</html>
