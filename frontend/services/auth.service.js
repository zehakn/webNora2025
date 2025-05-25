/**
 * Authentication service to handle login, logout, and token management
 */
class AuthService {
    login(username, password) {
        return fetch('/auth/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ username, password })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Invalid username or password');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.token) {
                localStorage.setItem('authToken', data.token);
                return data;
            }
        });
    }

    logout() {
        localStorage.removeItem('authToken');
    }

    getCurrentUser() {
        return localStorage.getItem('authToken');
    }

    isAuthenticated() {
        return !!this.getCurrentUser();
    }
}

// Create global instance
const authService = new AuthService();
