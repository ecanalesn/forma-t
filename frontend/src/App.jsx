import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { useAuth } from './context/AuthContext';
import Layout from './components/Layout';
import Landing from './pages/Landing';
import Login from './pages/Login';
import Register from './pages/Register';
import CourseCatalog from './pages/CourseCatalog';
import CourseView from './pages/CourseView';
import AdminDashboard from './pages/AdminDashboard';
import GestionDashboard from './pages/GestionDashboard';
import Profile from './pages/Profile';
import MisCursos from './pages/MisCursos';

const ProtectedRoute = ({ children, requiredRole }) => {
    const { isAuthenticated, user, loading } = useAuth();
    
    if (loading) return <div className="h-screen flex items-center justify-center">Cargando forma-T...</div>;
    
    if (!isAuthenticated) return <Navigate to="/login" />;
    
    if (requiredRole && user?.role !== requiredRole) {
        return <Navigate to="/cursos" />;
    }
    
    return children;
};

// Comprobar si es invitado (redirigir a cursos si ya ha iniciado sesión)
const GuestRoute = ({ children }) => {
    const { isAuthenticated, loading } = useAuth();
    if (loading) return null;
    if (isAuthenticated) return <Navigate to="/cursos" />;
    return children;
};

function App() {
  return (
    <Router>
        <Routes>
            <Route path="/" element={<Layout />}>
                <Route index element={<GuestRoute><Landing /></GuestRoute>} />
                <Route path="login" element={<GuestRoute><Login /></GuestRoute>} />
                <Route path="register" element={<GuestRoute><Register /></GuestRoute>} />
                
                {/* Rutas Protegidas (Estudiantes y Administradores) */}
                <Route path="cursos" element={
                    <ProtectedRoute>
                        <CourseCatalog />
                    </ProtectedRoute>
                } />
                <Route path="cursos/:id" element={
                    <ProtectedRoute>
                        <CourseView />
                    </ProtectedRoute>
                } />
                <Route path="mis-cursos" element={
                    <ProtectedRoute>
                        <MisCursos />
                    </ProtectedRoute>
                } />

                {/* Rutas de Administrador y Gestión */}
                <Route path="admin" element={
                    <ProtectedRoute requiredRole="admin">
                        <AdminDashboard />
                    </ProtectedRoute>
                } />
                <Route path="gestion" element={
                    <ProtectedRoute requiredRole="admin">
                        <GestionDashboard />
                    </ProtectedRoute>
                } />

                {/* Perfil de Usuario */}
                <Route path="perfil" element={
                    <ProtectedRoute>
                        <Profile />
                    </ProtectedRoute>
                } />
            </Route>
        </Routes>
    </Router>
  );
}

export default App;
