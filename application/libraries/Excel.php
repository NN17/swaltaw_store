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

	public function create() {
		
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Hello World !');

		$writer = new Xlsx($spreadsheet);
		$writer->save('hello world.xlsx');
	}

	

}

/* End of file Excel.php */
/* Location: ./application/libraries/Excel.php */
