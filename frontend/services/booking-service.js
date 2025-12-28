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
        $.blockUI({ message: '<h3>Loading booking details...</h3>' });
        RestClient.get('booking/' + id, function (data) {
            $.unblockUI();
            if (callback) callback(data);
        }, function (jqXHR, status, error) {
            $.unblockUI();
            console.error('Error fetching booking');
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to load booking details';
            toastr.error(msg);
        });
    },

    createBooking: function(booking, callback) {
        $.blockUI({ message: '<h3>Creating booking...</h3>' });
        RestClient.post('booking', booking, function(response) {
            $.unblockUI();
            toastr.success("Booking created successfully");
            if (callback) callback(response);
        }, function(jqXHR, status, error) {
            $.unblockUI();
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to create booking';
            toastr.error(msg);
        });
    },

    updateBooking: function(id, booking, callback) {
        $.blockUI({ message: '<h3>Updating booking...</h3>' });
        RestClient.put('booking/' + id, booking, function (data) {
            $.unblockUI();
            toastr.success("Booking updated successfully");
            if (callback) callback(data);
        }, function (jqXHR, status, error) {
            $.unblockUI();
            console.error('Error updating booking');
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to update booking';
            toastr.error(msg);
        });
    },

    patchBooking: function(id, booking, callback) {
        $.blockUI({ message: '<h3>Updating booking...</h3>' });
        RestClient.patch('booking/' + id, booking, function (data) {
            $.unblockUI();
            toastr.success("Booking updated successfully");
            if (callback) callback(data);
        }, function (jqXHR, status, error) {
            $.unblockUI();
            console.error('Error updating booking');
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to update booking';
            toastr.error(msg);
        });
    },

    deleteBooking: function(id, callback) {
        $.blockUI({ message: '<h3>Deleting booking...</h3>' });
        RestClient.delete('booking/' + id, null, function(response) {
            $.unblockUI();
            toastr.success("Booking deleted successfully");
            if (callback) callback(response);
        }, function(jqXHR, status, error) {
            $.unblockUI();
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to delete booking';
            toastr.error(msg);
        });
    }
};