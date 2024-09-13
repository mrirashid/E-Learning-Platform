<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Learning Platform</title>

    <!-- Favicon -->
    <link rel="icon" href="fav_icon.png" type="image/png"> 
     <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Style CSS -->
    <link rel="stylesheet" href="style.css">

    <!-- Google Fonts -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Saira:wght@400;500;600&family=Ubuntu&display=swap');

        body {
            font-family: 'Saira', sans-serif;
        }

        h1, h2, h3 {
            font-family: 'Ubuntu', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">

 <!-- Navbar -->
<nav class="bg-white shadow-md fixed top-0 left-0 w-full z-50">
    <div class="container mx-auto px-6 py-3 flex justify-between items-center">
        <a href="#" class="text-xl font-bold text-gray-800 hover:text-gray-600">E-Learning Platform</a>
        <div class="flex space-x-4">
            <a href="instructor.php" class="text-gray-800 hover:text-gray-600">Instructor</a>
            <a href="course_create.php" class="text-gray-800 hover:text-gray-600">Create Course</a>
            <a href="assignments.php" class="text-gray-800 hover:text-gray-600">Assignments</a>
            <a href="course_enroll.php" class="text-gray-800 hover:text-gray-600">Course Enrollment</a>
            <a href="manage_assessments.php" class="text-gray-800 hover:text-gray-600">Assessments</a>
            <a href="manage_quizzes.php" class="text-gray-800 hover:text-gray-600">Quizzes</a>
            <a href="grades.php" class="text-gray-800 hover:text-gray-600">Grades</a> 
            <a href="report.php" class="text-gray-800 hover:text-gray-600">Report</a> 
        </div>
    </div>
</nav>

 <!-- Hero Section -->
<section class="hero-section relative bg-cover bg-center bg-no-repeat" style="background: url('Hero-Bg.jpg') no-repeat center/cover; height: 100vh;">
    <div class="absolute inset-0 bg-black bg-opacity-20"></div>
    <div class="container mx-auto px-6 flex flex-col justify-center items-center text-center relative z-10 h-full">
        <h3 class="text-3xl font-bold text-white">Welcome to Our Application</h3>
        <h1 class="text-4xl font-bold text-white">Your Ultimate E-Learning Platform Management System</h1>
        <p class="mt-4 text-white text-lg">Empowering education through innovation and seamless management.</p>
        <a href="student_register.php" class="mt-6 inline-block bg-white text-blue-500 font-bold py-3 px-6 rounded-full shadow-lg hover:bg-gray-100">Student Register</a>
    </div>
</section>

 <!-- Features Section -->
<section class="py-12 bg-gray-100">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800">Platform Features</h2>
            <p class="mt-4 text-gray-600">Explore the features of our e-learning platform to manage and track your courses and students effectively.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Card 1 -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex justify-center mb-4">
                    <span class="bg-blue-500 text-white p-4 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 9V3a1 1 0 112 0v6a1 1 0 01-2 0zM5.293 4.293a1 1 0 011.414 0L10 7.586 12.293 5.293a1 1 0 111.414 1.414L10 10.414 5.293 5.707a1 1 0 010-1.414z" />
                        </svg>
                    </span>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 text-center">Student Registration</h3>
                <p class="mt-4 text-gray-600 text-center">Easily sign up and create profiles to start your educational journey with a streamlined registration process.</p>
            </div>

            <!-- Card 2 -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex justify-center mb-4">
                    <span class="bg-green-500 text-white p-4 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-3.293-3.707a1 1 0 00-1.414 1.414l.293.293H6.414l.293-.293a1 1 0 10-1.414-1.414L4 7.414a1 1 0 000 1.414l1.293 1.293a1 1 0 101.414-1.414l-.293-.293h7.172l-.293.293a1 1 0 101.414 1.414l1.293-1.293a1 1 0 000-1.414L14.707 6.293z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 text-center">Instructor Registration</h3>
                <p class="mt-4 text-gray-600 text-center">Join our platform, manage courses, and connect with students globally through our dynamic registration system.</p>
            </div>

            <!-- Card 3 -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex justify-center mb-4">
                    <span class="bg-red-500 text-white p-4 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a7 7 0 00-7 7v4a7 7 0 007 7h4a7 7 0 007-7v-4a7 7 0 00-7-7H10zm0 2h4a5 5 0 015 5v4a5 5 0 01-5 5H10a5 5 0 01-5-5v-4a5 5 0 015-5zm1 2v6a1 1 0 102 0V7a1 1 0 00-2 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 text-center">Course Management</h3>
                <p class="mt-4 text-gray-600 text-center">Effortlessly create, edit, and delete courses. Keep your content fresh and engaging with our intuitive tools.</p>
            </div>

            <!-- Card 4 -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex justify-center mb-4">
                    <span class="bg-yellow-500 text-white p-4 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9 4a1 1 0 10-2 0v2.18a5.006 5.006 0 00-1.468 1.836L3.707 8.293a1 1 0 10-1.414 1.414l1.293 1.293A5.004 5.004 0 005.18 12H9v2a1 1 0 102 0v-2a5.002 5.002 0 004.64-3.423L16.293 9a1 1 0 10-1.414-1.414l-1.293 1.293A5.003 5.003 0 0011 6.18V4a1 1 0 00-1-1z" />
                        </svg>
                    </span>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 text-center">Assignment Management</h3>
                <p class="mt-4 text-gray-600 text-center">Manage and track assignments efficiently. Set up tasks, review submissions, and provide feedback seamlessly.</p>
            </div>

            <!-- Card 5 -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex justify-center mb-4">
                    <span class="bg-purple-500 text-white p-4 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a7 7 0 00-7 7v4a7 7 0 007 7h4a7 7 0 007-7v-4a7 7 0 00-7-7H10zm0 2h4a5 5 0 015 5v4a5 5 0 01-5 5H10a5 5 0 01-5-5v-4a5 5 0 015-5zm1 2v6a1 1 0 102 0V7a1 1 0 00-2 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 text-center">Course Enrollment</h3>
                <p class="mt-4 text-gray-600 text-center">Allow students to browse and enroll in courses effortlessly. Manage registrations and track enrollment status easily.</p>
            </div>

            <!-- Card 6 -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
    <div class="flex justify-center mb-4">
        <span class="bg-teal-500 text-white p-4 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 3C8.13 3 5 6.13 5 10s3.13 7 7 7 7-3.13 7-7-3.13-7-7-7zm0 12c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zM12 4a6 6 0 00-6 6c0 .43.05.85.13 1.26L9.54 10h-2a1 1 0 00-1 1v1a1 1 0 001 1h2l-1.33 1.33A5.978 5.978 0 0012 16c3.31 0 6-2.69 6-6s-2.69-6-6-6zm0 12a5.978 5.978 0 01-4.66-2.33L10.4 14h3.2l2.05 2.05C14.66 17.01 13.38 18 12 18zm0-10a4 4 0 00-4 4c0 .4.1.77.27 1.1L8.1 11h3.8l1.85 1.85c.17-.33.27-.7.27-1.1a4 4 0 00-4-4z"/>
            </svg>
        </span>
    </div>
    <h3 class="text-xl font-semibold text-gray-800 text-center">Assessment & Quiz Management</h3>
    <p class="mt-4 text-gray-600 text-center">Create and oversee assessments and quizzes with ease. Track results and maintain academic integrity with our comprehensive tools.</p>
</div>
        </div>
    </div>
</section>

 <!-- Footer -->
 <footer class="bg-gray-800 text-white py-6">
    <div class="container mx-auto px-6">
        <div class="flex justify-between">
            <p>Developed by <a href="https://github.com/mrirashid" class="text-gray-400 hover:text-white">MD Rashidul Islam</a>
                . All rights reserved.</p>
            <div class="space-x-4">
                <a href="#" class="text-gray-400 hover:text-white">Privacy Policy</a>
                <a href="#" class="text-gray-400 hover:text-white">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
</body>
</html>
