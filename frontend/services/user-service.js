var UserService = {
    // ========== INITIALIZATION ==========
    init: function () {
        var token = localStorage.getItem("user_token");
        if (token && token !== undefined) {
            window.location.replace("#home");
        }

        // LOGIN FORM VALIDATION - NO MINIMUM LENGTH REQUIREMENTS
        $("#loginForm").validate({
            rules: {
                username: {
                    required: true
                },
                password: {
                    required: true
                }
            },
            messages: {
                username: {
                    required: "Username is required"
                },
                password: {
                    required: "Password is required"
                }
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            },
            submitHandler: function (form) {
                $.blockUI({ message: '<h3>Processing login...</h3>' });
                var entity = Object.fromEntries(new FormData(form).entries());
                UserService.login(entity);
            }
        });
    },

    initRegister: function() {
        // REGISTRATION FORM VALIDATION - WITH MINIMUM LENGTH REQUIREMENTS
        $("#registrationForm").validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
                username_reg: {
                    required: true,
                    minlength: 3
                },
                password_reg: {
                    required: true,
                    minlength: 6,
                    maxlength: 20
                }
            },
            messages: {
                email: {
                    required: "Email is required",
                    email: "Please enter a valid email address"
                },
                username_reg: {
                    required: "Username is required",
                    minlength: "Username must be at least 3 characters"
                },
                password_reg: {
                    required: "Password is required",
                    minlength: "Password must be at least 6 characters",
                    maxlength: "Password cannot exceed 20 characters"
                }
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            },
            submitHandler: function (form) {
                $.blockUI({ message: '<h3>Processing registration...</h3>' });
                var entity = Object.fromEntries(new FormData(form).entries());
                var userData = {
                    email: entity.email,
                    username: entity.username_reg,
                    password: entity.password_reg
                };
                UserService.register(userData);
            }
        });
    },

    // ========== API CALLS (MODEL LAYER) ==========
    login: function (credentials) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "auth/login",
            type: "POST",
            data: JSON.stringify(credentials),
            contentType: "application/json",
            dataType: "json",
            success: function (result) {
                $.unblockUI();
                
                // Handle different response structures
                const token = result.token || result.data?.token || result.data;
                
                if (token) {
                    localStorage.setItem("user_token", token);
                    toastr.success("Login successful!");
                    UserService.generateMenuItems();
                    
                    // Check user role and redirect appropriately
                    const user = Utils.getUserFromToken();
                    if (user && user.role === Constants.ADMIN_ROLE) {
                        window.location.replace("#admin-panel");
                    } else {
                        window.location.replace("#home");
                    }
                } else {
                    toastr.error("Invalid response from server");
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $.unblockUI();
                console.error("Login error:", XMLHttpRequest.responseJSON);
                
                const msg = XMLHttpRequest?.responseJSON?.message 
                    || XMLHttpRequest?.responseJSON?.error 
                    || 'Invalid username or password';
                
                toastr.error(msg);
            }
        });
    },

    register: function (userData) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "auth/register",
            type: "POST",
            data: JSON.stringify(userData),
            contentType: "application/json",
            dataType: "json",
            success: function (result) {
                $.unblockUI();
                toastr.success("Registration successful! Please login.");
                $("#registrationForm")[0].reset();
                window.location.replace("#login");
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $.unblockUI();
                console.error("Registration error:", XMLHttpRequest.responseJSON);
                
                const msg = XMLHttpRequest?.responseJSON?.message 
                    || XMLHttpRequest?.responseJSON?.error 
                    || 'Registration failed. Please try again.';
                
                toastr.error(msg);
            }
        });
    },

    logout: function () {
        localStorage.clear();
        toastr.info("Logged out successfully");
        UserService.generateMenuItems();
        window.location.replace("#login");
    },

    // ========== VIEW LAYER ==========
    generateMenuItems: function(){
        const token = localStorage.getItem("user_token");
        const user = Utils.parseJwt(token)?.user;
        
        let nav = "";
        
        if (user && user.role) {
            switch(user.role) {
                case Constants.USER_ROLE:
                    nav = '<li class="header-nav-list">' +
                        '<a class="header-nav-link" href="#home">Home</a>' +
                        '</li>' +
                        '<li class="header-nav-list">' +
                        '<a class="header-nav-link" href="#rooms">Rooms and Suites</a>' +
                        '</li>' +
                        '<li class="header-nav-list">' +
                        '<a class="header-nav-link" href="#facilities">Facilities</a>' +
                        '</li>' +
                        '<li class="header-nav-list">' +
                        '<a class="header-nav-link" href="#contact">Contact Us</a>' +
                        '</li>' +
                        '<li class="header-nav-list">' +
                        '<a class="header-nav-link" href="#dashboard">My Dashboard</a>' +
                        '</li>' +
                        '<li class="header-nav-list">' +
                        '<button class="header-btn header-btn-custom" onclick="UserService.logout()">Logout</button>' +
                        '</li>';
                    break;
                    
                case Constants.ADMIN_ROLE:
                    nav = '<li class="header-nav-list">' +
                        '<a class="header-nav-link" href="#home">Home</a>' +
                        '</li>' +
                        '<li class="header-nav-list">' +
                        '<a class="header-nav-link" href="#rooms">Rooms and Suites</a>' +
                        '</li>' +
                        '<li class="header-nav-list">' +
                        '<a class="header-nav-link" href="#facilities">Facilities</a>' +
                        '</li>' +
                        '<li class="header-nav-list">' +
                        '<a class="header-nav-link" href="#contact">Contact Us</a>' +
                        '</li>' +
                        '<li class="header-nav-list">' +
                        '<a class="header-nav-link" href="#admin-panel">Admin Panel</a>' +
                        '</li>' +
                        '<li class="header-nav-list">' +
                        '<button class="header-btn header-btn-custom" onclick="UserService.logout()">Logout</button>' +
                        '</li>';
                    break;
                    
                default:
                    nav = this.generateGuestMenu();
            }
        } else {
            nav = this.generateGuestMenu();
        }
        
        $(".header-nav-lists").html(nav);
    },
    
    generateGuestMenu: function() {
        return '<li class="header-nav-list">' +
            '<a class="header-nav-link" href="#home">Home</a>' +
            '</li>' +
            '<li class="header-nav-list">' +
            '<a class="header-nav-link" href="#rooms">Rooms and Suites</a>' +
            '</li>' +
            '<li class="header-nav-list">' +
            '<a class="header-nav-link" href="#facilities">Facilities</a>' +
            '</li>' +
            '<li class="header-nav-list">' +
            '<a class="header-nav-link" href="#contact">Contact Us</a>' +
            '</li>' +
            '<li class="header-nav-list">' +
            '<a class="header-nav-link" href="#login">Register/Login</a>' +
            '</li>' +
            '<li class="header-nav-list">' +
            '<a class="header-btn header-btn-custom" href="https://timbu.com/search?query=hotel">BOOK NOW</a>' +
            '</li>';
    }
};