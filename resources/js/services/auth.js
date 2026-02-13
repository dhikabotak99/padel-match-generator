
const USER_KEY = 'user';
const EXPIRY_HOURS = 24;

export const auth = {
    // Save user with expiry timestamp
    setUser(user) {
        const now = new Date();
        const item = {
            value: user,
            expiry: now.getTime() + (EXPIRY_HOURS * 60 * 60 * 1000)
        };
        localStorage.setItem(USER_KEY, JSON.stringify(item));
    },

    // Get user and check/refresh expiry
    getUser() {
        const itemStr = localStorage.getItem(USER_KEY);
        if (!itemStr) return null;

        try {
            const item = JSON.parse(itemStr);
            const now = new Date();

            // Check if expired
            if (now.getTime() > item.expiry) {
                localStorage.removeItem(USER_KEY);
                return null;
            }

            // Refresh expiry on activity (sliding window)
            this.setUser(item.value);

            return item.value;
        } catch (e) {
            localStorage.removeItem(USER_KEY);
            return null;
        }
    },

    // Remove user
    removeUser() {
        localStorage.removeItem(USER_KEY);
    }
};
