#!/usr/bin/env python3
"""
Manual conversion of specific text fields to RichText
This targets only actual text content fields, not numbers, URLs, etc.
"""

import re

def convert_specific_text_fields():
    """Convert specific text fields to RichText, excluding numbers, URLs, etc."""
    
    # Read the current blocks.js file
    with open('plugins/swrice-gutenberg-page-builder/assets/js/blocks.js', 'r') as f:
        content = f.read()
    
    print("Converting specific text fields to RichText...")
    
    # Define specific text fields to convert (excluding numbers, URLs, etc.)
    text_fields_to_convert = [
        # Hero Section
        ('Plugin Name', 'pluginName'),
        # Problem Section  
        ('Problem Heading', 'problemHeading'),
        # Solution Section
        ('Solution Heading', 'solutionHeading'),
        # Features Section
        ('Features Heading', 'featuresHeading'),
        # FAQ Section
        ('FAQ Heading', 'faqHeading'),
        # How It Works Section
        ('How It Works Heading', 'howItWorksHeading'),
        # Testimonials Section
        ('Testimonials Heading', 'testimonialsHeading'),
        # Bonuses Section
        ('Bonuses Heading', 'bonusesHeading'),
        # Guarantee Section
        ('Guarantee Heading', 'guaranteeHeading'),
        # Why Choose Section
        ('Why Choose Heading', 'whyChooseHeading'),
        # About Section
        ('About Heading', 'aboutHeading'),
        # Final CTA Section
        ('CTA Heading', 'ctaHeading'),
        ('CTA Subtitle', 'ctaSubtitle'),
        # Screenshots Section
        ('Screenshots Heading', 'screenshotsHeading'),
        # Video Tutorial Section
        ('Video Tutorial Heading', 'videoTutorialHeading'),
        # Version Changelog Section
        ('Version Changelog Heading', 'versionChangelogHeading'),
    ]
    
    # Convert TextControl fields for headings and titles
    for label, attr_name in text_fields_to_convert:
        # Pattern for TextControl with this specific label
        pattern = rf'createElement\(TextControl,\s*\{{\s*label:\s*[\'\"]{re.escape(label)}[\'\"],[^}}]*value:\s*getAttr\([\'\"]{re.escape(attr_name)}[\'\"]\),[^}}]*onChange:\s*\([^)]+\)\s*=>\s*setAttributes\(\{{\s*{re.escape(attr_name)}:\s*[^}}]+\}}\)[^}}]*\}}\)'
        
        replacement = f'''createElement('div', {{ style: {{ marginBottom: '16px' }} }},
                        createElement('label', {{ style: {{ display: 'block', marginBottom: '8px', fontWeight: 'bold' }} }}, '{label}'),
                        createElement(RichText, {{
                            tagName: "div",
                            placeholder: "Enter {label.lower()} with link support...",
                            value: getAttr('{attr_name}'),
                            onChange: (val) => setAttributes({{ {attr_name}: val }}),
                            allowedFormats: ["core/bold", "core/italic", "core/link"],
                            style: {{
                                border: "1px solid #ddd",
                                padding: "8px",
                                minHeight: "40px",
                                borderRadius: "4px"
                            }}
                        }})
                    )'''
        
        content = re.sub(pattern, replacement, content, flags=re.MULTILINE | re.DOTALL)
    
    # Convert specific TextareaControl fields (excluding shortcodes that might need special handling)
    textarea_fields_to_convert = [
        ('Hero Subtitle', 'heroSubtitle'),
        ('Solution Description', 'solutionDescription'),
        ('Guarantee Text', 'guaranteeText'),
        ('About Description', 'aboutDescription'),
        ('Screenshots Description', 'screenshotsDescription'),
        ('Video Tutorial Description', 'videoTutorialDescription'),
        ('Version Changelog Description', 'versionChangelogDescription'),
        ('Upgrade Notice', 'upgradeNotice'),
    ]
    
    for label, attr_name in textarea_fields_to_convert:
        # Pattern for TextareaControl with this specific label
        pattern = rf'createElement\(TextareaControl,\s*\{{\s*label:\s*[\'\"]{re.escape(label)}[\'\"],[^}}]*value:\s*getAttr\([\'\"]{re.escape(attr_name)}[\'\"]\),[^}}]*onChange:\s*\([^)]+\)\s*=>\s*setAttributes\(\{{\s*{re.escape(attr_name)}:\s*[^}}]+\}}\)[^}}]*\}}\)'
        
        replacement = f'''createElement('div', {{ style: {{ marginBottom: '16px' }} }},
                        createElement('label', {{ style: {{ display: 'block', marginBottom: '8px', fontWeight: 'bold' }} }}, '{label}'),
                        createElement(RichText, {{
                            tagName: "div",
                            placeholder: "Enter {label.lower()} with link support...",
                            value: getAttr('{attr_name}'),
                            onChange: (val) => setAttributes({{ {attr_name}: val }}),
                            allowedFormats: ["core/bold", "core/italic", "core/link"],
                            style: {{
                                border: "1px solid #ddd",
                                padding: "12px",
                                minHeight: "80px",
                                borderRadius: "4px"
                            }}
                        }})
                    )'''
        
        content = re.sub(pattern, replacement, content, flags=re.MULTILINE | re.DOTALL)
    
    # Convert repeater field text inputs in RepeaterField component
    # Look for the specific pattern in RepeaterField where text/textarea fields are rendered
    repeater_text_pattern = r'(if \(field\.type === [\'"]text[\'"] \|\| field\.type === [\'"]textarea[\'"].*?return\s+)createElement\((TextControl|TextareaControl),\s*\{[^}]*label:\s*field\.label[^}]*value:\s*item\[field\.key\][^}]*onChange:[^}]*\}\)'
    
    repeater_replacement = r'''\1createElement(RichText, {
                            tagName: "div",
                            placeholder: `Enter ${field.label.toLowerCase()} with link support...`,
                            value: item[field.key] || '',
                            onChange: (value) => updateItem(index, field.key, value),
                            allowedFormats: ["core/bold", "core/italic", "core/link"],
                            style: {
                                border: "1px solid #ddd",
                                padding: "10px",
                                minHeight: field.type === 'textarea' ? "80px" : "40px",
                                borderRadius: "4px",
                                marginBottom: "8px"
                            }
                        })'''
    
    content = re.sub(repeater_text_pattern, repeater_replacement, content, flags=re.MULTILINE | re.DOTALL)
    
    # Write the updated content back
    with open('plugins/swrice-gutenberg-page-builder/assets/js/blocks.js', 'w') as f:
        f.write(content)
    
    print("✅ Specific text fields converted to RichText!")
    
    # Count remaining TextControl/TextareaControl instances
    remaining_text_controls = len(re.findall(r'createElement\(TextControl', content))
    remaining_textarea_controls = len(re.findall(r'createElement\(TextareaControl', content))
    
    print(f"📊 Remaining TextControl instances: {remaining_text_controls}")
    print(f"📊 Remaining TextareaControl instances: {remaining_textarea_controls}")

if __name__ == "__main__":
    convert_specific_text_fields()
