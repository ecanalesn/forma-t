import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import api from '../services/api';
import { motion } from 'framer-motion';
import CourseCard from '../components/CourseCard';
import { useAuth } from '../context/AuthContext';

const CourseCatalog = () => {
    const { isAdmin } = useAuth();
    const [courses, setCourses] = useState([]);
    const [myRequests, setMyRequests] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        Promise.all([
            api.get('/courses'),
            api.get('/requests/my')
        ])
        .then(([coursesRes, requestsRes]) => {
            setCourses(coursesRes.data);
            setMyRequests(requestsRes.data);
        })
        .catch(err => console.error(err))
        .finally(() => setLoading(false));
    }, []);

    const getRequestStatus = (courseId) => {
        return myRequests.find(req => req.course_id === courseId);
    };

    const handleRequest = async (courseId) => {
        try {
            const res = await api.post('/requests', { course_id: courseId });
            setMyRequests([...myRequests, res.data]);
        } catch (err) {
            console.error(err);
        }
    };

    if (loading) return <div className="text-center py-20 text-primary">Cargando cursos...</div>;

    return (
        <div className="max-w-7xl mx-auto px-4 py-12 w-full">
            <h1 className="text-3xl font-bold text-primary tracking-tight mb-8">Catálogo de Cursos</h1>
            
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {courses.map((course, i) => {
                    const request = getRequestStatus(course.course_id);
                    const canAccess = isAdmin || (request && request.status === 'approved');
                    
                    return (
                        <CourseCard key={course.course_id} course={course} index={i}>
                            {canAccess ? (
                                <div className="mt-auto">
                                    <Link 
                                        to={`/cursos/${course.course_id}`}
                                        className="block text-center bg-primary hover:bg-primary-hover text-white font-bold py-3 rounded transition-all shadow shadow-primary/20"
                                    >
                                        Acceder al curso
                                    </Link>
                                </div>
                            ) : request ? (
                                <div className="mt-auto space-y-3">
                                    {request.status === 'rejected' ? (
                                        <div className="block text-center bg-red-100 text-red-600 font-bold py-3 rounded border border-red-200">
                                            Solicitud Rechazada
                                        </div>
                                    ) : (
                                        <div className="block text-center bg-amber-100 text-amber-700 font-bold py-3 rounded border border-amber-200">
                                            Pendiente de aprobación
                                        </div>
                                    )}
                                </div>
                            ) : (
                                <button 
                                    onClick={() => handleRequest(course.course_id)}
                                    className="mt-auto block w-full text-center bg-primary/10 hover:bg-primary/20 text-primary font-bold py-3 rounded border border-primary/30 transition-all"
                                >
                                    Solicitar acceso
                                </button>
                            )}
                        </CourseCard>
                    );
                })}
            </div>
        </div>
    );
};

export default CourseCatalog;
