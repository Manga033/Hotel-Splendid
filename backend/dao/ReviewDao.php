<?php
require_once __DIR__ . '/BaseDao.php';

class ReviewDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('reviews');
    }

    public function createReview($review) 
    {
        $data = [
            'guest_id' => $review['guest_id'],
            'rating' => $review['rating'],
            'title' => $review['title'],
            'comment' => $review['comment']
        ];
        return $this->insert($data);
    }

    public function deleteReview($id) 
    {
        return $this->delete($id);
    }

    public function updateReview($id, $review) 
    {
        $data = [
            'guest_id' => $review['guest_id'],
            'rating' => $review['rating'],
            'title' => $review['title'],
            'comment' => $review['comment']
        ];

        return $this->update($id, $data);
    }

    public function getReviewById($id) 
    {
        return $this->getById($id);
    }

    public function getAllReviews() 
    {
        return $this->getAll();
    }
}
?>