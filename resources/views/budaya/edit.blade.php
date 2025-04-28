@include('layout.header')

@include('layout.nav')
<style>
    .text-area-unchanged{
        resize: none;
    }
</style>
            <!-- Begin Page Content -->
            <div class="container-fluid">   
                <!-- Page Heading -->
                <h1 class="h3 mb-4 text-gray-800">Edit Data Budaya</h1>

                <div class="card shadow my-3 col-lg-9">
                    <div class="card-body w-100">
                        <form enctype="multipart/form-data" action="{{ route('budaya.update',$budaya->id_budaya) }}" method="post">
                            @method('put')
                            @csrf
                            <div class="form-group w-75">
                                <label for="">Nama Budaya</label>
                                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{old("nama",$budaya->nama_budaya)}}" placeholder="Nama Budaya...">
                                @error('nama')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group w-75">
                                <label for="">Jenis Budaya</label>
                                <select class="form-control select2 @error('nama') is-invalid @enderror" name="jenis">
                                    <option disabled {{ !$budaya->jenis_budaya ? 'selected' : '' }}>Pilih Jenis Budaya</option>
                                    <option value="rumah" {{ $budaya->jenis_budaya == 'rumah' ? 'selected' : '' }}>Rumah Adat</option>
                                    <option value="pakaian" {{ $budaya->jenis_budaya == 'pakaian' ? 'selected' : '' }}>Pakaian Adat</option>
                                    <option value="tari" {{ $budaya->jenis_budaya == 'tari' ? 'selected' : '' }}>Tari Adat</option>
                                    <option value="alat_musik" {{ $budaya->jenis_budaya == 'alat_musik' ? 'selected' : '' }}>Alat Musik Tradisional</option>
                                </select>
                                @error('jenis')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group w-75">
                                <label for="">Tipe</label>
                                    <div class="form-check">
                                        <input class="form-check-input  @error('nama') is-invalid @enderror" type="radio" name="tipe" id="tipe-audio" value="audio" @if($budaya->tipe_budaya == 'audio') checked @endif>
                                        <label class="form-check-label" for="tipe-audio">
                                            Audio (Musik)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input  @error('nama') is-invalid @enderror" type="radio" name="tipe" id="tipe-visual" value="visual" @if($budaya->tipe_budaya == 'visual') checked @endif>
                                        <label class="form-check-label" for="tipe-visual">
                                            Visual (Gambar)
                                        </label>
                                    </div>
                                    @error('tipe')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                            </div>
                            <div class="form-group w-75">
                                <label for="">Deskripsi Budaya</label>
                                <textarea name="description"  class="form-control text-area-unchanged @error('nama') is-invalid @enderror" rows="4">{{old('description',$budaya->deskripsi)}}</textarea>
                                @error('description')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group w-75">
                                <label for="">File</label>
                            
                                <div id="preview-container" class="mb-2">
                                    @if(isset($budaya->attachment))
                                        @if($budaya->tipe_budaya == 'visual')
                                            <img id="preview-media" 
                                                 src="{{ asset('uploads/budaya/' . $budaya->attachment) }}" 
                                                 alt="Preview Media" 
                                                 width="150">
                                        @elseif($budaya->tipe_budaya == 'audio')
                                            <audio id="preview-media" controls>
                                                <source src="{{ asset('uploads/budaya/' . $budaya->attachment) }}" type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio>
                                        @endif
                                    @else
                                        <img id="preview-media" src="#" alt="Preview Media" width="150" style="display: none;">
                                    @endif
                                </div>
                            
                                <!-- Input File -->
                                <input type="file" name="attachment" class="form-control-file  @error('attachment') is-invalid @enderror" id="attachment" accept="image/*,audio/*">
                                @error('attachment')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            
                            <button type="submit"  class="my-3 btn btn-primary">Edit Data</button>
                            <button type="button" class="float-right my-3 btn btn-info shadow-lg" onclick="location.reload()">Batal</button>
                        </form>
                </div>
            </div>

            <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Anda Yakin Ingin Logout ?</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Silahkan Klik Logout Jika Anda Ingin Keluar
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">
                                Batal
                            </button>
                            <a class="btn btn-primary" href="../../action/function.php?logout=1">Logout</a>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.getElementById('attachment').addEventListener('change', function(event) {
                    var input = event.target;
                    var previewContainer = document.getElementById('preview-container');
                    var previewMedia = document.getElementById('preview-media');
                    var file = input.files[0];
                
                    if (file) {
                        var fileType = file.type;
                        var reader = new FileReader();
                
                        reader.onload = function(e) {
                            // Reset container
                            previewContainer.innerHTML = "";
                
                            if (fileType.startsWith('image/')) {
                                // Jika file adalah gambar, buat elemen <img>
                                var img = document.createElement("img");
                                img.src = e.target.result;
                                img.alt = "Preview Media";
                                img.width = 150;
                                previewContainer.appendChild(img);
                            } else if (fileType.startsWith('audio/')) {
                                // Jika file adalah audio, buat elemen <audio>
                                var audio = document.createElement("audio");
                                audio.controls = true;
                                var source = document.createElement("source");
                                source.src = e.target.result;
                                source.type = fileType;
                                audio.appendChild(source);
                                previewContainer.appendChild(audio);
                            } else {
                                alert("File tidak didukung! Pilih gambar atau audio.");
                                input.value = ""; // Reset input file
                            }
                        };
                
                        reader.readAsDataURL(file);
                    }
                });
                </script>

                
            <script>
                $(document).ready(function() {
                    $('.select2').select2({
                        placeholder: "Pilih Jenis Budaya",
                        allowClear: true,
                        width: '100%' 
                    });
                });
            </script>
            
                

    @include('layout.footer')