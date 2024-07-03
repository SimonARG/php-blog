<?php

namespace App\Models;

class Report
{
    protected $db;
    protected $reportsPerPage;

    public function __construct()
    {
        // Use the global database instance
        $this->db = $GLOBALS['db'];

        $this->reportsPerPage = $GLOBALS['config']['reports_per_page'];
    }

    public function getAllReportsSortNew($currentPage = 1)
    {
        $offset = ($currentPage - 1) * $this->reportsPerPage;
        
        $sql = "SELECT reports.id,
                    reports.comment,
                    reports.created_at,
                    reports.reviewed,
                    reports.updated_at,
                    reporter.name reporter,
                    reporter.id reporter_id,
                    reviewer.name reviewer,
                    reviewer.id reviewer_id,
                    CASE
                        WHEN rr.post_id IS NOT NULL THEN 'Post'
                        WHEN rr.comment_id IS NOT NULL THEN 'Comment'
                        WHEN rr.user_id IS NOT NULL THEN 'User'
                    END AS resource_type,
                    CASE
                        WHEN rr.post_id IS NOT NULL THEN rr.post_id
                        WHEN rr.comment_id IS NOT NULL THEN rr.comment_id
                        WHEN rr.user_id IS NOT NULL THEN rr.user_id
                    END AS resource_id
                FROM reports
                LEFT JOIN users reporter ON reports.reported_by = reporter.id
                LEFT JOIN users reviewer ON reports.reviewed_by = reviewer.id
                LEFT JOIN reported_resources rr ON reports.resource_id = rr.id
                ORDER BY reports.created_at DESC
                LIMIT :offset, :limit;";
        
        // Bind parameters with explicit data types
        return $this->db->fetchAll($sql, [
            ':limit' => $this->reportsPerPage,
            ':offset' => $offset
        ], [
            ':limit' => \PDO::PARAM_INT,
            ':offset' => \PDO::PARAM_INT
        ]);
    }

    public function getAllReportsSortUnreviewed($currentPage = 1)
    {
        $offset = ($currentPage - 1) * $this->reportsPerPage;
        
        $sql = "SELECT reports.id,
                    reports.comment,
                    reports.created_at,
                    reports.reviewed,
                    reports.updated_at,
                    reporter.name reporter,
                    reporter.id reporter_id,
                    reviewer.name reviewer,
                    reviewer.id reviewer_id,
                    CASE
                        WHEN rr.post_id IS NOT NULL THEN 'Post'
                        WHEN rr.comment_id IS NOT NULL THEN 'Comment'
                        WHEN rr.user_id IS NOT NULL THEN 'User'
                    END AS resource_type,
                    CASE
                        WHEN rr.post_id IS NOT NULL THEN rr.post_id
                        WHEN rr.comment_id IS NOT NULL THEN rr.comment_id
                        WHEN rr.user_id IS NOT NULL THEN rr.user_id
                    END AS resource_id
                FROM reports
                LEFT JOIN users reporter ON reports.reported_by = reporter.id
                LEFT JOIN users reviewer ON reports.reviewed_by = reviewer.id
                LEFT JOIN reported_resources rr ON reports.resource_id = rr.id
                ORDER BY reports.reviewed ASC
                LIMIT :offset, :limit;";
        
        // Bind parameters with explicit data types
        return $this->db->fetchAll($sql, [
            ':limit' => $this->reportsPerPage,
            ':offset' => $offset
        ], [
            ':limit' => \PDO::PARAM_INT,
            ':offset' => \PDO::PARAM_INT
        ]);
    }

    public function getReportCount()
    {
        $sql = "SELECT COUNT(*) FROM reports";
        $result = $this->db->fetch($sql)['COUNT(*)'];

        return $result ? $result : 0;
    }

    public function getUnreviewedReportCount()
    {
        $sql = "SELECT COUNT(*) FROM reports WHERE reviewed = 0";
        $result = $this->db->fetch($sql)['COUNT(*)'];

        return $result ? $result : 0;
    }
}