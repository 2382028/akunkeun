<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DataBalikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.bmn.data-balik');
    }

    private function isValidDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    public function processUpload(Request $request)
    {
        $request->validate([
            'balikData' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('balikData');
        $filePath = $file->storeAs('uploads', $file->getClientOriginalName(), 'public');

        if (($handle = fopen(storage_path("app/public/$filePath"), "r")) !== FALSE) {
            fgetcsv($handle); // Skip header
            $insertedData = [];
            $invalidRows = [];

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                try {
                    // Periksa apakah jumlah kolom sesuai
                    if (count($data) < 11) {
                        $invalidRows[] = ['data' => $data, 'error' => 'Insufficient columns'];
                        continue; // Lewati iterasi ini
                    }

                    // Konversi tanggal
                    $tglBeli = strtoupper($data[4]) === "" || empty($data[4]) || !$this->isValidDate(date('Y-m-d', strtotime($data[4])), 'Y-m-d') 
                    ? null 
                    : date('Y-m-d', strtotime($data[4]));

                    $createdAt = strtoupper($data[8]) === "" || empty($data[8]) || !$this->isValidDate(date('Y-m-d H:i:s', strtotime($data[8])), 'Y-m-d H:i:s') 
                        ? null 
                        : date('Y-m-d H:i:s', strtotime($data[8]));

                    $updatedAt = strtoupper($data[9]) === "" || empty($data[9]) || !$this->isValidDate(date('Y-m-d H:i:s', strtotime($data[9])), 'Y-m-d H:i:s') 
                        ? null 
                        : date('Y-m-d H:i:s', strtotime($data[9]));

                        $newData = [
                            'kode_barang' => strtoupper($data[0]) === "" ? null : $data[0],
                            'nama_barang' => strtoupper($data[1]) === "" ? null : $data[1],
                            'NUP' => strtoupper($data[2]) === "" ? null : $data[2],
                            'nama_merek' => strtoupper($data[3]) === "" ? null : $data[3],
                            'tgl_beli' => $tglBeli,
                            'jenis_perawatan' => strtoupper($data[5]) === "" ? null : $data[5],
                            'status_kondisi' => strtoupper($data[6]) === "" ? null : $data[6],
                            'status_peminjaman' => strtoupper($data[7]) === "" ? null : $data[7],
                            'created_at' => $createdAt,
                            'updated_at' => $updatedAt,
                            'kategori' => strtoupper($data[10]) === "" ? null : $data[10],
                        ];

                    DB::table('assets')->insert($newData);
                    $insertedData[] = $newData;
                } catch (\Exception $e) {
                    $invalidRows[] = ['data' => $data, 'error' => $e->getMessage()];
                }
            }

            fclose($handle);

            // Return success and error details
            return redirect('/data-balik-asset')->with('status', count($insertedData) . ' rows inserted successfully.')
                ->with('errors', $invalidRows);
        }

        return redirect('/data-balik-asset')->with('error', 'Failed to process the file.');
    }
  
    public function processUploadKendaraan(Request $request)
      {
          $request->validate([
              'balikDataKendaraan' => 'required|file|mimes:csv,txt|max:2048',
          ]);

          $file = $request->file('balikDataKendaraan');
          $filePath = $file->storeAs('uploads', $file->getClientOriginalName(), 'public');

          if (($handle = fopen(storage_path("app/public/$filePath"), "r")) !== FALSE) {
              fgetcsv($handle); // Skip header
              $insertedData = [];
              $invalidRows = [];

              while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                  try {
                      // Periksa apakah jumlah kolom sesuai
                      if (count($data) < 12) {
                          $invalidRows[] = ['data' => $data, 'error' => 'Insufficient columns'];
                          continue; // Lewati iterasi ini
                      }
                      // Konversi tanggal
                      $createdAt = strtoupper($data[10]) === "" || empty($data[10]) || !$this->isValidDate(date('Y-m-d H:i:s', strtotime($data[10])), 'Y-m-d H:i:s') 
                          ? null 
                          : date('Y-m-d H:i:s', strtotime($data[10]));

                      $updatedAt = strtoupper($data[11]) === "" || empty($data[11]) || !$this->isValidDate(date('Y-m-d H:i:s', strtotime($data[11])), 'Y-m-d H:i:s') 
                          ? null 
                          : date('Y-m-d H:i:s', strtotime($data[11]));

                          $newData = [
                              'merek' => strtoupper($data[0]) === "" ? null : $data[0],
                              'no_polisi' => strtoupper($data[1]) === "" ? null : $data[1],
                              'no_mesin' => strtoupper($data[2]) === "" ? null : $data[2],
                              'no_stnk' => strtoupper($data[3]) === "" ? null : $data[3],
                              'no_bpkb' => strtoupper($data[4]) === "" ? null : $data[4],
                              'legalitas' => strtoupper($data[5]) === "" ? null : $data[5],
                              'legalitas_5th' => strtoupper($data[6]) === "" ? null : $data[6],
                              'tipe' => strtoupper($data[7]) === "" ? null : $data[7],
                              'status' => strtoupper($data[8]) === "" ? null : $data[8],
                              'is_used' => strtoupper($data[9]) === "" ? null : $data[9],
                              'created_at' => $createdAt,
                              'updated_at' => $updatedAt,
                          ];

                      DB::table('kendaraans')->insert($newData);
                      $insertedData[] = $newData;
                  } catch (\Exception $e) {
                      $invalidRows[] = ['data' => $data, 'error' => $e->getMessage()];
                  }
              }

              fclose($handle);

              // Return success and error details
              return redirect('/data-balik')->with('status', count($insertedData) . ' rows inserted successfully.')
                  ->with('errors', $invalidRows);
          }

          return redirect('/data-balik')->with('error', 'Failed to process the file.');
      }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Asset $asset)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asset $asset)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asset $asset)
    {
        //
    }
}
