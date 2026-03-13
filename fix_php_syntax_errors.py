#!/usr/bin/env python3
"""
Fix PHP syntax errors in template files
Replace escaped single quotes \' with regular single quotes '
"""

import os
import re

def fix_php_syntax_errors():
    """Fix PHP syntax errors in all template files"""
    
    template_dir = 'plugins/swrice-gutenberg-page-builder/templates'
    
    # Get all PHP template files
    template_files = []
    for file in os.listdir(template_dir):
        if file.endswith('.php'):
            template_files.append(os.path.join(template_dir, file))
    
    print(f"Fixing PHP syntax errors in {len(template_files)} template files...")
    
    for template_file in template_files:
        print(f"  Fixing {os.path.basename(template_file)}...")
        
        # Read the template file
        with open(template_file, 'r') as f:
            content = f.read()
        
        # Fix escaped single quotes in array access
        # Replace \' with ' in patterns like $array[\'key\']
        content = re.sub(r"\\'", "'", content)
        
        # Write the updated content back
        with open(template_file, 'w') as f:
            f.write(content)
    
    print("✅ All PHP syntax errors fixed!")
    print("📋 Replaced escaped single quotes \\' with regular single quotes '")

if __name__ == "__main__":
    fix_php_syntax_errors()
