<?php

namespace App\Controllers;

use DateTime;
use App\Models\Report;
use App\Models\Comment;

class ReportController extends Controller
{
    protected $comment;
    protected $report;
    protected $reportsPerPage;

    public function __construct()
    {
        $this->reportsPerPage = $GLOBALS['config']['reports_per_page'];
        
        parent::__construct();
        $this->comment = new Comment();
        $this->report = new Report();
    }

    public function index($request)
    {
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $reports = '';

        if (!(isset($request['sort'])) || !$request['sort']) {
            $reports = $this->report->getAllReportsSortNew($currentPage);
        } else {
            $reports = $this->report->getAllReportsSortUnreviewed($currentPage);
        }

        $totalReports = $this->report->getReportCount();

        $totalPages = ceil($totalReports / $this->reportsPerPage);

        $unreviewed = $this->report->getUnreviewedReportCount();
        
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

        return $this->helpers->view('admin.reports', [
            'reports' => $reports,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'unreviewed' => $unreviewed
        ]);
    }

    public function create($request)
    {
        $this->security->verifyCsrf($request['csrf'] ?? '');
        
        // Sanitize
        $comment = htmlspecialchars($request['comment'] ?? '');

        $type = htmlspecialchars($request['type']);
        $allowedTypes = ['post', 'comment', 'user'];
        if (!in_array($type, $allowedTypes)) {
            $this->helpers->setPopup('Error al reportar el post');

            return header('Location: /');
        }

        $url = $request['curr_url'];
        
        $resourceId = $request['id'];
        $reportedBy = $request['user_id'];

        $data = [
            'type' => $type,
            'reported_by' => $reportedBy
        ];
        
        if (($comment)) {
            $data['comment'] = $comment;
        }

        // Attempt to create the reported resource
        $result = $this->report->createReportedResource($type, $resourceId);

        // Else, create the report
        $result = $this->report->getReportedResourceId($type, $resourceId);

        $data['reported_id'] = $result['id'];

        $result = $this->report->createReport($data);

        if (!$result) {
            $this->helpers->setPopup('Ya has reportado este ' . $type);

            return header('Location: ' . $url);
        }

        if ($type == 'post') {
            $this->helpers->setPopup('Post reportado');
        } else if ($type == 'comment') {
            $this->helpers->setPopup('Comentario reportado');
        } else if ($type == 'user') {
            $this->helpers->setPopup('Usuario reportado');
        }

        return header('Location: ' . $url);
    }
}