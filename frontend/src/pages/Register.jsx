import { useState } from 'react';
import { useAuth } from '../context/AuthContext';
import { useNavigate, Link } from 'react-router-dom';
import { motion } from 'framer-motion';

const Register = () => {
    const { register } = useAuth();
    const navigate = useNavigate();
    const [formData, setFormData] = useState({
        first_name: '', last_name: '', email: '', password: '', phone: ''
    });
    const [error, setError] = useState('');
    const [loading, setLoading] = useState(false);

    const handleSubmit = async (e) => {
        e.preventDefault();
        setError('');
        setLoading(true);
        try {
            await register(formData);
            navigate('/cursos');
        } catch (err) {
            setError(err.response?.data?.message || 'Error al registrarse. Verifica los datos.');
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
                className="w-full max-w-lg bg-white/95 backdrop-blur-sm border border-brand-light p-8 rounded-xl shadow-2xl"
            >
                <h2 className="text-3xl font-bold text-center mb-6 text-brand-dark">Crea tu cuenta</h2>

                {error && (
                    <div className="bg-red-500/20 border border-red-500/50 text-red-700 p-3 rounded-lg mb-6 text-sm">
                        {error}
                    </div>
                )}

                <form onSubmit={handleSubmit} className="space-y-4">
                    <div className="grid grid-cols-2 gap-4">
                        <div>
                            <label className="block text-sm font-medium text-brand-gray mb-1">Nombre</label>
                            <input
                                type="text"
                                required
                                className="w-full bg-brand-light/30 border border-brand-gray/20 rounded-lg px-4 py-3 text-brand-dark focus:outline-none focus:ring-2 focus:ring-primary transition-all"
                                value={formData.first_name}
                                onChange={(e) => setFormData({ ...formData, first_name: e.target.value })}
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-brand-gray mb-1">Apellidos</label>
                            <input
                                type="text"
                                required
                                className="w-full bg-brand-light/30 border border-brand-gray/20 rounded-lg px-4 py-3 text-brand-dark focus:outline-none focus:ring-2 focus:ring-primary transition-all"
                                value={formData.last_name}
                                onChange={(e) => setFormData({ ...formData, last_name: e.target.value })}
                            />
                        </div>
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-brand-gray mb-1">Correo Electrónico</label>
                        <input
                            type="email"
                            required
                            pattern="^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$"
                            className="w-full bg-brand-light/30 border border-brand-gray/20 rounded-lg px-4 py-3 text-brand-dark focus:outline-none focus:ring-2 focus:ring-primary transition-all"
                            value={formData.email}
                            onInvalid={(e) => e.target.setCustomValidity('Por favor, introduce una dirección de correo válida (ej. usuario@dominio.com)')}
                            onInput={(e) => e.target.setCustomValidity('')}
                            onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                        />
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-brand-gray mb-1">Contraseña</label>
                        <input
                            type="password"
                            required
                            minLength="6"
                            className="w-full bg-brand-light/30 border border-brand-gray/20 rounded-lg px-4 py-3 text-brand-dark focus:outline-none focus:ring-2 focus:ring-primary transition-all"
                            value={formData.password}
                            onInvalid={(e) => e.target.setCustomValidity('La contraseña debe tener al menos 6 caracteres')}
                            onInput={(e) => e.target.setCustomValidity('')}
                            onChange={(e) => setFormData({ ...formData, password: e.target.value })}
                        />
                    </div>
 
                    <div>
                        <label className="block text-sm font-medium text-brand-gray mb-1">Teléfono (Opcional)</label>
                        <input
                            type="tel"
                            pattern="[0-9]{9}"
                            maxLength="9"
                            className="w-full bg-brand-light/30 border border-brand-gray/20 rounded-lg px-4 py-3 text-brand-dark focus:outline-none focus:ring-2 focus:ring-primary transition-all"
                            value={formData.phone}
                            onInvalid={(e) => e.target.setCustomValidity('El teléfono debe tener exactamente 9 dígitos numéricos')}
                            onInput={(e) => e.target.setCustomValidity('')}
                            onChange={(e) => setFormData({ ...formData, phone: e.target.value.replace(/\D/g, '') })}
                        />
                    </div>

                    <button
                        type="submit"
                        disabled={loading}
                        className="w-full bg-primary hover:bg-primary-hover text-white font-semibold py-3 rounded-lg shadow-md transition-colors disabled:opacity-50 mt-4"
                    >
                        {loading ? 'Creando cuenta...' : 'Registrarse'}
                    </button>
                </form>

                <p className="text-center mt-6 text-brand-gray text-sm">
                    ¿Ya tienes cuenta? <Link to="/login" className="text-primary hover:text-primary-hover font-medium">Iniciar sesión</Link>
                </p>
            </motion.div>
        </div>
    );
};

export default Register;
