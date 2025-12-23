import os
from dotenv import load_dotenv
import requests
import mysql.connector
from datetime import datetime
import smtplib
from email.message import EmailMessage

# Load environment variables
LARAVEL_ENV_PATH = '/home/victor/odinala/aquaPush/.env'  
load_dotenv(dotenv_path=LARAVEL_ENV_PATH)

# Database configuration
DB_CONFIG = {
    'host': os.getenv('DB_HOST', '127.0.0.1'),
    'user': os.getenv('DB_USERNAME'),
    'password': os.getenv('DB_PASSWORD'),
    'database': os.getenv('DB_DATABASE'),
}

SITE_URL = os.getenv('APP_URL')



def check_db():
    """Check database connection"""
    try:
        conn = mysql.connector.connect(**DB_CONFIG)
        conn.close()
        print("✓ Database connection OK")
        return True  
    except Exception as e:
        print(f"✗ Database connection FAILED: {e}")
        send_email_alert("AquaPush Database Alert", f"Database connection failed: {e}")
        return False

def send_email_alert(subject, message):
    """Send email alert"""
    try:
        msg = EmailMessage()
        msg["Subject"] = subject
        msg["From"] = "toyinsan27@gmail.com"
        msg["To"] = "chukwuemekavictor693@gmail.com"
        msg.set_content(message)
        
        
        user = os.getenv("MAIL_USERNAME")
        password = os.getenv("MAIL_PASSWORD")
        
        if not user or not password:
            print("ERROR: Email credentials not set in environment variables")
            return False
        
        with smtplib.SMTP_SSL("smtp.gmail.com", 465) as smtp:
            smtp.login(user, password)
            smtp.send_message(msg)
        
        print(f"[EMAIL SENT] {subject}")
        return True
    except Exception as e:
        print(f"[EMAIL FAILED] {e}")
        return False
    


def check_site():
    """Check if site is up"""
    try:
        response = requests.get(SITE_URL, timeout=10)
        if response.status_code == 200:
            print(f"✓ Site is up (Status: {response.status_code})")
            return True
        else:
            print(f"✗ Site returned status {response.status_code}")
            send_email_alert("AquaPush Site Alert", f"Site returned HTTP status {response.status_code}")
            return False
    except Exception as e:
        print(f"✗ Site check FAILED: {e}")
        send_email_alert("AquaPush Site Alert", f"HTTP request failed: {e}")
        return False

if __name__ == "__main__":
    now = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    print(f"\n=== Running AquaPush Health Check at {now} ===")
    
    db_ok = check_db()
    site_ok = check_site()
    
    if db_ok and site_ok:
        print("\n All systems operational\n")
    else:
        print("\n System issues detected - alerts sent\n")