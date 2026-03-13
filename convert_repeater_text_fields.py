#!/usr/bin/env python3
"""
Convert repeater text fields and remaining specific text fields to RichText
"""

import re

def convert_repeater_text_fields():
    """Convert repeater text fields and remaining specific text fields to RichText"""
    
    # Read the current blocks.js file
    with open('plugins/swrice-gutenberg-page-builder/assets/js/blocks.js', 'r') as f:
        content = f.read()
    
    print("Converting repeater text fields and remaining specific text fields to RichText...")
    
    # 1. Convert Screenshot Title in repeater (line ~1809)
    screenshot_title_repeater_pattern = r'createElement\(TextControl,\s*\{\s*label:\s*[\'"]Screenshot Title[\'"],\s*value:\s*item\.title\s*\|\|\s*[\'"][\'"],\s*onChange:\s*\([^)]+\)\s*=>\s*\{[^}]*updateScreenshotItem[^}]*\}\s*\}\)'
    
    screenshot_title_repeater_replacement = '''createElement(RichText, {
                                    tagName: "div",
                                    placeholder: "Enter screenshot title with link support...",
                                    value: item.title || '',
                                    onChange: (value) => {
                                        const newItems = [...screenshotItems];
                                        newItems[index] = { ...newItems[index], title: value };
                                        setAttributes({ screenshotItems: newItems });
                                    },
                                    allowedFormats: ["core/bold", "core/italic", "core/link"],
                                    style: {
                                        border: "1px solid #ddd",
                                        padding: "8px",
                                        minHeight: "40px",
                                        borderRadius: "4px",
                                        marginBottom: "8px"
                                    }
                                })'''
    
    content = re.sub(screenshot_title_repeater_pattern, screenshot_title_repeater_replacement, content, flags=re.MULTILINE | re.DOTALL)
    
    # 2. Convert Screenshot Description in repeater (line ~1821)
    screenshot_desc_repeater_pattern = r'createElement\(TextareaControl,\s*\{\s*label:\s*[\'"]Screenshot Description[\'"],\s*value:\s*item\.description\s*\|\|\s*[\'"][\'"],\s*onChange:\s*\([^)]+\)\s*=>\s*\{[^}]*updateScreenshotItem[^}]*\}\s*\}\)'
    
    screenshot_desc_repeater_replacement = '''createElement(RichText, {
                                    tagName: "div",
                                    placeholder: "Enter screenshot description with link support...",
                                    value: item.description || '',
                                    onChange: (value) => {
                                        const newItems = [...screenshotItems];
                                        newItems[index] = { ...newItems[index], description: value };
                                        setAttributes({ screenshotItems: newItems });
                                    },
                                    allowedFormats: ["core/bold", "core/italic", "core/link"],
                                    style: {
                                        border: "1px solid #ddd",
                                        padding: "12px",
                                        minHeight: "80px",
                                        borderRadius: "4px",
                                        marginBottom: "8px"
                                    }
                                })'''
    
    content = re.sub(screenshot_desc_repeater_pattern, screenshot_desc_repeater_replacement, content, flags=re.MULTILINE | re.DOTALL)
    
    # 3. Convert Video Title (more flexible pattern)
    video_title_pattern = r'createElement\(TextControl,\s*\{\s*label:\s*[\'"]Video Title[\'"],\s*value:\s*getAttr\([\'"]videoTitle[\'\"]\),\s*onChange:\s*\([^)]+\)\s*=>\s*setAttributes\(\{\s*videoTitle:\s*[^}]+\}\)[^}]*\}\)'
    
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
    
    # 4. Convert any remaining "Current Version" if it's text content
    current_version_pattern = r'createElement\(TextControl,\s*\{\s*label:\s*[\'"]Current Version[\'"],\s*value:\s*getAttr\([\'"]currentVersion[\'\"]\),\s*onChange:\s*\([^)]+\)\s*=>\s*setAttributes\(\{\s*currentVersion:\s*[^}]+\}\)[^}]*\}\)'
    
    current_version_replacement = '''createElement('div', { style: { marginBottom: '16px' } },
                        createElement('label', { style: { display: 'block', marginBottom: '8px', fontWeight: 'bold' } }, 'Current Version'),
                        createElement(RichText, {
                            tagName: "div",
                            placeholder: "Enter current version with link support...",
                            value: getAttr('currentVersion'),
                            onChange: (val) => setAttributes({ currentVersion: val }),
                            allowedFormats: ["core/bold", "core/italic", "core/link"],
                            style: {
                                border: "1px solid #ddd",
                                padding: "8px",
                                minHeight: "40px",
                                borderRadius: "4px"
                            }
                        })
                    )'''
    
    content = re.sub(current_version_pattern, current_version_replacement, content, flags=re.MULTILINE | re.DOTALL)
    
    # Write the updated content back
    with open('plugins/swrice-gutenberg-page-builder/assets/js/blocks.js', 'w') as f:
        f.write(content)
    
    print("✅ Repeater text fields and remaining specific text fields converted to RichText!")
    
    # Count remaining TextControl/TextareaControl instances
    remaining_text_controls = len(re.findall(r'createElement\(TextControl', content))
    remaining_textarea_controls = len(re.findall(r'createElement\(TextareaControl', content))
    
    print(f"📊 Remaining TextControl instances: {remaining_text_controls}")
    print(f"📊 Remaining TextareaControl instances: {remaining_textarea_controls}")
    
    # Show what's left (should only be numbers, URLs, etc.)
    if remaining_text_controls > 0:
        print("\n🔍 Remaining TextControl fields (should be non-text types):")
        text_controls = re.findall(r'createElement\(TextControl,\s*\{\s*label:\s*[\'"]([^\'"]*)[\'"]', content)
        for label in set(text_controls):  # Remove duplicates
            print(f"  - {label}")
    
    if remaining_textarea_controls > 0:
        print("\n🔍 Remaining TextareaControl fields:")
        textarea_controls = re.findall(r'createElement\(TextareaControl,\s*\{\s*label:\s*[\'"]([^\'"]*)[\'"]', content)
        for label in set(textarea_controls):  # Remove duplicates
            print(f"  - {label}")

if __name__ == "__main__":
    convert_repeater_text_fields()
