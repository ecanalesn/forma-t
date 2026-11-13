import { useState } from 'react';
import { useAuth } from '../context/AuthContext';
import { useNavigate, Link } from 'react-router-dom';
import { motion } from 'framer-motion';

const Login = () => {
    const { login } = useAuth();
    const navigate = useNavigate();
    const [credentials, setCredentials] = useState({ email: '', password: '' });
    const [error, setError] = useState('');
    const [loading, setLoading] = useState(false);

    const handleSubmit = async (e) => {
        e.preventDefault();
        setError('');
        setLoading(true);
        try {
            await login(credentials.email, credentials.password);
            navigate('/cursos');
        } catch (err) {
            setError(err.response?.data?.message || 'Error al iniciar sesión');
        } finally {
            setLoading(false);
        }
    };

    return (
        <div
            className="flex items-center justify-center flex-grow p-4 text-brand-dark"
            style={{ backgroundImage: `url('/resources/img/login.png')`, backgroundSize: 'cover', backgroundPosition: 'center' }}
        >
            <motion.div
                initial={{ opacity: 0, scale: 0.95 }}
                animate={{ opacity: 1, scale: 1 }}
                className="w-full max-w-md bg-white/95 backdrop-blur-sm border border-brand-light p-8 rounded-xl shadow-2xl"
            >
                <h2 className="text-3xl font-bold text-center mb-6 text-brand-dark">Bienvenido de nuevo</h2>

                {error && (
                    <div className="bg-red-500/20 border border-red-500/50 text-red-700 p-3 rounded-lg mb-6 text-sm">
                        {error}
                    </div>
                )}

                <form onSubmit={handleSubmit} className="space-y-4">
                    <div>
                        <label className="block text-sm font-medium text-brand-gray mb-1">Correo Electrónico</label>
                        <input
                            type="email"
                            required
                            pattern="^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$"
                            className="w-full bg-brand-light/30 border border-brand-gray/20 rounded-lg px-4 py-3 text-brand-dark placeholder-brand-gray/60 focus:outline-none focus:ring-2 focus:ring-primary transition-all"
                            placeholder="ejemplo@correo.com"
                            value={credentials.email}
                            onInvalid={(e) => e.target.setCustomValidity('Por favor, introduce una dirección de correo válida (ej. usuario@dominio.com)')}
                            onInput={(e) => e.target.setCustomValidity('')}
                            onChange={(e) => setCredentials({ ...credentials, email: e.target.value })}
                        />
                    </div>
                    <div>
                        <label className="block text-sm font-medium text-brand-gray mb-1">Contraseña</label>
                        <input
                            type="password"
                            required
                            className="w-full bg-brand-light/30 border border-brand-gray/20 rounded-lg px-4 py-3 text-brand-dark placeholder-brand-gray/60 focus:outline-none focus:ring-2 focus:ring-primary transition-all"
                            placeholder="••••••••"
                            value={credentials.password}
                            onChange={(e) => setCredentials({ ...credentials, password: e.target.value })}
                        />
                    </div>

                    <button
                        type="submit"
                        disabled={loading}
                        className="w-full bg-primary hover:bg-primary-hover text-white font-semibold py-3 rounded-lg shadow-md transition-colors disabled:opacity-50 mt-4"
                    >
                        {loading ? 'Iniciando sesión...' : 'Iniciar sesión'}
                    </button>
                </form>

                <p className="text-center mt-6 text-brand-gray text-sm">
                    ¿No tienes una cuenta? <Link to="/register" className="text-primary hover:text-primary-hover font-medium">Regístrate aquí</Link>
                </p>
            </motion.div>
        </div>
    );
};

export default Login;
