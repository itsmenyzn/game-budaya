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
                <h1 class="h3 mb-4 text-gray-800">Tambah Data Budaya</h1>

                <div class="card shadow my-3 col-lg-9">
                    <div class="card-body w-100">
                        <form method="POST" action="{{ route('budaya.store') }}" enctype="multipart/form-data">
                            @csrf
                        
                            <!-- Nama Budaya -->
                            <div class="form-group w-75">
                                <label for="nama">Nama Budaya <small style="color: red">*</small> </label>
                                <input value="{{ old('nama') }}" type="text" 
                                       class="form-control-lg form-control @error('nama') is-invalid @enderror" 
                                       placeholder="Nama Budaya..." 
                                       name="nama">
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <!-- Tipe Budaya -->
                            <div class="form-group w-75">
                                <label for="tipe">Tipe <small style="color: red">*</small></label>
                                <div class="form-check">
                                    <input class="form-check-input @error('tipe') is-invalid @enderror" type="radio" name="tipe" id="tipe-audio" value="audio">
                                    <label class="form-check-label" for="tipe-audio">Audio (Musik)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input @error('tipe') is-invalid @enderror" type="radio" name="tipe" id="tipe-visual" value="visual">
                                    <label class="form-check-label" for="tipe-visual">Visual (Gambar)</label>
                                </div>
                                @error('tipe')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <!-- Jenis Budaya -->
                            <div class="form-group w-75">
                                <label for="jenis">Jenis Budaya <small style="color: red">*</small></label>
                                <select class="form-control select2 @error('jenis') is-invalid @enderror" name="jenis">
                                    <option disabled selected>Pilih Jenis Budaya</option>
                                    <option value="rumah" {{ old('jenis') == 'rumah' ? 'selected' : '' }}>Rumah Adat</option>
                                    <option value="pakaian" {{ old('jenis') == 'pakaian' ? 'selected' : '' }}>Pakaian Adat</option>
                                    <option value="tari" {{ old('jenis') == 'tari' ? 'selected' : '' }}>Tari Adat</option>
                                    <option value="alat_musik" {{ old('jenis') == 'alat_musik' ? 'selected' : '' }}>Alat Musik Tradisional</option>
                                </select>
                                @error('jenis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <!-- Deskripsi Budaya -->
                            <div class="form-group w-75">
                                <label for="description">Deskripsi Budaya <small style="color: red">*</small></label>
                                <textarea name="description" class="form-control text-area-unchanged @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <!-- Upload File with Image Preview -->
                            <div class="form-group w-75">
                                <label for="attachment">File <small>(File yang diterima: JPG, PNG, JPEG, MP3, max 1MB)</small><small style="color: red">*</small></label>
                                <input type="file" name="attachment" id="attachment" class="form-control @error('attachment') is-invalid @enderror" accept="image/*,audio/*">
                                @error('attachment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="mt-3" id="preview-container">
                                    <img id="preview-media" src="" class="d-none img-thumbnail" width="200">
                                </div>
                            </div>
                            
                        
                            <!-- Buttons -->
                            <button type="submit" class="my-3 btn btn-primary">Tambah Data</button>
                            <button type="button" class="float-right my-3 btn btn-info shadow-lg" onclick="location.reload()">Batal</button>
                        </form>
                        
                        <!-- JavaScript to Show Image Preview -->
                        {{-- <script>
                            function previewImage(event) {
                                const image = document.getElementById("imagePreview");
                                const file = event.target.files[0];
                        
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        image.src = e.target.result;
                                        image.classList.remove("d-none"); // Show the image
                                    };
                                    reader.readAsDataURL(file);
                                } else {
                                    image.classList.add("d-none"); // Hide if no file selected
                                }
                            }
                        </script>   --}}
                        <script>
                            document.getElementById('attachment').addEventListener('change', function(event) {
                                var input = event.target;
                                var previewContainer = document.getElementById('preview-container');
                                var file = input.files[0];
                            
                                if (file) {
                                    var fileType = file.type;
                                    var reader = new FileReader();
                            
                                    reader.onload = function(e) {
                                        previewContainer.innerHTML = "";
                            
                                        if (fileType.startsWith('image/')) {
                                            var img = document.createElement("img");
                                            img.src = e.target.result;
                                            img.alt = "Preview Media";
                                            img.classList.add("img-thumbnail");
                                            img.width = 200;
                                            previewContainer.appendChild(img);
                                        } else if (fileType.startsWith('audio/')) {
                                            var audio = document.createElement("audio");
                                            audio.controls = true;
                                            var source = document.createElement("source");
                                            source.src = e.target.result;
                                            source.type = fileType;
                                            audio.appendChild(source);
                                            previewContainer.appendChild(audio);
                                        } else {
                                            alert("File tidak didukung! Pilih gambar atau audio.");
                                            input.value = "";
                                        }
                                    };
                            
                                    reader.readAsDataURL(file);
                                }
                            });
                            </script>
                            
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

            @include('layout.footer')

            <script>
                $(document).ready(function() {
                    $('.select2').select2({
                        placeholder: "Pilih Jenis Budaya",
                        allowClear: true,
                        width: '100%' 
                    });
                });
            </script>
            
    

 