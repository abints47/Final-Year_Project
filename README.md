# AI-Powered Online Learning & Career Platform

An integrated E-Learning ecosystem that bridges the gap between skill acquisition and job readiness. This platform enables users to complete technical courses while leveraging AI to automate resume optimization and interview preparation.

---

## 🚀 Key Features

* **Course Management:** Browse and enroll in technical courses with progress tracking.
* **AI Resume Parser:** Upload a PDF resume to automatically extract skills and experience.
* **Smart Summary Generator:** AI analyzes your profile and generates a high-impact professional summary.
* **Adaptive Interview Prep:** Generates personalized technical and behavioral interview questions based specifically on your uploaded resume and completed courses.

---

## 🧠 AI Integration Architecture

The AI features are powered by the **Gemini API**, utilizing a specialized pipeline to process user data.



### 1. Resume Processing Pipeline
We use `PyMuPDF` to convert unstructured PDF data into clean text/markdown. This text is then fed into the Gemini Pro model.

### 2. Prompt Engineering
The platform uses dynamic prompting to ensure high-quality outputs:
* **Summary Logic:** *"Act as a tech recruiter. Summarize the following candidate's profile in 3 impactful sentences focusing on [Extracted Skills]."*
* **Interview Logic:** *"Based on the projects listed in this resume: [Resume Text], generate 5 senior-level technical questions and 3 behavioral questions."*

---

## 🛠️ Tech Stack

| Layer          | Technology                          |
| :------------- | :---------------------------------- |
| **Frontend** | React.js / Next.js                  |
| **Backend** | Python (FastAPI / Flask)            |
| **Database** | PostgreSQL / MongoDB                |
| **AI Engine** | Google Gemini API                   |
| **PDF Parsing**| PyMuPDF / pdfminer.six              |

---

## 🔧 Installation & Setup

1.  **Clone the repository**
    ```bash
    git clone [https://github.com/yourusername/learning-platform.git](https://github.com/yourusername/learning-platform.git)
    ```

2.  **Install Dependencies**
    ```bash
    pip install -r requirements.txt
    ```

3.  **Set up API Keys**
    Create a `.env` file and add your Gemini API Key:
    ```env
    GEMINI_API_KEY=your_api_key_here
    ```

4.  **Run the Application**
    ```bash
    python main.py
    ```

---

## 📈 Future Enhancements
* **AI Mock Interviews:** Real-time voice-to-text mock interview sessions.
* **Skill Gap Analysis:** Suggesting specific platform courses based on missing keywords in your resume.
