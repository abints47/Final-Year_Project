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
        'image' => 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?auto=format&fit=crop&q=80&w=800',
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
    [
        'id' => '7',
        'title' => 'Generative AI & LLMs',
        'author' => 'Dr. Michael Watts',
        'category' => 'AI/ML',
        'level' => 'Advanced',
        'rating' => '4.9',
        'students' => '8,200',
        'duration' => '35 hours',
        'lessons' => '110',
        'image' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?auto=format&fit=crop&q=80&w=800',
        'youtube_id' => '5Sgco7yS-zE', // Generative AI overview
        'summary' => 'Master the future of AI. Learn prompt engineering, fine-tuning LLMs, and building applications with GPT-4 and LangChain.',
        'full_summary' => 'Generative AI is transforming industries. This course provides a deep dive into Large Language Models, from their transformer architecture to practical deployment. You will learn how to build AI-powered applications using modern frameworks like LangChain and LlamaIndex.',
        'what_you_learn' => [
            'Transformer Architecture and Attention Mechanisms',
            'Prompt Engineering and Vector Databases',
            'Fine-tuning LLMs for specific domains',
            'Building RAG (Retrieval-Augmented Generation) systems',
            'Ethical AI and Bias Mitigation'
        ],
        'resources' => [
            ['name' => 'OpenAI Documentation', 'url' => 'https://platform.openai.com/docs/'],
            ['name' => 'Hugging Face Course', 'url' => 'https://huggingface.co/learn/nlp-course/']
        ]
    ],
    [
        'id' => '8',
        'title' => 'Cyber Security Professional',
        'author' => 'Alex Rivera',
        'category' => 'Security',
        'level' => 'Intermediate',
        'rating' => '4.8',
        'students' => '14,150',
        'duration' => '50 hours',
        'lessons' => '140',
        'image' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&q=80&w=800',
        'youtube_id' => 'nzj7Wg4DAbs', // Cyber Security for beginners
        'summary' => 'Protect digital assets. Learn ethical hacking, network security, cryptography, and threat intelligence to become a security expert.',
        'full_summary' => 'This comprehensive security course covers the entire landscape of modern cybersecurity. You will learn defensive and offensive strategies, security auditing, and how to build resilient systems against evolving threats like ransomware and zero-day exploits.',
        'what_you_learn' => [
            'Ethical Hacking and Penetration Testing',
            'Network Security and Firewall Configuration',
            'Advanced Cryptography and Data Protection',
            'Identity and Access Management (IAM)',
            'Incident Response and Disaster Recovery'
        ],
        'resources' => [
            ['name' => 'OWASP Top 10', 'url' => 'https://owasp.org/www-project-top-ten/'],
            ['name' => 'NIST Cybersecurity Framework', 'url' => 'https://www.nist.gov/cyberframework']
        ]
    ],
    [
        'id' => '9',
        'title' => 'AWS Cloud Solutions Architect',
        'author' => 'Sarah Chen',
        'category' => 'Cloud',
        'level' => 'Intermediate',
        'rating' => '4.7',
        'students' => '22,400',
        'duration' => '45 hours',
        'lessons' => '130',
        'image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&q=80&w=800',
        'youtube_id' => 'RrKRN9zrbKo', // AWS Certified Solutions Architect
        'summary' => 'Master the world\'s leading cloud platform. Learn to design scalable, reliable, and cost-effective systems on Amazon Web Services.',
        'full_summary' => 'Cloud computing is the backbone of modern business. This course prepares you for the AWS Certified Solutions Architect Associate exam by teaching you how to architect complex solutions using EC2, S3, RDS, Lambda, and more.',
        'what_you_learn' => [
            'Designing High Availability Architectures',
            'Cost Optimization and Performance Scaling',
            'AWS Serverless Computing (Lambda & API Gateway)',
            'Identity and Access Management in the Cloud',
            'Data Storage and Database selection strategies'
        ],
        'resources' => [
            ['name' => 'AWS Documentation', 'url' => 'https://docs.aws.amazon.com/'],
            ['name' => 'Cloud Architect Learning Path', 'url' => 'https://aws.amazon.com/training/learning-paths/']
        ]
    ],
    [
        'id' => '10',
        'title' => 'Data Analytics with Python',
        'author' => 'Vikram Reddy',
        'category' => 'Data Science',
        'level' => 'Intermediate',
        'rating' => '4.8',
        'students' => '11,200',
        'duration' => '38 hours',
        'lessons' => '115',
        'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&q=80&w=800',
        'youtube_id' => 'GPVsHOlRBBI', // Data Analysis with Python
        'summary' => 'Turn data into insights. Master Pandas, NumPy, Matplotlib, and Seaborn to perform complex data analysis and visualization.',
        'full_summary' => 'Data is the new oil. This course teaches you how to clean, manipulate, and visualize large datasets using the Python ecosystem. You will work on real-world datasets to uncover trends and build predictive models.',
        'what_you_learn' => [
            'Data Cleaning and Preprocessing with Pandas',
            'Numerical Computing with NumPy',
            'Advanced Data Visualization with Matplotlib & Seaborn',
            'Exploratory Data Analysis (EDA) techniques',
            'Statistical Analysis and Hypothesis Testing'
        ],
        'resources' => [
            ['name' => 'Pandas Documentation', 'url' => 'https://pandas.pydata.org/docs/'],
            ['name' => 'Kaggle Datasets', 'url' => 'https://www.kaggle.com/datasets']
        ]
    ],
    [
        'id' => '11',
        'title' => 'Flutter Mobile Development',
        'author' => 'Priya Sharma',
        'category' => 'Mobile Dev',
        'level' => 'Intermediate',
        'rating' => '4.9',
        'students' => '19,300',
        'duration' => '40 hours',
        'lessons' => '125',
        'image' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?auto=format&fit=crop&q=80&w=800',
        'youtube_id' => 'VPvVD8t0208', // Flutter for beginners
        'summary' => 'Build beautiful cross-platform apps with a single codebase. Master Dart and Flutter to deploy on iOS, Android, and Web.',
        'full_summary' => 'Flutter is revolutionizing mobile app development. This course takes you from the basics of the Dart language to building high-performance, beautiful applications that run natively on multiple platforms.',
        'what_you_learn' => [
            'Dart Programming Language Fundamentals',
            'Building Responsive UIs with Flutter Widgets',
            'State Management techniques (Provider, Riverpod)',
            'Connecting to REST APIs and Firebase integration',
            'App Store and Play Store Deployment processes'
        ],
        'resources' => [
            ['name' => 'Flutter Documentation', 'url' => 'https://docs.flutter.dev/'],
            ['name' => 'Dart.dev Guides', 'url' => 'https://dart.dev/guides']
        ]
    ],
];
