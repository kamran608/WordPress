#!/usr/bin/env python3
"""
Comprehensive script to convert ALL text fields in ALL blocks to RichText
This includes titles, names, subtitles, labels, descriptions, and any other text content
"""

import re

def convert_all_text_fields_to_richtext():
    """Convert ALL TextControl and TextareaControl fields to RichText across all blocks"""
    
    # Read the current blocks.js file
    with open('plugins/swrice-gutenberg-page-builder/assets/js/blocks.js', 'r') as f:
        content = f.read()
    
    print("Converting ALL text fields to RichText...")
    
    # Pattern 1: Convert TextControl fields to RichText
    # This handles single-line text inputs like titles, names, labels, etc.
    textcontrol_pattern = r'createElement\(TextControl,\s*\{\s*label:\s*[\'"]([^\'"]*)[\'"],\s*value:\s*([^,]+),\s*onChange:\s*\(([^)]+)\)\s*=>\s*([^}]+)\}([^}]*)\}'
    
    def replace_textcontrol(match):
        label = match.group(1)
        value = match.group(2)
        param = match.group(3)
        onchange = match.group(4)
        extra_props = match.group(5)
        
        # Skip if it's a number, url, or other non-text type
        if 'type:' in extra_props and ('number' in extra_props or 'url' in extra_props or 'email' in extra_props):
            return match.group(0)  # Return original
        
        return f'''createElement(RichText, {{
                        tagName: "div",
                        placeholder: "Enter {label.lower()} with link support...",
                        value: {value},
                        onChange: ({param}) => {onchange},
                        allowedFormats: ["core/bold", "core/italic", "core/link"],
                        style: {{
                            border: "1px solid #ddd",
                            padding: "8px",
                            minHeight: "40px",
                            borderRadius: "4px"
                        }}
                    }})'''
    
    # Apply TextControl conversion
    content = re.sub(textcontrol_pattern, replace_textcontrol, content, flags=re.MULTILINE | re.DOTALL)
    
    # Pattern 2: Convert TextareaControl fields to RichText
    # This handles multi-line text inputs like descriptions, content, etc.
    textarea_pattern = r'createElement\(TextareaControl,\s*\{\s*label:\s*[\'"]([^\'"]*)[\'"],\s*value:\s*([^,]+),\s*onChange:\s*\(([^)]+)\)\s*=>\s*([^}]+)\}([^}]*)\}'
    
    def replace_textarea(match):
        label = match.group(1)
        value = match.group(2)
        param = match.group(3)
        onchange = match.group(4)
        
        return f'''createElement(RichText, {{
                        tagName: "div",
                        placeholder: "Enter {label.lower()} with link support...",
                        value: {value},
                        onChange: ({param}) => {onchange},
                        allowedFormats: ["core/bold", "core/italic", "core/link"],
                        style: {{
                            border: "1px solid #ddd",
                            padding: "12px",
                            minHeight: "80px",
                            borderRadius: "4px"
                        }}
                    }})'''
    
    # Apply TextareaControl conversion
    content = re.sub(textarea_pattern, replace_textarea, content, flags=re.MULTILINE | re.DOTALL)
    
    # Pattern 3: Convert RepeaterField text fields that might still be using TextControl/TextareaControl
    # Update the RepeaterField component to handle all text field types as RichText
    repeater_pattern = r'(if \(field\.type === [\'"]text[\'"] \|\| field\.type === [\'"]textarea[\'"].*?)(createElement\(TextControl|createElement\(TextareaControl)(.*?)\}\)(\s*\}\s*else)'
    
    def replace_repeater_text(match):
        condition = match.group(1)
        rest = match.group(3)
        else_part = match.group(4)
        
        return f'''{condition}createElement(RichText, {{
                            tagName: "div",
                            placeholder: `Enter ${{field.label.toLowerCase()}} with link support...`,
                            value: item[field.key] || '',
                            onChange: (value) => updateItem(index, field.key, value),
                            allowedFormats: ["core/bold", "core/italic", "core/link"],
                            style: {{
                                border: "1px solid #ddd",
                                padding: "10px",
                                minHeight: field.type === 'textarea' ? "80px" : "40px",
                                borderRadius: "4px",
                                marginBottom: "8px"
                            }}
                        }}){else_part}'''
    
    content = re.sub(repeater_pattern, replace_repeater_text, content, flags=re.MULTILINE | re.DOTALL)
    
    # Pattern 4: Update all preview displays to use RichText.Content
    # Convert createElement('h1', ...), createElement('h2', ...), createElement('h3', ...), createElement('p', ...) 
    # that display text content to use RichText.Content
    
    preview_patterns = [
        # Headings and paragraphs with text content
        (r'createElement\([\'"]h([1-6])[\'"],\s*\{([^}]*)\},\s*([^)]+)\)', 
         r'createElement(RichText.Content, { tagName: "h\1", \2, value: \3 })'),
        (r'createElement\([\'"]p[\'"],\s*\{([^}]*)\},\s*([^)]+)\)', 
         r'createElement(RichText.Content, { tagName: "p", \1, value: \2 })'),
        (r'createElement\([\'"]div[\'"],\s*\{([^}]*)\},\s*([^)]+)\)', 
         r'createElement(RichText.Content, { tagName: "div", \1, value: \2 })'),
        (r'createElement\([\'"]span[\'"],\s*\{([^}]*)\},\s*([^)]+)\)', 
         r'createElement(RichText.Content, { tagName: "span", \1, value: \2 })'),
    ]
    
    for pattern, replacement in preview_patterns:
        content = re.sub(pattern, replacement, content, flags=re.MULTILINE)
    
    # Write the updated content back
    with open('plugins/swrice-gutenberg-page-builder/assets/js/blocks.js', 'w') as f:
        f.write(content)
    
    print("✅ ALL text fields converted to RichText!")
    
    # Count remaining TextControl/TextareaControl instances
    remaining_text_controls = len(re.findall(r'createElement\(TextControl', content))
    remaining_textarea_controls = len(re.findall(r'createElement\(TextareaControl', content))
    
    print(f"📊 Remaining TextControl instances: {remaining_text_controls}")
    print(f"📊 Remaining TextareaControl instances: {remaining_textarea_controls}")
    
    if remaining_text_controls > 0 or remaining_textarea_controls > 0:
        print("⚠️  Some text controls may still need manual conversion (likely non-text types like numbers, URLs)")
    else:
        print("🎉 ALL text controls successfully converted!")

if __name__ == "__main__":
    convert_all_text_fields_to_richtext()
