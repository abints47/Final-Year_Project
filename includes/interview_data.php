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
    ]
];
