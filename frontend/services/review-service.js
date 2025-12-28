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

    deleteReview: function(id, callback) {
        $.blockUI({ message: '<h3>Deleting review...</h3>' });
        RestClient.delete('review/' + id, null, function(response) {
            $.unblockUI();
            toastr.success("Review deleted successfully");
            if (callback) callback(response);
        }, function(jqXHR, status, error) {
            $.unblockUI();
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to delete review';
            toastr.error(msg);
        });
    }
};