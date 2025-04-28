<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budaya;

class QuestionController extends Controller
{
    public function getSoalPilihanGanda(Request $request)
    {
        $questionQty = $request->query('qty', 10);
        $imageBaseUrl = env('APP_URL','http://127.0.0.1:8000') . '/uploads/budaya/';

        $questions = Budaya::where('tipe_budaya', 'visual')
            ->inRandomOrder()
            ->limit($questionQty)
            ->get()
            ->map(function ($budaya) use ($imageBaseUrl) {
                
                $questionText = match ($budaya->jenis_budaya) {
                    'rumah' => "Apakah nama dari rumah adat ini?",
                    'pakaian' => "Apakah nama dari pakaian adat ini?",
                    'tari' => "Apakah nama dari tarian adat ini?",
                    'alat_musik' => "Apakah nama dari alat musik tradisional ini?",
                };

                $otherOptions = Budaya::where('id_budaya', '!=', $budaya->id_budaya)
                    ->where('jenis_budaya', $budaya->jenis_budaya)
                    ->where('tipe_budaya', 'visual')
                    ->inRandomOrder()
                    ->get()
                    ->pluck('nama_budaya')
                    ->unique()
                    ->reject(fn($nama) => $nama === $budaya->nama_budaya)
                    ->take(3)
                    ->values()
                    ->toArray();

                $options = array_merge($otherOptions, [$budaya->nama_budaya]);
                shuffle($options);

                return [
                    'question' => $questionText,
                    'options' => $options,
                    'answer' => $budaya->nama_budaya,
                    'attachment' => $imageBaseUrl . $budaya->attachment,
                    'desc' => $budaya->deskripsi,
                ];
            });

        return response()->json([
            'data' => $questions,
            'status' => "success",
            'message' => "Successfully retrieved questions from the question bank"
        ], 200);
    }
    
    public function getSoalTebakGambar(Request $request)
    {
        $questionQty = $request->query('qty', 10);
        $baseUrl = env('APP_URL','http://127.0.0.1:8000') . '/uploads/budaya/';
    
        $questions = Budaya::where('tipe_budaya', 'visual')
            ->inRandomOrder()
            ->limit($questionQty)
            ->get()
            ->map(function ($budaya) use ($baseUrl) {
                return [
                    'full_name' => str_replace(" ", "", strtoupper($budaya->nama_budaya)),
                    'attachment' => $baseUrl . $budaya->attachment,
                    'description' => $budaya->deskripsi,
                ];
            });
    
        return response()->json([
            'data' => $questions,
            'status' => "success",
            'message' => "Successfully retrieved questions from the question bank"
        ], 200);
    }

    public function getSoalTebakAlatMusik(Request $request)
    {
        $questionQty = $request->query('qty', 10);
        $appUrl = env('APP_URL','http://127.0.0.1:8000');
        $baseUrl = $appUrl . "/uploads/budaya/";

        $questions = Budaya::where('jenis_budaya', 'alat_musik')
            ->where('tipe_budaya', 'audio')
            ->inRandomOrder()
            ->limit($questionQty)
            ->get()
            ->map(function ($budaya) use ($baseUrl) {
                $otherOptions = Budaya::where('id_budaya', '!=', $budaya->id_budaya)
                    ->where('jenis_budaya', 'alat_musik')
                    ->where('tipe_budaya', 'audio')
                    ->inRandomOrder()
                    ->get()
                    ->pluck('nama_budaya')
                    ->unique()
                    ->reject(fn($nama) => $nama === $budaya->nama_budaya)
                    ->take(3)
                    ->values()
                    ->toArray();

                $options = array_merge($otherOptions, [$budaya->nama_budaya]);
                shuffle($options);

                return [
                    'question' => "Apakah nama dari alat musik tradisional ini?",
                    'options' => $options,
                    'answer' => $budaya->nama_budaya,
                    'attachment' => $baseUrl . $budaya->attachment,
                    'desc' => $budaya->deskripsi,
                ];
            });

        return response()->json([
            'data' => $questions,
            'status' => "success",
            'message' => "Successfully retrieved questions from the question bank"
        ], 200);
    }

    public function LoadBudayaData(Request $request) {
        $limit = $request->query('limit', 5); 
        $page = $request->query('page', 1); 
        $jenis = $request->query('jenis_budaya', null); 
        $tipe = $request->query('tipe_budaya', null); 
    
        $offset = ($page - 1) * $limit; 
     
        $appUrl = env('APP_URL', 'http://127.0.0.1:8000');

        $url = $appUrl . "/uploads/budaya/";

        $total = Budaya::when($jenis, function ($query) use ($jenis) {
                    return $query->where('jenis_budaya', $jenis);
                })
                ->when($tipe, function ($query) use ($tipe) {
                    return $query->where('tipe_budaya', $tipe);
                })
                ->count();
    
        $listBudaya = Budaya::when($jenis, function ($query) use ($jenis) {
                    return $query->where('jenis_budaya', $jenis);
                })
                ->when($tipe, function ($query) use ($tipe) {
                    return $query->where('tipe_budaya', $tipe);
                })
                ->select('nama_budaya', 'jenis_budaya', 'deskripsi', 'attachment')
                ->orderBy('created_at','DESC')
                ->limit($limit)
                ->offset($offset)
                ->get()
                ->map(function ($budaya) use ($url) {
                    $budaya->attachment = $url . $budaya->attachment;
                    return $budaya;
                });
    
        $totalPage = ceil($total / $limit);
    
        return response()->json([
            'data' => $listBudaya,
            'page' => $page,
            'limit' => $limit,
            'total_data' => $total,
            'total_page' => $totalPage,
            'status' => "success",
            'message' => "Successfully retrieved culture data"
        ], 200);
    }
}
