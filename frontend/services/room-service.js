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

    getRoomById: function(id, callback) {
        RestClient.get('room/' + id, function (data) {
            if (callback) callback(data);
        }, function (jqXHR, status, error) {
            console.error('Error fetching room');
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to load room details';
            toastr.error(msg);
        });
    },

    getRoomsByStatus: function(status, callback) {
        RestClient.get('room?status=' + status, function(data) {
            if (callback) callback(data);
        }, function (jqXHR, s, error) {
            console.error('Error fetching rooms by status');
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to load rooms';
            toastr.error(msg);
        });
    },

    checkRoomAvailability: function(id, callback) {
        RestClient.get('room/' + id + '/availability', function(data) {
            if (callback) callback(data);
        }, function (jqXHR, status, error) {
            console.error('Error checking room availability');
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to check availability';
            toastr.error(msg);
        });
    },

    createRoom: function(room, callback) {
        RestClient.post('room', room, function(response) {
            toastr.success("Room created successfully");
            if (callback) callback(response);
        }, function(jqXHR, status, error) {
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to create room';
            toastr.error(msg);
        });
    },

    updateRoom: function(id, room, callback) {
        RestClient.put('room/' + id, room, function (data) {
            toastr.success("Room updated successfully");
            if (callback) callback(data);
        }, function (jqXHR, status, error) {
            console.error('Error updating room');
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to update room';
            toastr.error(msg);
        });
    },

    patchRoom: function(id, room, callback) {
        RestClient.patch('room/' + id, room, function (data) {
            toastr.success("Room updated successfully");
            if (callback) callback(data);
        }, function (jqXHR, status, error) {
            console.error('Error updating room');
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to update room';
            toastr.error(msg);
        });
    },

    deleteRoom: function(id, callback) {
        RestClient.delete('room/' + id, null, function(response) {
            toastr.success("Room deleted successfully");
            if (callback) callback(response);
        }, function(jqXHR, status, error) {
            const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || 'Failed to delete room';
            toastr.error(msg);
        });
    }
};
