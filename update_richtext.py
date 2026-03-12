#!/usr/bin/env python3
"""
Script to update all text fields in Swrice blocks to support RichText
"""

import re

def update_repeater_field_textarea(content):
    """Update RepeaterField component to use RichText for textarea fields"""
    
    # Find the textarea field handling in RepeaterField
    pattern = r'''if \(field\.type === 'textarea'\) \{
                        return createElement\(TextareaControl, \{
                            key: field\.key,
                            label: field\.label,
                            value: item\[field\.key\] \|\| '',
                            onChange: \(value\) => updateItem\(index, field\.key, value\),
                            rows: 3
                        \}\);'''
    
    replacement = '''if (field.type === 'textarea') {
                        return createElement('div', { key: field.key, style: { marginBottom: '15px' } },
                            createElement('label', { 
                                style: { 
                                    display: 'block', 
                                    marginBottom: '5px', 
                                    fontWeight: 'bold',
                                    fontSize: '13px'
                                }
                            }, field.label),
                            createElement(RichText, {
                                tagName: "div",
                                placeholder: `Enter ${field.label.toLowerCase()} with link support...`,
                                value: item[field.key] || '',
                                onChange: (value) => updateItem(index, field.key, value),
                                allowedFormats: ["core/bold", "core/italic", "core/link"],
                                style: { 
                                    border: "1px solid #ddd", 
                                    padding: "8px", 
                                    borderRadius: "4px", 
                                    minHeight: "60px",
                                    backgroundColor: "#fff"
                                }
                            })
                        );'''
    
    content = re.sub(pattern, replacement, content, flags=re.MULTILINE | re.DOTALL)
    return content

def update_main_text_fields(content):
    """Update main text fields to use RichText in editor preview areas"""
    
    # List of text fields to update with their block context
    text_field_updates = [
        # Guarantee Section - guaranteeText
        {
            'search': r"createElement\('p', \{ className: 'sppm-guarantee-text' \}, \s*getAttr\('guaranteeText', 'We guarantee your satisfaction with our product\.'\)\s*\)",
            'replace': "createElement(RichText, { tagName: 'p', className: 'sppm-guarantee-text', placeholder: 'Enter guarantee text with link support...', value: getAttr('guaranteeText'), onChange: (val) => setAttributes({ guaranteeText: val }), allowedFormats: ['core/bold', 'core/italic', 'core/link'] })"
        },
        # About Section - aboutDescription  
        {
            'search': r"createElement\('p', null, \s*getAttr\('aboutDescription', 'Learn more about our company and mission\.'\)\s*\)",
            'replace': "createElement(RichText, { tagName: 'p', placeholder: 'Enter about description with link support...', value: getAttr('aboutDescription'), onChange: (val) => setAttributes({ aboutDescription: val }), allowedFormats: ['core/bold', 'core/italic', 'core/link'] })"
        },
        # Final CTA Section - ctaSubtitle
        {
            'search': r"createElement\('p', \{ className: 'sppm-cta-subtitle' \},\s*getAttr\('ctaSubtitle', 'Join thousands of satisfied customers'\)\s*\)",
            'replace': "createElement(RichText, { tagName: 'p', className: 'sppm-cta-subtitle', placeholder: 'Enter CTA subtitle with link support...', value: getAttr('ctaSubtitle'), onChange: (val) => setAttributes({ ctaSubtitle: val }), allowedFormats: ['core/bold', 'core/italic', 'core/link'] })"
        }
    ]
    
    for update in text_field_updates:
        content = re.sub(update['search'], update['replace'], content, flags=re.MULTILINE | re.DOTALL)
    
    return content

def update_section_descriptions(content):
    """Update section description fields to use RichText"""
    
    # Screenshots section description
    content = re.sub(
        r"createElement\('p', \{ className: 'sppm-section-description' \},\s*getAttr\('screenshotsDescription'\)\s*\)",
        "createElement(RichText, { tagName: 'p', className: 'sppm-section-description', placeholder: 'Enter screenshots description with link support...', value: getAttr('screenshotsDescription'), onChange: (val) => setAttributes({ screenshotsDescription: val }), allowedFormats: ['core/bold', 'core/italic', 'core/link'] })",
        content, flags=re.MULTILINE | re.DOTALL
    )
    
    # Video tutorial section description
    content = re.sub(
        r"createElement\('p', \{ className: 'sppm-section-description' \},\s*getAttr\('videoTutorialDescription'\)\s*\)",
        "createElement(RichText, { tagName: 'p', className: 'sppm-section-description', placeholder: 'Enter video tutorial description with link support...', value: getAttr('videoTutorialDescription'), onChange: (val) => setAttributes({ videoTutorialDescription: val }), allowedFormats: ['core/bold', 'core/italic', 'core/link'] })",
        content, flags=re.MULTILINE | re.DOTALL
    )
    
    # Version changelog section description
    content = re.sub(
        r"createElement\('p', \{ className: 'sppm-section-description' \},\s*getAttr\('versionChangelogDescription'\)\s*\)",
        "createElement(RichText, { tagName: 'p', className: 'sppm-section-description', placeholder: 'Enter version changelog description with link support...', value: getAttr('versionChangelogDescription'), onChange: (val) => setAttributes({ versionChangelogDescription: val }), allowedFormats: ['core/bold', 'core/italic', 'core/link'] })",
        content, flags=re.MULTILINE | re.DOTALL
    )
    
    return content

def update_repeater_previews(content):
    """Update repeater item previews to use RichText.Content"""
    
    # Problem descriptions
    content = re.sub(
        r"createElement\('p', \{ className: 'sppm-problem-desc' \}, \s*problem\.description \|\| 'Problem description'\s*\)",
        "createElement(RichText.Content, { tagName: 'p', className: 'sppm-problem-desc', value: problem.description || 'Problem description' })",
        content, flags=re.MULTILINE | re.DOTALL
    )
    
    # Feature descriptions
    content = re.sub(
        r"createElement\('p', \{ className: 'sppm-feature-desc' \}, \s*feature\.description \|\| 'Feature description'\s*\)",
        "createElement(RichText.Content, { tagName: 'p', className: 'sppm-feature-desc', value: feature.description || 'Feature description' })",
        content, flags=re.MULTILINE | re.DOTALL
    )
    
    # Step descriptions
    content = re.sub(
        r"createElement\('p', \{ className: 'sppm-step-desc' \}, \s*step\.description \|\| 'Step description'\s*\)",
        "createElement(RichText.Content, { tagName: 'p', className: 'sppm-step-desc', value: step.description || 'Step description' })",
        content, flags=re.MULTILINE | re.DOTALL
    )
    
    # Bonus descriptions
    content = re.sub(
        r"createElement\('p', \{ className: 'sppm-bonus-desc' \}, \s*bonus\.description \|\| 'Bonus description'\s*\)",
        "createElement(RichText.Content, { tagName: 'p', className: 'sppm-bonus-desc', value: bonus.description || 'Bonus description' })",
        content, flags=re.MULTILINE | re.DOTALL
    )
    
    # Benefit descriptions
    content = re.sub(
        r"createElement\('p', \{ className: 'sppm-benefit-desc' \}, \s*benefit\.description \|\| 'Benefit description'\s*\)",
        "createElement(RichText.Content, { tagName: 'p', className: 'sppm-benefit-desc', value: benefit.description || 'Benefit description' })",
        content, flags=re.MULTILINE | re.DOTALL
    )
    
    return content

def main():
    # Read the current blocks.js file
    with open('plugins/swrice-gutenberg-page-builder/assets/js/blocks.js', 'r') as f:
        content = f.read()
    
    print("Updating RepeaterField component...")
    content = update_repeater_field_textarea(content)
    
    print("Updating main text fields...")
    content = update_main_text_fields(content)
    
    print("Updating section descriptions...")
    content = update_section_descriptions(content)
    
    print("Updating repeater previews...")
    content = update_repeater_previews(content)
    
    # Write the updated content back
    with open('plugins/swrice-gutenberg-page-builder/assets/js/blocks.js', 'w') as f:
        f.write(content)
    
    print("✅ JavaScript updates completed!")

if __name__ == "__main__":
    main()

