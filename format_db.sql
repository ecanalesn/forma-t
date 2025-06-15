-- Crear la base de datos (si no existe)
CREATE DATABASE IF NOT EXISTS `format_db`;
USE `format_db`;

-- Tabla de usuarios
CREATE TABLE `users` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL, 
  `role` ENUM('admin', 'student') NOT NULL,
  `status` TINYINT DEFAULT 1, 
  `phone` VARCHAR(15),
  PRIMARY KEY (`user_id`)
);

-- Tabla de cursos
CREATE TABLE `courses` (
  `course_id` INT NOT NULL AUTO_INCREMENT,
  `course_name` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `category` VARCHAR(100),
  `image` VARCHAR(255),
  `status` TINYINT DEFAULT 1, 
  `content` TEXT,  
  `video_link` VARCHAR(255),  
  PRIMARY KEY (`course_id`)
);

-- Tabla que registra las peticiones de matrícula
CREATE TABLE `course_requests` (
  `request_id` INT NOT NULL AUTO_INCREMENT,              
  `user_id` INT NOT NULL,                                
  `course_id` INT NOT NULL,                              
  `request_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,   
  `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending', 
  `allowed_access` BOOLEAN DEFAULT FALSE,              
  `is_read` TINYINT(1) DEFAULT 0,                        
  PRIMARY KEY (`request_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE, 
  FOREIGN KEY (`course_id`) REFERENCES `courses`(`course_id`) ON DELETE CASCADE 
);

-- Insertar usuarios con contraseñas cifradas
INSERT INTO `users` (`first_name`, `last_name`, `email`, `password`, `role`, `status`) 
VALUES 
('Estefania', 'Canales', 'admin@ejemplo.com', SHA2('123456', 256), 'admin', 1),
('Rosa', 'Lopez', 'rosa@ejemplo.com', SHA2('123456', 256), 'student', 1),
('Elena', 'Torres', 'elena@ejemplo.com', SHA2('123456', 256), 'student', 1);

-- Insertar el contenido de los cursos
INSERT INTO `courses` (`course_name`, `description`, `category`, `image`, `status`, `content`, `video_link`)
VALUES
  ('Iniciación Musical', 'Descripción: Curso dirigido a niños de 3 a 6 años donde aprenderán los fundamentos básicos de la música, ritmo y notas musicales a través de actividades interactivas y juegos.', 
  'Teoría Musical', '../resources/img/img1.png', 1, 
  'En la primera lección, aprenderemos las notas musicales de una manera divertida y dinámica, utilizando el canto como herramienta principal. 
  A través de canciones y actividades interactivas, podremos identificar las notas, fomentando el desarrollo auditivo, rítmico y creativo mientras se divierten explorando el fascinante mundo de la música.', 
  'https://www.youtube.com/embed/WplI0O5n7ag?si=jQSZVb4vIIy97rLo'),
  
  ('Música y movimiento', 'Descripción: Curso dirigido a niños donde aprenderán música mediante el movimiento corporal, promoviendo el desarrollo motor, la coordinación y la creatividad mientras exploran diferentes ritmos y sonidos.', 
  'Teoría Musical', '../resources/img/img2.png', 1, 
  'En la primera lección, aprenderemos mediante un musicograma basado en los grandes éxitos de ABBA. 
  A través de actividades de percusión corporal, los niños desarrollarán la coordinación motora, mejorarán la concentración y la memoria, y disfrutarán de un ambiente divertido y lleno de ritmo.', 
  'https://www.youtube.com/embed/paVeU_43NhQ?si=X82vFpLtyjCbbhob'),

  ('Lenguaje Musical', 'Descripción: Curso dirigido a niños para aprender a leer partituras, reconocer escalas y acordes.', 
  'Teoría Musical', '../resources/img/img3.png', 1, 
  'En la primera lección, aprenderemos a reconocer las figuras musicales, sus silencios y la duración de cada una. 
  Introduciremos el uso del metrónomo para mantener el ritmo adecuado mientras cantamos. A través de ejercicios prácticos, desarrollaremos la comprensión del ritmo y la estructura musical.', 
  'https://www.youtube.com/embed/Vx9YihLfR2Q?si=9b6LHkKi47LewY1A'),

  ('Historia de la música', 'Descripción: Curso para todas las edades. Un viaje fascinante a través de las épocas y estilos musicales, explorando los grandes compositores, movimientos musicales y la evolución de la música a lo largo de la historia.', 
  'Teoría Musical', '../resources/img/img4.png', 1, 
  'En la primera lección, aprenderemos sobre la vida de Wolfgang Amadeus Mozart a través del recurso "Draw My Life". 
  Este enfoque visual nos permitirá conocer los momentos más importantes de su biografía, desde su infancia como prodigio musical hasta su legado como uno de los compositores más influyentes de la historia. A través de dibujos y narración, exploraremos su música, su vida personal y las circunstancias que marcaron su brillante carrera.', 
  'https://www.youtube.com/embed/F_5BS2c6Bt4?si=C6dYZAdAaU2fCoOp'),

  ('Piano Nivel I', 'Descripción: Curso introductorio para aprender a tocar el piano desde cero, comenzando con nociones básicas de lenguaje musical hasta la interpretación de las primeras obras musicales sencillas.', 
  'Instrumento', '../resources/img/img5.png', 1, 
  'En la primera lección, aprenderemos a tocar "Recuerdame" del piano, la canción de la película Coco. 
  A través de una guía sencilla, los niños conocerán las notas y acordes de la melodía. Esta actividad les permitirá mejorar su técnica al piano mientras se divierten tocando una canción fácil y popular, ayudándoles a conectar con la música de manera práctica y entretenida.', 
  'https://www.youtube.com/embed/J1dsUiHaCqk?si=PzvnM8h4pNeaK_We'),

  ('Piano Nivel II', 'Descripción: Curso intermedio para estudiantes que ya dominan el curso introductorio de piano. Enfocado en aprender obras de mayor complejidad.', 
  'Instrumento', '../resources/img/img6.png', 1, 
  'En esta lección, aprenderemos a tocar "All of Me" de John Legend en el piano. Con su ritmo suave y relajado, esta canción es ideal para practicar la coordinación mientras se disfruta de una melodía agradable y fácil de seguir.', 
  'https://www.youtube.com/embed/KKFBFyUON6Y?si=QEgVwBdTsU3pKLMM');
