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

    public function getAllReportsSortNew(int $currentPage = 1): array|bool
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
                    MAX(mod_actions.motive) AS motive,
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
                    author.name AS resource_owner,
                    author.id AS owner_id,
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
                    mod_actions ON reports.id = mod_actions.report_id
                LEFT JOIN 
                    consequences ON mod_actions.consequence_id = consequences.id
                LEFT JOIN 
                posts ON rr.post_id = posts.id
                LEFT JOIN 
                    comments ON rr.comment_id = comments.id
                LEFT JOIN 
                    users author ON 
                        CASE
                            WHEN rr.post_id IS NOT NULL THEN posts.user_id
                            WHEN rr.comment_id IS NOT NULL THEN comments.user_id
                            ELSE rr.user_id
                        END = author.id
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
                    rr.user_id,
                    author.name
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

    public function getAllReportsSortUnreviewed(int $currentPage = 1): array|bool
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
                    MAX(mod_actions.motive) AS motive,
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
                    author.name AS resource_owner,
                    author.id AS owner_id,
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
                    mod_actions ON reports.id = mod_actions.report_id
                LEFT JOIN 
                    consequences ON mod_actions.consequence_id = consequences.id
                LEFT JOIN 
                posts ON rr.post_id = posts.id
                LEFT JOIN 
                    comments ON rr.comment_id = comments.id
                LEFT JOIN 
                    users author ON 
                        CASE
                            WHEN rr.post_id IS NOT NULL THEN posts.user_id
                            WHEN rr.comment_id IS NOT NULL THEN comments.user_id
                            ELSE rr.user_id
                        END = author.id
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
                    rr.user_id,
                    author.name
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

    public function getReportedResourceId(string $type, int $resourceId): array|bool
    {
        $columnName = $type . "_id";

        $sql = "SELECT id FROM reported_resources WHERE ($columnName) = :resource_id;";

        $result = $this->db->fetch($sql, [
            ':resource_id' => $resourceId
        ]);

        return $result ? $result : false;
    }

    public function getReportCount(): int|bool
    {
        $sql = "SELECT COUNT(*) FROM reports";
        $result = $this->db->fetch($sql)['COUNT(*)'];

        return $result ? $result : false;
    }

    public function getUnreviewedReportCount(): int|bool
    {
        $sql = "SELECT COUNT(*) FROM reports WHERE reviewed = 0";
        $result = $this->db->fetch($sql)['COUNT(*)'];

        return $result ? $result : false;
    }

    public function createReportedResource(string $type, int $resourceId): object|bool
    {
        $columnName = $type . "_id";

        $sql = "INSERT INTO reported_resources ($columnName) VALUES (:resource_id)";

        $result = $this->db->query($sql, [
            ':resource_id' => $resourceId
        ]);

        return $result ? $result : false;
    }

    public function createReport(array $data): object|bool
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
                    MAX(mod_actions.motive) AS motive,
                    MAX(mod_actions.id) AS mod_action_id,
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
                    author.name AS resource_owner,
                    author.id AS owner_id,
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
                    mod_actions ON reports.id = mod_actions.report_id
                LEFT JOIN 
                    consequences ON mod_actions.consequence_id = consequences.id
                LEFT JOIN 
                    posts ON rr.post_id = posts.id
                LEFT JOIN 
                    comments ON rr.comment_id = comments.id
                LEFT JOIN 
                    users author ON 
                        CASE
                            WHEN rr.post_id IS NOT NULL THEN posts.user_id
                            WHEN rr.comment_id IS NOT NULL THEN comments.user_id
                            ELSE rr.user_id
                        END = author.id
                WHERE reports.id = :id
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
                    rr.user_id,
                    author.name";

        $result = $this->db->fetch($sql, [':id' => $id]);

        return $result ? $result : false;
    }

    public function reset(int $id, int $modActionId): object|bool
    {
        $sql = "UPDATE reports SET reviewed = 0, reviewed_by = NULL WHERE id = :id;";

        $result = $this->db->query($sql, [':id' => $id]);

        $sql = "DELETE FROM mod_actions WHERE id = :modActionId;";

        $result = $this->db->query($sql, [
            ':modActionId' => $modActionId
        ]);

        return $result ? $result : false;
    }

    public function setAsReviewed(int $id, int $reviewerId): object|bool
    {
        $sql = "UPDATE reports SET reviewed = 1, reviewed_by = :reviewer WHERE id = :id; ";

        $result = $this->db->query($sql, [
            ':id' => $id,
            ':reviewer' => $reviewerId
        ]);

        return $result ? $result : false;
    }

    public function createModAction(int $reviewerId, int $consequenceId, int $reportId, string $motive): object|bool
    {
        $sql = "INSERT INTO mod_actions (reviewer_id, motive, report_id, consequence_id) VALUES (:reviewer_id, :motive, :report_id, :consequence_id);";

        $result = $this->db->query($sql, [
            ':reviewer_id' => $reviewerId,
            ':consequence_id' => $consequenceId,
            ':report_id' => $reportId,
            ':motive' => $motive
        ]);

        return $result ? $result : false;
    }
}
