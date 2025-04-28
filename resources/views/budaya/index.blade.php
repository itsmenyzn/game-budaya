@include('layout.header')
@include('layout.nav')

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Ragam Budaya</h1>
        <a href="{{ route('budaya.create') }}" class="btn btn-primary">Tambah Data</a>
    </div>
    
    <!-- Filter Dropdown -->
    <div class="mb-3 w-25">
        <label for="filterJenis" class="form-label">Filter Jenis Budaya:</label>
        <select id="filterJenis" class="form-control">
            <option value="">Semua</option>
            <option value="Rumah Adat">Rumah Adat</option>
            <option value="Tarian Adat">Tarian Adat</option>
            <option value="Pakaian Adat">Pakaian Adat</option>
            <option value="Alat Musik Tradisional">Alat Musik Tradisional</option>
        </select>
    </div>
    
    <div class="row d-flex rounded">
        @if (Session::has('success'))
            <div class="col-md-10 mt-4">
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            </div>    
        @endif            
        <div class="col-lg-12">
            <div class="card border-0 shadow-lg">
                <div class="card-body">
                    <table id="budayaTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th >No.</th>
                                <th>Nama</th>
                                <th>Jenis</th>
                                <th>File</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($data->isNotEmpty())
                                <?php $count = 1; ?>
                                @foreach ($data as $budaya)
                                    <tr>
                                        <td class="text-center">{{ $count++ }}</td>
                                        <td>{{ $budaya->nama_budaya }}</td>
                                        <td>
                                            @if ($budaya->jenis_budaya == 'rumah') Rumah Adat
                                                @elseif ($budaya->jenis_budaya == 'tari') Tarian Adat
                                                @elseif ($budaya->jenis_budaya == 'pakaian') Pakaian Adat
                                                @elseif ($budaya->jenis_budaya == 'alat_musik') Alat Musik Tradisional
                                            @endif
                                        </td>
                                        <td>
                                            @if ($budaya->attachment != "")
                                                @if ($budaya->tipe_budaya == 'audio')
                                                    <audio controls>
                                                        <source src="{{ asset('uploads/budaya/'.$budaya->attachment) }}" type="audio/mpeg">
                                                        Your browser does not support the audio element.
                                                    </audio>
                                                @else
                                                    <img width="150" height="150" src="{{ asset('uploads/budaya/'.$budaya->attachment) }}" alt="">
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('budaya.edit',$budaya->id_budaya) }}" class="btn btn-dark">Edit</a>
                                            <button class="btn btn-danger delete-btn" 
                                                    data-id="{{ $budaya->id_budaya }}"
                                                    data-toggle="modal" 
                                                    data-target="#deleteModal">Delete</button>
                                            <form id="delete-budaya-from-{{ $budaya->id_budaya }}" action="{{ route('budaya.destroy',$budaya->id_budaya) }}" method="post">
                                                @csrf
                                                @method('delete')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center">No data to be shown</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah anda yakin akan menghapus data ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let table = $('#budayaTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "pageLength": 5,
            "lengthMenu": [5, 10, 20],
            "language": {
                "lengthMenu": "Total data yang ditampilkan _MENU_",
                "info": "Menampilkan _START_ hingga _END_ dari _TOTAL_ data"
            }
        });

        $('#filterJenis').on('change', function() {
            let jenis = $(this).val();
            table.column(2).search(jenis).draw();
        });

        let deleteId;
        
        // Tangkap event klik tombol delete
        $('.delete-btn').click(function() {
            deleteId = $(this).data('id');
        });

        // Konfirmasi delete
        $('#confirmDelete').click(function() {
            $('#delete-budaya-from-' + deleteId).submit();
            $('#deleteModal').modal('hide');
        });
    });
</script>

@include('layout.footer')