<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoice;
use DB;
use Auth;
use App\PONumber;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class InvoiceController extends Controller
{
    public function index()
    {
        $year = date("Y");
        $year_before = Invoice::select(DB::raw('YEAR(date) year'))->orderBy('year','desc')->groupBy('year')->get();
        $sidebar_collapse = true;

        return view('admin/invoice', compact('year', 'sidebar_collapse', 'year_before'))->with(['initView'=> $this->initMenuBase()]);
    }

    public function getNoPo()
    {
        $getPo = collect(PONumber::select(DB::raw('`no` AS `id`,`no_po` AS `text`'))->whereYear('date', date('Y'))->where('status', 'N')->get());

        return array("data" => $getPo);
    }

    public function store(Request $request)
    {
        $insert = new Invoice();
        $insert->no_invoice = $request['no_invoice'];
        $insert->no_po = $request['no_po'];
        $edate = strtotime($_POST['date']); 
        $edate = date("Y-m-d",$edate);
        $insert->date = $edate;
        $insert->from_eksternal = $request['from_eksternal'];
        $insert->issuance = Auth::User()->name;
        $insert->save();

        $update = PONumber::where('no',$request['no_po'])->first();
        $update->status = 'P';
        $update->update();

        return redirect('invoice')->with('success', 'Create Invoice Successfully!');
    }

    public function getData()
    {
        $data = Invoice::join('tb_po', 'tb_po.no', '=', 'tb_invoice.no_po')->select('no_invoice', 'tb_po.no_po', 'from_eksternal', 'tb_invoice.issuance', 'tb_invoice.date', 'tb_po.no', 'id')->whereYear('tb_invoice.date', date('Y'))->orderBy('id', 'desc')->get();

        return array("data"=>$data);
    }

    public function getFilterYear(Request $request)
    {
        $data = Invoice::join('tb_po', 'tb_po.no', '=', 'tb_invoice.no_po')->select('no_invoice', 'tb_po.no_po', 'from_eksternal', 'tb_invoice.issuance', 'tb_invoice.date', 'tb_po.no', 'id')->whereYear('tb_invoice.date', $request->data)->orderBy('id', 'desc')->get();

        return array("data"=>$data);
    }

    public function getInvoiceEdit(Request $request)
    {
        $data = Invoice::join('tb_po', 'tb_po.no', '=', 'tb_invoice.no_po')->select('no_invoice', 'tb_po.no_po', 'from_eksternal', 'tb_invoice.issuance', 'tb_invoice.date', 'tb_po.no', 'id')
        ->where('id',$request->id_invoice)
        ->get();

        $getPo = collect(PONumber::select(DB::raw('`no` AS `id`,`no_po` AS `text`'))
            ->whereYear('date', date('Y'))
            ->where('status', 'N')
            ->orWhere('no', $data->first()->no)
            ->get());

        return array("data"=>$data, "no_po" => $getPo);   
    }

    public function update_invoice(Request $request)
    {
        $update = Invoice::where('id', $request['id_edit'])->first();
        $update->no_invoice = $request['edit_no_invoice'];
        $update->no_po = $request['edit_no_po'];
        $edate = strtotime($_POST['edit_date']); 
        $edate = date("Y-m-d",$edate);
        $update->date = $edate;
        $update->from_eksternal = $request['edit_from'];
        $update->update();

        $update_no_po = PONumber::where('no', $request->edit_no_po)->first();
        $update_no_po->status = 'P';
        $update_no_po->update();

        $update_no_po_ex = PONumber::where('no', $request->edit_no_po_existing)->first();
        $update_no_po_ex->status = 'N';
        $update_no_po_ex->update();

        return redirect('invoice')->with('update', 'Update Invoice Successfully!');
    }

    public function downloadExcel(Request $request) {

        $spreadsheet = new Spreadsheet();

        $invoiceSheet = new Worksheet($spreadsheet,'Invoice');
        $spreadsheet->addSheet($invoiceSheet);
        $spreadsheet->removeSheetByIndex(0);
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->mergeCells('A1:F1');
        $normalStyle = [
            'font' => [
                'name' => 'Calibri',
                'size' => 11
            ],
        ];

        $titleStyle = $normalStyle;
        $titleStyle['alignment'] = ['horizontal' => Alignment::HORIZONTAL_CENTER];
        $titleStyle['borders'] = ['outline' => ['borderStyle' => Border::BORDER_THIN]];
        $titleStyle['fill'] = ['fillType' => Fill::FILL_SOLID, 'startColor' => ["argb" => "FFFCD703"]];
        $titleStyle['font']['bold'] = true;

        $sheet->getStyle('A1:F1')->applyFromArray($titleStyle);
        $sheet->setCellValue('A1','Rekap Invoice');

        $headerStyle = $normalStyle;
        $headerStyle['font']['bold'] = true;
        $sheet->getStyle('A2:F2')->applyFromArray($headerStyle);;

        $headerContent = ["No", "NO INVOICE", "DATE", "NO PURCHASE ORDER", "FROM(EXTERNAL)","ISSUANCE"];
        $sheet->fromArray($headerContent,NULL,'A2');

        $data = Invoice::join('tb_po', 'tb_po.no', '=', 'tb_invoice.no_po')->select('no_invoice',  'tb_invoice.date', 'tb_po.no_po', 'from_eksternal', 'tb_invoice.issuance')->whereYear('tb_invoice.date', $request->year)->get();

        foreach ($data as $key => $data) {
            $sheet->fromArray(array_merge([$key + 1],array_values($data->toArray())),NULL,'A' . ($key + 3));
        }

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);


        $fileName = 'Daftar Buku Admin (Invoice) ' . date('Y') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        ob_end_clean();
        return $writer->save("php://output");
    }
}
