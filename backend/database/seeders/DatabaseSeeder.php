<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // La base de datos (Supabase) persiste entre despliegues y el seeder
        // se ejecuta en cada arranque del contenedor: solo se siembra si está vacía
        if (User::exists()) {
            return;
        }

        User::create([
            'first_name' => 'Estefania',
            'last_name' => 'Canales',
            'email' => 'admin@ejemplo.com',
            'password' => Hash::make('1234admin'),
            'role' => 'admin',
            'status' => 1,
        ]);

        User::create([
            'first_name' => 'Rosa',
            'last_name' => 'Lopez',
            'email' => 'estudiante1@ejemplo.com',
            'password' => Hash::make('1234estudiante1'),
            'role' => 'student',
            'status' => 1,
        ]);

        User::create([
            'first_name' => 'Elena',
            'last_name' => 'Torres',
            'email' => 'estudiante2@ejemplo.com',
            'password' => Hash::make('1234estudiante2'),
            'role' => 'student',
            'status' => 1,
        ]);

        Course::create([
            'course_name' => 'Iniciación Musical',
            'description' => 'Descripción: Curso dirigido a niños de 3 a 6 años, diseñado para introducirlos en el mundo de la música de forma divertida y dinámica. ',
            'category' => 'Teoría Musical',
            'image' => '../resources/img/img01.png',
            'status' => 1,
            'content' => 'En esta primera lección, los niños aprenderán las notas musicales de forma divertida y dinámica, utilizando el canto como herramienta principal. A través de canciones, juegos y actividades interactivas, comenzarán a reconocer e identificar las notas mientras participan activamente en la clase.
            Estas actividades están diseñadas para estimular el oído musical, desarrollar el sentido del ritmo y fomentar la creatividad y la expresión. De esta manera, los niños podrán aprender mientras se divierten, dando sus primeros pasos en la exploración del fascinante mundo de la música.',
            'video_link' => 'https://www.youtube.com/embed/WplI0O5n7ag?si=jQSZVb4vIIy97rLo'
        ]);

        Course::create([
            'course_name' => 'Música y movimiento',
            'description' => 'Descripción: Curso dirigido a niños donde la música se aprende a través del movimiento corporal y la expresión física.',
            'category' => 'Teoría Musical',
            'image' => '../resources/img/img02.png',
            'status' => 1,
            'content' => 'En esta primera lección, los niños aprenderán a través de un musicograma basado en grandes éxitos de ABBA. Mediante actividades guiadas de percusión corporal y movimiento, seguirán la música con diferentes gestos y ritmos, relacionando lo que escuchan con acciones corporales.',
            'video_link' => 'https://www.youtube.com/embed/paVeU_43NhQ?si=X82vFpLtyjCbbhob'
        ]);

        Course::create([
            'course_name' => 'Lenguaje Musical',
            'description' => 'Descripción: Curso diseñado para que los niños aprendan de forma divertida los fundamentos de la música. ',
            'category' => 'Teoría Musical',
            'image' => '../resources/img/img03.png',
            'status' => 1,
            'content' => 'En esta primera lección, los estudiantes aprenderán a reconocer las principales figuras musicales y sus correspondientes silencios, comprendiendo la duración de cada una dentro del compás. También se introducirá el uso del metrónomo como herramienta para mantener un pulso constante y desarrollar una correcta noción del tiempo musical. 
            A través de ejercicios prácticos de lectura rítmica y canto, los niños practicarán seguir el pulso mientras identifican y ejecutan diferentes figuras. Estas actividades ayudarán a fortalecer la comprensión del ritmo, mejorar la coordinación y sentar las bases para entender la estructura musical de forma clara y progresiva.',
            'video_link' => 'https://www.youtube.com/embed/Vx9YihLfR2Q?si=9b6LHkKi47LewY1A'
        ]);

        Course::create([
            'course_name' => 'Historia de la música',
            'description' => 'Descripción: Curso para todas las edades que propone un viaje por las distintas épocas y estilos musicales, explorando grandes compositores y la evolución de la música a lo largo de la historia.',
            'category' => 'Teoría Musical',
            'image' => '../resources/img/img04.png',
            'status' => 1,
            'content' => 'En la primera lección, aprenderemos sobre la vida de Wolfgang Amadeus Mozart a través del recurso "Draw My Life". Este enfoque visual nos permitirá conocer los momentos más importantes de su biografía, desde su infancia como prodigio musical hasta su legado como uno de los compositores más influyentes de la historia. A través de dibujos y narración, exploraremos su música, su vida personal y las circunstancias que marcaron su brillante carrera.',
            'video_link' => 'https://www.youtube.com/embed/F_5BS2c6Bt4?si=C6dYZAdAaU2fCoOp'
        ]);

        Course::create([
            'course_name' => 'Piano Nivel I',
            'description' => 'Descripción: Curso introductorio para aprender a tocar el piano desde cero, comenzando con nociones básicas de lenguaje musical hasta la interpretación de las primeras obras musicales sencillas.',
            'category' => 'Instrumento',
            'image' => '../resources/img/img05.png',
            'status' => 1,
            'content' => 'En la primera lección, aprenderemos a tocar "Recuerdame" del piano, la canción de la película Coco. A través de una guía sencilla, los niños conocerán las notas y acordes de la melodía. Esta actividad les permitirá mejorar su técnica al piano mientras se divierten tocando una canción fácil y popular, ayudándoles a conectar con la música de manera práctica y entretenida.',
            'video_link' => 'https://www.youtube.com/embed/J1dsUiHaCqk?si=PzvnM8h4pNeaK_We'
        ]);

        Course::create([
            'course_name' => 'Piano Nivel II',
            'description' => 'Descripción: Curso intermedio para estudiantes que ya dominan el curso introductorio de piano. Enfocado en aprender obras de mayor complejidad.',
            'category' => 'Instrumento',
            'image' => '../resources/img/img06.png',
            'status' => 1,
            'content' => 'En esta lección, aprenderemos a tocar "All of Me" de John Legend en el piano. Con su ritmo suave y relajado, esta canción es ideal para practicar la coordinación mientras se disfruta de una melodía agradable y fácil de seguir.',
            'video_link' => 'https://www.youtube.com/embed/KKFBFyUON6Y?si=QEgVwBdTsU3pKLMM'
        ]);
    }
}
