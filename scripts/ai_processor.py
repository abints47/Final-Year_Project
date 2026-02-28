import os
import sys
import json
import fitz  # PyMuPDF
import google.generativeai as genai
from dotenv import load_dotenv

# Load environment variables relative to script directory
script_dir = os.path.dirname(os.path.abspath(__file__))
load_dotenv(os.path.join(script_dir, ".env"))

# Configure Gemini API
GEMINI_API_KEY = os.getenv("GEMINI_API_KEY")
if not GEMINI_API_KEY:
    print(json.dumps({"error": "GEMINI_API_KEY not found in environment"}))
    sys.exit(1)

genai.configure(api_key=GEMINI_API_KEY)

def extract_text_from_pdf(pdf_path):
    try:
        doc = fitz.open(pdf_path)
        text = ""
        for page in doc:
            text += page.get_text()
        return text
    except Exception as e:
        return str(e)

def analyze_resume(resume_text):
    try:
        model = genai.GenerativeModel("gemini-flash-latest")
        
        prompt = f"""
        Act as a professional tech recruiter and career coach. 
        Analyze the following resume text and provide a comprehensive career assessment.

        Resume Text:
        {resume_text}

        Output the result ONLY as a JSON object with the following structure:
        {{
            "candidate_name": "Full Name of Candidate",
            "contact_info": {{
                "email": "Email address",
                "phone": "Phone number",
                "location": "City, Country or Address",
                "linkedin": "LinkedIn URL (if found)"
            }},
            "ats_compatibility": 0-100,
            "summary": "3-sentence high-impact professional summary",
            "health_checks": {{
                "Programming": "Strong/Moderate/Weak",
                "Web Development": "Strong/Moderate/Weak",
                "AI & Data Science": "Strong/Moderate/Weak",
                "Infrastructure & Tools": "Strong/Moderate/Weak",
                "Management & Soft Skills": "Strong/Moderate/Weak"
            }},
            "job_fit": {{
                "Fullstack Developer": 0-100,
                "Data Scientist": 0-100,
                "DevOps Engineer": 0-100,
                "Frontend Developer": 0-100
            }},
            "skills_found": {{
                "Programming": ["Skill1", "Skill2"],
                "Web Development": ["Skill1", "Skill2"],
                "Databases": ["Skill1", "Skill2"],
                "Tools": ["Skill1", "Skill2"]
            }},
            "projects": [
                {{
                    "title": "Project Name",
                    "description": "Brief description"
                }}
            ],
            "interview_prep": [
                {{
                    "type": "Technical / Behavioral / Leadership",
                    "question": "The interview question",
                    "answer": "Detailed guide on how the candidate should respond, including key technical points or behavioral frameworks like STAR."
                }}
            ],
            "recommended_jobs": [
                {{
                    "title": "Specific Job Title",
                    "match_score": 0-100,
                    "why_fit": "One sentence explaining why this is a good match",
                    "search_query": "LinkedIn search URL or keywords"
                }}
            ]
        }}
        
        CRITICAL: Provide AT LEAST 10 technical questions based on skills/projects and 6 behavioral/leadership questions based on experience in the 'interview_prep' list. For each question, provide a comprehensive 'answer' guide.
        """
        
        response = model.generate_content(prompt)
        # Extract JSON from response (handling potential markdown wrapper)
        result_text = response.text
        if "```json" in result_text:
            result_text = result_text.split("```json")[1].split("```")[0].strip()
        elif "```" in result_text:
            result_text = result_text.split("```")[1].split("```")[0].strip()
            
        return json.loads(result_text)
    except Exception as e:
        return {{"error": str(e)}}

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(json.dumps({"error": "No PDF path provided"}))
        sys.exit(1)
        
    pdf_path = sys.argv[1]
    if not os.path.exists(pdf_path):
        print(json.dumps({"error": f"File not found: {pdf_path}"}))
        sys.exit(1)
        
    resume_text = extract_text_from_pdf(pdf_path)
    if isinstance(resume_text, str) and not resume_text.strip():
         print(json.dumps({"error": "Could not extract text from PDF"}))
         sys.exit(1)
         
    analysis = analyze_resume(resume_text)
    print(json.dumps(analysis))
