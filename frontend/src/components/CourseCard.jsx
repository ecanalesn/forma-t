import { Link } from 'react-router-dom';
import { motion } from 'framer-motion';

const CourseCard = ({ course, index, children }) => {
    return (
        <motion.div 
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: index * 0.1 }}
            className="bg-white border border-brand-light shadow-md hover:shadow-lg rounded-xl overflow-hidden transition-all flex flex-col"
        >
            {course.image && (
                <img src={course.image.replace('../', '/')} alt={course.course_name} className="w-full h-48 object-cover opacity-80" />
            )}
            <div className="p-6 flex flex-col flex-grow">
                <span className="text-xs font-semibold text-brand-gray mb-2 uppercase tracking-wider">{course.category}</span>
                <h3 className="text-xl font-bold text-brand-dark mb-3">{course.course_name}</h3>
                <p className="text-base text-brand-gray mb-6 flex-grow leading-relaxed" style={{ textAlign: 'justify' }}>
                    {course.description.startsWith('Descripción:') ? (
                        <>
                            <strong>Descripción:</strong> {course.description.substring(12)}
                        </>
                    ) : (
                        course.description
                    )}
                </p>
                {children}
            </div>
        </motion.div>
    );
};

export default CourseCard;
