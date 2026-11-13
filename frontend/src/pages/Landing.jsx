import { Link } from 'react-router-dom';
import { motion } from 'framer-motion';

const Landing = () => {
    return (
        <div className="flex flex-col items-center flex-grow py-20 px-4 w-full bg-white text-brand-dark">
            <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.6 }}
                className="max-w-3xl text-center z-10"
            >
                <h1 className="text-4xl md:text-6xl font-extrabold tracking-tight mb-6 text-brand-dark">
                    Descubre el ritmo de <br/> <span className="text-primary">tu talento</span>
                </h1>

                <p className="text-lg md:text-xl text-brand-gray mb-10 max-w-2xl mx-auto leading-relaxed font-medium text-justify">
                    Aprende música de manera autodidacta. Con FORMA-T, accede a recursos interactivos, videoclases y guías prácticas para dominar cualquier instrumento desde cero, incluso si nunca has tocado uno antes.
                    <br /><br />
                    <span className="text-2xl text-primary font-bold block text-center">
                        Empieza hoy y convierte tu pasión por la música <br /> en una habilidad real
                    </span>
                </p>

                <div className="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                    <Link to="/register" className="px-8 py-3 bg-primary hover:bg-primary-hover text-white font-semibold text-lg rounded transition-colors shadow-md">
                        Comenzar gratis
                    </Link>

                    <Link to="/login" className="px-8 py-3 bg-transparent hover:bg-brand-gray/10 border-2 border-primary text-primary font-semibold text-lg rounded transition-colors">
                        Ya tengo cuenta
                    </Link>
                </div>
            </motion.div>

            {/* Features Normal Grid */}
            <motion.div
                initial={{ opacity: 0, y: 40 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.8, delay: 0.2 }}
                className="grid grid-cols-1 md:grid-cols-3 gap-8 mt-24 max-w-6xl w-full z-10"
            >
                {[
                    { title: "Aprende a tu ritmo", desc: "Clases grabadas y material interactivo disponible 24/7." },
                    { title: "Catálogo extenso", desc: "Desde lenguaje musical hasta instrumentos específicos como Piano." },
                    { title: "Comunidad", desc: "Próximamente podrás interactuar con profesores y estudiantes" }
                ].map((feature, i) => (
                    <div key={i} className="bg-white border text-center border-brand-light shadow-sm p-8 hover:shadow-md transition-shadow">
                        <h3 className="text-xl font-bold text-primary mb-3">{feature.title}</h3>
                        <p className="text-brand-gray">{feature.desc}</p>
                    </div>
                ))}
            </motion.div>
        </div>
    );
};

export default Landing;
