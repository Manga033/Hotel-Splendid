/**
 * AppService - Application-level controller
 * Handles routing, authentication guards, and menu initialization
 * MVC Pattern: Controller layer for application-wide concerns
 */
let AppService = {
    /**
     * Initialize application
     * Sets up event listeners and initial state
     */
    init: function() {
        // Generate initial menu based on authentication state
        UserService.generateMenuItems();
        
        // Set up route guards and navigation listeners
        this.setupRouteGuards();
    },
    
    /**
     * Setup route guards for protected pages
     * Ensures users are authenticated and authorized for specific routes
     */
    setupRouteGuards: function() {
        $(window).on('hashchange', function() {
            const hash = location.hash;
            const user = Utils.getUserFromToken();
            
            // Guard: Protected routes require authentication
            if (AppService.isProtectedRoute(hash) && !user) {
                toastr.warning('Please login to access this page');
                window.location.replace('#login');
                return;
            }
            
            // Guard: Admin panel requires admin role
            if (hash === '#admin-panel' && user && user.role !== Constants.ADMIN_ROLE) {
                toastr.error('Access denied. Admins only.');
                window.location.replace('#home');
                return;
            }
            
            // Update menu items based on current user state
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

// Initialize application when DOM is ready
$(document).ready(function() {
    AppService.init();
});