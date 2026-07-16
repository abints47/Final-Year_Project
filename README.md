# 🌐 Openly - AI-Powered E-Learning & Career Platform

An integrated, end-to-end E-Learning ecosystem engineered to bridge the gap between academic skill acquisition and professional job readiness. By seamlessly blending structured learning with advanced artificial intelligence, Openly automates the core components of career preparation—ranging from automated resume parsing and optimization to dynamically adaptive interview simulations.

---

## 🔗 Live Deployment & Credentials

* **Production URL:** [https://openly-app.infinityfreeapp.com/](https://openly-app.infinityfreeapp.com/)

> ⚠️ **IMPORTANT ACCESSIBILITY NOTE:** Please access the live website using an **Incognito / Private Window**. Strict browser-level security policies or aggressive Safe Browsing filters on certain browsers (like Google Chrome) may occasionally flag or restrict standard navigation to free hosting subdomains (`infinityfreeapp.com`).

### 🔑 Test & Administrative Credentials

To facilitate comprehensive testing across all platform authorization levels, use the following pre-configured credentials:

| Role | Identifier / Field | Value |
| :--- | :--- | :--- |
| **Admin** | Email | `Openly@gmail.com` |
| **Admin** | Password | `openly2026` |
| **Teacher** | Access Registration Secret | `OPENLY_TEACHER_2026` |

---

## 🚀 Key Architectural Features

### 👨‍💻 Dynamic Course Management System
* **Curated Playlists:** Allows teachers to pull and structure specialized technical courses utilizing YouTube API playlist integration.
* **Granular Tracking:** Students can seamlessly browse public course catalogs, enroll instantly, and dynamically track their learning percentages as they complete course milestones.

### 🧠 Deep AI Integration Pipeline
* **Automated Resume Parsing:** Upload an unstructured PDF resume to immediately extract key components (Skills, Professional Experience, Contact Metrics).
* **Smart Summary Optimizer:** Utilizes Large Language Models (LLMs) to scan a student's profile and construct high-impact, industry-tailored professional summaries.
* **Adaptive Interview Prep Engine:** Generates hyper-personalized, context-aware technical and behavioral interview questionnaires derived exclusively from a combination of the user's parsed resume and their completed platform courses.

### 🎮 Code Racer (Developer Typing Game)
* A high-performance, gamified typing utility designed specifically for developers to sharpen muscle memory and keyboard agility, featuring live Words-Per-Minute (WPM) calculations and accuracy tracking metrics.

### 🔐 Role-Based Access Control (RBAC) System
* **Student Dashboard:** View learning metrics, resume parsing outputs, generated interview configurations, and typing history.
* **Teacher Dashboard:** Course authoring tools, video playlist synchronization, and student enrollment metrics.
* **Admin Dashboard:** Platform-wide oversight, comprehensive user management, usage statistics, and system security controls.

---

## 🛠️ Comprehensive Tech Stack

| Layer | Component | Implementation Details |
| :--- | :--- | :--- |
| **Frontend Layout** | UI / UX | Vanilla HTML5, Semantic CSS3, Responsive Flexbox/Grid systems |
| **Frontend Logic** | Client Interactions | Vanilla JavaScript (ES6+), Asynchronous Fetch API for server requests |
| **Backend Core** | Server-Side Processing | Vanilla PHP (Procedural & Object-Oriented paradigms) |
| **AI Interfacing** | Data Processing Scripts | Python 3.x standalone extraction scripts |
| **Database Engine** | Storage Layer | MySQL relational database containing normalized relational schemas |
| **AI Processing** | LLM Engine | Groq Cloud API interacting with the high-throughput `llama-3.3-70b-versatile` model |
| **Document Processing** | Text Extraction | `PyPDF2` library for binary text extraction from PDF payloads |

---

## 🧠 AI Engine Architecture & Pipeline

```
[User Uploads PDF] ➔ [PyPDF2 Extraction Script] ➔ [Structured Text Output]
                                                             │
[Groq API (llama-3.3-70b-versatile)] 🖎 [Dynamic Prompt Engineering Engine]
                                                             │
[Strict JSON Output Object] ➔ [Parsed & Extracted UI Fields + Tailored Interview Bank]
```

### 1. Document Extraction Layer
When a user uploads a resume, the PHP backend handles the multipart form upload, validating file headers before handing the file descriptor off to the background Python engine. The `PyPDF2` runtime converts the binary document stream into clean, unformatted ASCII/UTF-8 string literals.

### 2. Contextual Prompt Engineering & JSON Formatting
The raw string data is interpolated into a custom, highly constrained system prompt. The script forces the `llama-3.3-70b-versatile` engine to behave strictly as an isolated JSON serializer. It outputs strict, predictable structured JSON fields mapping exactly to:
* Extracted Tech Stack & Core Competencies
* Historical Professional Experiences
* Dynamic Scoring Model (Job Fit Index)
* Targeted, customized Interview Questions complete with programmatic grading keys

---

## 🔧 Installation, Environment, & Local Setup

### Prerequisite Checklist
* A local PHP web server wrapper environment (e.g., **XAMPP, WAMP, MAMP, or native Apache/PHP runtimes**).
* **MySQL** Server instance running locally (typically port 3306).
* **Python 3.8+** runtime installed with `pip` package manager configurations exposed globally.

### Step-by-Step Deployment Guide

#### 1. Repository Setup
Clone the version control repository to your web server root folder (e.g., `htdocs` for XAMPP):
```bash
git clone https://github.com/abints47/Final-Year_Project.git
cd Final-Year_Project
```

#### 2. Relational Database Initializations
1. Open your database administration utility (e.g., **phpMyAdmin**).
2. Instantiate a clean structural database schema designated precisely as: `login`.
3. Locate the project's source database installation files and import the relational schema tables (`users`, `courses`, `enrollments`, `typing_records`, etc.).
4. Verify or adjust the credential mapping arrays within the file location `includes/db.php`:
   ```php
   $conn = mysqli_connect("localhost", "your_db_user", "your_db_password", "login");
   ```

#### 3. Python AI Environment Construction
Isolate the dependencies needed for document parsing and AI execution:
```bash
cd scripts
# Install required modules
pip install -r requirements.txt
```
*Ensure the file `requirements.txt` correctly provisions: `pyPDF2`, `groq`, and `python-dotenv`.*

Create a localized system file called `.env` within the `scripts/` directory root to secure the API credentials securely:
```env
GROQ_API_KEY=xy_your_actual_groq_api_credential_token_here
```

#### 4. System Security Configuration
Verify security keys by creating or updating `includes/config.php` to define the structural security keys used during teacher registration phases:
```php
<?php
if (!defined('TEACHER_SECRET_KEY')) {
    define('TEACHER_SECRET_KEY', 'OPENLY_TEACHER_2026');
}
?>
```

#### 5. Launch the Application
Start your local server modules (Apache and MySQL). Then open your browser and navigate to the application environment:
```
http://localhost/Final-Year_Project/
```

---

## 📈 Work in Progress (WIP) & Roadmap

Openly is undergoing active development. Below is the project roadmap, detailing completed features and future target releases.

### Done / Fully Integrated
* [x] Basic authentication layers and secure Role-Based Dashboards (RBAC) for Admins, Teachers, and Students.
* [x] Python-based `PyPDF2` resume scraping and dynamic text isolation system.
* [x] Asynchronous backend execution links feeding inputs directly to the Groq LLaMA pipeline.
* [x] Playlist-driven course rendering mechanisms via YouTube video frameworks.
* [x] Core game engine logic for the Code Racer typing modules.

### ⏳ Current Development Cycle (Active WIP)
* [ ] **Skill Gap Analytics Interface:** A matching matrix that compares parsed resume tech stacks against available site courses, auto-suggesting exact video modules to patch technical knowledge deficits.
* [ ] **Strict Schema Validations:** Hardening the Python-to-PHP bridge against potential JSON formatting variations during periods of high Groq API latency.
* [ ] **Advanced Dynamic Course Metrics:** Adding persistent state retention to individual videos within a playlist to allow granular "resume playback" behavior.

### 🔮 Future Enhancements (Backlog Roadmap)
* [ ] **AI-Powered Mock Voice Interviews:** Leveraging WebRTC audio streaming coupled with downstream Whisper (STT) and text-to-speech models to enable full vocal interview dry runs.
* [ ] **Automated PDF Export Engine:** Allowing students to export their AI-optimized resumes straight back into professionally styled, print-ready PDF formats.
