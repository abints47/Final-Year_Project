<?php
// includes/courses_data.php

$all_courses = [
    [
        'id' => '1',
        'title' => 'Python Programming Masterclass',
        'author' => 'Arjun Kumar',
        'category' => 'Programming',
        'level' => 'Beginner',
        'rating' => '4.9',
        'students' => '15,420',
        'duration' => '42 hours',
        'lessons' => '150',
        'image' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?auto=format&fit=crop&q=80&w=800',
        'youtube_id' => 'rfscVS0vtbw', // freeCodeCamp Python
        'summary' => 'Master Python from scratch with hands-on projects. Learn fundamentals, control flow, functions, OOP, and build real-world automation scripts.',
        'full_summary' => 'This comprehensive Python masterclass is designed to take you from a complete beginner to a proficient developer. You will dive deep into Python syntax, data structures, and advanced concepts like decorators and generators. The course focuses on practical application, ensuring you can build your own tools and applications by the end.',
        'what_you_learn' => [
            'Python Fundamentals and Advanced Syntax',
            'Object-Oriented Programming (OOP) in depth',
            'File Handling and Data Manipulation',
            'Building Automation Scripts and Web Scrapers',
            'Error and Exception Handling best practices'
        ],
        'resources' => [
            ['name' => 'Official Python Docs', 'url' => 'https://docs.python.org/3/'],
            ['name' => 'W3Schools Python Tutorial', 'url' => 'https://www.w3schools.com/python/']
        ]
    ],
    [
        'id' => '2',
        'title' => 'Java Complete Developer Course',
        'author' => 'Priya Sharma',
        'category' => 'Programming',
        'level' => 'Beginner',
        'rating' => '4.8',
        'students' => '12,350',
        'duration' => '48 hours',
        'lessons' => '180',
        'image' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&q=80&w=800',
        'youtube_id' => 'grEKMHGYyz4', // Programming with Mosh Java
        'summary' => 'A complete guide to Java development. Covers Core Java, OOP, Spring Boot, and database integration for full-stack applications.',
        'full_summary' => 'Become a Professional Java Developer by learning the most in-depth Java course on the market. This course covers everything from basic syntax to advanced topics like multithreading, collections, and the Spring Framework. Perfect for those looking to start a career in enterprise software development.',
        'what_you_learn' => [
            'Core Java Concepts (JDK, JVM, JRE)',
            'Advanced Object-Oriented Programming',
            'Spring Boot Framework for Microservices',
            'Database management with Hibernate/JPA',
            'Unit Testing with JUnit'
        ],
        'resources' => [
            ['name' => 'Oracle Java Documentation', 'url' => 'https://docs.oracle.com/en/java/'],
            ['name' => 'Spring Framework Docs', 'url' => 'https://spring.io/projects/spring-framework']
        ]
    ],
    [
        'id' => '3',
        'title' => 'C Programming Fundamentals',
        'author' => 'Vikram Reddy',
        'category' => 'Programming',
        'level' => 'Beginner',
        'rating' => '4.7',
        'students' => '8,920',
        'duration' => '32 hours',
        'lessons' => '120',
        'image' => 'https://images.unsplash.com/photo-1515879218367-8466d910aaa4?auto=format&fit=crop&q=80&w=800',
        'youtube_id' => 'irqbmMNs2Bo', // freeCodeCamp C
        'summary' => 'Learn the foundation of modern computing. Master memory management, pointers, and efficient algorithm implementation using C.',
        'full_summary' => 'C is the "mother of all languages." Learning C gives you a deep understanding of how computers work at a low level. This course covers memory management, pointers, structures, and file I/O, providing the foundation for learning any other programming language.',
        'what_you_learn' => [
            'Low-level Memory Management and Pointers',
            'Arrays, Strings, and Custom Structures',
            'File I/O and System-level Programming',
            'Algorithm Efficiency and Optimization',
            'Building foundation for C++ and Java'
        ],
        'resources' => [
            ['name' => 'GNU C Reference Manual', 'url' => 'https://www.gnu.org/software/libc/manual/'],
            ['name' => 'C Programming on Wikibooks', 'url' => 'https://en.wikibooks.org/wiki/C_Programming']
        ]
    ],
    [
        'id' => '4',
        'title' => 'Web Development Bootcamp',
        'author' => 'Sarah Chen',
        'category' => 'Web Dev',
        'level' => 'Intermediate',
        'rating' => '4.9',
        'students' => '25,100',
        'duration' => '65 hours',
        'lessons' => '250',
        'image' => 'https://images.unsplash.com/photo-1547658719-da2b51169166?auto=format&fit=crop&q=80&w=800',
        'youtube_id' => 'mU6an7qBrHw', // Traversy Media Web Dev
        'summary' => 'The only course you need to learn web development. HTML, CSS, JavaScript, Node, and React. Build 10+ professional projects.',
        'full_summary' => 'Go from zero to full-stack web developer. This bootcamp is a fast-paced, project-based course that teaches you the latest technologies used by industry leaders. You will learn how to design beautiful interfaces and build robust backends.',
        'what_you_learn' => [
            'Responsive Design with HTML5 and CSS3',
            'Modern JavaScript (ES6+) and DOM Manipulation',
            'React JS for building dynamic User Interfaces',
            'Backend development with Node.js and Express',
            'Database Management with MongoDB'
        ],
        'resources' => [
            ['name' => 'MDN Web Docs', 'url' => 'https://developer.mozilla.org/'],
            ['name' => 'React Documentation', 'url' => 'https://react.dev/']
        ]
    ],
    [
        'id' => '5',
        'title' => 'Machine Learning A-Z',
        'author' => 'Dr. Michael Watts',
        'category' => 'AI/ML',
        'level' => 'Advanced',
        'rating' => '4.8',
        'students' => '18,700',
        'duration' => '55 hours',
        'lessons' => '200',
        'image' => 'https://images.unsplash.com/photo-1555255707-c07966488bc0?auto=format&fit=crop&q=80&w=800',
        'youtube_id' => '7eh4d6sabA0', // Sentdex Machine Learning
        'summary' => 'Journey into the world of AI. Learn supervised, unsupervised, and deep learning with Python and scikit-learn.',
        'full_summary' => 'This course is a deep dive into the mathematical and practical aspects of Machine Learning. You will master algorithms like Linear Regression, Random Forests, and Neural Networks. The course provides clear explanations and hands-on coding templates.',
        'what_you_learn' => [
            'Supervised and Unsupervised Learning Algorithms',
            'Data Preprocessing and Feature Engineering',
            'Deep Learning with TensorFlow and Keras',
            'Natural Language Processing (NLP) techniques',
            'Model Evaluation and Hyperparameter Tuning'
        ],
        'resources' => [
            ['name' => 'Scikit-learn Documentation', 'url' => 'https://scikit-learn.org/'],
            ['name' => 'TensorFlow Documentation', 'url' => 'https://www.tensorflow.org/']
        ]
    ],
    [
        'id' => '6',
        'title' => 'React 19 Deep Dive',
        'author' => 'Arjun Kumar',
        'category' => 'Web Dev',
        'level' => 'Advanced',
        'rating' => '4.9',
        'students' => '10,200',
        'duration' => '30 hours',
        'lessons' => '100',
        'image' => 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?auto=format&fit=crop&q=80&w=800',
        'youtube_id' => '81uK0uXF1m0', // React 19 overview
        'summary' => 'Stay ahead of the curve with React 19. Learn about Actions, Server Components, and the new React Compiler.',
        'full_summary' => 'React 19 is a game-changer. This course covers everything new in the latest version, including Server Components, Actions API for forms, and the revolutionary React Compiler that optimizes your code automatically.',
        'what_you_learn' => [
            'React Server Components (RSC) architecture',
            'New Actions API and useActionState hook',
            'Optimistic updates with useOptimistic',
            'React Compiler and automatic memoization',
            'Direct Ref support and Metadata handling'
        ],
        'resources' => [
            ['name' => 'React 19 Release Notes', 'url' => 'https://react.dev/blog/2024/04/25/react-19'],
            ['name' => 'React Dev Docs', 'url' => 'https://react.dev/']
        ]
    ],
];
