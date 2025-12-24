<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Spreadsheet_Lib
{
    protected $spreadsheet;

    public function __construct()
    {
        // Pastikan composer autoload terpanggil
        if (!class_exists(Spreadsheet::class)) {
            require_once FCPATH . 'vendor/autoload.php';
        }

        $this->spreadsheet = new Spreadsheet();
    }

    /**
     * Ambil objek spreadsheet
     */
    public function getSpreadsheet()
    {
        return $this->spreadsheet;
    }

    /**
     * Output file Excel ke browser
     */
    public function output($filename = 'export.xlsx')
    {
        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($this->spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
