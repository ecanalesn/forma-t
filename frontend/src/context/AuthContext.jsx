import { createContext, useState, useEffect, useContext } from 'react';
import api from '../services/api';

const AuthContext = createContext();

export const useAuth = () => useContext(AuthContext);

export const AuthProvider = ({ children }) => {
    const [user, setUser] = useState(null);
    const [token, setToken] = useState(localStorage.getItem('auth_token') || null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        if (token) {
            // Validar token y obtener el usuario al cargar
            api.get('/me')
                .then(res => {
                    setUser(res.data);
                })
                .catch(() => {
                    // Token inválido o expirado
                    logout();
                })
                .finally(() => {
                    setLoading(false);
                });
        } else {
            setLoading(false);
        }
    }, [token]);

    const login = async (email, password) => {
        const res = await api.post('/login', { email, password });
        const { access_token, user } = res.data;
        
        localStorage.setItem('auth_token', access_token);
        localStorage.setItem('user', JSON.stringify(user));
        
        setToken(access_token);
        setUser(user);
        return user;
    };

    const register = async (userData) => {
        const res = await api.post('/register', userData);
        const { access_token, user } = res.data;
        
        localStorage.setItem('auth_token', access_token);
        localStorage.setItem('user', JSON.stringify(user));
        
        setToken(access_token);
        setUser(user);
        return user;
    };

    const logout = async () => {
        try {
            await api.post('/logout');
        } catch (e) {
            console.error('Logout error', e);
        } finally {
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user');
            setToken(null);
            setUser(null);
        }
    };

    const updateProfile = async (userData) => {
        const res = await api.put(`/users/${user.user_id}`, userData);
        setUser(res.data);
        localStorage.setItem('user', JSON.stringify(res.data));
        return res.data;
    }

    const value = {
        user,
        token,
        loading,
        login,
        register,
        logout,
        updateProfile,
        isAuthenticated: !!user,
        isAdmin: user?.role === 'admin'
    };

    return (
        <AuthContext.Provider value={value}>
            {!loading && children}
        </AuthContext.Provider>
    );
};
