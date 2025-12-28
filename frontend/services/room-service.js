let RoomService = {
    getAllRooms: function(callback) {
        RestClient.get("room", function(data) {
            if (callback) callback(data);
        }, function (jqXHR, status, error) {
            console.error('Error fetching rooms:', error);
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to load rooms';
            toastr.error(msg);
        });
    },

    createRoom: function(room, callback) {
        $.blockUI({ message: '<h3>Creating room...</h3>' });
        RestClient.post('room', room, function(response) {
            $.unblockUI();
            toastr.success("Room created successfully");
            if (callback) callback(response);
        }, function(jqXHR, status, error) {
            $.unblockUI();
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to create room';
            toastr.error(msg);
        });
    },

    deleteRoom: function(id, callback) {
        $.blockUI({ message: '<h3>Deleting room...</h3>' });
        RestClient.delete('room/' + id, null, function(response) {
            $.unblockUI();
            toastr.success("Room deleted successfully");
            if (callback) callback(response);
        }, function(jqXHR, status, error) {
            $.unblockUI();
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to delete room';
            toastr.error(msg);
        });
    }
};