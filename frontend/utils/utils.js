let Utils = {
    parseJwt: function(token) {
        if (!token) return null;
        try {
            const payload = token.split('.')[1];
            const decoded = atob(payload);
            return JSON.parse(decoded);
        } catch (e) {
            console.error("Invalid JWT token", e);
            return null;
        }
    },

    getUserFromToken: function() {
        const token = localStorage.getItem("user_token");
        if (!token) return null;
        const decoded = Utils.parseJwt(token);
        return decoded ? decoded.user : null;
    },

    isLoggedIn: function() {
        return !!localStorage.getItem("user_token");
    },

    isAdmin: function() {
        const user = Utils.getUserFromToken();
        return user && user.role === Constants.ADMIN_ROLE;
    },

    isUser: function() {
        const user = Utils.getUserFromToken();
        return user && user.role === Constants.USER_ROLE;
    }
}