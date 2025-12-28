
let AppService = {

    init: function() {
        UserService.generateMenuItems();

        this.setupRouteGuards();
    },

    setupRouteGuards: function() {
        $(window).on('hashchange', function() {
            const hash = location.hash;
            const user = Utils.getUserFromToken();

            if (AppService.isProtectedRoute(hash) && !user) {
                toastr.warning('Please login to access this page');
                window.location.replace('#login');
                return;
            }

            if (hash === '#admin-panel' && user && user.role !== Constants.ADMIN_ROLE) {
                toastr.error('Access denied. Admins only.');
                window.location.replace('#home');
                return;
            }

            UserService.generateMenuItems();
        });
    },
    
    /**
     * Check if a route requires authentication
     * @param {string} hash - The URL hash (route)
     * @returns {boolean} - True if route is protected
     */
    isProtectedRoute: function(hash) {
        const protectedRoutes = ['#dashboard', '#admin-panel'];
        return protectedRoutes.includes(hash);
    },
    
    /**
     * Navigate to a specific route
     * @param {string} route - The route to navigate to (without #)
     */
    navigateTo: function(route) {
        window.location.hash = route;
    },
    
    /**
     * Get current route
     * @returns {string} - Current route without #
     */
    getCurrentRoute: function() {
        return location.hash.slice(1) || 'home';
    },
    
    /**
     * Check if user has access to current route
     * @returns {boolean} - True if user can access current route
     */
    canAccessCurrentRoute: function() {
        const hash = location.hash;
        const user = Utils.getUserFromToken();
        
        if (this.isProtectedRoute(hash) && !user) {
            return false;
        }
        
        if (hash === '#admin-panel' && (!user || user.role !== Constants.ADMIN_ROLE)) {
            return false;
        }
        
        return true;
    }
};

$(document).ready(function() {
    AppService.init();
});