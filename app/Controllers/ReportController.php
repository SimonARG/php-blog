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

    public function create($request)
    {
        // Sanitize
        $comment = htmlspecialchars($request['comment'] ?? '');

        $type = htmlspecialchars($request['type']);
        $allowedTypes = ['post', 'comment', 'user'];
        if (!in_array($type, $allowedTypes)) {
            $message = 'Error al reportar el post';

            return route('/', ['popup_content' => $message]);
        }
        
        $resourceId = $request['id'];
        $reportedBy = $request['user_id'];

        $data = [
            'type' => $type,
            'resource_id' => $resourceId,
            'reported_by' => $reportedBy
        ];
        
        if (($comment)) {
            $data['comment'] = $comment;
        }

        // Attempt to create the reported resource
        $result = $this->reportModel->createReportedResource($type, $resourceId);

        // Is user already reported the resource, return with error message
        if (!$result) {
            $message = 'Ya has reportado este ' . $type;

            return route('/', ['popup_content' => $message]);
        }

        // Else, create the report
        $result = $this->reportModel->createReport($data);

        $message = '';

        if (!$result) {
            $message = 'Error al reportar el ' . $type;

            return route('/', ['popup_content' => $message]);
        }

        if ($type == 'post') {
            $message = 'Post reportado';
        } else if ($type == 'comment') {
            $message = 'Comentario reportado';
        } else if ($type == 'user') {
            $message = 'Usuario reportado';
        }

        return route('/', ['popup_content' => $message]);
    }
}