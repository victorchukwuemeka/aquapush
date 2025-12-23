import os
from dotenv import load_dotenv
import mysql.connector
import smtplib
from email.message import EmailMessage

# Load environment variables
LARAVEL_ENV_PATH = '/home/victor/odinala/aquaPush/.env'  
load_dotenv(dotenv_path=LARAVEL_ENV_PATH)

def test_env_variables():
    
    
    required_vars = {
        'DB_HOST': os.getenv('DB_HOST'),
        'DB_USERNAME': os.getenv('DB_USERNAME'),
        'DB_PASSWORD': os.getenv('DB_PASSWORD'),
        'DB_DATABASE': os.getenv('DB_DATABASE'),
        'MAIL_USERNAME': os.getenv('MAIL_USERNAME'),
        'MAIL_PASSWORD': os.getenv('MAIL_PASSWORD'),
        'APP_URL': os.getenv('APP_URL'),
    }
    print(required_vars)
    all_ok = True
    for var_name, var_value in required_vars.items():
        if var_value:
    
            if 'PASSWORD' in var_name:
                display_value = '*' * 8
            
            else:
                display_value = var_value
            print(f"{var_name}: {display_value}")
        else:
            print(f" {var_name}: NOT SET")
            all_ok = False
    
    return all_ok



def test_database_connection():
    
    print("\n=== Testing Database Connection ===")
    
    try:
        DB_CONFIG = {
            'host': os.getenv('DB_HOST', '127.0.0.1'),
            'user': os.getenv('DB_USERNAME'),
            'password': os.getenv('DB_PASSWORD'),
            'database': os.getenv('DB_DATABASE'),
        }
        
        print(f"Connecting to: {DB_CONFIG['user']}@{DB_CONFIG['host']}/{DB_CONFIG['database']}")
        
        conn = mysql.connector.connect(**DB_CONFIG)
        cursor = conn.cursor()
        
        
        cursor.execute("SELECT DATABASE(), VERSION(), NOW()")
        result = cursor.fetchone()
        
        print(f" Connected to database: {result[0]}")
        print(f" MySQL version: {result[1]}")
        print(f" Server time: {result[2]}")
        
        cursor.close()
        conn.close()
        
        return True
        
    except mysql.connector.Error as e:
        print(f" Database connection FAILED")
        print(f"  Error code: {e.errno}")
        print(f"  Error message: {e.msg}")
        return False
    except Exception as e:
        print(f" Unexpected error: {e}")
        return False

def test_email_credentials():
    """Test email authentication (without sending)"""
    print("\n=== Testing Email Credentials ===")
    
    try:
        user = os.getenv("MAIL_USERNAME")
        password = os.getenv("MAIL_PASSWORD")
        
        if not user or not password:
            print("✗ Email credentials not set in environment variables")
            return False
        
        print(f"Testing SMTP login for: {user}")
        
        # Test SMTP connection and authentication
        with smtplib.SMTP_SSL("smtp.gmail.com", 465, timeout=10) as smtp:
            smtp.login(user, password)
            print("✓ SMTP authentication successful")
            print("✓ Gmail credentials are valid")
        
        return True
        
    except smtplib.SMTPAuthenticationError as e:
        print(f"✗ SMTP authentication FAILED")
        print(f"  Error: {e}")
        print("\n  Possible issues:")
        print("  - Wrong username or password")
        print("  - 2-Step Verification not enabled")
        print("  - App Password not generated correctly")
        print("  - 'Less secure app access' needed (not recommended)")
        return False
    except Exception as e:
        print(f"✗ SMTP connection FAILED: {e}")
        return False

def test_send_email():
    """Test sending an actual email"""
    print("\n=== Testing Email Sending ===")
    
    try:
        user = os.getenv("MAIL_USERNAME")
        password = os.getenv("MAIL_PASSWORD")
        
        if not user or not password:
            print("✗ Email credentials not set")
            return False
        
        # Create test email
        msg = EmailMessage()
        msg["Subject"] = "AquaPush Test Email"
        msg["From"] = user
        msg["To"] = "chukwuemekavictor693@gmail.com"
        msg.set_content("""
This is a test email from AquaPush monitoring system.

If you receive this, email notifications are working correctly!

Test timestamp: """ + str(os.popen('date').read().strip()))
        
        print(f"Sending test email from {user}...")
        
        with smtplib.SMTP_SSL("smtp.gmail.com", 465, timeout=10) as smtp:
            smtp.login(user, password)
            smtp.send_message(msg)
        
        print("✓ Test email sent successfully!")
        print(f"  Check inbox: chukwuemekavictor693@gmail.com")
        
        return True
        
    except Exception as e:
        print(f"✗ Email sending FAILED: {e}")
        return False

def run_all_tests():
    """Run all tests"""
    print("=" * 60)
    print("AquaPush Health Check - Component Testing")
    print("=" * 60)
    
    results = {
        "Environment Variables": test_env_variables(),
        "Database Connection": test_database_connection(),
        "Email Credentials": test_email_credentials(),
        "Email Sending": test_send_email(),
    }
    
    # Summary
    print("\n" + "=" * 60)
    print("TEST SUMMARY")
    print("=" * 60)
    
    for test_name, passed in results.items():
        status = "✓ PASSED" if passed else "✗ FAILED"
        print(f"{test_name:.<40} {status}")
    
    print("=" * 60)
    
    all_passed = all(results.values())
    if all_passed:
        print("\n All tests PASSED! Your system is ready.")
    else:
        print("\n Some tests FAILED. Please fix the issues above.")
    
    return all_passed

if __name__ == "__main__":
    run_all_tests()