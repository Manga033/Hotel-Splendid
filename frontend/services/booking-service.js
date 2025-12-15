let BookingService = {
    getAllBookings: function(callback) {
        RestClient.get("booking", function(data) {
            const bookings = Array.isArray(data) ? data : (data.data || []);
            if (callback) callback(bookings);
        }, function (jqXHR, status, error) {
            console.error('Error fetching bookings:', error);
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to load bookings';
            toastr.error(msg);
        });
    },

    getBookingById: function(id, callback) {
        RestClient.get('booking/' + id, function (data) {
            if (callback) callback(data);
        }, function (jqXHR, status, error) {
            console.error('Error fetching booking');
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to load booking details';
            toastr.error(msg);
        });
    },

    createBooking: function(booking, callback) {
        RestClient.post('booking', booking, function(response) {
            toastr.success("Booking created successfully");
            if (callback) callback(response);
        }, function(jqXHR, status, error) {
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to create booking';
            toastr.error(msg);
        });
    },

    updateBooking: function(id, booking, callback) {
        RestClient.put('booking/' + id, booking, function (data) {
            toastr.success("Booking updated successfully");
            if (callback) callback(data);
        }, function (jqXHR, status, error) {
            console.error('Error updating booking');
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to update booking';
            toastr.error(msg);
        });
    },

    patchBooking: function(id, booking, callback) {
        RestClient.patch('booking/' + id, booking, function (data) {
            toastr.success("Booking updated successfully");
            if (callback) callback(data);
        }, function (jqXHR, status, error) {
            console.error('Error updating booking');
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to update booking';
            toastr.error(msg);
        });
    },

    deleteBooking: function(id, callback) {
        RestClient.delete('booking/' + id, null, function(response) {
            toastr.success("Booking deleted successfully");
            if (callback) callback(response);
        }, function(jqXHR, status, error) {
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to delete booking';
            toastr.error(msg);
        });
    },

    listByCreatedAt: function(callback) {
        RestClient.get('booking?order=created_at', function(data) {
            if (callback) callback(data);
        }, function (jqXHR, status, error) {
            console.error('Error fetching bookings');
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to load bookings';
            toastr.error(msg);
        });
    }
};
