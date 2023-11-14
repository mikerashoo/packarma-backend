<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportBannerReports implements FromView, WithHeadings
{


    private $data;


    function __construct($data)
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        return ["User Name", "Email", "Date", "Banner Start Date Time", "Banner End Date Time"];
    }

    public function view(): View
    {
        return view('backend.banner_reports.banner_report_excel', ["data" => $this->data]);
    }
}
