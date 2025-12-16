let ReviewService = {
    getAllReviews: function(callback) {
        RestClient.get("review", function(data) {
            if (callback) callback(data);
        }, function (jqXHR, status, error) {
            console.error('Error fetching reviews:', error);
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to load reviews';
            toastr.error(msg);
        });
    },

    getReviewById: function(id, callback) {
        RestClient.get('review/' + id, function (data) {
            if (callback) callback(data);
        }, function (jqXHR, status, error) {
            console.error('Error fetching review');
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to load review details';
            toastr.error(msg);
        });
    },

    createReview: function(review, callback) {
        RestClient.post('review', review, function(response) {
            toastr.success("Review created successfully");
            if (callback) callback(response);
        }, function(jqXHR, status, error) {
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to create review';
            toastr.error(msg);
        });
    },

    updateReview: function(id, review, callback) {
        RestClient.put('review/' + id, review, function (data) {
            toastr.success("Review updated successfully");
            if (callback) callback(data);
        }, function (jqXHR, status, error) {
            console.error('Error updating review');
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to update review';
            toastr.error(msg);
        });
    },

    patchReview: function(id, review, callback) {
        RestClient.patch('review/' + id, review, function (data) {
            toastr.success("Review updated successfully");
            if (callback) callback(data);
        }, function (jqXHR, status, error) {
            console.error('Error updating review');
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to update review';
            toastr.error(msg);
        });
    },

    deleteReview: function(id, callback) {
        RestClient.delete('review/' + id, null, function(response) {
            toastr.success("Review deleted successfully");
            if (callback) callback(response);
        }, function(jqXHR, status, error) {
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to delete review';
            toastr.error(msg);
        });
    },

    listByRating: function(callback) {
        RestClient.get('review?order=rating', function(data) {
            if (callback) callback(data);
        }, function (jqXHR, status, error) {
            console.error('Error fetching reviews');
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to load reviews';
            toastr.error(msg);
        });
    }
};
