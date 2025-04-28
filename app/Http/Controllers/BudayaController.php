<?php

namespace App\Http\Controllers;

use App\Models\Budaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Str;

class BudayaController extends Controller
{   
    public function index() {
        $listBudaya = Budaya::orderBy('created_at','DESC')->get();

        return view('budaya.index',[
            'data' => $listBudaya
        ]);
    }

    public function create() {
        return view('budaya.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'nama'         => 'required|string|min:2',
            'jenis'        => 'required|string',
            'tipe'         => 'required|string',
            'description'  => 'required|string|min:10',
            'attachment'   => 'required|file|max:1000'
        ];
        
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $mime = $file->getMimeType();
        
            if (Str::startsWith($mime, 'image')) {
                $rules['attachment'] .= '|mimes:jpeg,jpg,png';
            } 
            else {
                $rules['attachment'] = function ($attribute, $value, $fail) {
                    if ($value->getClientOriginalExtension() !== 'mp3') {
                        $fail($attribute . ' harus .mp3!');
                    }
                    if ($value->getSize() > 1000 * 1024) { 
                        $fail($attribute . ' tidak boleh lebih dari 1 MB!');
                    }
                };
            }
        }
        
        $messages = [
            'nama.required'         => 'Nama budaya wajib diisi.',
            'nama.min'              => 'Nama budaya minimal harus 2 karakter.',
            'jenis.required'        => 'Jenis budaya harus dipilih.',
            'tipe.required'         => 'Tipe budaya harus dipilih.',
            'description.required'  => 'Deskripsi budaya tidak boleh kosong.',
            'description.min'       => 'Deskripsi budaya minimal 10 karakter.',
            'attachment.required'   => 'File harus diunggah.',
            'attachment.mimes'      => 'File gambar harus berupa JPG, JPEG, atau PNG.',
            'attachment.audio'      => 'File audio harus berupa file audio yang valid.',
            'attachment.codec'      => 'Audio harus menggunakan codec MP3 atau PCM S16LE.',
            'attachment.reject'     => 'File yang diunggah harus berupa gambar atau audio yang valid.',
            'attachment.max'        => 'Ukuran file maksimal 1MB.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->route('budaya.create')->withInput()->withErrors($validator);
        }

        $data = new Budaya();
        $data->nama_budaya = $request->nama;
        $data->jenis_budaya = $request->jenis;
        $data->tipe_budaya = $request->tipe;
        $data->deskripsi = $request->description;

        // handle image request
        $image = $request->attachment;
        $ext = $image->getClientOriginalExtension();
        $imageName = time().'.'.$ext; 

        $image->move(public_path('uploads/budaya'),$imageName);
        
        $data->attachment = $imageName;
        $data->save();
        
        return redirect()->route('budaya.index')->with('success','Berhasil menambahkan data.');
    }

    public function edit($id) {
        $data = Budaya::findOrFail($id);
        return view('budaya.edit',[
            'budaya' => $data
        ]);
    }

    public function update($id, Request $request)
    {
        $data = Budaya::findOrFail($id);
    
        $rules = [
            'nama'         => 'required|string|min:2',
            'jenis'        => 'required|string',
            'tipe'         => 'required|string',
            'description'  => 'required|string|min:10',
            'attachment'   => 'nullable|file|max:1000'
        ];
        
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $mime = $file->getMimeType();
        
            if (Str::startsWith($mime, 'image')) {
                $rules['attachment'] .= '|mimes:jpeg,jpg,png';
            } 
            else 
            {
                $rules['attachment'] = function ($attribute, $value, $fail) {
                    if ($value->getClientOriginalExtension() !== 'mp3') {
                        $fail($attribute . ' harus .mp3!');
                    }
                    if ($value->getSize() > 1000 * 1024) { 
                        $fail($attribute . ' tidak boleh lebih dari 1 MB!');
                    }
                };
            }
        }
        
        $messages = [
            'nama.required'         => 'Nama budaya wajib diisi.',
            'nama.min'              => 'Nama budaya minimal harus 2 karakter.',
            'jenis.required'        => 'Jenis budaya harus dipilih.',
            'tipe.required'         => 'Tipe budaya harus dipilih.',
            'description.required'  => 'Deskripsi budaya tidak boleh kosong.',
            'description.min'       => 'Deskripsi budaya minimal 10 karakter.',
            'attachment.required'   => 'File harus diunggah.',
            'attachment.mimes'      => 'File gambar harus berupa JPG, JPEG, atau PNG.',
            'attachment.audio'      => 'File audio harus berupa file audio yang valid.',
            'attachment.codec'      => 'Audio harus menggunakan codec MP3 atau PCM S16LE.',
            'attachment.reject'     => 'File yang diunggah harus berupa gambar atau audio yang valid.',
            'attachment.max'        => 'Ukuran file maksimal 1MB.'
        ];
    
        $validator = Validator::make($request->all(), $rules, $messages);
    
        if ($validator->fails()) {
            return redirect()->route('budaya.edit', $data->id_budaya)->withInput()->withErrors($validator);
        }
    
        $data->nama_budaya = $request->nama;
        $data->jenis_budaya = $request->jenis;
        $data->tipe_budaya = $request->tipe;
        $data->deskripsi = $request->description;
    
        if ($request->hasFile('attachment')) {
            $oldFilePath = public_path('uploads/budaya/' . $data->attachment);
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);  
            }
    
            $image = $request->file('attachment');
            $ext = $image->getClientOriginalExtension();
            $imageName = time() . '.' . $ext; 
    
            $image->move(public_path('uploads/budaya'), $imageName);
    
            $data->attachment = $imageName;
        }
    
        $data->save();
    
        return redirect()->route('budaya.index')->with('success', 'Berhasil mengupdate data.');
    }
    
    public function destroy($id) {
       $data = Budaya::findOrFail($id);

       File::delete(public_path('uploads/budaya/'.$data->attachment));

       $data->delete();

       return redirect()->route('budaya.index')->with('success','Berhasil menghapus data.');
    }
}
