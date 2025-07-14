<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BonusSQL extends Controller
{
    public function nilaiRT(Request $request)
    {
        $query = "
            select
                nama as nama,
                nisn,
                nama_pelajaran,
                skor
            from
                nilai
            where
                materi_uji_id = 7
                and nama_pelajaran != 'pelajaran khusus'
            group by
                nama,
                nisn,
                nama_pelajaran,
                skor  -- only_full_group_by is on
        ";

        $results = DB::select($query);
        $response = collect($results)
            ->groupBy('nisn')
            ->map(function ($studentScores, $nisn) {
                $nilaiRT = $studentScores->mapWithKeys(function ($score) {
                    return [strtolower($score->nama_pelajaran) => $score->skor];
                });

                return [
                    'name' => $studentScores->first()->nama,
                    'nisn' => $nisn,
                    'nilaiRT' => $nilaiRT,
                ];
            })
            ->values();
        return response()->json($response);
    }

    public function nilaiST(Request $request)
    {
        $query = "
            select
                nama,
                nisn,
                pelajaran_id,
                nama_pelajaran,
                skor,
                case
                    when pelajaran_id = 44 then skor * 41.67
                    when pelajaran_id = 45 then skor * 29.67
                    when pelajaran_id = 46 then skor * 100
                    when pelajaran_id = 47 then skor * 23.81
                end as nilai
            from
                nilai
            where
                materi_uji_id = 4;
        ";

        $results = DB::select($query);
        $response = collect($results)
            ->groupBy('nisn')
            ->map(function ($studentScores, $nisn) {
                $skor = 0;
                $nilaiST = $studentScores->mapWithKeys(function ($score) {
                    return [strtolower($score->nama_pelajaran) => $score->nilai];
                });

                foreach ($studentScores as $score) {
                    $skor += $score->nilai;
                }

                return [
                    'name' => $studentScores->first()->nama,
                    'nisn' => $nisn,
                    'nilaiST' => $nilaiST,
                    'skor' => $skor,
                ];
            })->values();

        $response = $response->sortByDesc('skor')->values();
        return response()->json($response);
    }
}
