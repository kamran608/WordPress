/**
 * Yoast SEO Integration for Swrice Gutenberg Page Builder
 * 
 * This plugin ensures that Yoast SEO can analyze content from all
 * Swrice Gutenberg blocks for proper SEO analysis and word count.
 */

/* global YoastSEO, wp, jQuery */

class SwriceYoastSEOIntegration {
    constructor() {
        // Ensure YoastSEO.js is present and can access the necessary features
        if (typeof YoastSEO === "undefined" || 
            typeof YoastSEO.analysis === "undefined" || 
            typeof YoastSEO.analysis.worker === "undefined") {
            return;
        }

        // Register this plugin with Yoast SEO
        YoastSEO.app.registerPlugin("SwriceYoastSEOIntegration", { status: "ready" });
        
        // Register our content modification
        this.registerModifications();
    }

    /**
     * Registers the addContent modification.
     * 
     * @returns {void}
     */
    registerModifications() {
        const callback = this.addContent.bind(this);
        
        // Register content modification with Yoast SEO
        YoastSEO.app.registerModification("content", callback, "SwriceYoastSEOIntegration", 10);
    }

    /**
     * Adds content from Swrice blocks to be analyzed by Yoast SEO.
     * 
     * @param {string} data The current data string.
     * @returns {string} The data string parameter with the added content.
     */
    addContent(data) {
        // Get all Swrice blocks content
        const swriceContent = this.extractSwriceBlocksContent();
        
        if (swriceContent) {
            data += " " + swriceContent;
        }
        
        return data;
    }

    /**
     * Extracts text content from all Swrice Gutenberg blocks on the page.
     * 
     * @returns {string} Combined text content from all Swrice blocks.
     */
    extractSwriceBlocksContent() {
        let content = "";
        
        // Get all Swrice block elements from the page
        const swriceBlocks = document.querySelectorAll('[class*="sppm-"]');
        
        swriceBlocks.forEach(block => {
            // Extract text content, excluding script and style elements
            const textContent = this.getTextContent(block);
            if (textContent.trim()) {
                content += " " + textContent.trim();
            }
        });

        // Also try to get content from Gutenberg editor if we're in the editor
        if (typeof wp !== "undefined" && wp.data && wp.data.select) {
            const editorContent = this.getGutenbergEditorContent();
            if (editorContent) {
                content += " " + editorContent;
            }
        }
        
        return content.trim();
    }

    /**
     * Gets text content from an element, excluding script and style tags.
     * 
     * @param {Element} element The DOM element to extract text from.
     * @returns {string} The extracted text content.
     */
    getTextContent(element) {
        // Clone the element to avoid modifying the original
        const clone = element.cloneNode(true);
        
        // Remove script and style elements
        const scriptsAndStyles = clone.querySelectorAll('script, style');
        scriptsAndStyles.forEach(el => el.remove());
        
        // Get text content and clean it up
        let text = clone.textContent || clone.innerText || '';
        
        // Clean up whitespace
        text = text.replace(/\s+/g, ' ').trim();
        
        return text;
    }

    /**
     * Gets content from Gutenberg editor blocks (when in editor mode).
     * 
     * @returns {string} Content from Swrice blocks in the editor.
     */
    getGutenbergEditorContent() {
        try {
            const blocks = wp.data.select('core/block-editor').getBlocks();
            let content = "";
            
            blocks.forEach(block => {
                if (block.name && block.name.startsWith('swrice/')) {
                    content += " " + this.extractBlockAttributes(block);
                }
            });
            
            return content.trim();
        } catch (error) {
            // Silently fail if we can't access Gutenberg data
            return "";
        }
    }

    /**
     * Extracts text content from block attributes.
     * 
     * @param {Object} block The Gutenberg block object.
     * @returns {string} Extracted text content from block attributes.
     */
    extractBlockAttributes(block) {
        let content = "";
        
        if (block.attributes) {
            // Extract text from common text attributes
            const textAttributes = [
                'pluginName', 'heroSubtitle', 'problemHeading', 'solutionHeading',
                'featuresHeading', 'faqHeading', 'howItWorksHeading', 'testimonialsHeading',
                'bonusesHeading', 'guaranteeHeading', 'whyChooseHeading', 'aboutHeading',
                'finalCtaHeading', 'screenshotsHeading', 'videoTutorialHeading',
                'versionChangelogHeading', 'content', 'description', 'text'
            ];
            
            textAttributes.forEach(attr => {
                if (block.attributes[attr] && typeof block.attributes[attr] === 'string') {
                    content += " " + block.attributes[attr];
                }
            });
            
            // Extract text from array attributes (like problem items, features, etc.)
            const arrayAttributes = [
                'problemItems', 'features', 'faqItems', 'howItWorksSteps',
                'testimonials', 'bonuses', 'whyChooseItems', 'screenshots'
            ];
            
            arrayAttributes.forEach(attr => {
                if (block.attributes[attr] && Array.isArray(block.attributes[attr])) {
                    block.attributes[attr].forEach(item => {
                        if (typeof item === 'string') {
                            content += " " + item;
                        } else if (typeof item === 'object') {
                            // Extract text from object properties
                            Object.values(item).forEach(value => {
                                if (typeof value === 'string') {
                                    content += " " + value;
                                }
                            });
                        }
                    });
                }
            });
        }
        
        return content.trim();
    }
}

/**
 * Initialize the Yoast SEO integration when ready.
 */
function initializeSwriceYoastIntegration() {
    new SwriceYoastSEOIntegration();
}

// Load the plugin when Yoast SEO is ready
if (typeof YoastSEO !== "undefined" && typeof YoastSEO.app !== "undefined") {
    initializeSwriceYoastIntegration();
} else {
    // Wait for Yoast SEO to be ready
    if (typeof jQuery !== "undefined") {
        jQuery(window).on("YoastSEO:ready", initializeSwriceYoastIntegration);
    } else {
        // Fallback for when jQuery is not available
        window.addEventListener('load', function() {
            setTimeout(initializeSwriceYoastIntegration, 1000);
        });
    }
}
