import { useState, useEffect } from 'react';
import { useLocation } from 'react-router-dom';
import { motion } from 'framer-motion';
import api from '../services/api';

const AdminDashboard = () => {
    const location = useLocation();
    
    const [loading, setLoading] = useState(true);
    const [requests, setRequests] = useState([]);
    const [activeTab, setActiveTab] = useState('pending');

    useEffect(() => {
        const queryParams = new URLSearchParams(location.search);
        const tab = queryParams.get('tab');
        if (tab && ['pending', 'history'].includes(tab)) {
            setActiveTab(tab);
        }
    }, [location.search]);

    useEffect(() => {
        fetchData();
    }, []);

    const fetchData = () => {
        setLoading(true);
        api.get('/admin/requests')
            .then((reqRes) => {
                setRequests(reqRes.data);
            })
            .catch(err => console.error("Error cargando solicitudes:", err))
            .finally(() => setLoading(false));
    };

    const updateStatus = async (id, newStatus) => {
        try {
            await api.put(`/requests/${id}/status`, { status: newStatus });
            fetchData();
        } catch (err) {
            alert("Error al actualizar la solicitud");
        }
    };

    if (loading) return <div className="text-center py-20 text-brand-gray font-medium">Cargando panel de administración...</div>;

    const pendingRequests = requests.filter(r => r.status === 'pending');
    const resolvedRequests = requests.filter(r => r.status !== 'pending');

    const tabs = [
        { id: 'pending', label: 'Solicitudes pendientes', badge: pendingRequests.length },
        { id: 'history', label: 'Historial' },
    ];

    return (
        <div className="max-w-7xl mx-auto px-4 py-12 w-full bg-white text-brand-dark min-h-screen">
            <h1 className="text-3xl font-bold text-primary tracking-tight mb-8">
                Panel de Administración
            </h1>

            {/* Menú de navegación por pestañas */}
            <div className="flex border-b border-brand-light mb-8 space-x-1">
                {tabs.map(tab => (
                    <button
                        key={tab.id}
                        onClick={() => setActiveTab(tab.id)}
                        className={`px-8 py-4 text-lg font-bold rounded-t-xl transition-all flex items-center gap-2 ${activeTab === tab.id
                                ? 'bg-brand-light/20 text-primary border-b-2 border-primary -mb-[2px]'
                                : 'text-brand-gray hover:text-brand-dark hover:bg-brand-light/10'
                            }`}
                    >
                        {tab.label}
                        {tab.badge > 0 && (
                            <span className="bg-primary text-white text-base font-black px-2.5 py-1 rounded-full shadow-sm">
                                {tab.badge}
                            </span>
                        )}
                    </button>
                ))}
            </div>

            {/* Pestaña: Solicitudes Pendientes */}
            {activeTab === 'pending' && (
                <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} className="bg-brand-light/30 border border-brand-light rounded-2xl p-4 md:p-8 shadow-sm">
                    {pendingRequests.length === 0 ? (
                        <div className="text-center py-10">
                            <p className="text-brand-gray text-lg italic">No hay solicitudes esperando aprobación.</p>
                        </div>
                    ) : (
                        <div className="overflow-x-auto scrollbar-hide lg:overflow-visible">
                            <table className="w-full text-left border-collapse text-base">
                                <thead>
                                    <tr className="border-b border-brand-gray/20 text-brand-gray text-base font-bold">
                                        <th className="py-4 px-2 font-black">Estudiante</th>
                                        <th className="py-4 px-2 font-black">Curso</th>
                                        <th className="py-4 px-2 font-black">Fecha</th>
                                        <th className="py-4 px-2 text-left font-black">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {pendingRequests.map(req => (
                                        <tr key={req.request_id} className="border-b border-brand-light/50 hover:bg-white transition-colors">
                                            <td className="py-5 px-2">
                                                <div className="font-bold text-brand-dark">{req.user?.first_name} {req.user?.last_name}</div>
                                                <div className="text-base text-brand-gray truncate max-w-[150px]" title={req.user?.email}>{req.user?.email}</div>
                                            </td>
                                            <td className="py-5 px-2 font-bold text-brand-dark">
                                                {req.course?.course_name}
                                            </td>
                                            <td className="py-5 px-2 text-brand-gray text-base">{new Date(req.request_date).toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' })}</td>
                                            <td className="py-5 px-2">
                                                <div className="flex flex-col lg:flex-row items-stretch lg:items-center justify-start gap-2">
                                                    <button
                                                        onClick={() => updateStatus(req.request_id, 'approved')}
                                                    className="bg-green-100 hover:bg-green-200 text-green-700 px-4 py-2 rounded-lg text-base font-bold transition-all shadow-sm"
                                                >
                                                    Aprobar
                                                </button>
                                                <button
                                                    onClick={() => updateStatus(req.request_id, 'rejected')}
                                                    className="bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-lg text-base font-bold transition-all shadow-sm"
                                                >
                                                    Rechazar
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    )}
                </motion.div>
            )}

            {/* Pestaña: Historial */}
            {activeTab === 'history' && (
                <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} className="bg-brand-light/20 border border-brand-light rounded-2xl p-8">
                    {resolvedRequests.length === 0 ? (
                        <p className="text-brand-gray italic text-center py-10">No hay historial de solicitudes.</p>
                    ) : (
                        <div className="overflow-x-auto scrollbar-hide lg:overflow-visible">
                            <table className="w-full text-left border-collapse text-base">
                                <thead>
                                    <tr className="border-b border-brand-gray/20 text-brand-gray text-base font-bold">
                                        <th className="py-4 px-4 font-black">Estudiante</th>
                                        <th className="py-4 px-4 font-black">Curso</th>
                                        <th className="py-4 px-4 font-black">Estado</th>
                                        <th className="py-4 px-4 font-black">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {resolvedRequests.map(req => (
                                        <tr key={req.request_id} className="border-b border-brand-light/50">
                                            <td className="py-4 px-4 text-brand-dark font-bold">{req.user?.first_name} {req.user?.last_name}</td>
                                            <td className="py-4 px-4 text-brand-gray font-medium">{req.course?.course_name}</td>
                                            <td className="py-4 px-4">
                                                <span className={`px-3 py-1 rounded-full text-base font-bold ${req.status === 'approved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'
                                                    }`}>
                                                    {req.status === 'approved' ? 'Aprobada' : 'Rechazada'}
                                                </span>
                                            </td>
                                            <td className="py-4 px-4 text-base text-brand-gray font-medium">{new Date(req.request_date).toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' })}</td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    )}
                </motion.div>
            )}
        </div>
    );
};
export default AdminDashboard;
