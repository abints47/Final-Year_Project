export interface DatasetEntry {
    keywords: string[];
    answer: string;
}

export const botDataset: DatasetEntry[] = [
    {
        keywords: ["course", "courses", "learn", "teach", "what do you offer"],
        answer: "We offer a wide range of courses including HTML Foundations, Modern CSS, JavaScript Mastery, React 19, C Programming, Java Enterprise, Go Microservices, and MongoDB. You can find them all on our Courses page!"
    },
    {
        keywords: ["certificate", "certification", "degree", "diploma"],
        answer: "Yes! Upon successful completion of any course on Openly, you will receive a digital certificate that you can add to your LinkedIn profile or resume."
    },
    {
        keywords: ["price", "cost", "free", "payment", "money"],
        answer: "Many of our introductory courses are completely free! For premium specialized tracks, we offer competitive pricing and occasional discounts for students."
    },
    {
        keywords: ["login", "signin", "account", "signup", "register"],
        answer: "You can create an account by clicking the 'Sign Up' button in the navigation bar. If you already have an account, just use the 'Login' option."
    },
    {
        keywords: ["who are you", "what is techyarjun", "what is openly", "about", "creator"],
        answer: "Openly is an AI-powered learning platform designed to help students and professionals master modern tech skills through hands-on projects and expert-led content."
    },
    {
        keywords: ["contact", "support", "help", "email", "reach out"],
        answer: "You can reach our support team via the Contact page or email us directly at support@openly.com. We're here to help!"
    },
    {
        keywords: ["react", "frontend", "framework"],
        answer: "Our React 19 Framework course covers everything from component architecture to the latest Server Components features. It's perfect for advanced frontend developers!"
    },
    {
        keywords: ["python", "ai", "machine learning", "ml"],
        answer: "We have extensive resources for Python and AI. Check out our 'Master a Language' section on the homepage for deep dives into AI-related technologies."
    },
    {
        keywords: ["java", "backend", "enterprise"],
        answer: "Our Java Enterprise course teaches you OOP principles and how to build robust, scalable server-side applications used by top companies."
    },
    {
        keywords: ["track", "progress", "streak"],
        answer: "You can track your learning progress and daily streaks directly on your Dashboard. Keep learning every day to maintain your streak!"
    }
];
