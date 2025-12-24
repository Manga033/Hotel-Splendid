var UserService = {
    init: function () {
        var token = localStorage.getItem("user_token");
        if (token && token !== undefined) {
            window.location.replace("#home");
        }

        $("#loginForm").validate({
            submitHandler: function (form) {
                var entity = Object.fromEntries(new FormData(form).entries());
                UserService.login(entity);
            },
        });
    },

    initRegister: function() {
        $("#registrationForm").validate({
            submitHandler: function (form) {
                var entity = Object.fromEntries(new FormData(form).entries());
                console.log('Registration form data:', entity);
                var userData = {
                    // first_name: entity.first_name,
                    // last_name: entity.last_name,
                    // dob: entity.date_of_birth,
                    // gender: entity.gender,
                    email: entity.email,
                    username: entity.username_reg,
                    password: entity.password_reg, 
                    // tel_num: entity.tel_number, 
                    // country: entity.country,
                    // city: entity.city,
                    // address: entity.address
                };
                
                console.log('User data for registration:', userData);
                UserService.register(userData);
                
            },
        });
    },

    login: function (entity) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "auth/login",
            type: "POST",
            data: JSON.stringify(entity),
            contentType: "application/json",
            dataType: "json",
            success: function (result) {
                console.log(result);
                localStorage.setItem("user_token", result.data.token);
                toastr.success("Login successful!");
                UserService.generateMenuItems();
                window.location.replace("#home");
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                toastr.error(XMLHttpRequest?.responseText ? XMLHttpRequest.responseText : 'Invalid username or password');
            },
        });
    },

    register: function (entity) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "auth/register",
            type: "POST",
            data: JSON.stringify(entity),
            contentType: "application/json",
            dataType: "json",
            success: function (result) {
                console.log(result);
                toastr.success("Registration successful! Please login.");
                window.location.replace("#login");
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                toastr.error(XMLHttpRequest?.responseText ? XMLHttpRequest.responseText : 'Registration failed');
            },
        });
    },

    logout: function () {
        localStorage.clear();
        toastr.info("Logged out successfully");
        UserService.generateMenuItems();
        window.location.replace("#login");
    },

    generateMenuItems: function(){
        const token = localStorage.getItem("user_token");
        const user = Utils.parseJwt(token)?.user;
        
        if (user && user.role) {
            let nav = "";
            
            switch(user.role) {
                case Constants.USER_ROLE:
                    nav = '<li class="header-nav-list">' +
                        '<a class="header-nav-link header-active" href="#home">Home</a>' +
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
                    $(".header-nav-lists").html(nav);
                    break;
                    
                case Constants.ADMIN_ROLE:
                    nav = '<li class="header-nav-list">' +
                        '<a class="header-nav-link header-active" href="#home">Home</a>' +
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
                    $(".header-nav-lists").html(nav);
                    break;
                    
                default:
                    nav = '<li class="header-nav-list">' +
                        '<a class="header-nav-link header-active" href="#home">Home</a>' +
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
                    $(".header-nav-lists").html(nav);
            }
        } else {
            let nav = '<li class="header-nav-list">' +
                '<a class="header-nav-link header-active" href="#home">Home</a>' +
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
            $(".header-nav-lists").html(nav);
        }
    }
};