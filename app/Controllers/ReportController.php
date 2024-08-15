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
        parent::__construct();

        $this->reportsPerPage = $GLOBALS['config']['reports_per_page'];

        $this->comment = new Comment();
        $this->report = new Report();
    }

    public function index(array $request): void
    {
        if (!$this->security->isElevatedUser()) {
            $this->helpers->setPopup('Operacion no autorizada');

            header('Location: /');

            return;
        }

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

        return;
    }

    public function store(array $request): void
    {
        $url = $request['curr_url'];

        if (!$this->security->verifyCsrf($request['csrf'] ?? '')) {
            $this->helpers->setPopup('Error de seguridad');

            header('Location: ' . $url);

            return;
        }

        if (!$this->security->canReport()) {
            $this->helpers->setPopup('No puedes realizar esta accion');

            header('Location: ' . $url);

            return;
        }

        $this->security->verifyCsrf($request['csrf'] ?? '');

        // Sanitize
        $comment = htmlspecialchars($request['comment'] ?? '');

        $type = htmlspecialchars($request['type']);
        $allowedTypes = ['post', 'comment', 'user'];
        if (!in_array($type, $allowedTypes)) {
            $this->helpers->setPopup('Tipo de recurso desconocido');

            header('Location: /');
        }

        $resourceId = $request['id'];
        $reportedBy = $request['user_id'];

        $data = [
            'type' => $type,
            'reported_by' => $reportedBy
        ];

        if (($comment)) {
            $data['comment'] = $comment;
        }

        $result = $this->report->createReportedResource($type, $resourceId);

        $result = $this->report->getReportedResourceId($type, $resourceId);

        $data['reported_id'] = $result['id'];

        $result = $this->report->createReport($data);

        if (!$result) {
            $this->helpers->setPopup('Ya has reportado este ' . $type);

            header('Location: ' . $url);

            return;
        }

        if ($type == 'post') {
            $this->helpers->setPopup('Post reportado');
        } elseif ($type == 'comment') {
            $this->helpers->setPopup('Comentario reportado');
        } elseif ($type == 'user') {
            $this->helpers->setPopup('Usuario reportado');
        }

        header('Location: ' . $url);

        return;
    }

    public function show(int $id): void
    {
        if (!$this->security->isElevatedUser()) {
            $this->helpers->setPopup('Operacion no autorizada');

            header('Location: /');

            return;
        }

        $report = $this->report->get($id);

        $report = $this->helpers->formatDates($report);

        if (isset($report['mod_actions'])) {
            $report['mod_actions'] = json_decode($report['mod_actions'], true);
        }

        $this->helpers->view('admin.report', [
            'report' => $report
        ]);

        return;
    }

    public function reset(int $id, array $request): void
    {
        if (!$this->security->verifyCsrf($request['csrf'] ?? '')) {
            $this->helpers->setPopup('Error de seguridad');

            header('Location: /');

            return;
        }

        if (!$this->security->isElevatedUser()) {
            $this->helpers->setPopup('Operacion no autorizada');

            header('Location: /');

            return;
        }

        $modActionId = $request['mod-action-id'];

        $this->report->reset($id, $modActionId);

        $report = $this->report->get($id);

        $report = $this->helpers->formatDates($report);

        $this->helpers->setPopup('Reporte reiniciado');

        header('Location: /admin/report/' . $id);

        return;
    }

    public function review(int $id, array $request): void
    {
        if (!$this->security->verifyCsrf($request['csrf'] ?? '')) {
            $this->helpers->setPopup('Error de seguridad');

            header('Location: /');

            return;
        }

        if (!$this->security->isElevatedUser()) {
            $this->helpers->setPopup('Operacion no autorizada');

            header('Location: /');

            return;
        }
        
        $reviewerId = $request['reviewer-id'];

        // Set as reviewed and write reviewer_id
        $this->report->setAsReviewed($id, $reviewerId);

        // Create consequence array
        $consequences = [];
        if (!empty($request['none'])) {
            $consequences[] = $request['none'];
        }
        if (!empty($request['warning'])) {
            $consequences[] = $request['warning'];
        }
        if (!empty($request['modified'])) {
            $consequences[] = $request['modified'];
        }
        if (!empty($request['deleted'])) {
            $consequences[] = $request['deleted'];
        }
        if (!empty($request['restricted'])) {
            $consequences[] = $request['restricted'];
        }
        if (!empty($request['banned'])) {
            $consequences[] = $request['banned'];
        }

        $motive = $request['motive'];

        foreach ($consequences as $consequence) {
            $this->report->createModAction($reviewerId, $consequence, $id, $motive);
        }

        $this->helpers->setPopup('Revision completa');

        header('Location: /admin/report/' . $id);

        return;
    }
}
