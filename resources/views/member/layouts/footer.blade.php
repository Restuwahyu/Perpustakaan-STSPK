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
                <form action="{{ route('logoutMember') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">Ya</button>
                </form>
            </div><!-- /.modal-footer -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Footer -->
<footer class="footer">
    2023 © Seminari Tinggi St. Paulus Kentungan
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
    $(".alert").delay(5000).fadeOut(400);

    $(document).ready(function() {
        var pemesananBuku_table = $('#pemesananBuku_table').DataTable({
            'columnDefs': [{
                'targets': 1,
                'width': '150px',
            }, {
                'targets': 2,
                'width': '150px',
            }, {
                'targets': 3,
                'width': '100px',
            }, {
                'targets': 4,
                'orderable': false,
                'width': '65px',
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
            'order': [
                [4, 'asc']
            ]
        });
    });
</script>
</body>

</html>
