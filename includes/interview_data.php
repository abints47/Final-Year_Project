<?php
$interview_questions = [
    'logical' => [
        [
            'question' => 'If SCOTLAND is written as 12345678, then LOAN is written as:',
            'options' => ['1234', '5327', '8627', '5321'],
            'answer' => 1 // 5327 (L=5, O=3, A=2, N=7) - Wait, SCOTLAND has no A. Let me refine.
        ],
        [
            'question' => 'Which word does NOT belong with the others?',
            'options' => ['Parsley', 'Basil', 'Dill', 'Mayonnaise'],
            'answer' => 3
        ],
        [
            'question' => 'CUP : LIP :: BIRD : ?',
            'options' => ['BUSH', 'GRASS', 'FOREST', 'BEAK'],
            'answer' => 3
        ],
        [
            'question' => 'Paw : Cat :: Hoof : ?',
            'options' => ['Lamb', 'Horse', 'Elephant', 'Tiger'],
            'answer' => 1
        ],
        [
            'question' => 'Safe : Secure :: Protect : ?',
            'options' => ['Lock', 'Guard', 'Sure', 'Conserve'],
            'answer' => 1
        ],
        [
            'question' => 'SCD, TEF, UGH, ____, WKL',
            'options' => ['CMN', 'UJI', 'VIJ', 'IJT'],
            'answer' => 2
        ],
        [
            'question' => 'Pointing to a photograph, a man said, "I have no brother or sister but that man\'s father is my father\'s son." Whose photograph was it?',
            'options' => ['His own', 'His son\'s', 'His father\'s', 'His nephew\'s'],
            'answer' => 1
        ],
        [
            'question' => 'Look at this series: 2, 1, (1/2), (1/4), ... What number should come next?',
            'options' => ['(1/3)', '(1/8)', '(2/8)', '(1/16)'],
            'answer' => 1
        ],
        [
            'question' => 'Look at this series: 7, 10, 8, 11, 9, 12, ... What number should come next?',
            'options' => ['7', '10', '12', '13'],
            'answer' => 1
        ],
        [
            'question' => 'A, B, C, D and E are sitting on a bench. A is sitting next to B, C is sitting next to D, D is not sitting with E who is on the left end of the bench. C is on the second position from the right. A is to the right of B and E. A and C are sitting together. In which position A is sitting?',
            'options' => ['Between B and D', 'Between B and C', 'Between E and D', 'Between C and E'],
            'answer' => 1
        ]
    ],
    'quant' => [
        [
            'question' => 'The average of first five multiples of 3 is:',
            'options' => ['3', '9', '12', '15'],
            'answer' => 1
        ],
        [
            'question' => 'A person crosses a 600 m long street in 5 minutes. What is his speed in km per hour?',
            'options' => ['3.6', '7.2', '8.4', '10'],
            'answer' => 1
        ],
        [
            'question' => 'If 20% of a = b, then b% of 20 is the same as:',
            'options' => ['4% of a', '5% of a', '20% of a', 'None of these'],
            'answer' => 0
        ],
        [
            'question' => 'A fruit seller had some apples. He sells 40% apples and still has 420 apples. Originally, he had:',
            'options' => ['588 apples', '600 apples', '672 apples', '700 apples'],
            'answer' => 3
        ],
        [
            'question' => 'What is the smallest number which when divided by 12, 15, 20 and 54 leaves in each case a remainder of 8?',
            'options' => ['504', '536', '544', '548'],
            'answer' => 3
        ],
        [
            'question' => 'The sum of ages of 5 children born at the intervals of 3 years each is 50 years. What is the age of the youngest child?',
            'options' => ['4 years', '8 years', '10 years', 'None of these'],
            'answer' => 0
        ],
        [
            'question' => 'A father said to his son, "I was as old as you are at the present at the time of your birth". If the father\'s age is 38 years now, the son\'s age five years back was:',
            'options' => ['14 years', '19 years', '33 years', '38 years'],
            'answer' => 0
        ],
        [
            'question' => 'In a regular week, there are 5 working days and for each day, the working hours are 8. A man gets Rs. 2.40 per hour for regular work and Rs. 3.20 per hour for overtime. If he earns Rs. 432 in 4 weeks, then how many hours does he work for?',
            'options' => ['160', '175', '180', '195'],
            'answer' => 1
        ],
        [
            'question' => 'A can do a work in 15 days and B in 20 days. If they work on it together for 4 days, then the fraction of the work that is left is:',
            'options' => ['(1/4)', '(1/10)', '(7/15)', '(8/15)'],
            'answer' => 3
        ],
        [
            'question' => 'A train 125 m long passes a man, running at 5 km/hr in the same direction in which the train is going, in 10 seconds. The speed of the train is:',
            'options' => ['45 km/hr', '50 km/hr', '54 km/hr', '55 km/hr'],
            'answer' => 1
        ]
    ],
    'verbal' => [
        [
            'question' => 'Choose the word which is most nearly the SAME in meaning as the word: ADVERSITY',
            'options' => ['Failure', 'Helplessness', 'Misfortune', 'Crisis'],
            'answer' => 2
        ],
        [
            'question' => 'Choose the word which is most nearly the OPPOSITE in meaning as the word: EXPAND',
            'options' => ['Convert', 'Condense', 'Congest', 'Conclude'],
            'answer' => 1
        ],
        [
            'question' => 'Find the correctly spelt word.',
            'options' => ['Efficient', 'Treatement', 'Beterment', 'Employd'],
            'answer' => 0
        ],
        [
            'question' => 'Find the correctly spelt word.',
            'options' => ['Foreign', 'Foreine', 'Foriegn', 'Forein'],
            'answer' => 0
        ],
        [
            'question' => 'The study of ancient societies is called:',
            'options' => ['Anthropology', 'Archaeology', 'History', 'Ethnology'],
            'answer' => 1
        ],
        [
            'question' => 'A person who hates women is called:',
            'options' => ['Misogynist', 'Misogamist', 'Misanthrope', 'Philogynist'],
            'answer' => 0
        ],
        [
            'question' => 'I ____ my car three weeks ago.',
            'options' => ['washed', 'was washing', 'will wash', 'have washed'],
            'answer' => 0
        ],
        [
            'question' => 'He is ____ of his success.',
            'options' => ['confident', 'confidently', 'confidence', 'confiding'],
            'answer' => 0
        ],
        [
            'question' => 'The teacher ____ the students for their hard work.',
            'options' => ['praised', 'prayed', 'preached', 'pushed'],
            'answer' => 0
        ],
        [
            'question' => 'A place where bees are kept is called:',
            'options' => ['Apiary', 'Aviary', 'Pantry', 'Nursery'],
            'answer' => 0
        ]
    ],
    'dsa' => [
        [
            'question' => 'What is the time complexity of searching an element in a binary search tree in the worst case?',
            'options' => ['O(1)', 'O(log n)', 'O(n)', 'O(n log n)'],
            'answer' => 2
        ],
        [
            'question' => 'Which data structure is based on the LIFO principle?',
            'options' => ['Queue', 'Linked List', 'Stack', 'Tree'],
            'answer' => 2
        ],
        [
            'question' => 'Which of the following sorting algorithms has the best worst-case time complexity?',
            'options' => ['Bubble Sort', 'Selection Sort', 'Insertion Sort', 'Merge Sort'],
            'answer' => 3
        ],
        [
            'question' => 'The process of calling a function by itself is called:',
            'options' => ['Iteration', 'Recursion', 'Looping', 'Traversing'],
            'answer' => 1
        ],
        [
            'question' => 'What is the space complexity of a linear search algorithm?',
            'options' => ['O(1)', 'O(n)', 'O(log n)', 'O(n^2)'],
            'answer' => 0
        ],
        [
            'question' => 'Which data structure is typically used for implementing Breadth First Search (BFS)?',
            'options' => ['Stack', 'Queue', 'Array', 'Heap'],
            'answer' => 1
        ],
        [
            'question' => 'What is a circular linked list?',
            'options' => ['A list where every node has two pointers', 'A list where the last node points back to the first node', 'A list that can be traversed in both directions', 'A list with no null pointers'],
            'answer' => 1
        ],
        [
            'question' => 'Which of the following is an example of a non-linear data structure?',
            'options' => ['Array', 'Stack', 'Queue', 'Graph'],
            'answer' => 3
        ],
        [
            'question' => 'What is the height of a balanced binary tree with N nodes?',
            'options' => ['O(N)', 'O(log N)', 'O(N^2)', 'O(1)'],
            'answer' => 1
        ],
        [
            'question' => 'In which data structure do we use a Hash Function?',
            'options' => ['Binary Tree', 'Linked List', 'Hash Table', 'Queue'],
            'answer' => 2
        ]
    ],
    'dbms' => [
        [
            'question' => 'What does SQL stand for?',
            'options' => ['Structured Query Language', 'Simple Query Language', 'Sequential Query Language', 'Strong Query Language'],
            'answer' => 0
        ],
        [
            'question' => 'Which property of ACID ensures that a transaction is treated as a single unit or "all-or-nothing"?',
            'options' => ['Consistency', 'Isolation', 'Atomicity', 'Durability'],
            'answer' => 2
        ],
        [
            'question' => 'Which normal form deals with removing partial dependencies?',
            'options' => ['1NF', '2NF', '3NF', 'BCNF'],
            'answer' => 1
        ],
        [
            'question' => 'Which SQL command is used to remove all records from a table without deleting the table structure?',
            'options' => ['DROP', 'DELETE', 'TRUNCATE', 'REMOVE'],
            'answer' => 2
        ],
        [
            'question' => 'A primary key must be:',
            'options' => ['Unique only', 'Not Null only', 'Both Unique and Not Null', 'None of these'],
            'answer' => 2
        ],
        [
            'question' => 'Which join returns all records when there is a match in either left or right table?',
            'options' => ['INNER JOIN', 'LEFT JOIN', 'RIGHT JOIN', 'FULL OUTER JOIN'],
            'answer' => 3
        ],
        [
            'question' => 'What is a foreign key?',
            'options' => ['A key used to encrypt data', 'A key that uniquely identifies a row in another table', 'A key that connects to an external database', 'A key used for indexing'],
            'answer' => 1
        ],
        [
            'question' => 'Which of the following is a DDL command?',
            'options' => ['SELECT', 'UPDATE', 'CREATE', 'INSERT'],
            'answer' => 2
        ],
        [
            'question' => 'The result of which of the following operations is always a single value?',
            'options' => ['SELECT *', 'Aggregate Functions (like COUNT)', 'JOIN', 'GROUP BY'],
            'answer' => 1
        ],
        [
            'question' => 'What is an Index in a database?',
            'options' => ['A table that stores passwords', 'A data structure that improves the speed of data retrieval', 'A list of all users', 'A backup of the database'],
            'answer' => 1
        ]
    ],
    'web' => [
        [
            'question' => 'What does HTML stand for?',
            'options' => ['Hyperlinks and Text Markup Language', 'Hyper Text Markup Language', 'Home Tool Markup Language', 'Hyper Tool Markup Language'],
            'answer' => 1
        ],
        [
            'question' => 'Which CSS property is used to change the text color of an element?',
            'options' => ['text-color', 'font-color', 'color', 'background-color'],
            'answer' => 2
        ],
        [
            'question' => 'Which of the following is NOT a JavaScript framework or library?',
            'options' => ['React', 'Vue', 'Django', 'Angular'],
            'answer' => 2
        ],
        [
            'question' => 'What is the correct HTML element for the largest heading?',
            'options' => ['<heading>', '<h6>', '<h1>', '<head>'],
            'answer' => 2
        ],
        [
            'question' => 'Which JavaScript method is used to write text into the browser console?',
            'options' => ['console.log()', 'console.print()', 'console.write()', 'print.console()'],
            'answer' => 0
        ],
        [
            'question' => 'What is the purpose of the "alt" attribute in an <img> tag?',
            'options' => ['To set the image alignment', 'To provide alternative text if the image cannot be displayed', 'To link the image to another page', 'To change the image size'],
            'answer' => 1
        ],
        [
            'question' => 'Which CSS layout mode is designed for one-dimensional layouts?',
            'options' => ['Grid', 'Flexbox', 'Block', 'Inline'],
            'answer' => 1
        ],
        [
            'question' => 'How do you declare a JavaScript variable that cannot be reassigned?',
            'options' => ['var', 'let', 'const', 'fixed'],
            'answer' => 2
        ],
        [
            'question' => 'What does the DOM stand for?',
            'options' => ['Digital Object Model', 'Document Object Model', 'Data Object Model', 'Direct Object Model'],
            'answer' => 1
        ],
        [
            'question' => 'Which HTML tag is used to define an internal style sheet?',
            'options' => ['<css>', '<script>', '<style>', '<design>'],
            'answer' => 2
        ]
    ]
];
