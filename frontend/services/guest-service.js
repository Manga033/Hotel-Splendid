let GuestService = {
    // MODEL - Data operations
    getAllGuests: function(callback) {
        $.blockUI({ message: '<h3>Loading guests...</h3>' });
        RestClient.get("guest", function(data) {
            $.unblockUI();
            console.log('Fetched guests:', data);
            const guests = Array.isArray(data) ? data : (data.data || []);
            if (callback) callback(guests);
        }, function (jqXHR, status, error) {
            $.unblockUI();
            console.error('Error fetching guests:', error);
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to load guests';
            toastr.error(msg);
        });
    },

    getGuestById: function(id, callback) {
        $.blockUI({ message: '<h3>Loading guest details...</h3>' });
        RestClient.get('guest/' + id, function (data) {
            $.unblockUI();
            if (callback) callback(data);
        }, function (jqXHR, status, error) {
            $.unblockUI();
            console.error('Error fetching guest');
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to load guest details';
            toastr.error(msg);
        });
    },

    createGuest: function(guest, callback) {
        console.log('Creating guest:', guest);
        $.blockUI({ message: '<h3>Creating guest...</h3>' });
        RestClient.post('guest', guest, function(response) {
            $.unblockUI();
            toastr.success("Guest created successfully");
            if (callback) callback(response);
        }, function(jqXHR, status, error) {
            $.unblockUI();
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to create guest';
            toastr.error(msg);
        });
    },

    updateGuest: function(id, guest, callback) {
        $.blockUI({ message: '<h3>Updating guest...</h3>' });
        RestClient.put('guest/' + id, guest, function (data) {
            $.unblockUI();
            toastr.success("Guest updated successfully");
            if (callback) callback(data);
        }, function (jqXHR, status, error) {
            $.unblockUI();
            console.error('Error updating guest');
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to update guest';
            toastr.error(msg);
        });
    },

    deleteGuest: function(id, callback) {
        $.blockUI({ message: '<h3>Deleting guest...</h3>' });
        RestClient.delete('guest/' + id, null, function(response) {
            $.unblockUI();
            toastr.success("Guest deleted successfully");
            if (callback) callback(response);
        }, function(jqXHR, status, error) {
            $.unblockUI();
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to delete guest';
            toastr.error(msg);
        });
    },

    getGuestByEmail: function(email, callback) {
        $.blockUI({ message: '<h3>Searching guest...</h3>' });
        RestClient.get('guest?email=' + email, function(data) {
            $.unblockUI();
            if (callback) callback(data);
        }, function (jqXHR, status, error) {
            $.unblockUI();
            console.error('Error fetching guest by email');
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to find guest';
            toastr.error(msg);
        });
    }
};