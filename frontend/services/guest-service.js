let GuestService = {
    getAllGuests: function(callback) {
        RestClient.get("guest", function(data) {
            console.log('Fetched guests:', data);
            const guests = Array.isArray(data) ? data : (data.data || []);
            if (callback) callback(guests);
        }, function (jqXHR, status, error) {
            console.error('Error fetching guests:', error);
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to load guests';
            toastr.error(msg);
        });
    },

    getGuestById: function(id, callback) {
        RestClient.get('guest/' + id, function (data) {
            if (callback) callback(data);
        }, function (jqXHR, status, error) {
            console.error('Error fetching guest');
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to load guest details';
            toastr.error(msg);
        });
    },

    createGuest: function(guest, callback) {
        console.log('Creating guest:', guest);
        RestClient.post('guest', guest, function(response) {
            toastr.success("Guest created successfully");
            if (callback) callback(response);
        }, function(jqXHR, status, error) {
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to create guest';
            toastr.error(msg);
        });
    },

    updateGuest: function(id, guest, callback) {
        RestClient.put('guest/' + id, guest, function (data) {
            toastr.success("Guest updated successfully");
            if (callback) callback(data);
        }, function (jqXHR, status, error) {
            console.error('Error updating guest');
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to update guest';
            toastr.error(msg);
        });
    },

    deleteGuest: function(id, callback) {
        RestClient.delete('guest/' + id, null, function(response) {
            toastr.success("Guest deleted successfully");
            if (callback) callback(response);
        }, function(jqXHR, status, error) {
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to delete guest';
            toastr.error(msg);
        });
    },

    getGuestByEmail: function(email, callback) {
        RestClient.get('guest?email=' + email, function(data) {
            if (callback) callback(data);
        }, function (jqXHR, status, error) {
            console.error('Error fetching guest by email');
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to find guest';
            toastr.error(msg);
        });
    }
};
