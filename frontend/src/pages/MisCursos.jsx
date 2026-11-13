import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import api from '../services/api';
import CourseCard from '../components/CourseCard';
import { useAuth } from '../context/AuthContext';

const MisCursos = () => {
    const { isAdmin } = useAuth();
    const [enrolledCourses, setEnrolledCourses] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        if (isAdmin) {
            // Si es admin, mostrar todos los cursos
            api.get('/courses')
                .then(res => setEnrolledCourses(res.data))
                .catch(err => console.error(err))
                .finally(() => setLoading(false));
        } else {
            // Si es estudiante, mostrar solo aprobados
            api.get('/requests/my')
                .then(res => {
                    // Filtrar solo los cursos aprobados (con acceso permitido)
                    const approved = res.data.filter(req => req.allowed_access || req.status === 'approved');
                    // Extraer la información del curso
                    const courses = approved.map(req => req.course).filter(c => c !== null);
                    setEnrolledCourses(courses);
                })
                .catch(err => console.error(err))
                .finally(() => setLoading(false));
        }
    }, [isAdmin]);

    if (loading) return <div className="text-center py-20 text-primary">Cargando cursos...</div>;

    return (
        <div className="max-w-7xl mx-auto px-4 py-12 w-full">
            <h1 className="text-3xl font-bold text-primary tracking-tight mb-8">
                {isAdmin ? 'Administrar Cursos' : 'Mis Cursos'}
            </h1>

            {enrolledCourses.length === 0 ? (
                <div className="bg-white p-8 rounded-xl border border-brand-light shadow-sm text-center">
                    <p className="text-brand-gray mb-4 text-lg">
                        {isAdmin ? 'No hay cursos disponibles para administrar.' : 'Aún no estás matriculado en ningún curso.'}
                    </p>
                    <Link to="/cursos" className="inline-block bg-primary text-white px-6 py-2 rounded-md font-medium hover:bg-primary-hover transition-all">
                        Explorar Catálogo
                    </Link>
                </div>
            ) : (
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    {enrolledCourses.map((course, i) => (
                        <CourseCard key={course.course_id} course={course} index={i}>
                            <Link
                                to={`/cursos/${course.course_id}`}
                                className="mt-auto block text-center bg-primary hover:bg-primary-hover text-white font-bold py-3 rounded transition-all shadow shadow-primary/20"
                            >
                                Acceder al curso
                            </Link>
                        </CourseCard>
                    ))}
                </div>
            )}
        </div>
    );
};

export default MisCursos;
