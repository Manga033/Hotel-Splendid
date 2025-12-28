let DashboardService = {
  init: function() {
    const user = Utils.getUserFromToken();
    if (!user) {
      toastr.error("Please login first");
      window.location.replace("#login");
      return;
    }
    
    $('#user-name').text(user.username);
    $('#guest_id').val(user.id);
    $('#my-bookings-table tbody').html('<tr><td colspan="7">Loading...</td></tr>');
    
    this.loadMyBookings();
    this.setupForm();
  },

  setupForm: function() {
    $("#createBookingForm").validate({
      rules: {
        check_in_date: { required: true },
        check_out_date: { required: true },
        num_of_guests: { required: true, min: 1 },
        num_of_children: { min: 0 },
        type: { required: true }
      },
      messages: {
        check_in_date: "Check-in date is required",
        check_out_date: "Check-out date is required",
        num_of_guests: "Number of guests is required",
        type: "Booking type is required"
      },
      submitHandler: function(form) {
        const data = Object.fromEntries(new FormData(form).entries());
        DashboardService.createBooking(data);
      }
    });
  },
  
  loadMyBookings: function() {
    const user = Utils.getUserFromToken();
    BookingService.getAllBookings(function(data) {
      $('#my-bookings-table tbody').empty();
      const myBookings = data.filter(b => b.guest_id == user.id);
      const tbody = $('#bookings-tbody');
      tbody.empty();
      
      if (myBookings.length === 0) {
        tbody.append('<tr><td colspan="7" style="text-align: center;">No bookings found</td></tr>');
        return;
      }
      
      myBookings.forEach(booking => {
        tbody.append(`
          <tr>
            <td>${booking.id}</td>
            <td>${booking.check_in_date}</td>
            <td>${booking.check_out_date}</td>
            <td>${booking.num_of_guests}</td>
            <td><span style="padding: 4px 8px; border-radius: 4px; background: ${booking.status === 'confirmed' ? '#d4af37' : '#ccc'}; color: white;">${booking.status}</span></td>
            <td>$${booking.total_price}</td>
            <td>
              <button class="btn btn-ghost" style="margin-right: 5px; font-size: 12px; padding: 5px 10px;" onclick="DashboardService.viewBooking(${booking.id})">View</button>
              ${booking.status === 'pending' ? `<button class="btn btn-ghost" style="font-size: 12px; padding: 5px 10px;" onclick="DashboardService.cancelBooking(${booking.id})">Cancel</button>` : ''}
            </td>
          </tr>
        `);
      });
    });
  },
  
  openCreateBookingModal: function() {
    $('#createBookingModal').show();
  },
  
  closeModal: function() {
    $('#createBookingModal').hide();
    $('#createBookingForm')[0].reset();
  },
  
  createBooking: function(data) {
    $.blockUI({ message: '<h3>Creating booking...</h3>' });
    BookingService.createBooking(data, function(response) {
      $.unblockUI();
      DashboardService.closeModal();
      DashboardService.loadMyBookings();
    });
  },
  
  viewBooking: function(id) {
    BookingService.getBookingById(id, function(data) {
      const statusColor = data.status === 'confirmed' ? '#28a745' : (data.status === 'pending' ? '#ffc107' : '#dc3545');
      
      let html = `
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
          <div>
            <p><strong>Booking Reference:</strong><br> <span style="color: #d4af37; font-weight: bold;">#${data.id}</span></p>
            <p><strong>Check-In Date:</strong><br> ${data.check_in_date}</p>
            <p><strong>Check-Out Date:</strong><br> ${data.check_out_date}</p>
          </div>
          <div>
            <p><strong>Current Status:</strong><br> 
              <span style="background: ${statusColor}; color: white; padding: 2px 8px; border-radius: 4px; font-size: 12px; text-transform: uppercase;">
                ${data.status}
              </span>
            </p>
            <p><strong>Room Type:</strong><br> ${data.type.charAt(0).toUpperCase() + data.type.slice(1)}</p>
            <p><strong>Guests:</strong><br> ${data.num_of_guests} Adults, ${data.num_of_children} Children</p>
          </div>
        </div>
        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
        <div style="text-align: right;">
          <h4 style="margin: 0;">Total Amount Paid:</h4>
          <h2 style="margin: 0; color: #241e09;">$${data.total_price}</h2>
        </div>
      `;

      $('#bookingDetailsContent').html(html);
      $('#viewBookingModal').fadeIn(200);
    });
  },
  
  cancelBooking: function(id) {
    if (confirm('Are you sure you want to cancel this booking?')) {
      $.blockUI({ message: '<h3>Cancelling booking...</h3>' });
      BookingService.patchBooking(id, { status: 'cancelled' }, function() {
        $.unblockUI();
        DashboardService.loadMyBookings();
      });
    }
  }
};