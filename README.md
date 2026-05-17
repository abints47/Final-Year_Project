# Openly - AI-Powered E-Learning & Career Platform

An integrated E-Learning ecosystem that bridges the gap between skill acquisition and job readiness. This platform enables users to complete technical courses while leveraging AI to automate resume optimization and interview preparation.

---

## 🌐 Live Demo
**Website:** [https://openly-app.infinityfreeapp.com/](https://openly-app.infinityfreeapp.com/)

> **⚠️ IMPORTANT NOTE:** Please open the live website in an **Incognito/Private window**. Some browsers (like Chrome) with strict Safe Browsing settings might restrict normal search access to the domain.

---

## 🚀 Key Features

* **Course Management:** Browse, enroll, and track progress in technical courses (with YouTube video playlist support).
* **AI Resume Parser:** Upload a PDF resume to automatically extract skills, experience, and contact details.
* **Smart Summary Generator:** AI analyzes your profile and generates a high-impact professional summary.
* **Adaptive Interview Prep:** Generates personalized technical and behavioral interview questions based specifically on your uploaded resume and completed courses.
* **Code Racer (Typing Game):** A built-in typing speed calculator game to help developers improve their typing speed.
* **Role-Based Dashboards:** Separate dashboards for Students, Teachers, and Admins to manage content and track progress.

---

## 🧠 AI Integration Architecture

The AI features are powered by the **Groq API** using the `llama-3.3-70b-versatile` model, utilizing a specialized pipeline to process user data.

1. **Resume Processing Pipeline:** We use `PyPDF2` in Python to convert unstructured PDF data into clean text. This text is then fed into the Groq model.
2. **Prompt Engineering:** The platform uses dynamic prompting to ensure high-quality outputs, extracting categorized skills, job fit scores, and tailored interview questions in a strict JSON format.

---

## 🛠️ Tech Stack

| Layer          | Technology                          |
| :------------- | :---------------------------------- |
| **Frontend**   | HTML5, CSS3, JavaScript (Vanilla)   |
| **Backend**    | PHP (Vanilla), Python (for AI scripts)|
| **Database**   | MySQL                               |
| **AI Engine**  | Groq API (LLaMA 3)                  |
| **PDF Parsing**| PyPDF2                              |

---

## 🔧 Installation & Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/abints47/Final-Year_Project.git
   cd Final-Year_Project
   ```

2. **Database Setup**
   - Create a MySQL database named `login`.
   - Import the required tables (users, courses, enrollments, etc.).
   - Configure your database connection in `includes/db.php`.

3. **Set up AI Processing (Python Environment)**
   ```bash
   cd scripts
   pip install -r requirements.txt # Ensure PyPDF2, groq, python-dotenv are installed
   ```
   Create a `.env` file inside the `scripts/` folder and add your Groq API Key:
   ```env
   GROQ_API_KEY=your_api_key_here
   ```

4. **Set up Teacher Access**
   Create a `config.php` inside the `includes/` folder (if not present) and define the secret key:
   ```php
   <?php
   define('TEACHER_SECRET_KEY', 'your_secret_key_here');
   ?>
   ```

5. **Run the Application**
   - Start your local server (e.g., XAMPP, WAMP).
   - Navigate to the project folder in your browser (`http://localhost/Openly`).

---

## 📈 Future Enhancements
* **AI Mock Interviews:** Real-time voice-to-text mock interview sessions.
* **Skill Gap Analysis:** Suggesting specific platform courses based on missing keywords in your resume.
