#!/usr/bin/env python3
"""
Convert remaining text fields to RichText
This handles generic labels and remaining text content fields
"""

import re

def convert_remaining_text_fields():
    """Convert remaining text fields to RichText"""
    
    # Read the current blocks.js file
    with open('plugins/swrice-gutenberg-page-builder/assets/js/blocks.js', 'r') as f:
        content = f.read()
    
    print("Converting remaining text fields to RichText...")
    
    # 1. Convert "Section Heading" TextControl fields (these are text content)
    section_heading_pattern = r'createElement\(TextControl,\s*\{\s*label:\s*[\'"]Section Heading[\'"],\s*value:\s*getAttr\([\'"]([^\'\"]+)[\'\"]\),\s*onChange:\s*\([^)]+\)\s*=>\s*setAttributes\(\{\s*([^:]+):\s*[^}]+\}\)\s*\}\)'
    
    def replace_section_heading(match):
        attr_name = match.group(1)
        return f'''createElement('div', {{ style: {{ marginBottom: '16px' }} }},
                        createElement('label', {{ style: {{ display: 'block', marginBottom: '8px', fontWeight: 'bold' }} }}, 'Section Heading'),
                        createElement(RichText, {{
                            tagName: "div",
                            placeholder: "Enter section heading with link support...",
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
    
    content = re.sub(section_heading_pattern, replace_section_heading, content, flags=re.MULTILINE | re.DOTALL)
    
    # 2. Convert "Buy Now Shortcode" TextareaControl (this is text content that can have links)
    buy_now_pattern = r'createElement\(TextareaControl,\s*\{\s*label:\s*[\'"]Buy Now Shortcode[\'"],\s*value:\s*getAttr\([\'"]buyNowShortcode[\'\"]\),\s*onChange:\s*\([^)]+\)\s*=>\s*setAttributes\(\{\s*buyNowShortcode:\s*[^}]+\}\),[^}]*\}\)'
    
    buy_now_replacement = '''createElement('div', { style: { marginBottom: '16px' } },
                        createElement('label', { style: { display: 'block', marginBottom: '8px', fontWeight: 'bold' } }, 'Buy Now Shortcode'),
                        createElement(RichText, {
                            tagName: "div",
                            placeholder: "Enter buy now shortcode with link support...",
                            value: getAttr('buyNowShortcode'),
                            onChange: (val) => setAttributes({ buyNowShortcode: val }),
                            allowedFormats: ["core/bold", "core/italic", "core/link"],
                            style: {
                                border: "1px solid #ddd",
                                padding: "12px",
                                minHeight: "80px",
                                borderRadius: "4px"
                            }
                        })
                    )'''
    
    content = re.sub(buy_now_pattern, buy_now_replacement, content, flags=re.MULTILINE | re.DOTALL)
    
    # 3. Fix the RepeaterField component - convert the TextControl for text/textarea fields
    repeater_pattern = r'return createElement\(TextControl,\s*\{\s*key:\s*field\.key,\s*label:\s*field\.label,\s*value:\s*item\[field\.key\]\s*\|\|\s*[\'"][\'"],\s*onChange:\s*\([^)]+\)\s*=>\s*updateItem\([^)]+\)\s*\}\);'
    
    repeater_replacement = '''return createElement(RichText, {
                            key: field.key,
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
                        });'''
    
    content = re.sub(repeater_pattern, repeater_replacement, content, flags=re.MULTILINE | re.DOTALL)
    
    # 4. Convert any remaining TextareaControl that are for text content (not shortcodes with special formatting)
    # Look for common text content patterns
    text_content_labels = [
        'Description', 'Content', 'Text', 'Message', 'Note', 'Summary', 
        'Details', 'Information', 'About', 'Bio', 'Story'
    ]
    
    for label in text_content_labels:
        pattern = rf'createElement\(TextareaControl,\s*\{{\s*label:\s*[\'\"]{label}[\'\"],[^}}]*value:\s*getAttr\([\'"]([^\'\"]+)[\'\"]\),[^}}]*onChange:\s*\([^)]+\)\s*=>\s*setAttributes\(\{{\s*([^:]+):\s*[^}}]+\}}\)[^}}]*\}}\)'
        
        def replace_textarea_content(match):
            attr_name = match.group(1)
            return f'''createElement('div', {{ style: {{ marginBottom: '16px' }} }},
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
        
        content = re.sub(pattern, replace_textarea_content, content, flags=re.MULTILINE | re.DOTALL)
    
    # Write the updated content back
    with open('plugins/swrice-gutenberg-page-builder/assets/js/blocks.js', 'w') as f:
        f.write(content)
    
    print("✅ Remaining text fields converted to RichText!")
    
    # Count remaining TextControl/TextareaControl instances
    remaining_text_controls = len(re.findall(r'createElement\(TextControl', content))
    remaining_textarea_controls = len(re.findall(r'createElement\(TextareaControl', content))
    
    print(f"📊 Remaining TextControl instances: {remaining_text_controls}")
    print(f"📊 Remaining TextareaControl instances: {remaining_textarea_controls}")
    
    # Show what's left
    if remaining_text_controls > 0:
        print("\n🔍 Remaining TextControl fields:")
        text_controls = re.findall(r'createElement\(TextControl,\s*\{\s*label:\s*[\'"]([^\'"]*)[\'"]', content)
        for label in text_controls[:10]:  # Show first 10
            print(f"  - {label}")
    
    if remaining_textarea_controls > 0:
        print("\n🔍 Remaining TextareaControl fields:")
        textarea_controls = re.findall(r'createElement\(TextareaControl,\s*\{\s*label:\s*[\'"]([^\'"]*)[\'"]', content)
        for label in textarea_controls[:10]:  # Show first 10
            print(f"  - {label}")

if __name__ == "__main__":
    convert_remaining_text_fields()
