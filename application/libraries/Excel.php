<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') OR exit('No direct script access allowed');

class Excel
{
	protected $ci;

	public function __construct()
	{
        $this->ci =& get_instance();
	}

	public function create($balance, $warehouse) {
		
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		// set header
		$sheet->setCellValue('A1', '#');
		$sheet->setCellValue('B1', 'Code Number');
		$sheet->setCellValue('C1', 'Item Name');
		$sheet->setCellValue('D1', 'Model Number');
		$sheet->setCellValue('E1', 'Supplier');
		$sheet->setCellValue('F1', 'Purchase Price');
		$sheet->setCellValue('G1', 'Total Items');
		$sheet->setCellValue('H1', 'Amount');

		$i = 1;
		$j = 2;
		foreach($balance as $bal) {
			$sheet->setCellValue('A'.$j, $i);
			$sheet->setCellValue('B'.$j, $bal->codeNumber);
			$sheet->setCellValue('C'.$j, $bal->itemName);
			$sheet->setCellValue('D'.$j, $bal->itemModel);
			$sheet->setCellValue('E'.$j, $bal->supplierName);
			$sheet->setCellValue('F'.$j, $bal->price);
			$totalQty = 0;
			foreach($warehouse as $row){
				$balance = $this->ci->ignite_model->get_limit_datas('stocks_balance_tbl', ['itemId' => $bal->itemId, 'warehouseId' => $row->warehouseId])->row();
				if(isset($balance->qty)){
					$totalQty += $balance->qty;
				}
			}
			$sheet->setCellValue('G'.$j, $totalQty);
			$sheet->setCellValue('H'.$j, $totalQty * $bal->price);
			$j++;
			$i++;
		}

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="balancesheet_'.date('y-m-d').'.xlsx"'); 
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}

	

}

/* End of file Excel.php */
/* Location: ./application/libraries/Excel.php */
