#!/usr/bin/env python3
"""
Convert the final remaining text content fields to RichText
"""

import re

def convert_final_text_fields():
    """Convert the final text content fields to RichText"""
    
    # Read the current blocks.js file
    with open('plugins/swrice-gutenberg-page-builder/assets/js/blocks.js', 'r') as f:
        content = f.read()
    
    print("Converting final text content fields to RichText...")
    
    # 1. Convert "CTA Title" TextControl
    cta_title_pattern = r'createElement\(TextControl,\s*\{\s*label:\s*[\'"]CTA Title[\'"],\s*value:\s*getAttr\([\'"]ctaTitle[\'\"]\),\s*onChange:\s*\([^)]+\)\s*=>\s*setAttributes\(\{\s*ctaTitle:\s*[^}]+\}\)\s*\}\)'
    
    cta_title_replacement = '''createElement('div', { style: { marginBottom: '16px' } },
                        createElement('label', { style: { display: 'block', marginBottom: '8px', fontWeight: 'bold' } }, 'CTA Title'),
                        createElement(RichText, {
                            tagName: "div",
                            placeholder: "Enter CTA title with link support...",
                            value: getAttr('ctaTitle'),
                            onChange: (val) => setAttributes({ ctaTitle: val }),
                            allowedFormats: ["core/bold", "core/italic", "core/link"],
                            style: {
                                border: "1px solid #ddd",
                                padding: "8px",
                                minHeight: "40px",
                                borderRadius: "4px"
                            }
                        })
                    )'''
    
    content = re.sub(cta_title_pattern, cta_title_replacement, content, flags=re.MULTILINE | re.DOTALL)
    
    # 2. Convert "Screenshot Title" TextControl
    screenshot_title_pattern = r'createElement\(TextControl,\s*\{\s*label:\s*[\'"]Screenshot Title[\'"],\s*value:\s*getAttr\([\'"]screenshotTitle[\'\"]\),\s*onChange:\s*\([^)]+\)\s*=>\s*setAttributes\(\{\s*screenshotTitle:\s*[^}]+\}\)\s*\}\)'
    
    screenshot_title_replacement = '''createElement('div', { style: { marginBottom: '16px' } },
                        createElement('label', { style: { display: 'block', marginBottom: '8px', fontWeight: 'bold' } }, 'Screenshot Title'),
                        createElement(RichText, {
                            tagName: "div",
                            placeholder: "Enter screenshot title with link support...",
                            value: getAttr('screenshotTitle'),
                            onChange: (val) => setAttributes({ screenshotTitle: val }),
                            allowedFormats: ["core/bold", "core/italic", "core/link"],
                            style: {
                                border: "1px solid #ddd",
                                padding: "8px",
                                minHeight: "40px",
                                borderRadius: "4px"
                            }
                        })
                    )'''
    
    content = re.sub(screenshot_title_pattern, screenshot_title_replacement, content, flags=re.MULTILINE | re.DOTALL)
    
    # 3. Convert "Video Title" TextControl
    video_title_pattern = r'createElement\(TextControl,\s*\{\s*label:\s*[\'"]Video Title[\'"],\s*value:\s*getAttr\([\'"]videoTitle[\'\"]\),\s*onChange:\s*\([^)]+\)\s*=>\s*setAttributes\(\{\s*videoTitle:\s*[^}]+\}\)\s*\}\)'
    
    video_title_replacement = '''createElement('div', { style: { marginBottom: '16px' } },
                        createElement('label', { style: { display: 'block', marginBottom: '8px', fontWeight: 'bold' } }, 'Video Title'),
                        createElement(RichText, {
                            tagName: "div",
                            placeholder: "Enter video title with link support...",
                            value: getAttr('videoTitle'),
                            onChange: (val) => setAttributes({ videoTitle: val }),
                            allowedFormats: ["core/bold", "core/italic", "core/link"],
                            style: {
                                border: "1px solid #ddd",
                                padding: "8px",
                                minHeight: "40px",
                                borderRadius: "4px"
                            }
                        })
                    )'''
    
    content = re.sub(video_title_pattern, video_title_replacement, content, flags=re.MULTILINE | re.DOTALL)
    
    # 4. Convert "Section Description" TextareaControl fields
    section_desc_pattern = r'createElement\(TextareaControl,\s*\{\s*label:\s*[\'"]Section Description[\'"],\s*value:\s*getAttr\([\'"]([^\'\"]+)[\'\"]\),\s*onChange:\s*\([^)]+\)\s*=>\s*setAttributes\(\{[^}]*\}[^}]*\}\)'
    
    def replace_section_desc(match):
        attr_name = match.group(1)
        return f'''createElement('div', {{ style: {{ marginBottom: '16px' }} }},
                        createElement('label', {{ style: {{ display: 'block', marginBottom: '8px', fontWeight: 'bold' }} }}, 'Section Description'),
                        createElement(RichText, {{
                            tagName: "div",
                            placeholder: "Enter section description with link support...",
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
    
    content = re.sub(section_desc_pattern, replace_section_desc, content, flags=re.MULTILINE | re.DOTALL)
    
    # 5. Convert "Screenshot Description" TextareaControl
    screenshot_desc_pattern = r'createElement\(TextareaControl,\s*\{\s*label:\s*[\'"]Screenshot Description[\'"],\s*value:\s*getAttr\([\'"]screenshotDescription[\'\"]\),\s*onChange:\s*\([^)]+\)\s*=>\s*setAttributes\(\{[^}]*\}[^}]*\}\)'
    
    screenshot_desc_replacement = '''createElement('div', { style: { marginBottom: '16px' } },
                        createElement('label', { style: { display: 'block', marginBottom: '8px', fontWeight: 'bold' } }, 'Screenshot Description'),
                        createElement(RichText, {
                            tagName: "div",
                            placeholder: "Enter screenshot description with link support...",
                            value: getAttr('screenshotDescription'),
                            onChange: (val) => setAttributes({ screenshotDescription: val }),
                            allowedFormats: ["core/bold", "core/italic", "core/link"],
                            style: {
                                border: "1px solid #ddd",
                                padding: "12px",
                                minHeight: "80px",
                                borderRadius: "4px"
                            }
                        })
                    )'''
    
    content = re.sub(screenshot_desc_pattern, screenshot_desc_replacement, content, flags=re.MULTILINE | re.DOTALL)
    
    # Write the updated content back
    with open('plugins/swrice-gutenberg-page-builder/assets/js/blocks.js', 'w') as f:
        f.write(content)
    
    print("✅ Final text content fields converted to RichText!")
    
    # Count remaining TextControl/TextareaControl instances
    remaining_text_controls = len(re.findall(r'createElement\(TextControl', content))
    remaining_textarea_controls = len(re.findall(r'createElement\(TextareaControl', content))
    
    print(f"📊 Remaining TextControl instances: {remaining_text_controls}")
    print(f"📊 Remaining TextareaControl instances: {remaining_textarea_controls}")
    
    # Show what's left (should only be numbers, URLs, etc.)
    if remaining_text_controls > 0:
        print("\n🔍 Remaining TextControl fields (should be non-text types):")
        text_controls = re.findall(r'createElement\(TextControl,\s*\{\s*label:\s*[\'"]([^\'"]*)[\'"]', content)
        for label in text_controls:
            print(f"  - {label}")
    
    if remaining_textarea_controls > 0:
        print("\n🔍 Remaining TextareaControl fields:")
        textarea_controls = re.findall(r'createElement\(TextareaControl,\s*\{\s*label:\s*[\'"]([^\'"]*)[\'"]', content)
        for label in textarea_controls:
            print(f"  - {label}")

if __name__ == "__main__":
    convert_final_text_fields()
