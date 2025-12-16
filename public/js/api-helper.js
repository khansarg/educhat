// API Helper untuk komunikasi dengan backend
const API_BASE = '/api';

// Get auth token from session storage
function getAuthToken() {
    return sessionStorage.getItem('auth_token');
}

// Get current user
function getCurrentUser() {
    const user = sessionStorage.getItem('user');
    return user ? JSON.parse(user) : null;
}

// Check if user is authenticated
function isAuthenticated() {
    return !!getAuthToken();
}

// API call wrapper
async function apiCall(endpoint, options = {}) {
    const token = getAuthToken();
    
    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': token ? `Bearer ${token}` : '',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
    };
    
    const mergedOptions = {
        ...defaultOptions,
        ...options,
        headers: {
            ...defaultOptions.headers,
            ...options.headers
        }
    };
    
    try {
        const response = await fetch(`${API_BASE}${endpoint}`, mergedOptions);
        const data = await response.json();
        
        // If unauthorized, redirect to login
        if (response.status === 401 || response.status === 403) {
            sessionStorage.clear();
            window.location.href = '/login';
            return null;
        }
        
        if (!response.ok) {
            throw new Error(data.message || 'API Error');
        }
        
        return data;
    } catch (error) {
        console.error('API Call Error:', error);
        throw error;
    }
}

// Student API endpoints
const StudentAPI = {
    // Get all courses
    async getCourses() {
        return await apiCall('/student/courses');
    },
    
    // Get CLO detail
    async getCLODetail(cloId) {
        return await apiCall(`/student/clo/${cloId}`);
    },
    
    // Get profile
    async getProfile() {
        return await apiCall('/student/profile');
    }
};

// Chat API endpoints
const ChatAPI = {
    // Get chat history
    async getHistory() {
        return await apiCall('/chat');
    },
    
    // Create new chat session
    async createSession(cloId, title = null) {
        return await apiCall('/chat', {
            method: 'POST',
            body: JSON.stringify({ clo_id: cloId, title })
        });
    },
    
    // Get specific chat session
    async getSession(sessionId) {
        return await apiCall(`/chat/${sessionId}`);
    },
    
    // Send message
    async sendMessage(sessionId, message) {
        return await apiCall(`/chat/${sessionId}/message`, {
            method: 'POST',
            body: JSON.stringify({ message })
        });
    }
};

// Auth API endpoints
const AuthAPI = {
    // Get current user info
    async me() {
        return await apiCall('/me');
    },
    
    // Logout
    async logout() {
        const result = await apiCall('/logout', { method: 'POST' });
        sessionStorage.clear();
        return result;
    }
};

// Redirect if not authenticated
function requireAuth() {
    if (!isAuthenticated()) {
        window.location.href = '/login';
    }
}

// Redirect if already authenticated
function requireGuest() {
    if (isAuthenticated()) {
        window.location.href = '/dashboard';
    }
}