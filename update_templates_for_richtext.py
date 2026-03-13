#!/usr/bin/env python3
"""
Update all PHP templates to use wp_kses_post() for RichText fields
"""

import os
import re

def update_templates_for_richtext():
    """Update all PHP templates to use wp_kses_post() instead of esc_html() for text content"""
    
    template_dir = 'plugins/swrice-gutenberg-page-builder/templates'
    
    # Get all PHP template files
    template_files = []
    for file in os.listdir(template_dir):
        if file.endswith('.php'):
            template_files.append(os.path.join(template_dir, file))
    
    print(f"Updating {len(template_files)} template files for RichText support...")
    
    # Define text content fields that should use wp_kses_post() instead of esc_html()
    text_content_fields = [
        'pluginName',
        'heroSubtitle', 
        'buyNowShortcode',
        'problemHeading',
        'solutionHeading',
        'solutionDescription',
        'featuresHeading',
        'faqHeading',
        'howItWorksHeading',
        'testimonialsHeading',
        'bonusesHeading',
        'guaranteeHeading',
        'guaranteeText',
        'whyChooseHeading',
        'aboutHeading',
        'aboutDescription',
        'ctaHeading',
        'ctaSubtitle',
        'screenshotsHeading',
        'screenshotsDescription',
        'videoTutorialHeading',
        'videoTutorialDescription',
        'versionChangelogHeading',
        'versionChangelogDescription',
        'upgradeNotice',
        'ctaTitle',
        'screenshotTitle',
        'videoTitle',
        'currentVersion'
    ]
    
    for template_file in template_files:
        print(f"  Updating {os.path.basename(template_file)}...")
        
        # Read the template file
        with open(template_file, 'r') as f:
            content = f.read()
        
        # Replace esc_html() with wp_kses_post() for text content fields
        for field in text_content_fields:
            # Pattern: esc_html($attributes['fieldName'])
            pattern = rf'esc_html\(\$attributes\[[\'\"]{field}[\'\"]\]\)'
            replacement = f'wp_kses_post($attributes[\'{field}\'])'
            content = re.sub(pattern, replacement, content)
            
            # Pattern: esc_html($fieldName)
            pattern = rf'esc_html\(\${field}\)'
            replacement = f'wp_kses_post(${field})'
            content = re.sub(pattern, replacement, content)
        
        # Also handle repeater item fields (like screenshot titles/descriptions)
        # Pattern: esc_html($item['title']) or esc_html($item['description'])
        repeater_patterns = [
            (r'esc_html\(\$item\[[\'\"](title|description)[\'\"]\]\)', r'wp_kses_post($item[\'\1\'])'),
            (r'esc_html\(\$screenshot\[[\'\"](title|description)[\'\"]\]\)', r'wp_kses_post($screenshot[\'\1\'])'),
            (r'esc_html\(\$feature\[[\'\"](title|description)[\'\"]\]\)', r'wp_kses_post($feature[\'\1\'])'),
            (r'esc_html\(\$faq\[[\'\"](question|answer)[\'\"]\]\)', r'wp_kses_post($faq[\'\1\'])'),
            (r'esc_html\(\$step\[[\'\"](title|description)[\'\"]\]\)', r'wp_kses_post($step[\'\1\'])'),
            (r'esc_html\(\$testimonial\[[\'\"](name|text|company)[\'\"]\]\)', r'wp_kses_post($testimonial[\'\1\'])'),
            (r'esc_html\(\$bonus\[[\'\"](title|description)[\'\"]\]\)', r'wp_kses_post($bonus[\'\1\'])'),
            (r'esc_html\(\$benefit\[[\'\"](title|description)[\'\"]\]\)', r'wp_kses_post($benefit[\'\1\'])'),
        ]
        
        for pattern, replacement in repeater_patterns:
            content = re.sub(pattern, replacement, content)
        
        # Write the updated content back
        with open(template_file, 'w') as f:
            f.write(content)
    
    print("✅ All template files updated for RichText support!")
    print("📋 Templates now use wp_kses_post() for safe HTML rendering of rich text content")

if __name__ == "__main__":
    update_templates_for_richtext()
