<?php

namespace App\Models;

use App\Models\Model;

class Report extends Model
{
    protected $reportsPerPage;

    public function __construct()
    {
        parent::__construct();
        $this->reportsPerPage = $GLOBALS['config']['reports_per_page'];
    }

    public function getAllReportsSortNew(int $currentPage = 1) : array|bool
    {
        $offset = ($currentPage - 1) * $this->reportsPerPage;
        
        $sql = "SELECT 
                    reports.id,
                    reports.comment,
                    reports.created_at,
                    reports.reviewed,
                    reports.updated_at,
                    reporter.name AS reporter,
                    reporter.id AS reporter_id,
                    reviewer.name AS reviewer,
                    reviewer.id AS reviewer_id,
                    CASE
                        WHEN rr.post_id IS NOT NULL THEN 'Post'
                        WHEN rr.comment_id IS NOT NULL THEN 'Comment'
                        WHEN rr.user_id IS NOT NULL THEN 'User'
                    END AS resource_type,
                    CASE
                        WHEN rr.post_id IS NOT NULL THEN rr.post_id
                        WHEN rr.comment_id IS NOT NULL THEN rr.comment_id
                        WHEN rr.user_id IS NOT NULL THEN rr.user_id
                    END AS resource_id,
                    JSON_ARRAYAGG(
                        JSON_OBJECT('consequence', consequences.consequence)
                    ) AS mod_actions
                FROM 
                    reports
                LEFT JOIN 
                    users reporter ON reports.reported_by = reporter.id
                LEFT JOIN 
                    users reviewer ON reports.reviewed_by = reviewer.id
                LEFT JOIN 
                    reported_resources rr ON reports.resource_id = rr.id
                LEFT JOIN 
                    mod_actions ON reports.id = mod_actions.resource_id
                LEFT JOIN 
                    consequences ON mod_actions.consequence_id = consequences.id
                GROUP BY
                    reports.id,
                    reports.comment,
                    reports.created_at,
                    reports.reviewed,
                    reports.updated_at,
                    reporter.name,
                    reporter.id,
                    reviewer.name,
                    reviewer.id,
                    rr.post_id,
                    rr.comment_id,
                    rr.user_id
                ORDER BY 
                    reports.created_at DESC
                LIMIT 
                    :offset, :limit;";
        
        // Bind parameters with explicit data types
        $result = $this->db->fetchAll($sql, [
            ':limit' => $this->reportsPerPage,
            ':offset' => $offset
        ], [
            ':limit' => \PDO::PARAM_INT,
            ':offset' => \PDO::PARAM_INT
        ]);

        return $result ? $result : false;
    }

    public function getAllReportsSortUnreviewed(int $currentPage = 1) : array|bool
    {
        $offset = ($currentPage - 1) * $this->reportsPerPage;
        
        $sql = "SELECT 
                    reports.id,
                    reports.comment,
                    reports.created_at,
                    reports.reviewed,
                    reports.updated_at,
                    reporter.name AS reporter,
                    reporter.id AS reporter_id,
                    reviewer.name AS reviewer,
                    reviewer.id AS reviewer_id,
                    CASE
                        WHEN rr.post_id IS NOT NULL THEN 'Post'
                        WHEN rr.comment_id IS NOT NULL THEN 'Comment'
                        WHEN rr.user_id IS NOT NULL THEN 'User'
                    END AS resource_type,
                    CASE
                        WHEN rr.post_id IS NOT NULL THEN rr.post_id
                        WHEN rr.comment_id IS NOT NULL THEN rr.comment_id
                        WHEN rr.user_id IS NOT NULL THEN rr.user_id
                    END AS resource_id,
                    JSON_ARRAYAGG(
                        JSON_OBJECT('consequence', consequences.consequence)
                    ) AS mod_actions
                FROM 
                    reports
                LEFT JOIN 
                    users reporter ON reports.reported_by = reporter.id
                LEFT JOIN 
                    users reviewer ON reports.reviewed_by = reviewer.id
                LEFT JOIN 
                    reported_resources rr ON reports.resource_id = rr.id
                LEFT JOIN 
                    mod_actions ON reports.id = mod_actions.resource_id
                LEFT JOIN 
                    consequences ON mod_actions.consequence_id = consequences.id
                WHERE
                reviewed = 0
                GROUP BY
                    reports.id,
                    reports.comment,
                    reports.created_at,
                    reports.reviewed,
                    reports.updated_at,
                    reporter.name,
                    reporter.id,
                    reviewer.name,
                    reviewer.id,
                    rr.post_id,
                    rr.comment_id,
                    rr.user_id
                ORDER BY 
                    reports.created_at DESC
                LIMIT 
                    :offset, :limit;";
        
        // Bind parameters with explicit data types
        $result = $this->db->fetchAll($sql, [
            ':limit' => $this->reportsPerPage,
            ':offset' => $offset
        ], [
            ':limit' => \PDO::PARAM_INT,
            ':offset' => \PDO::PARAM_INT
        ]);

        return $result ? $result : false;
    }

    public function getReportedResourceId(string $type, int $resourceId) : array|null
    {
        $columnName = $type . "_id";
    
        $sql = "SELECT id FROM reported_resources WHERE ($columnName) = :resource_id;";

        $result = $this->db->fetch($sql, [
            ':resource_id' => $resourceId
        ]);

        return $result ? $result : false;
    }

    public function getReportCount() : int|null
    {
        $sql = "SELECT COUNT(*) FROM reports";
        $result = $this->db->fetch($sql)['COUNT(*)'];

        return $result ? $result : false;
    }

    public function getUnreviewedReportCount() : int|null
    {
        $sql = "SELECT COUNT(*) FROM reports WHERE reviewed = 0";
        $result = $this->db->fetch($sql)['COUNT(*)'];

        return $result ? $result : false;
    }

    public function createReportedResource(string $type, int $resourceId) : object|null
    {
        $columnName = $type . "_id";
    
        $sql = "INSERT INTO reported_resources ($columnName) VALUES (:resource_id)";

        $result = $this->db->query($sql, [
            ':resource_id' => $resourceId
        ]);

        return $result ? $result : false;
    }

    public function createReport(array $data) : object|null
    {
        $fields = ['resource_id', 'reported_by'];
        $placeholders = [':resource_id', ':reported_by'];
        $params = [
            ':resource_id' => $data['reported_id'],
            ':reported_by' => $data['reported_by'],
        ];
    
        if (isset($data['comment'])) {
            $fields[] = 'comment';
            $placeholders[] = ':comment';
            $params[':comment'] = $data['comment'];
        }
    
        $sql = "INSERT INTO reports (" . implode(', ', $fields) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";
    
        $result = $this->db->query($sql, $params);

        return $result ? $result : false;
    }

    public function get(int $id): array|bool
    {
        $sql = "SELECT * FROM reports
                WHERE id = :id";

        $result = $this->db->fetch($sql, [':id' => $id]);

        return $result ? $result : false;
    }
}