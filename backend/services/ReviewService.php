<?php
require_once __DIR__ . '/../dao/ReviewDao.php';
require_once __DIR__ . '/BaseService.php';

class ReviewService extends BaseService {
    public function __construct() {
        $dao = new ReviewDao();
        parent::__construct($dao);
    }

    public function createReview($data) {
        if(empty($data['guest_id']) || empty($data['rating']) || empty($data['title'])) {
            throw new Exception("Guest ID, Rating, and Title are required to create a review.");
        }

        if(!is_numeric($data['rating']) || $data['rating'] < 1 || $data['rating'] > 5) {
            throw new Exception("Rating must be a number between 1 and 5.");
        }

        if(empty($data['comment'])) {
            $data['comment'] = null;
        }

        return $this->create($data);
    }

    public function listReviewsByRating() {
        return $this->dao->listByRating();
    }
}
?>