<?php

namespace App\Http\Controllers\Backend;

use App\Exports\ExportBannerReports;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\BannerClick;
use App\Models\BannerView;
use App\Models\SolutionBanner;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use stdClass;

class BannerReportController extends Controller
{

    /**
     *   Created by : Mikiyas Birhanu
     *   Uses :  to load banners impression report
     *   @param int $id
     *   @return Response
     */
    public function solutionClicksReport($id)
    {
        $banner = SolutionBanner::find($id);


        if (!$banner) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => "Invalid solution banner id"
            ], 401);
        }
        $views = BannerClick::ofSolutionBanner($id)->get();
        $data = $this->getData($banner, $views);
        $downloadLink = route('solution_banner_clicks_download', ['id' => $id]);
        $data->downloadLink = $downloadLink;

        return view('backend.banner_reports.index', ["data" => $data]);
    }

    /**
     *   Created by : Mikiyas Birhanu
     *   Uses :  to load banners impression report
     *   @param $id
     *   @return Response
     */
    public function solutionClicksReportDownload($id)
    {
        $banner = SolutionBanner::find($id);
        if (!$banner) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => "Invalid solution banner id"
            ], 401);
        }

        $views = BannerClick::ofSolutionBanner($id)->get();
        $data = $this->getData($banner, $views);
        $now = Carbon::now()->toDateTimeString();
        $bannerName =  strtolower(str_replace(' ', '_', $banner->title));

        $fileName = 'solution_banner_' . $bannerName . '_clicks_report_' . $now . '.xlsx';
        $exportViews = new ExportBannerReports($data);

        return Excel::download($exportViews, $fileName);
    }


    /**
     *   Created by : Mikiyas Birhanu
     *   Uses :  to load banners impression report
     *   @param int $id
     *   @return Response
     */
    public function solutionViewsReport($id)
    {
        $banner = SolutionBanner::find($id);


        if (!$banner) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => "Invalid solution banner id"
            ], 401);
        }

        $views = BannerView::ofSolutionBanner($id)->get();

        $data = $this->getData($banner, $views);
        $downloadLink = route('solution_banner_views_download', ['id' => $id]);
        $data->downloadLink = $downloadLink;

        return view('backend.banner_reports.index', ["data" => $data]);
    }

    /**
     *   Created by : Mikiyas Birhanu
     *   Uses :  to load banners impression report
     *   @param $id
     *   @return Response
     */
    public function solutionViewsReportDownload($id)
    {
        $banner = SolutionBanner::find($id);
        if (!$banner) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => "Invalid solution banner id"
            ], 401);
        }

        $views = BannerView::ofSolutionBanner($id)->get();
        $data = $this->getData($banner, $views);
        $now = Carbon::now()->toDateTimeString();
        $bannerName =  strtolower(str_replace(' ', '_', $banner->title));
        $fileName = 'solution_banner_' . $bannerName . '_view_reports_' . $now . '.xlsx';
        $exportViews = new ExportBannerReports($data);

        return Excel::download($exportViews, $fileName);
    }


    // Home page banners


    /**
     *   Created by : Mikiyas Birhanu
     *   Uses :  to load banners impression report
     *   @param int $id
     *   @return Response
     */
    public function homeClicksReport($id)
    {
        $banner = Banner::find($id);


        if (!$banner) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => "Invalid solution banner id"
            ], 401);
        }
        $views = BannerClick::ofBanner($id)->get();
        $data = $this->getData($banner, $views);
        $downloadLink = route('home_banner_clicks_download', ['id' => $id]);
        $data->downloadLink = $downloadLink;

        return view('backend.banner_reports.index', ["data" => $data]);
    }

    /**
     *   Created by : Mikiyas Birhanu
     *   Uses :  to load banners impression report
     *   @param $id
     *   @return Response
     */
    public function homeClicksReportDownload($id)
    {
        $banner = Banner::find($id);
        if (!$banner) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => "Invalid home banner id"
            ], 401);
        }

        $views = BannerClick::ofBanner($id)->get();
        $data = $this->getData($banner, $views);
        $now = Carbon::now()->toDateTimeString();
        $bannerName =  strtolower(str_replace(' ', '_', $banner->title));

        $fileName = 'home_banner_' . $bannerName . '_clicks_report_' . $now . '.xlsx';
        $exportViews = new ExportBannerReports($data);

        return Excel::download($exportViews, $fileName);
    }


    /**
     *   Created by : Mikiyas Birhanu
     *   Uses :  to load banners impression report
     *   @param int $id
     *   @return Response
     */
    public function homeViewsReport($id)
    {
        $banner = Banner::find($id);


        if (!$banner) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => "Invalid home banner id"
            ], 401);
        }

        $views = BannerView::ofBanner($id)->get();

        $data = $this->getData($banner, $views);
        $downloadLink = route('home_banner_views_download', ['id' => $id]);
        $data->downloadLink = $downloadLink;

        return view('backend.banner_reports.index', ["data" => $data]);
    }

    /**
     *   Created by : Mikiyas Birhanu
     *   Uses :  to load banners impression report
     *   @param $id
     *   @return Response
     */
    public function homeViewsReportDownload($id)
    {
        $banner = Banner::find($id);
        if (!$banner) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => "Invalid home banner id"
            ], 401);
        }

        $views = BannerView::ofBanner($id)->get();
        $data = $this->getData($banner, $views);
        $now = Carbon::now()->toDateTimeString();
        $bannerName =  strtolower(str_replace(' ', '_', $banner->title));

        $fileName = 'home_banner_' . $bannerName . '_views_reports_' . $now . '.xlsx';
        $exportViews = new ExportBannerReports($data);

        return Excel::download($exportViews, $fileName);
    }
    // End of home page banners



    public function getData($banner, $clickView)
    {

        $startDate = Carbon::parse($banner->start_date_time ?? $banner->created_at)->toDateTimeString();
        $endDate = $banner->end_date_time ? Carbon::parse($banner->end_date_time)->toDateTimeString() : Carbon::now()->toDateTimeString();

        $data = new stdClass;
        $data->id = $banner->id;
        $data->start_date = $startDate;
        $data->end_date = $endDate;
        $data->data = $clickView->map(function ($click) {
            $newData = new stdClass;
            $newData->user_name = $click->user->name;
            $newData->email = $click->user->email;
            $newData->date = $click->created_at;
            return $newData;
        });
        return $data;
    }
}
