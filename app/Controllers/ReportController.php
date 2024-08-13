<?php

namespace App\Controllers;

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

    public function index(array $request) : void
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

        $reports = $this->helpers->formatDates($reports);
        
        foreach ($reports as $key => $report) {
            if (isset($report['mod_actions'])) {
                $reports[$key]['mod_actions'] = json_decode($report['mod_actions'], true);
            }
        }

        $this->helpers->view('admin.reports', [
            'reports' => $reports,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'unreviewed' => $unreviewed
        ]);
    }

    public function store(array $request) : void
    {
        $this->security->verifyCsrf($request['csrf'] ?? '');
        
        // Sanitize
        $comment = htmlspecialchars($request['comment'] ?? '');

        $type = htmlspecialchars($request['type']);
        $allowedTypes = ['post', 'comment', 'user'];
        if (!in_array($type, $allowedTypes)) {
            $this->helpers->setPopup('Error al reportar el post');

            header('Location: /');
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

            header('Location: ' . $url);
        }

        if ($type == 'post') {
            $this->helpers->setPopup('Post reportado');
        } else if ($type == 'comment') {
            $this->helpers->setPopup('Comentario reportado');
        } else if ($type == 'user') {
            $this->helpers->setPopup('Usuario reportado');
        }

        header('Location: ' . $url);
    }

    public function show(int $id): void
    {
        $report = $this->report->get($id);

        $report = $this->helpers->formatDates($report);

        if (isset($report['mod_actions'])) {
            $report['mod_actions'] = json_decode($report['mod_actions'], true);
        }

        $this->helpers->view('admin.report', [
            'report' => $report
        ]);
    }
}