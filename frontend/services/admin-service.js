let AdminService = {
  init: function() {
    const user = Utils.getUserFromToken();
    if (!user || user.role !== Constants.ADMIN_ROLE) {
      toastr.error("Access denied. Admins only.");
      window.location.replace("#home");
      return;
    }
    
    this.loadGuests();
    this.setupForms();
  },

  setupForms: function() {
    $("#createGuestForm").validate({
      submitHandler: function(form) {
        const data = Object.fromEntries(new FormData(form).entries());
        $.blockUI({ message: '<h3>Creating guest...</h3>' });
        GuestService.createGuest(data, function() {
          $.unblockUI();
          AdminService.closeModal('createGuestModal');
          AdminService.loadGuests();
        });
      }
    });
    
    $("#createRoomForm").validate({
      submitHandler: function(form) {
        const data = Object.fromEntries(new FormData(form).entries());
        $.blockUI({ message: '<h3>Creating room...</h3>' });
        RoomService.createRoom(data, function() {
          $.unblockUI();
          AdminService.closeModal('createRoomModal');
          AdminService.loadRooms();
        });
      }
    });
  },
  
  showTab: function(tab) {
    $('.admin-tab').hide();
    $('#' + tab + '-tab').show();
    
    if (tab === 'guests') this.loadGuests();
    if (tab === 'bookings') this.loadBookings();
    if (tab === 'rooms') this.loadRooms();
    if (tab === 'reviews') this.loadReviews();
  },
  
  loadGuests: function() {
    GuestService.getAllGuests(function(data) {
      const guests = Array.isArray(data) ? data : [];
      const tbody = $('#guests-tbody');
      tbody.empty();
      
      if (guests.length === 0) {
        tbody.append('<tr><td colspan="5" style="text-align: center;">No guests found</td></tr>');
        return;
      }
      
      guests.forEach(function(guest) {
        tbody.append(
          '<tr>' +
            '<td>' + guest.id + '</td>' +
            '<td>' + guest.first_name + ' ' + guest.last_name + '</td>' +
            '<td>' + guest.email + '</td>' +
            '<td>' + (guest.tel_num || 'N/A') + '</td>' +
            '<td>' +
              '<button class="btn btn-ghost" style="font-size: 12px; padding: 5px 10px;" onclick="AdminService.deleteGuest(' + guest.id + ')">Delete</button>' +
            '</td>' +
          '</tr>'
        );
      });
    });
  },
  
  loadBookings: function() {
    BookingService.getAllBookings(function(data) {
      const bookings = Array.isArray(data) ? data : [];
      const tbody = $('#admin-bookings-tbody');
      tbody.empty();
      
      if (bookings.length === 0) {
        tbody.append('<tr><td colspan="7" style="text-align: center;">No bookings found</td></tr>');
        return;
      }
      
      bookings.forEach(function(booking) {
        tbody.append(
          '<tr>' +
            '<td>' + booking.id + '</td>' +
            '<td>' + booking.guest_id + '</td>' +
            '<td>' + booking.check_in_date + '</td>' +
            '<td>' + booking.check_out_date + '</td>' +
            '<td>' + booking.status + '</td>' +
            '<td>$' + booking.total_price + '</td>' +
            '<td>' +
              '<button class="btn btn-ghost" style="font-size: 12px; padding: 5px 10px;" onclick="AdminService.deleteBooking(' + booking.id + ')">Delete</button>' +
            '</td>' +
          '</tr>'
        );
      });
    });
  },
  
  loadRooms: function() {
    RoomService.getAllRooms(function(data) {
      const rooms = Array.isArray(data) ? data : [];
      const tbody = $('#rooms-tbody');
      tbody.empty();
      
      if (rooms.length === 0) {
        tbody.append('<tr><td colspan="6" style="text-align: center;">No rooms found</td></tr>');
        return;
      }
      
      rooms.forEach(function(room) {
        tbody.append(
          '<tr>' +
            '<td>' + room.id + '</td>' +
            '<td>' + room.room_number + '</td>' +
            '<td>' + room.type + '</td>' +
            '<td>$' + room.base_price + '</td>' +
            '<td>' + room.status + '</td>' +
            '<td>' +
              '<button class="btn btn-ghost" style="font-size: 12px; padding: 5px 10px;" onclick="AdminService.deleteRoom(' + room.id + ')">Delete</button>' +
            '</td>' +
          '</tr>'
        );
      });
    });
  },
  
  loadReviews: function() {
    ReviewService.getAllReviews(function(data) {
      const reviews = Array.isArray(data) ? data : [];
      const tbody = $('#reviews-tbody');
      tbody.empty();
      
      if (reviews.length === 0) {
        tbody.append('<tr><td colspan="6" style="text-align: center;">No reviews found</td></tr>');
        return;
      }
      
      reviews.forEach(function(review) {
        const comment = review.comment ? review.comment.substring(0, 50) : 'N/A';
        tbody.append(
          '<tr>' +
            '<td>' + review.id + '</td>' +
            '<td>' + review.guest_id + '</td>' +
            '<td>' + review.rating + '/5</td>' +
            '<td>' + review.title + '</td>' +
            '<td>' + comment + '</td>' +
            '<td>' +
              '<button class="btn btn-ghost" style="font-size: 12px; padding: 5px 10px;" onclick="AdminService.deleteReview(' + review.id + ')">Delete</button>' +
            '</td>' +
          '</tr>'
        );
      });
    });
  },
  
  openCreateGuestModal: function() {
    $('#createGuestModal').show();
  },
  
  openCreateRoomModal: function() {
    $('#createRoomModal').show();
  },
  
  closeModal: function(modalId) {
    $('#' + modalId).hide();
    $('#' + modalId + ' form')[0].reset();
  },
  
  deleteGuest: function(id) {
    if (confirm('Delete this guest?')) {
      $.blockUI({ message: '<h3>Deleting...</h3>' });
      GuestService.deleteGuest(id, function() {
        $.unblockUI();
        AdminService.loadGuests();
      });
    }
  },
  
  deleteBooking: function(id) {
    if (confirm('Delete this booking?')) {
      $.blockUI({ message: '<h3>Deleting...</h3>' });
      BookingService.deleteBooking(id, function() {
        $.unblockUI();
        AdminService.loadBookings();
      });
    }
  },
  
  deleteRoom: function(id) {
    if (confirm('Delete this room?')) {
      $.blockUI({ message: '<h3>Deleting...</h3>' });
      RoomService.deleteRoom(id, function() {
        $.unblockUI();
        AdminService.loadRooms();
      });
    }
  },
  
  deleteReview: function(id) {
    if (confirm('Delete this review?')) {
      $.blockUI({ message: '<h3>Deleting...</h3>' });
      ReviewService.deleteReview(id, function() {
        $.unblockUI();
        AdminService.loadReviews();
      });
    }
  }
};