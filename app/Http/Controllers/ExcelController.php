<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use App\Models\Product; // Product modelinizi import edin
use Illuminate\Support\Facades\Validator;

class ExcelController extends Controller
{
    /**
     * Products Export
     */
    public function exportProducts()
    {
        // Database-dən bütün products-ları götür
        $products = Product::all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Excel başlıqları (Product table-nizin column-larına görə dəyişdirin)
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Description');
        $sheet->setCellValue('D1', 'Price');
        $sheet->setCellValue('E1', 'Category');
        $sheet->setCellValue('F1', 'Stock');
        $sheet->setCellValue('G1', 'Status');
        $sheet->setCellValue('H1', 'Created At');
        
        // Başlıq sətirini bold et
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        
        // Products məlumatlarını əlavə et
        $row = 2;
        foreach ($products as $product) {
            $sheet->setCellValue('A' . $row, $product->id);
            $sheet->setCellValue('B' . $row, $product->name);
            $sheet->setCellValue('C' . $row, $product->description);
            $sheet->setCellValue('D' . $row, $product->price);
            $sheet->setCellValue('E' . $row, $product->category);
            $sheet->setCellValue('F' . $row, $product->stock);
            $sheet->setCellValue('G' . $row, $product->status);
            $sheet->setCellValue('H' . $row, $product->created_at ? $product->created_at->format('Y-m-d H:i:s') : '');
            $row++;
        }
        
        // Column genişliyini avtomatik ayarla
        foreach (range('A', 'H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        $writer = new Xlsx($spreadsheet);
        $fileName = 'products_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return response()->stream(
            function () use ($writer) {
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                'Cache-Control' => 'max-age=0',
            ]
        );
    }

    /**
     * Products Import
     */
    public function importProducts(Request $request)
    {
        // Fayl validation
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Zəhmət olmasa düzgün Excel faylı seçin!');
        }

        try {
            $file = $request->file('file');
            $reader = new XlsxReader();
            $spreadsheet = $reader->load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            // İlk sətir başlıqdırsa atlayın
            array_shift($rows);
            
            $imported = 0;
            $errors = [];
            
            foreach ($rows as $index => $row) {
                // Boş sətirləri atlayın
                if (empty(array_filter($row))) {
                    continue;
                }
                
                try {
                    // Product yaradın (column-ları sizin table structure-nıza görə dəyişdirin)
                    Product::create([
                        'name' => $row[1] ?? '', // B column
                        'description' => $row[2] ?? '', // C column
                        'price' => is_numeric($row[3]) ? $row[3] : 0, // D column
                        'category' => $row[4] ?? '', // E column
                        'stock' => is_numeric($row[5]) ? (int)$row[5] : 0, // F column
                        'status' => $row[6] ?? 'active', // G column
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Sətr " . ($index + 2) . ": " . $e->getMessage();
                }
            }
            
            $message = "$imported məhsul uğurla import edildi.";
            if (!empty($errors)) {
                $message .= " " . count($errors) . " səhv var.";
            }
            
            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Fayl oxunarkən səhv: ' . $e->getMessage());
        }
    }

    /**
     * Sample Excel Template Download
     */
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Template başlıqları
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Description');
        $sheet->setCellValue('D1', 'Price');
        $sheet->setCellValue('E1', 'Category');
        $sheet->setCellValue('F1', 'Stock');
        $sheet->setCellValue('G1', 'Status');
        
        // Sample məlumat
        $sheet->setCellValue('A2', '');
        $sheet->setCellValue('B2', 'iPhone 15');
        $sheet->setCellValue('C2', 'Latest iPhone model');
        $sheet->setCellValue('D2', '1200');
        $sheet->setCellValue('E2', 'Electronics');
        $sheet->setCellValue('F2', '50');
        $sheet->setCellValue('G2', 'active');
        
        // Style
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getStyle('A2:G2')->getFont()->setItalic(true);
        
        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        $writer = new Xlsx($spreadsheet);
        $fileName = 'products_template.xlsx';
        
        return response()->stream(
            function () use ($writer) {
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                'Cache-Control' => 'max-age=0',
            ]
        );
    }

    /**
     * Excel page göstər
     */
    public function index()
{
    $productsCount = Product::count();
    return view('excel.index', compact('productsCount'));
}

}