import { useState, useEffect } from 'react';
import { useLocation } from 'react-router-dom';
import { motion } from 'framer-motion';
import api from '../services/api';

const GestionDashboard = () => {
    const location = useLocation();
    
    // Cursos State
    const [showCourseModal, setShowCourseModal] = useState(false);
    const [editingCourse, setEditingCourse] = useState(null);
    const [courseForm, setCourseForm] = useState({
        course_name: '', description: '', category: 'Teoría Musical', image: '', image_file: null, content: '', video_link: '', status: 1
    });
    
    // Users State
    const [showUserModal, setShowUserModal] = useState(false);
    const [editingUser, setEditingUser] = useState(null);
    const [userForm, setUserForm] = useState({
        first_name: '', last_name: '', email: '', password: '', role: 'student', status: 1, phone: ''
    });
    
    const [loading, setLoading] = useState(true);
    const [courses, setCourses] = useState([]);
    const [users, setUsers] = useState([]);
    const [activeTab, setActiveTab] = useState('courses');

    // Modal de confirmación de eliminación
    const [deleteConfirm, setDeleteConfirm] = useState({ show: false, type: null, id: null, name: '' });

    const openDeleteConfirm = (type, id, name) => setDeleteConfirm({ show: true, type, id, name });
    const closeDeleteConfirm = () => setDeleteConfirm({ show: false, type: null, id: null, name: '' });

    const confirmDelete = async () => {
        try {
            if (deleteConfirm.type === 'course') {
                await api.delete(`/courses/${deleteConfirm.id}`);
            } else {
                await api.delete(`/users/${deleteConfirm.id}`);
            }
            fetchData();
        } catch (err) {
            alert(`Error al eliminar el ${deleteConfirm.type === 'course' ? 'curso' : 'usuario'}`);
        } finally {
            closeDeleteConfirm();
        }
    };

    useEffect(() => {
        const queryParams = new URLSearchParams(location.search);
        const tab = queryParams.get('tab');
        if (tab) {
            setActiveTab(tab);
        }
    }, [location.search]);

    useEffect(() => {
        fetchData();
    }, []);

    const fetchData = () => {
        setLoading(true);
        Promise.all([
            api.get('/admin/courses'),
            api.get('/users')
        ])
            .then(([courseRes, userRes]) => {
                setCourses(courseRes.data);
                setUsers(userRes.data);
            })
            .catch(err => console.error("Error cargando datos de gestión:", err))
            .finally(() => setLoading(false));
    };

    // ----- CURSOS HANDLERS -----
    const handleOpenCourseModal = (course = null) => {
        if (course) {
            setEditingCourse(course);
            setCourseForm({
                course_name: course.course_name, description: course.description, category: course.category,
                image: course.image, image_file: null, content: course.content, video_link: course.video_link, status: course.status
            });
        } else {
            setEditingCourse(null);
            setCourseForm({
                course_name: '', description: 'Descripción: ', category: 'Teoría Musical',
                image: '', image_file: null, content: '', video_link: '', status: 1
            });
        }
        setShowCourseModal(true);
    };

    const handleCourseSubmit = async (e) => {
        e.preventDefault();
        try {
            const formData = new FormData();
            formData.append('course_name', courseForm.course_name);
            formData.append('description', courseForm.description);
            formData.append('category', courseForm.category);
            formData.append('content', courseForm.content);
            formData.append('video_link', courseForm.video_link);
            formData.append('status', courseForm.status);
            
            if (courseForm.image_file) {
                formData.append('image_file', courseForm.image_file);
            }

            if (editingCourse) {
                formData.append('_method', 'PUT');
                await api.post(`/courses/${editingCourse.course_id}`, formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });
            } else {
                await api.post('/courses', formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });
            }
            setShowCourseModal(false);
            fetchData();
        } catch (err) {
            alert(err.response?.data?.message || "Error al guardar el curso");
        }
    };

    const handleDeleteCourse = (id, name) => openDeleteConfirm('course', id, name);

    // ----- USUARIOS HANDLERS -----
    const handleOpenUserModal = (user = null) => {
        if (user) {
            setEditingUser(user);
            setUserForm({
                first_name: user.first_name, last_name: user.last_name, email: user.email,
                role: user.role, status: user.status, phone: user.phone || '', password: ''
            });
        } else {
            setEditingUser(null);
            setUserForm({
                first_name: '', last_name: '', email: '', password: '', role: 'student', status: 1, phone: ''
            });
        }
        setShowUserModal(true);
    };

    const handleUserSubmit = async (e) => {
        e.preventDefault();
        try {
            const payload = { ...userForm };
            if (editingUser) {
                if (!payload.password) delete payload.password;
                await api.put(`/users/${editingUser.user_id}`, payload);
            } else {
                await api.post('/users', payload);
            }
            setShowUserModal(false);
            fetchData();
        } catch (err) {
            alert(err.response?.data?.message || "Error al guardar el usuario");
        }
    };

    const handleDeleteUser = (id, name) => openDeleteConfirm('user', id, name);

    if (loading) return <div className="text-center py-20 text-brand-gray font-medium">Cargando panel de gestión...</div>;

    const tabs = [
        { id: 'courses', label: 'Cursos' },
        { id: 'users', label: 'Usuarios' },
    ];

    return (
        <div className="max-w-7xl mx-auto px-4 py-12 w-full bg-white text-brand-dark min-h-screen">
            <h1 className="text-3xl font-bold text-primary tracking-tight mb-8">
                Panel de Gestión
            </h1>

            {/* Modal de Confirmación de Eliminación */}
            {deleteConfirm.show && (
                <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-[70] p-4">
                    <motion.div
                        initial={{ opacity: 0, scale: 0.95 }}
                        animate={{ opacity: 1, scale: 1 }}
                        className="bg-white rounded-lg shadow-2xl w-full max-w-md"
                    >
                        <div className="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                            <h5 className="text-lg font-bold text-brand-dark">
                                Confirmar eliminación
                            </h5>
                            <button onClick={closeDeleteConfirm} className="text-gray-400 hover:text-gray-600 text-xl font-bold leading-none">&times;</button>
                        </div>
                        <div className="px-6 py-5">
                            <p className="text-brand-gray text-sm">
                                ¿Estás seguro de que deseas eliminar <span className="font-semibold text-brand-dark">"{deleteConfirm.name}"</span>? Esta acción no se puede deshacer.
                            </p>
                        </div>
                        <div className="flex justify-end gap-3 px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-lg">
                            <button
                                onClick={closeDeleteConfirm}
                                className="px-5 py-2 rounded border border-gray-300 text-brand-gray font-medium text-sm hover:bg-gray-100 transition-colors"
                            >
                                Cancelar
                            </button>
                            <button
                                onClick={confirmDelete}
                                className="px-5 py-2 rounded bg-red-600 hover:bg-red-700 text-white font-medium text-sm transition-colors shadow"
                            >
                                Eliminar
                            </button>
                        </div>
                    </motion.div>
                </div>
            )}

            {/* Modal de Curso */}
            {showCourseModal && (
                <div className="fixed inset-0 bg-brand-dark/80 backdrop-blur-sm flex items-center justify-center z-[60] p-4">
                    <motion.div
                        initial={{ opacity: 0, scale: 0.95 }}
                        animate={{ opacity: 1, scale: 1 }}
                        className="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
                    >
                        <div className="p-8">
                            <h2 className="text-2xl font-bold mb-6 text-brand-dark">
                                {editingCourse ? 'Editar Curso' : 'Nuevo Curso'}
                            </h2>
                            <form onSubmit={handleCourseSubmit} className="space-y-4">
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div className="space-y-1">
                                        <label className="text-sm font-bold text-brand-gray">Nombre del Curso</label>
                                        <input
                                            type="text" required
                                            className="w-full px-4 py-3 rounded-lg border border-brand-light focus:ring-2 focus:ring-primary outline-none transition-all"
                                            value={courseForm.course_name}
                                            onChange={e => setCourseForm({ ...courseForm, course_name: e.target.value })}
                                        />
                                    </div>
                                    <div className="space-y-1">
                                        <label className="text-sm font-bold text-brand-gray">Categoría</label>
                                        <select
                                            className="w-full px-4 py-3 rounded-lg border border-brand-light focus:ring-2 focus:ring-primary outline-none transition-all"
                                            value={courseForm.category}
                                            onChange={e => setCourseForm({ ...courseForm, category: e.target.value })}
                                        >
                                            <option value="Teoría Musical">Teoría Musical</option>
                                            <option value="Instrumento">Instrumento</option>
                                        </select>
                                    </div>
                                </div>
                                <div className="space-y-1">
                                    <label className="text-sm font-bold text-brand-gray">Descripción</label>
                                    <textarea
                                        required rows="3"
                                        className="w-full px-4 py-3 rounded-lg border border-brand-light focus:ring-2 focus:ring-primary outline-none transition-all resize-none"
                                        value={courseForm.description}
                                        onChange={e => setCourseForm({ ...courseForm, description: e.target.value })}
                                    ></textarea>
                                </div>
                                <div className="space-y-1">
                                    <label className="text-sm font-bold text-brand-gray">Contenido del curso</label>
                                    <textarea
                                        required rows="5"
                                        className="w-full px-4 py-3 rounded-lg border border-brand-light focus:ring-2 focus:ring-primary outline-none transition-all resize-none"
                                        value={courseForm.content}
                                        onChange={e => setCourseForm({ ...courseForm, content: e.target.value })}
                                    ></textarea>
                                </div>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div className="space-y-1">
                                        <label className="text-sm font-bold text-brand-gray">Imagen (PNG/JPG)</label>
                                        <input
                                            type="file"
                                            accept=".png, .jpg, .jpeg"
                                            className="w-full px-4 py-3 rounded-lg border border-brand-light focus:ring-2 focus:ring-primary outline-none transition-all file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-brand-light file:text-brand-gray hover:file:bg-brand-light/70 file:transition-all"
                                            onChange={e => setCourseForm({ ...courseForm, image_file: e.target.files[0] })}
                                        />
                                        {editingCourse && !courseForm.image_file && (
                                            <p className="text-xs text-brand-gray mt-1">
                                                Imagen actual: {courseForm.image.split('/').pop()}
                                            </p>
                                        )}
                                        {!editingCourse && !courseForm.image_file && (
                                            <p className="text-xs text-brand-gray mt-1">
                                                Si no subes imagen, se usará la guardada por defecto.
                                            </p>
                                        )}
                                    </div>
                                    <div className="space-y-1">
                                        <label className="text-sm font-bold text-brand-gray">Link Video (YouTube Embed)</label>
                                        <input
                                            type="text" required
                                            className="w-full px-4 py-3 rounded-lg border border-brand-light focus:ring-2 focus:ring-primary outline-none transition-all"
                                            value={courseForm.video_link}
                                            onChange={e => setCourseForm({ ...courseForm, video_link: e.target.value })}
                                        />
                                    </div>
                                    <div className="space-y-1">
                                        <label className="text-sm font-bold text-brand-gray">Estado del Curso</label>
                                        <select
                                            className="w-full px-4 py-3 rounded-lg border border-brand-light focus:ring-2 focus:ring-primary outline-none transition-all"
                                            value={courseForm.status}
                                            onChange={e => setCourseForm({ ...courseForm, status: parseInt(e.target.value) })}
                                        >
                                            <option value={1}>Activo</option>
                                            <option value={0}>Inactivo</option>
                                        </select>
                                    </div>
                                </div>
                                <div className="flex justify-end gap-3 pt-6 border-t border-brand-light mt-6">
                                    <button
                                        type="button"
                                        onClick={() => setShowCourseModal(false)}
                                        className="px-6 py-3 text-brand-gray font-bold hover:text-brand-dark transition-colors"
                                    >
                                        Cancelar
                                    </button>
                                    <button
                                        type="submit"
                                        className="px-8 py-3 bg-primary hover:bg-primary-hover text-white font-bold rounded-lg shadow-lg shadow-primary/30 transition-all"
                                    >
                                        {editingCourse ? 'Actualizar Curso' : 'Crear Curso'}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </motion.div>
                </div>
            )}

            {/* Modal de Usuario */}
            {showUserModal && (
                <div className="fixed inset-0 bg-brand-dark/80 backdrop-blur-sm flex items-center justify-center z-[60] p-4">
                    <motion.div
                        initial={{ opacity: 0, scale: 0.95 }}
                        animate={{ opacity: 1, scale: 1 }}
                        className="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
                    >
                        <div className="p-8">
                            <h2 className="text-2xl font-bold mb-6 text-brand-dark">
                                {editingUser ? 'Editar Usuario' : 'Nuevo Usuario'}
                            </h2>
                            <form onSubmit={handleUserSubmit} className="space-y-4">
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div className="space-y-1">
                                        <label className="text-sm font-bold text-brand-gray">Nombre</label>
                                        <input
                                            type="text" required
                                            className="w-full px-4 py-3 rounded-lg border border-brand-light focus:ring-2 focus:ring-primary outline-none transition-all"
                                            value={userForm.first_name}
                                            onChange={e => setUserForm({ ...userForm, first_name: e.target.value })}
                                        />
                                    </div>
                                    <div className="space-y-1">
                                        <label className="text-sm font-bold text-brand-gray">Apellidos</label>
                                        <input
                                            type="text" required
                                            className="w-full px-4 py-3 rounded-lg border border-brand-light focus:ring-2 focus:ring-primary outline-none transition-all"
                                            value={userForm.last_name}
                                            onChange={e => setUserForm({ ...userForm, last_name: e.target.value })}
                                        />
                                    </div>
                                </div>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div className="space-y-1">
                                        <label className="text-sm font-bold text-brand-gray">Email</label>
                                        <input
                                            type="email" required
                                            pattern="^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$"
                                            className="w-full px-4 py-3 rounded-lg border border-brand-light focus:ring-2 focus:ring-primary outline-none transition-all"
                                            value={userForm.email}
                                            onInvalid={(e) => e.target.setCustomValidity('Por favor, introduce una dirección de correo válida (ej. usuario@dominio.com)')}
                                            onInput={(e) => e.target.setCustomValidity('')}
                                            onChange={e => setUserForm({ ...userForm, email: e.target.value })}
                                        />
                                    </div>
                                    <div className="space-y-1">
                                        <label className="text-sm font-bold text-brand-gray">Teléfono</label>
                                        <input
                                            type="tel"
                                            pattern="[0-9]{9}"
                                            maxLength="9"
                                            className="w-full px-4 py-3 rounded-lg border border-brand-light focus:ring-2 focus:ring-primary outline-none transition-all"
                                            value={userForm.phone}
                                            onInvalid={(e) => e.target.setCustomValidity('El teléfono debe tener exactamente 9 dígitos numéricos')}
                                            onInput={(e) => e.target.setCustomValidity('')}
                                            onChange={e => setUserForm({ ...userForm, phone: e.target.value.replace(/\D/g, '') })}
                                        />
                                    </div>
                                </div>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div className="space-y-1">
                                        <label className="text-sm font-bold text-brand-gray">Contraseña {editingUser && '(vacío para no cambiar)'}</label>
                                        <input
                                            type="password" 
                                            required={!editingUser}
                                            minLength="6"
                                            className="w-full px-4 py-3 rounded-lg border border-brand-light focus:ring-2 focus:ring-primary outline-none transition-all"
                                            value={userForm.password}
                                            onInvalid={(e) => e.target.setCustomValidity('La contraseña debe tener al menos 6 caracteres')}
                                            onInput={(e) => e.target.setCustomValidity('')}
                                            onChange={e => setUserForm({ ...userForm, password: e.target.value })}
                                        />
                                    </div>
                                    <div className="space-y-1">
                                        <label className="text-sm font-bold text-brand-gray">Rol</label>
                                        <select
                                            className="w-full px-4 py-3 rounded-lg border border-brand-light focus:ring-2 focus:ring-primary outline-none transition-all"
                                            value={userForm.role}
                                            onChange={e => setUserForm({ ...userForm, role: e.target.value })}
                                        >
                                            <option value="student">Estudiante</option>
                                            <option value="admin">Administrador</option>
                                        </select>
                                    </div>
                                </div>
                                <div className="flex justify-end gap-3 pt-6 border-t border-brand-light mt-6">
                                    <button
                                        type="button"
                                        onClick={() => setShowUserModal(false)}
                                        className="px-6 py-3 text-brand-gray font-bold hover:text-brand-dark transition-colors"
                                    >
                                        Cancelar
                                    </button>
                                    <button
                                        type="submit"
                                        className="px-8 py-3 bg-primary hover:bg-primary-hover text-white font-bold rounded-lg shadow-lg shadow-primary/30 transition-all"
                                    >
                                        {editingUser ? 'Actualizar Usuario' : 'Crear Usuario'}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </motion.div>
                </div>
            )}

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
                    </button>
                ))}
            </div>

            {/* Pestaña: Gestión de Cursos */}
            {activeTab === 'courses' && (
                <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} className="bg-brand-light/20 border border-brand-light rounded-2xl p-4 md:p-8 ml-2 mr-2">
                    <div className="flex justify-between items-center mb-8">
                        <h2 className="text-xl font-bold text-brand-dark">Listado de Cursos</h2>
                        <button
                            onClick={() => handleOpenCourseModal()}
                            className="bg-primary hover:bg-primary-hover text-white px-5 py-2 rounded-lg font-bold text-base shadow-md shadow-primary/20 transition-all flex items-center gap-2"
                        >
                            <span className="text-base">+</span> Nuevo Curso
                        </button>
                    </div>

                    <div className="overflow-x-auto scrollbar-hide lg:overflow-visible">
                        <table className="w-full text-left border-collapse text-base">
                            <thead>
                                <tr className="border-b border-brand-gray/20 text-brand-gray text-base font-bold">
                                    <th className="py-4 px-2 font-black">Miniatura</th>
                                    <th className="py-4 px-2 font-black">Nombre</th>
                                    <th className="py-4 px-2 font-black">Categoría</th>
                                    <th className="py-4 px-2 font-black text-left">Estado</th>
                                    <th className="py-4 px-2 text-left font-black">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                {courses.map(course => (
                                    <tr key={course.course_id} className="border-b border-brand-light/50 hover:bg-white transition-colors">
                                        <td className="py-4 px-2">
                                            <img
                                                src={course.image.replace('../', '/')}
                                                alt=""
                                                className="w-12 h-12 rounded-lg object-cover border border-brand-light shadow-sm"
                                            />
                                        </td>
                                        <td className="py-4 px-2 font-bold text-brand-dark truncate max-w-[120px]" title={course.course_name}>{course.course_name}</td>
                                        <td className="py-4 px-2 text-brand-gray font-medium">{course.category}</td>
                                        <td className="py-4 px-2">
                                            <span className={`px-3 py-1 rounded-full text-base font-bold ${course.status ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-500'}`}>
                                                {course.status ? 'Activo' : 'Inactivo'}
                                            </span>
                                        </td>
                                        <td className="py-4 px-2">
                                            <div className="flex flex-col lg:flex-row items-stretch lg:items-center justify-start gap-2">
                                                <button
                                                    onClick={() => handleOpenCourseModal(course)}
                                                className="text-brand-dark hover:text-primary font-bold text-base p-2 transition-colors underline decoration-primary/30 underline-offset-4"
                                            >
                                                Editar
                                            </button>
                                            <button
                                                onClick={() => handleDeleteCourse(course.course_id, course.course_name)}
                                                className="text-red-500 hover:text-red-700 font-bold text-base p-2 transition-colors underline decoration-red-200 underline-offset-4"
                                            >
                                                Eliminar
                                            </button>
                                            </div>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </motion.div>
            )}

            {/* Pestaña: Gestión de Usuarios */}
            {activeTab === 'users' && (
                <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} className="bg-brand-light/20 border border-brand-light rounded-2xl p-8">
                    <div className="flex justify-between items-center mb-8">
                        <h2 className="text-xl font-bold text-brand-dark">Listado de Usuarios</h2>
                        <button
                            onClick={() => handleOpenUserModal()}
                            className="bg-primary hover:bg-primary-hover text-white px-5 py-2 rounded-lg font-bold text-base shadow-md shadow-primary/20 transition-all flex items-center gap-2"
                        >
                            <span className="text-base">+</span> Nuevo Usuario
                        </button>
                    </div>

                    <div className="overflow-x-auto scrollbar-hide lg:overflow-visible">
                        <table className="w-full text-left border-collapse text-base">
                            <thead>
                                <tr className="border-b border-brand-gray/20 text-brand-gray text-base font-bold">
                                    <th className="py-4 px-2 font-black">Nombre</th>
                                    <th className="py-4 px-2 font-black">Email</th>
                                    <th className="py-4 px-2 font-black">Rol</th>
                                    <th className="py-4 px-2 font-black">Estado</th>
                                    <th className="py-4 px-2 text-left font-black">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                {users.map(user => (
                                    <tr key={user.user_id} className="border-b border-brand-light/50 hover:bg-white transition-colors">
                                        <td className="py-4 px-2 font-bold text-brand-dark truncate max-w-[150px]" title={`${user.first_name} ${user.last_name}`}>{user.first_name} {user.last_name}</td>
                                        <td className="py-4 px-2 text-brand-gray font-medium truncate max-w-[120px]" title={user.email}>{user.email}</td>
                                        <td className="py-4 px-2 text-brand-gray font-medium">
                                            {user.role === 'admin' ? 'Administrador' : 'Estudiante'}
                                        </td>
                                        <td className="py-4 px-2">
                                            <span className={`px-3 py-1 rounded-full text-base font-bold ${user.status ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-500'}`}>
                                                {user.status ? 'Activo' : 'Inactivo'}
                                            </span>
                                        </td>
                                        <td className="py-4 px-2">
                                            <div className="flex flex-col lg:flex-row items-stretch lg:items-center justify-start gap-2">
                                                <button
                                                    onClick={() => handleOpenUserModal(user)}
                                                className="text-brand-dark hover:text-primary font-bold text-base p-2 transition-colors underline decoration-primary/30 underline-offset-4"
                                            >
                                                Editar
                                            </button>
                                            <button
                                                onClick={() => handleDeleteUser(user.user_id, `${user.first_name} ${user.last_name}`)}
                                                className="text-red-500 hover:text-red-700 font-bold text-base p-2 transition-colors underline decoration-red-200 underline-offset-4"
                                            >
                                                Eliminar
                                            </button>
                                            </div>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </motion.div>
            )}
        </div>
    );
};
export default GestionDashboard;
