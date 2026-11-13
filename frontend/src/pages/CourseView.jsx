import { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import api from '../services/api';
import { useAuth } from '../context/AuthContext';

const CourseView = () => {
    const { id } = useParams();
    const { isAdmin, user } = useAuth();
    const navigate = useNavigate();

    const [course, setCourse] = useState(null);
    const [requestStatus, setRequestStatus] = useState(null); // 'pending', 'approved', 'rejected', o nulo
    const [hasAccess, setHasAccess] = useState(false);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchData = async () => {
            try {
                const [courseRes, reqRes] = await Promise.all([
                    api.get(`/courses/${id}`),
                    api.get('/requests/my')
                ]);

                setCourse(courseRes.data);

                // Comprobar si el usuario solicitó este curso
                const request = reqRes.data.find(r => r.course_id == id);
                if (request) {
                    setRequestStatus(request.status);
                    setHasAccess(request.allowed_access == 1);
                }

                // Los administradores siempre tienen acceso
                if (isAdmin) setHasAccess(true);

            } catch (err) {
                console.error(err);
                if (err.response?.status === 404) navigate('/cursos');
            } finally {
                setLoading(false);
            }
        };
        fetchData();
    }, [id, isAdmin, navigate]);

    const [showModal, setShowModal] = useState(false);

    const handleRequestAccess = async () => {
        try {
            const res = await api.post('/requests', { course_id: id });
            setRequestStatus(res.data.status);
            setShowModal(true); // Mostrar el popup de confirmación
        } catch (err) {
            alert(err.response?.data?.message || "Error al solicitar acceso");
        }
    };

    if (loading) return <div className="text-center py-20">Cargando detalles...</div>;
    if (!course) return null;

    return (
        <div className="max-w-4xl mx-auto px-4 py-12 w-full bg-white text-brand-dark">
            {/* Modal de confirmación de solicitud */}
            {showModal && (
                <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                    <div className="bg-white rounded-xl shadow-2xl p-8 max-w-md w-full mx-4 text-center">
                        <h3 className="text-2xl font-bold text-brand-dark mb-3">¡Solicitud enviada!</h3>
                        <p className="text-brand-gray mb-6">Tu solicitud de acceso ha sido enviada correctamente. El administrador la revisará y te notificará si ha sido aprobada o no.</p>
                        <button
                            onClick={() => setShowModal(false)}
                            className="px-8 py-3 bg-primary hover:bg-primary-hover text-white font-bold rounded transition-colors shadow"
                        >
                            Aceptar
                        </button>
                    </div>
                </div>
            )}

            <button onClick={() => navigate(-1)} className="text-brand-gray hover:text-primary mb-6 flex items-center text-sm font-semibold">
                &larr; Volver al catálogo
            </button>

            <div className="bg-white border border-brand-light shadow-lg rounded-xl overflow-hidden">
                {course.image && (
                    <img src={course.image.replace('../', '/')} alt={course.course_name} className="w-full h-64 object-cover" />
                )}

                <div className="p-8 md:p-12">
                    <div className="flex justify-between items-start mb-6">
                        <div>
                            <span className="text-sm font-semibold text-primary uppercase tracking-wider">{course.category}</span>
                            <h1 className="text-2xl md:text-3xl font-bold text-brand-dark mt-2">{course.course_name}</h1>
                        </div>
                    </div>

                    <p className="text-base text-brand-gray mb-8 leading-relaxed" style={{ textAlign: 'justify' }}>
                        {course.description.startsWith('Descripción:') ? (
                            <>
                                <strong>Descripción:</strong> {course.description.substring(12)}
                            </>
                        ) : (
                            course.description
                        )}
                    </p>

                    {/* Lógica de Acceso */}
                    {!hasAccess ? (
                        <div className="bg-brand-light border border-brand-gray/20 rounded-xl p-8 mt-8 shadow-sm">
                            <h3 className="text-2xl font-bold text-brand-dark mb-3 text-center">Contenido restringido</h3>
                            <p className="text-brand-gray mb-6 max-w-lg mx-auto" style={{ textAlign: 'justify' }}>Para ver el material completo y los vídeos de este curso, necesitas solicitar acceso al administrador.</p>

                            {!requestStatus ? (
                                <div className="text-center">
                                    <button
                                        onClick={handleRequestAccess}
                                        className="px-8 py-3 bg-primary hover:bg-primary-hover rounded text-white font-bold transition-colors shadow"
                                    >
                                        Solicitar acceso
                                    </button>
                                </div>
                            ) : (
                                <div className="text-center">
                                    <div className={`inline-block px-6 py-3 rounded font-medium ${requestStatus === 'pending' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' :
                                        requestStatus === 'rejected' ? 'bg-red-100 text-red-800 border border-red-200' : ''
                                        }`}>
                                        Estado de solicitud: {requestStatus === 'pending' ? 'Pendiente' : 'Rechazada'}
                                    </div>
                                </div>
                            )}
                        </div>
                    ) : (
                        <div className="mt-12 space-y-8 animate-fade-in text-left">
                            <h2 className="text-2xl font-bold border-b border-brand-gray/20 pb-4 text-brand-dark">Contenido del Curso</h2>
                            <div className="prose max-w-none text-brand-gray">
                                {course.content.split('\n').map((paragraph, i) => (
                                    <p key={i} className="mb-4" style={{ textAlign: 'justify' }}>{paragraph}</p>
                                ))}
                            </div>

                            {course.video_link && (
                                <div className="mt-8 rounded-xl overflow-hidden shadow-lg border border-brand-light bg-black aspect-video">
                                    <iframe
                                        width="100%"
                                        height="100%"
                                        src={course.video_link}
                                        title="Video del curso"
                                        frameBorder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowFullScreen
                                    ></iframe>
                                </div>
                            )}
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
};

export default CourseView;
