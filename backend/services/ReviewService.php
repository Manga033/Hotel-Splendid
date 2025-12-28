<?php
require_once __DIR__ . '/../dao/ReviewDao.php';
require_once __DIR__ . '/BaseService.php';

class ReviewService extends BaseService {
    public function __construct() {
        $dao = new ReviewDao();
        parent::__construct($dao);
    }

    public function createReview($data) {
        $errors = [];

        if (!isset($data['guest_id']) || empty($data['guest_id'])) {
            $errors['guest_id'] = 'Guest ID is required';
        } elseif (!is_numeric($data['guest_id']) || $data['guest_id'] < 1) {
            $errors['guest_id'] = 'Invalid guest ID';
        }

        if (!isset($data['rating']) || $data['rating'] === '') {
            $errors['rating'] = 'Rating is required';
        } elseif (!is_numeric($data['rating']) || $data['rating'] < 1 || $data['rating'] > 5) {
            $errors['rating'] = 'Rating must be between 1 and 5';
        }

        if (!isset($data['title']) || empty(trim($data['title']))) {
            $errors['title'] = 'Review title is required';
        } else {
            $title = trim($data['title']);
            
            if (strlen($title) < 3) {
                $errors['title'] = 'Title must be at least 3 characters';
            }
            
            if (strlen($title) > 150) {
                $errors['title'] = 'Title cannot exceed 150 characters';
            }
        }
        if (isset($data['comment']) && !empty($data['comment'])) {
            if (strlen($data['comment']) > 1000) {
                $errors['comment'] = 'Comment cannot exceed 1000 characters';
            }
        } else {
            $data['comment'] = null;
        }

        if (!empty($errors)) {
            throw new Exception(json_encode([
                'validation_failed' => true,
                'errors' => $errors
            ]));
        }

        $sanitizedData = [
            'guest_id' => (int)$data['guest_id'],
            'rating' => (int)$data['rating'],
            'title' => htmlspecialchars(trim($data['title']), ENT_QUOTES, 'UTF-8'),
            'comment' => $data['comment'] ? htmlspecialchars(trim($data['comment']), ENT_QUOTES, 'UTF-8') : null
        ];

        return $this->dao->createReview($sanitizedData);
    }

    public function listReviewsByRating() {
        return $this->dao->listByRating();
    }
}