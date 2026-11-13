import { Outlet, Link, useNavigate, useLocation } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { motion, AnimatePresence } from 'framer-motion';
import { useState, useEffect, useRef } from 'react';
import api from '../services/api';

const Layout = () => {
    const { isAuthenticated, user, logout, isAdmin } = useAuth();
    const navigate = useNavigate();
    const location = useLocation();
    const isFullPage = ['/', '/login', '/register'].includes(location.pathname);

    // Student notifications
    const [requests, setRequests] = useState([]);
    const [readIds, setReadIds] = useState(new Set()); // IDs vistos tras abrir el panel
    const [showNotifications, setShowNotifications] = useState(false);
    const notificationRef = useRef(null);

    // Mobile menu state
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);

    // Admin notifications
    const [pendingCount, setPendingCount] = useState(0);
    const [showAdminNotif, setShowAdminNotif] = useState(false);
    const adminNotifRef = useRef(null);

    const handleLogout = async () => {
        await logout();
        navigate('/');
    };

    // Student: fetch own requests
    useEffect(() => {
        if (isAuthenticated && !isAdmin) {
            fetchRequests();
            const interval = setInterval(fetchRequests, 30000);
            return () => clearInterval(interval);
        }
    }, [isAuthenticated, isAdmin]);

    // Admin: fetch pending request count
    useEffect(() => {
        if (isAuthenticated && isAdmin) {
            fetchPendingCount();
            const interval = setInterval(fetchPendingCount, 30000);
            return () => clearInterval(interval);
        }
    }, [isAuthenticated, isAdmin]);

    useEffect(() => {
        const handleClickOutside = (event) => {
            if (notificationRef.current && !notificationRef.current.contains(event.target)) {
                setShowNotifications(false);
            }
            if (adminNotifRef.current && !adminNotifRef.current.contains(event.target)) {
                setShowAdminNotif(false);
            }
        };
        document.addEventListener('mousedown', handleClickOutside);
        return () => document.removeEventListener('mousedown', handleClickOutside);
    }, []);

    const fetchRequests = async () => {
        try {
            const res = await api.get('/requests/my');
            setRequests(res.data);
        } catch (err) {
            console.error('Error fetching requests', err);
        }
    };

    const fetchPendingCount = async () => {
        try {
            const res = await api.get('/admin/requests');
            const count = res.data.filter(r => r.status === 'pending').length;
            setPendingCount(count);
        } catch (err) {
            console.error('Error fetching admin requests', err);
        }
    };

    // Notifications that have been resolved (not pending)
    const resolvedRequests = requests.filter(req => req.status !== 'pending');
    // Unread = resolved but not yet seen (not in readIds)
    const unreadCount = resolvedRequests.filter(req => !readIds.has(req.request_id)).length;

    const toggleNotifications = () => {
        const next = !showNotifications;
        setShowNotifications(next);
        // When opening, mark all current resolved as read
        if (next) {
            setReadIds(new Set(resolvedRequests.map(r => r.request_id)));
        }
    };

    const toggleAdminNotif = () => {
        setShowAdminNotif(prev => !prev);
    };

    return (
        <div className="min-h-screen text-brand-dark flex flex-col font-sans bg-white">
            {/* Top Header */}
            <header className="sticky top-0 z-50 w-full bg-brand-dark">
                <div className="w-full px-6">
                    <div className="flex items-center justify-between h-16">
                        {/* Logo */}
                        <div className="flex items-center">
                            {isAuthenticated && (
                                <button
                                    onClick={() => setIsMobileMenuOpen(true)}
                                    className="md:hidden mr-4 text-primary hover:text-white transition-colors"
                                    aria-label="Abrir menú"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
                                </button>
                            )}
                            <Link to="/" className="font-bold text-primary tracking-wide" style={{ fontFamily: "'Work Sans', sans-serif", fontSize: '2rem', letterSpacing: '1px' }}>
                                FORMA-T
                            </Link>
                        </div>

                        {/* Top Navigation */}
                        <nav className="flex items-center gap-6">
                            {isAuthenticated ? (
                                <>
                                    <div className="hidden sm:flex items-center text-primary font-semibold">
                                        <span>Hola, {user.first_name}</span>
                                    </div>

                                    <div className="flex items-center gap-4">
                                        {/* Student Notification Bell */}
                                        {!isAdmin && (
                                            <div className="relative" ref={notificationRef}>
                                                <button 
                                                    onClick={toggleNotifications}
                                                    className={`relative p-1 transition-all ${showNotifications ? 'text-primary' : 'text-white/70 hover:text-white'}`}
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                                                    {unreadCount > 0 && (
                                                        <span className="absolute -top-1 -right-1 flex h-5 w-5">
                                                            <span className="relative inline-flex rounded-full h-5 w-5 bg-primary text-[11px] items-center justify-center font-bold text-white leading-none border border-brand-dark">
                                                                {unreadCount}
                                                            </span>
                                                        </span>
                                                    )}
                                                </button>

                                                <AnimatePresence>
                                                    {showNotifications && (
                                                        <motion.div 
                                                            initial={{ opacity: 0, y: 10 }}
                                                            animate={{ opacity: 1, y: 0 }}
                                                            exit={{ opacity: 0, y: 10 }}
                                                            className="absolute right-0 mt-3 w-72 bg-white rounded-lg shadow-xl border border-brand-light overflow-hidden z-50"
                                                        >
                                                            <div className="bg-primary px-4 py-2 text-white font-bold text-xs uppercase tracking-wider">
                                                                Notificaciones
                                                            </div>
                                                            <div className="max-h-64 overflow-y-auto">
                                                                {resolvedRequests.length === 0 ? (
                                                                    <div className="p-4 text-center text-brand-gray text-xs italic">
                                                                        No hay notificaciones
                                                                    </div>
                                                                ) : (
                                                                    resolvedRequests.slice(0, 5).map(req => {
                                                                        const isUnread = !readIds.has(req.request_id);
                                                                        return (
                                                                            <div key={req.request_id} className={`p-3 border-b border-brand-light transition-colors ${isUnread ? 'bg-gray-100' : 'hover:bg-brand-light/20'}`}>
                                                                                <p className="text-sm text-brand-dark">
                                                                                    Tu solicitud para {req.course?.course_name} ha sido 
                                                                                    <span className="ms-1">
                                                                                        {req.status === 'approved' ? 'Aprobada' : 'Rechazada'}
                                                                                    </span>
                                                                                </p>
                                                                            </div>
                                                                        );
                                                                    })
                                                                )}
                                                            </div>
                                                            <Link 
                                                                to="/mis-cursos" 
                                                                className="block text-center py-2 text-sm font-bold text-primary hover:bg-brand-light border-t border-brand-light"
                                                                onClick={() => setShowNotifications(false)}
                                                            >
                                                                Ver mis cursos
                                                            </Link>
                                                        </motion.div>
                                                    )}
                                                </AnimatePresence>
                                            </div>
                                        )}

                                        {/* Admin Notification Bell */}
                                        {isAdmin && (
                                            <div className="relative" ref={adminNotifRef}>
                                                <button
                                                    onClick={toggleAdminNotif}
                                                    className={`relative p-1 transition-all ${showAdminNotif ? 'text-primary' : 'text-white/70 hover:text-white'}`}
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                                                    {pendingCount > 0 && (
                                                        <span className="absolute -top-1 -right-1 flex h-5 w-5">
                                                            <span className="relative inline-flex rounded-full h-5 w-5 bg-primary text-[11px] items-center justify-center font-bold text-white leading-none border border-brand-dark">
                                                                {pendingCount}
                                                            </span>
                                                        </span>
                                                    )}
                                                </button>
                                                <AnimatePresence>
                                                    {showAdminNotif && (
                                                        <motion.div
                                                            initial={{ opacity: 0, y: 10 }}
                                                            animate={{ opacity: 1, y: 0 }}
                                                            exit={{ opacity: 0, y: 10 }}
                                                            className="absolute right-0 mt-3 w-64 bg-white rounded-lg shadow-xl border border-brand-light overflow-hidden z-50"
                                                        >
                                                            <div className="bg-primary px-4 py-2 text-white font-bold text-xs uppercase tracking-wider">
                                                                Solicitudes
                                                            </div>
                                                            <div className="p-4 text-center">
                                                                {pendingCount === 0 ? (
                                                                    <p className="text-sm text-brand-gray italic">No hay solicitudes pendientes</p>
                                                                ) : (
                                                                    <p className="text-sm text-brand-dark">
                                                                        Tienes <span className="font-bold text-primary">{pendingCount}</span> solicitud{pendingCount > 1 ? 'es' : ''} pendiente{pendingCount > 1 ? 's' : ''} de revisión
                                                                    </p>
                                                                )}
                                                            </div>
                                                            <Link
                                                                to="/admin"
                                                                className="block text-center py-2 text-sm font-bold text-primary hover:bg-brand-light border-t border-brand-light"
                                                                onClick={() => setShowAdminNotif(false)}
                                                            >
                                                                Ver solicitudes
                                                            </Link>
                                                        </motion.div>
                                                    )}
                                                </AnimatePresence>
                                            </div>
                                        )}

                                        <Link to="/perfil" className="text-gray-300 hover:text-white transition-colors" title="Mi Perfil">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                        </Link>
                                    </div>
                                </>
                            ) : (
                                <>
                                    <Link to="/login" className="px-4 py-2 rounded text-sm font-medium hover:text-primary transition-all text-white">Iniciar sesión</Link>
                                    <Link to="/register" className="bg-primary hover:bg-primary-hover text-white px-4 py-2 rounded text-sm font-medium transition-all shadow shadow-primary/30">Registrarse</Link>
                                </>
                            )}
                        </nav>
                    </div>
                </div>
            </header>

            {/* Layout Body */}
            <div className="flex flex-grow overflow-hidden">
                {/* Sidebar */}
                {isAuthenticated && (
                    <>
                        {/* Mobile Sidebar Overlay */}
                        <AnimatePresence>
                            {isMobileMenuOpen && (
                                <div className="md:hidden">
                                    <motion.div
                                        initial={{ opacity: 0 }}
                                        animate={{ opacity: 1 }}
                                        exit={{ opacity: 0 }}
                                        onClick={() => setIsMobileMenuOpen(false)}
                                        className="fixed inset-0 bg-black/40 backdrop-blur-sm z-40"
                                    />
                                    <motion.aside
                                        initial={{ x: '-100%' }}
                                        animate={{ x: 0 }}
                                        exit={{ x: '-100%' }}
                                        transition={{ type: 'tween', duration: 0.3 }}
                                        className="fixed inset-y-0 left-0 w-[260px] bg-brand-dark shadow-2xl flex flex-col z-50 md:hidden"
                                    >
                                        <div className="flex items-center justify-between p-4 border-b border-white/5">
                                            <Link to="/" onClick={() => setIsMobileMenuOpen(false)} className="font-bold text-primary tracking-wide text-2xl" style={{ fontFamily: "'Work Sans', sans-serif", letterSpacing: '1px' }}>
                                                FORMA-T
                                            </Link>
                                            <button onClick={() => setIsMobileMenuOpen(false)} className="text-gray-400 hover:text-white p-2 bg-white/5 rounded-md">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                            </button>
                                        </div>

                                        <nav className="flex-grow p-4 space-y-2 overflow-y-auto font-bold">
                                            <Link onClick={() => setIsMobileMenuOpen(false)} to="/cursos" className={`flex items-center gap-3 p-3 rounded-xl transition-all ${location.pathname === '/cursos' ? 'bg-primary text-white shadow-lg shadow-primary/20 scale-[1.02]' : 'text-gray-300 hover:bg-white/5'}`}>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" /><polyline points="9 22 9 12 15 12 15 22" /></svg>
                                                Inicio
                                            </Link>
                                            
                                            <Link onClick={() => setIsMobileMenuOpen(false)} to="/perfil" className={`flex items-center gap-3 p-3 rounded-xl transition-all ${location.pathname === '/perfil' ? 'bg-primary text-white shadow-lg shadow-primary/20 scale-[1.02]' : 'text-gray-300 hover:bg-white/5'}`}>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" /><circle cx="12" cy="7" r="4" /></svg>
                                                Mi perfil
                                            </Link>

                                            {!isAdmin && (
                                                <Link onClick={() => setIsMobileMenuOpen(false)} to="/mis-cursos" className={`flex items-center gap-3 p-3 rounded-xl transition-all ${location.pathname === '/mis-cursos' ? 'bg-primary text-white shadow-lg shadow-primary/20 scale-[1.02]' : 'text-gray-300 hover:bg-white/5'}`}>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-0.5-5.5Z"/><path d="M8 7h6"/><path d="M8 11h8"/><path d="M8 15h6"/></svg>
                                                    Mis Cursos
                                                </Link>
                                            )}

                                            {isAdmin && (
                                                <>
                                                    <div className="pt-4 pb-2 px-4 border-t border-white/5 mt-2">
                                                        <p className="text-[10px] font-bold text-gray-500 uppercase tracking-widest">PANEL DE CONTROL</p>
                                                    </div>
                                                    <Link onClick={() => setIsMobileMenuOpen(false)} to="/admin" className={`flex items-center gap-3 p-3 rounded-xl transition-all ${location.pathname.startsWith('/admin') ? 'bg-primary text-white shadow-lg shadow-primary/20 scale-[1.02]' : 'text-gray-300 hover:bg-white/5'}`}>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1-1z" /></svg>
                                                        Administración
                                                    </Link>
                                                    <Link onClick={() => setIsMobileMenuOpen(false)} to="/gestion" className={`flex items-center gap-3 p-3 rounded-xl transition-all ${location.pathname.startsWith('/gestion') ? 'bg-primary text-white shadow-lg shadow-primary/20 scale-[1.02]' : 'text-gray-300 hover:bg-white/5'}`}>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect width="7" height="7" x="3" y="3" rx="1" /><rect width="7" height="7" x="14" y="3" rx="1" /><rect width="7" height="7" x="14" y="14" rx="1" /><rect width="7" height="7" x="3" y="14" rx="1" /></svg>
                                                        Gestión
                                                    </Link>
                                                </>
                                            )}
                                        </nav>

                                        <div className="p-4 border-t border-white/5">
                                            <button
                                                onClick={() => { setIsMobileMenuOpen(false); handleLogout(); }}
                                                className="w-full flex items-center gap-3 p-3 rounded-xl font-bold text-red-400 hover:bg-red-400/10 transition-all font-bold"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                                Salir
                                            </button>
                                        </div>
                                    </motion.aside>
                                </div>
                            )}
                        </AnimatePresence>

                        {/* Desktop Sidebar */}
                        <aside className="w-[220px] bg-brand-dark shadow-[inset_-3px_0px_5px_rgba(0,0,0,0.1)] flex flex-col flex-shrink-0 z-40 hidden md:flex">
                            <div className="flex flex-col h-full px-4 py-8 overflow-y-auto">
                                <nav className="flex flex-col space-y-2">
                                <Link to="/cursos" className={`flex items-center px-4 py-3 rounded-md text-sm font-medium transition-all ${location.pathname === '/cursos' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-gray-300 hover:bg-white/10 hover:text-primary'}`}>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className="mr-3"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" /><polyline points="9 22 9 12 15 12 15 22" /></svg>
                                    Inicio
                                </Link>

                                <Link to="/perfil" className={`flex items-center px-4 py-3 rounded-md text-sm font-medium transition-all ${location.pathname === '/perfil' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-gray-300 hover:bg-white/10 hover:text-primary'}`}>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className="mr-3"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" /><circle cx="12" cy="7" r="4" /></svg>
                                    Mi perfil
                                </Link>

                                {!isAdmin && (
                                    <Link to="/mis-cursos" className={`flex items-center px-4 py-3 rounded-md text-sm font-medium transition-all ${location.pathname === '/mis-cursos' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-gray-300 hover:bg-white/10 hover:text-primary'}`}>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className="mr-3"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-0.5-5.5Z"/><path d="M8 7h6"/><path d="M8 11h8"/><path d="M8 15h6"/></svg>
                                        Mis Cursos
                                    </Link>
                                )}

                                {isAdmin && (
                                    <>
                                        <div className="pt-4 pb-2 px-4 border-t border-white/5 mt-2">
                                            <p className="text-[10px] font-bold text-gray-500 uppercase tracking-widest">PANEL DE CONTROL</p>
                                        </div>

                                        <Link to="/admin" className={`flex items-center px-4 py-3 rounded-md text-sm font-medium transition-all ${location.pathname.startsWith('/admin') ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-gray-300 hover:bg-white/10 hover:text-primary'}`}>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className="mr-3"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1-1z" /></svg>
                                            Administración
                                        </Link>

                                        <Link to="/gestion" className={`flex items-center px-4 py-3 rounded-md text-sm font-medium transition-all ${location.pathname.startsWith('/gestion') ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-gray-300 hover:bg-white/10 hover:text-primary'}`}>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className="mr-3"><rect width="7" height="7" x="3" y="3" rx="1" /><rect width="7" height="7" x="14" y="3" rx="1" /><rect width="7" height="7" x="14" y="14" rx="1" /><rect width="7" height="7" x="3" y="14" rx="1" /></svg>
                                            Gestión
                                        </Link>
                                    </>
                                )}
                            </nav>

                            <div className="pt-6 border-t border-white/5 mt-4">
                                <button
                                    onClick={handleLogout}
                                    className="flex items-center w-full px-4 py-3 rounded-md text-sm font-medium text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-all group"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className="mr-3 group-hover:translate-x-1 transition-transform"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                    Salir
                                </button>
                            </div>
                        </div>
                        </aside>
                    </>
                )}

                {/* Main Content Area */}
                <main className="flex-grow flex flex-col relative w-full items-center overflow-y-auto bg-gray-50/30 scroll-smooth">
                    <motion.div
                        initial={{ opacity: 0, x: 20 }}
                        animate={{ opacity: 1, x: 0 }}
                        transition={{ duration: 0.4 }}
                        className="flex-grow flex flex-col w-full"
                    >
                        <Outlet />
                    </motion.div>
                    
                    <footer className="w-full bg-white border-t border-brand-gray/20 py-5 text-center text-sm text-brand-gray mt-auto">
                        <p>&copy; {new Date().getFullYear()} Forma-T. Desarrollado por Estefanía Canales</p>
                    </footer>
                </main>
            </div>
        </div>
    );
};

export default Layout;
