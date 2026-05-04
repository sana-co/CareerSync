<?php
class Bookmark
{
    use Model;
    protected $table = 'bookmarks';
    protected $allowedColumns = [
        'bm_id',
        'user_id',
        'job_id',
        'created_at',
    ];

    public function getBmStatus($user_id, $job_id)
    {
        $query = "SELECT * FROM bookmarks
                  WHERE user_id = ?
                  AND job_id = ? 
                  LIMIT 1";

        $result = $this->query($query, [$user_id, $job_id]);

        return !empty($result) ? $result[0] : null;
    }

    public function addBookmark($user_id, $job_id)
    {
        return $this->insert([
            'user_id' => $user_id,
            'job_id'  => $job_id,
        ]);
    }

    public function removeBookmark($user_id, $job_id)
    {
        $query = "DELETE FROM bookmarks
                  WHERE user_id = ?
                  AND job_id = ?";

        return $this->query($query, [$user_id, $job_id]);
    }

    public function getMyBookmarks($user_id)
    {
        $query = "  SELECT 
                    b.bm_id,
                    b.job_id,
                    j.posTitle,
                    c.companyName,
                    c.company_photo_path
                    FROM bookmarks b
                    INNER JOIN jobPost j 
                    ON b.job_id = j.job_id
                    INNER JOIN company c 
                    ON j.company_id = c.user_id
                    WHERE b.user_id = ?
                    ORDER BY b.created_at DESC
                ";

        return $this->query($query, [$user_id]);
    }
}
