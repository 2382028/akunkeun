<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PdfUploadController extends Controller
{
    public function index()
    {
        return view('user.upload-tes');
    }

    public function uploadChunk(Request $request)
    {
        try {
            // Pastikan ada file yang dikirim
            if (!$request->hasFile('file_chunk')) {
                return response()->json(['error' => 'Tidak ada file chunk yang dikirim'], 400);
            }

            // Ambil informasi chunk
            $chunk = $request->file('file_chunk');
            $chunkIndex = $request->input('chunk_index');
            $totalChunks = $request->input('total_chunks');

            // Simpan sementara di storage Laravel
            $tempDir = storage_path('app/temp_uploads/');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0775, true);
            }

            $tempPath = $tempDir . "temp_file_part_{$chunkIndex}.tmp";
            file_put_contents($tempPath, file_get_contents($chunk));

            Log::info("Chunk ke-$chunkIndex berhasil disimpan di $tempPath");

            return response()->json(['message' => "Chunk ke-$chunkIndex berhasil diunggah"]);
        } catch (\Exception $e) {
            Log::error("Error upload chunk: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function mergeChunks(Request $request)
    {
        try {
            $totalChunks = $request->input('total_chunks');
            $finalFilename = Str::random(40).'.pdf';
            $finalPath = storage_path("app/uploads/$finalFilename");

            // Pastikan direktori tujuan ada
            $uploadDir = storage_path('app/uploads/');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }

            $finalFile = fopen($finalPath, 'wb');
            for ($i = 0; $i < $totalChunks; $i++) {
                $tempPartPath = storage_path("app/temp_uploads/temp_file_part_{$i}.tmp");

                if (!file_exists($tempPartPath)) {
                    Log::error("Chunk ke-$i hilang!");
                    return response()->json(['error' => "Chunk ke-$i tidak ditemukan!"], 500);
                }

                fwrite($finalFile, file_get_contents($tempPartPath));
                unlink($tempPartPath); // Hapus chunk setelah digabungkan
            }

            fclose($finalFile);
            Log::info("File akhir berhasil dibuat: $finalPath");

            return response()->json(['message' => 'File berhasil diunggah!', 'path' => "/storage/uploads/$finalFilename"]);
        } catch (\Exception $e) {
            Log::error("Error merging chunks: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
