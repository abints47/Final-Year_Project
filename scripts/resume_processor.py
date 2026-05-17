import os
import sys
import json
import PyPDF2
import warnings

# Silence all warnings
warnings.filterwarnings("ignore")

from groq import Groq
from dotenv import load_dotenv

# Load environment variables
load_dotenv()
GROQ_API_KEY = os.getenv('GROQ_API_KEY')

if not GROQ_API_KEY:
    # If GROQ_API_KEY is not set, try to fallback to any provided key for now
    # or print error
    print(json.dumps({"error": "GROQ_API_KEY not found in .env. Please add it to start analysis."}))
    sys.exit(1)

client = Groq(api_key=GROQ_API_KEY)

def extract_text_from_pdf(pdf_path):
    try:
        with open(pdf_path, 'rb') as file:
            reader = PyPDF2.PdfReader(file)
            text = ""
            for page in reader.pages:
                text += page.extract_text()
            return text
    except Exception as e:
        return str(e)

def analyze_resume(resume_text):
    prompt = f"""
    Analyze the following resume text and provide a detailed structured report.
    You MUST return a JSON object with PRECISELY these top-level keys:
    1. "candidate_name": string
    2. "contact_details": object with keys like "email", "phone", "location"
    3. "skills": object where keys are categories and values are lists of strings
    4. "skill_levels": object where keys are main skill categories (e.g., "Technical", "Soft Skills") and values are integers (0-100)
    5. "categorized_skills": object with keys "strong", "medium", "weak", each containing a list of strings
    6. "job_fit": list of EXACTLY 4 objects, each with "role", "match", and "reason" (tailored to the resume's strengths)
    7. "summary": string (max 4 sentences)
    8. "interview_questions": list of 10 objects (each with "question" and "suggested_answer" using the STAR method). These must be the exact type of high-probability behavioral and situational questions an HR Manager would ask after reviewing this specific resume (e.g., "Tell me about a challenge you faced during [Project X]", "How did you handle the transition between [Role A] and [Role B]?").
    9. "elevator_pitch": object with keys "hook", "experience", "goal" (a 30-second "Tell me about yourself" script tailored to the resume)
    10. "describe_me": string (a punchy, one-sentence professional identity summary, e.g., "A visionary Full-Stack Engineer specializing in high-performance cloud architectures.")

    Example Structure:
    {{
      "candidate_name": "John Doe",
      "contact_details": {{ "email": "john@example.com", "phone": "1234567890" }},
      "skills": {{ "Technical": ["Python", "PHP"], "Soft": ["Leadership"] }},
      "skill_levels": {{ "Technical": 90, "Soft Skills": 85, "Communication": 70 }},
      "categorized_skills": {{ "strong": ["Python", "Backend"], "medium": ["JavaScript"], "weak": ["Design"] }},
      "job_fit": [ {{ "role": "Software Engineer", "match": "90%", "reason": "Strong backend skills" }} ],
      "summary": "Experienced developer with a focus on web technologies."
    }}

    Resume Text:
    {resume_text}

    Return ONLY the raw JSON object. Do not include markdown formatting or any other text.
    """
    
    try:
        completion = client.chat.completions.create(
            model="llama-3.3-70b-versatile",
            messages=[
                {
                    "role": "system",
                    "content": "You are a professional resume analyzer that outputs strictly valid JSON."
                },
                {
                    "role": "user",
                    "content": prompt
                }
            ],
            response_format={"type": "json_object"}
        )
        
        res_text = completion.choices[0].message.content.strip()
        return json.loads(res_text)
    except Exception as e:
        return {"error": f"Groq Analysis failed: {str(e)}"}

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(json.dumps({"error": "No PDF path provided"}))
        sys.exit(1)
        
    pdf_path = sys.argv[1]
    if not os.path.exists(pdf_path):
        print(json.dumps({"error": f"File not found: {pdf_path}"}))
        sys.exit(1)
        
    resume_text = extract_text_from_pdf(pdf_path)
    if not resume_text or len(resume_text.strip()) < 10:
        print(json.dumps({"error": "Could not extract text from PDF or PDF is empty."}))
        sys.exit(1)
        
    analysis = analyze_resume(resume_text)
    print(json.dumps(analysis, indent=2))
