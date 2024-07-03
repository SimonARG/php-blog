<?php

namespace App\Controllers;

use DateTime;
use App\Models\Report;
use App\Models\Comment;

class ReportController
{
    protected $baseUrl;
    protected $commentModel;
    protected $reportModel;
    protected $reportsPerPage;

    public function __construct()
    {
        $this->baseUrl = $GLOBALS['config']['base_url'];
        $this->commentModel = new Comment();
        $this->reportModel = new Report();
        $this->reportsPerPage = $GLOBALS['config']['reports_per_page'];
    }

    public function index($request)
    {
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $reports = '';

        if (!(isset($request['sort'])) || !$request['sort']) {
            $reports = $this->reportModel->getAllReportsSortNew($currentPage);
        } else {
            $reports = $this->reportModel->getAllReportsSortUnreviewed($currentPage);
        }

        $totalReports = $this->reportModel->getReportCount();

        $totalPages = ceil($totalReports / $this->reportsPerPage);

        $unreviewed = $this->reportModel->getUnreviewedReportCount();
;
        foreach ($reports as $key => $report) {
            $reportDate = new DateTime($report['created_at']);
            $reportStrdate = $reportDate->format('Y/m/d H:i');
            $reports[$key]['created_at'] = $reportStrdate;
    
            if (isset($report['updated_at'])) {
                $reportUpDate = new DateTime($report['updated_at']);
                $reportUpStrdate = $reportUpDate->format('Y/m/d H:i');
                $reports[$key]['updated_at'] = $reportUpStrdate;
            }

            if (isset($report['mod_actions'])) {
                $reports[$key]['mod_actions'] = json_decode($report['mod_actions'], true);
            }
        }

        return view('admin.reports', [
            'reports' => $reports,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'unreviewed' => $unreviewed
        ]);
    }
}