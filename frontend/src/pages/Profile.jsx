import { useState, useEffect } from 'react';
import { useAuth } from '../context/AuthContext';
import { motion } from 'framer-motion';
import api from '../services/api';

const Profile = () => {
    const { user, updateProfile } = useAuth();
    const [formData, setFormData] = useState({
        first_name: '',
        last_name: '',
        email: '',
        phone: '',
        password: '',
        profile_image: ''
    });
    const [loading, setLoading] = useState(false);
    const [message, setMessage] = useState({ type: '', text: '' });
    const [preview, setPreview] = useState(null);

    useEffect(() => {
        if (user) {
            setFormData({
                first_name: user.first_name || '',
                last_name: user.last_name || '',
                email: user.email || '',
                phone: user.phone || '',
                password: '',
                profile_image: user.profile_image || ''
            });
            setPreview(user.profile_image || null);
        }
    }, [user]);

    const handleImageChange = (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onloadend = () => {
                setPreview(reader.result);
                setFormData({ ...formData, profile_image: reader.result });
            };
            reader.readAsDataURL(file);
        }
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        setMessage({ type: '', text: '' });

        try {
            const dataToUpdate = { ...formData };
            if (!dataToUpdate.password) delete dataToUpdate.password;

            await updateProfile(dataToUpdate);
            
            setMessage({ type: 'success', text: '¡Perfil actualizado correctamente!' });
            setFormData(prev => ({ ...prev, password: '' }));
        } catch (err) {
            setMessage({ 
                type: 'error', 
                text: err.response?.data?.message || 'Error al actualizar el perfil' 
            });
        } finally {
            setLoading(false);
        }
    };

    if (!user) return <div className="text-center py-20 text-brand-gray font-medium">Cargando perfil...</div>;

    const initials = `${user.first_name?.charAt(0) || ''}${user.last_name?.charAt(0) || ''}`.toUpperCase();

    return (
        <div className="max-w-4xl mx-auto px-4 py-16 w-full min-h-screen font-sans">
            <motion.div 
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                className="bg-white rounded-[2rem] shadow-2xl border border-brand-light overflow-hidden"
            >
                {/* Header matching Admin Dashboard design */}
                <div className="bg-white border-b border-brand-light p-10 flex flex-col md:flex-row items-center gap-8 relative">
                    <div className="relative z-10 flex-shrink-0 group">
                        <div 
                            className="w-32 h-32 rounded-full bg-brand-light border border-brand-light flex items-center justify-center text-4xl font-medium text-brand-gray shadow-sm overflow-hidden relative cursor-pointer"
                            onClick={() => document.getElementById('avatar-upload').click()}
                        >
                            {preview ? (
                                <img src={preview} alt="Profile" className="w-full h-full object-cover" />
                            ) : (
                                <span className="relative z-10">{initials}</span>
                            )}
                            {/* Overlay for upload */}
                            <div className="absolute inset-0 bg-brand-dark/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span className="text-[10px] font-semibold uppercase tracking-widest text-brand-dark bg-white/80 px-2 py-1 rounded">Cambiar</span>
                            </div>
                        </div>

                        {/* Remove photo button */}
                        {preview && (
                            <button 
                                type="button"
                                onClick={(e) => {
                                    e.stopPropagation();
                                    setPreview(null);
                                    setFormData({ ...formData, profile_image: null });
                                }}
                                className="absolute -top-1 -right-1 bg-white text-red-500 rounded-full p-1.5 shadow-md border border-brand-light hover:bg-red-50 transition-colors z-20"
                                title="Eliminar foto"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        )}

                        <input 
                            id="avatar-upload"
                            type="file" accept="image/*"
                            className="hidden"
                            onChange={handleImageChange}
                        />
                    </div>
                    
                    <div className="relative z-10 text-center md:text-left">
                        <h1 className="text-3xl font-bold text-primary tracking-tight">
                            Mi Perfil
                        </h1>
                        <div className="mt-2 space-y-1">
                            <p className="text-brand-dark font-medium text-lg">
                                Gestiona tus datos
                            </p>
                            <p className="text-brand-gray font-normal text-sm max-w-md">
                                Cuando necesitemos contactar contigo usaremos estos datos
                            </p>
                        </div>
                    </div>
                </div>

                <div className="p-8 md:p-14">
                    {message.text && (
                        <motion.div 
                            initial={{ opacity: 0, scale: 0.9 }}
                            animate={{ opacity: 1, scale: 1 }}
                            className={`mb-10 p-5 rounded-2xl text-sm font-semibold flex items-center gap-4 ${
                                message.type === 'success' 
                                ? 'bg-green-50 text-green-700 border border-green-200' 
                                : 'bg-red-50 text-red-700 border border-red-200'
                            }`}
                        >
                            <span>{message.type === 'success' ? '✓' : '✗'}</span>
                            {message.text}
                        </motion.div>
                    )}

                    <form onSubmit={handleSubmit} className="space-y-8">
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div className="space-y-2">
                                <label className="text-xs font-medium text-brand-gray uppercase tracking-widest pl-1">Nombre</label>
                                <input 
                                    type="text" required
                                    className="w-full px-6 py-4 rounded-xl border border-brand-light focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-normal text-brand-dark bg-white shadow-sm"
                                    value={formData.first_name}
                                    onChange={e => setFormData({...formData, first_name: e.target.value})}
                                />
                            </div>
                            <div className="space-y-2">
                                <label className="text-xs font-medium text-brand-gray uppercase tracking-widest pl-1">Apellidos</label>
                                <input 
                                    type="text" required
                                    className="w-full px-6 py-4 rounded-xl border border-brand-light focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-normal text-brand-dark bg-white shadow-sm"
                                    value={formData.last_name}
                                    onChange={e => setFormData({...formData, last_name: e.target.value})}
                                />
                            </div>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div className="space-y-2">
                                <label className="text-xs font-medium text-brand-gray uppercase tracking-widest pl-1">Email</label>
                                <input 
                                    type="email" required
                                    id="email"
                                    name="email"
                                    autoComplete="email"
                                    className="w-full px-6 py-4 rounded-xl border border-brand-light bg-gray-50 text-brand-gray/60 transition-all font-normal cursor-not-allowed italic"
                                    value={formData.email}
                                    disabled
                                />
                            </div>
                            <div className="space-y-2">
                                <label className="text-xs font-medium text-brand-gray uppercase tracking-widest pl-1">Teléfono</label>
                                {/* Dummy input to trap browser autofill */}
                                <input type="text" style={{ display: 'none' }} autoComplete="off" />
                                <input 
                                    type="tel"
                                    pattern="[0-9]{9}"
                                    maxLength="9"
                                    id="phone_field"
                                    name="random_phone_name"
                                    autoComplete="off"
                                    className="w-full px-6 py-4 rounded-xl border border-brand-light focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-normal text-brand-dark bg-white shadow-sm"
                                    value={formData.phone}
                                    onInvalid={(e) => e.target.setCustomValidity('El teléfono debe tener exactamente 9 dígitos numéricos')}
                                    onInput={(e) => e.target.setCustomValidity('')}
                                    onChange={e => setFormData({...formData, phone: e.target.value.replace(/\D/g, '')})}
                                />
                            </div>
                        </div>

                        <div className="pt-10 border-t border-brand-light">
                            <div className="max-w-md space-y-4">
                                <label className="text-xs font-medium text-brand-gray uppercase tracking-widest pl-1">Nueva Contraseña</label>
                                <input 
                                    type="password"
                                    minLength="6"
                                    placeholder="••••••••••••"
                                    className="w-full px-6 py-4 rounded-xl border border-brand-light focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-normal text-brand-dark shadow-sm bg-white"
                                    value={formData.password}
                                    onInvalid={(e) => e.target.setCustomValidity('La contraseña debe tener al menos 6 caracteres')}
                                    onInput={(e) => e.target.setCustomValidity('')}
                                    onChange={e => setFormData({...formData, password: e.target.value})}
                                />
                                <p className="text-[11px] text-brand-gray font-normal italic">Dejar en blanco para no cambiar</p>
                            </div>
                        </div>

                        <div className="flex justify-end pt-8">
                            <button 
                                type="submit"
                                disabled={loading}
                                className={`px-10 py-4 bg-primary hover:bg-primary-hover text-white font-medium rounded-xl shadow-lg transition-all uppercase tracking-widest text-xs ${loading ? 'opacity-50' : ''}`}
                            >
                                {loading ? 'Guardando...' : 'Guardar Cambios'}
                            </button>
                        </div>
                    </form>
                </div>
            </motion.div>
        </div>
    );
};

export default Profile;

