<?php

namespace App\Http\Controllers;

use App\Services\ExcelImportService;
use App\Services\IndicatorsSummaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IndicatorsController extends Controller
{

    public function __construct(protected ExcelImportService $importService, protected IndicatorsSummaryService $summaryService)
    {
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx|max:10240'
        ]);

        $total = $this->importService->import($request->file('file'));

        return response()->json([
            'message' => 'Arquivo importado com sucesso',
            'records_saved' => $total
        ]);
    }

    public function summary()
    {
        return $this->summaryService->summary();
    }



}
