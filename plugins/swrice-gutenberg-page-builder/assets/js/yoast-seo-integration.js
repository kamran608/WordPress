/**
 * Enhanced Yoast SEO Integration for Swrice Gutenberg Page Builder
 * 
 * This plugin ensures comprehensive Yoast SEO analysis for all three sections:
 * - SEO Analysis (keywords, meta descriptions, etc.)
 * - Readability Analysis (sentence structure, paragraph length, etc.)
 * - Inclusive Language Analysis (terminology suggestions)
 * 
 * Supports real-time content analysis in both editor and frontend environments.
 */

/* global YoastSEO, wp, jQuery */

class SwriceYoastSEOIntegration {
    constructor() {
        // Ensure YoastSEO.js is present and can access the necessary features
        if (typeof YoastSEO === "undefined") {
            console.log('Swrice SEO Integration: YoastSEO not available');
            return;
        }

        // Initialize integration state
        this.isRegistered = false;
        this.lastContent = '';
        this.contentCache = new Map();
        
        // Register this plugin with Yoast SEO
        this.registerPlugin();
        
        // Register our content modifications
        this.registerModifications();
        
        // Set up real-time content monitoring
        this.setupContentMonitoring();
        
        console.log('Swrice SEO Integration: Successfully initialized');
    }
    
    /**
     * Register the plugin with Yoast SEO
     */
    registerPlugin() {
        try {
            if (typeof YoastSEO.app !== "undefined" && typeof YoastSEO.app.registerPlugin === "function") {
                YoastSEO.app.registerPlugin("SwriceYoastSEOIntegration", { status: "ready" });
                this.isRegistered = true;
                console.log('Swrice SEO Integration: Plugin registered successfully');
            }
        } catch (error) {
            console.warn('Swrice SEO Integration: Plugin registration failed', error);
        }
    }

    /**
     * Registers content modifications for all three Yoast SEO analysis types.
     * 
     * @returns {void}
     */
    registerModifications() {
        if (!this.isRegistered || typeof YoastSEO.app === "undefined" || typeof YoastSEO.app.registerModification !== "function") {
            console.warn('Swrice SEO Integration: Cannot register modifications - app not ready');
            return;
        }
        
        try {
            // Register single content modification that handles all analysis types
            YoastSEO.app.registerModification("content", this.addContent.bind(this), "SwriceYoastSEOIntegration", 10);
            
            // Register data modification for highlighting support
            if (typeof YoastSEO.app.registerModification === "function") {
                YoastSEO.app.registerModification("data", this.addDataForHighlighting.bind(this), "SwriceYoastSEOIntegration", 10);
            }
            
            console.log('Swrice SEO Integration: Content modifications registered successfully');
        } catch (error) {
            console.warn('Swrice SEO Integration: Failed to register modifications', error);
        }
    }
    
    /**
     * Set up real-time content monitoring for editor changes
     */
    setupContentMonitoring() {
        // Monitor Gutenberg editor changes
        if (typeof wp !== "undefined" && wp.data && wp.data.subscribe) {
            let previousBlocks = [];
            
            wp.data.subscribe(() => {
                if (wp.data.select('core/block-editor')) {
                    const currentBlocks = wp.data.select('core/block-editor').getBlocks();
                    
                    // Check if Swrice blocks have changed
                    if (this.hasSwriceBlocksChanged(previousBlocks, currentBlocks)) {
                        // Clear cache to force fresh content extraction
                        this.contentCache.clear();
                        
                        // Trigger Yoast SEO re-analysis
                        this.triggerYoastReanalysis();
                        
                        previousBlocks = JSON.parse(JSON.stringify(currentBlocks));
                    }
                }
            });
        }
        
        // Monitor DOM changes for frontend
        if (typeof MutationObserver !== "undefined") {
            const observer = new MutationObserver((mutations) => {
                let hasSwriceChanges = false;
                
                mutations.forEach((mutation) => {
                    if (mutation.type === 'childList') {
                        mutation.addedNodes.forEach((node) => {
                            if (node.nodeType === 1 && (node.className && node.className.includes('sppm-'))) {
                                hasSwriceChanges = true;
                            }
                        });
                    }
                });
                
                if (hasSwriceChanges) {
                    this.contentCache.clear();
                    this.triggerYoastReanalysis();
                }
            });
            
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }
    }
    
    /**
     * Check if Swrice blocks have changed between two block arrays
     */
    hasSwriceBlocksChanged(previousBlocks, currentBlocks) {
        const prevSwriceBlocks = this.filterSwriceBlocks(previousBlocks);
        const currSwriceBlocks = this.filterSwriceBlocks(currentBlocks);
        
        return JSON.stringify(prevSwriceBlocks) !== JSON.stringify(currSwriceBlocks);
    }
    
    /**
     * Filter only Swrice blocks from a blocks array
     */
    filterSwriceBlocks(blocks) {
        return blocks.filter(block => block.name && block.name.startsWith('swrice/'));
    }
    
    /**
     * Trigger Yoast SEO re-analysis
     */
    triggerYoastReanalysis() {
        if (typeof YoastSEO !== "undefined" && YoastSEO.app && typeof YoastSEO.app.refresh === "function") {
            setTimeout(() => {
                YoastSEO.app.refresh();
            }, 100);
        }
    }

    /**
     * Adds content from Swrice blocks for all analysis types.
     * 
     * @param {string} data The current data string.
     * @returns {string} The data string parameter with the added content.
     */
    addContent(data) {
        const cacheKey = 'unified_content';
        
        // Check cache first
        if (this.contentCache.has(cacheKey)) {
            const cachedContent = this.contentCache.get(cacheKey);
            return data + " " + cachedContent;
        }
        
        // Get all Swrice blocks content with proper structure for readability
        const swriceContent = this.extractSwriceBlocksContentForReadability();
        
        if (swriceContent) {
            this.contentCache.set(cacheKey, swriceContent);
            data += " " + swriceContent;
        }
        
        return data;
    }
    
    /**
     * Adds data modification for highlighting support.
     * This ensures Yoast can map content back to DOM elements for highlighting.
     * 
     * @param {Object} data The current data object.
     * @returns {Object} The data object with Swrice content mapping.
     */
    addDataForHighlighting(data) {
        try {
            // Add Swrice block elements to the data for highlighting
            const swriceElements = document.querySelectorAll('[class*="sppm-"]');
            
            if (swriceElements.length > 0) {
                // Create a mapping of Swrice content to DOM elements
                const swriceMapping = [];
                
                swriceElements.forEach((element, index) => {
                    const textContent = this.getTextContentForHighlighting(element);
                    if (textContent.trim()) {
                        swriceMapping.push({
                            element: element,
                            content: textContent,
                            id: `swrice-block-${index}`
                        });
                    }
                });
                
                // Add to data object for Yoast highlighting
                if (!data.swriceBlocks) {
                    data.swriceBlocks = swriceMapping;
                }
            }
        } catch (error) {
            console.warn('Swrice SEO Integration: Failed to add highlighting data', error);
        }
        
        return data;
    }

    /**
     * Extracts text content from all Swrice Gutenberg blocks optimized for readability analysis.
     * This method preserves paragraph structure and heading hierarchy.
     * 
     * @returns {string} Combined text content from all Swrice blocks with proper structure.
     */
    extractSwriceBlocksContentForReadability() {
        let content = "";
        
        // Get all Swrice block elements from the page
        const swriceBlocks = document.querySelectorAll('[class*="sppm-"]');
        
        swriceBlocks.forEach(block => {
            // Extract structured text content for readability
            const textContent = this.getStructuredTextFromElement(block);
            if (textContent.trim()) {
                content += "\n\n" + textContent.trim() + "\n\n";
            }
        });

        // Also try to get content from Gutenberg editor if we're in the editor
        if (typeof wp !== "undefined" && wp.data && wp.data.select) {
            const editorContent = this.getGutenbergEditorContentForReadability();
            if (editorContent) {
                content += "\n\n" + editorContent + "\n\n";
            }
        }
        
        return content.trim();
    }

    /**
     * Get text content optimized for highlighting functionality
     */
    getTextContentForHighlighting(element) {
        // Clone the element to avoid modifying the original
        const clone = element.cloneNode(true);
        
        // Remove script and style elements
        const scriptsAndStyles = clone.querySelectorAll('script, style');
        scriptsAndStyles.forEach(el => el.remove());
        
        // Get clean text content
        let text = clone.textContent || clone.innerText || '';
        
        // Clean up whitespace but preserve basic structure
        text = text.replace(/\s+/g, ' ').trim();
        
        return text;
    }

    /**
     * Extract structured text from an element optimized for readability analysis.
     * This method preserves semantic structure and proper spacing.
     * 
     * @param {Element} element The DOM element to extract structured text from
     * @returns {string} The extracted structured text
     */
    getStructuredTextFromElement(element) {
        // Clone the element to avoid modifying the original
        const clone = element.cloneNode(true);
        
        // Remove script and style elements
        const scriptsAndStyles = clone.querySelectorAll('script, style');
        scriptsAndStyles.forEach(el => el.remove());
        
        let structuredText = '';
        
        // Process different content types in order of importance for readability
        
        // 1. Extract headings with proper hierarchy
        const headings = clone.querySelectorAll('h1, h2, h3, h4, h5, h6, .sppm-heading, .sppm-title');
        headings.forEach(heading => {
            const headingText = (heading.textContent || heading.innerText || '').trim();
            if (headingText) {
                structuredText += headingText + '\n\n';
            }
        });
        
        // 2. Extract paragraphs and descriptions
        const paragraphs = clone.querySelectorAll('p, .sppm-description, .sppm-content, .sppm-text');
        paragraphs.forEach(paragraph => {
            const paragraphText = (paragraph.textContent || paragraph.innerText || '').trim();
            if (paragraphText && !this.isHeadingText(paragraphText, headings)) {
                // Split long paragraphs into sentences for better readability analysis
                const sentences = this.splitIntoSentences(paragraphText);
                sentences.forEach(sentence => {
                    if (sentence.trim()) {
                        structuredText += sentence.trim() + ' ';
                    }
                });
                structuredText += '\n\n';
            }
        });
        
        // 3. Extract list items
        const lists = clone.querySelectorAll('ul, ol, .sppm-list');
        lists.forEach(list => {
            const listItems = list.querySelectorAll('li, .sppm-list-item');
            listItems.forEach(item => {
                const itemText = (item.textContent || item.innerText || '').trim();
                if (itemText) {
                    structuredText += itemText + '. ';
                }
            });
            if (listItems.length > 0) {
                structuredText += '\n\n';
            }
        });
        
        // 4. Extract any remaining text content not captured above
        const remainingText = clone.textContent || clone.innerText || '';
        const cleanRemainingText = remainingText.replace(/\s+/g, ' ').trim();
        
        // Only add remaining text if it's not already included
        if (cleanRemainingText && !structuredText.includes(cleanRemainingText.substring(0, 50))) {
            structuredText += cleanRemainingText + '\n\n';
        }
        
        return structuredText.trim();
    }
    
    /**
     * Check if text is already captured as a heading
     */
    isHeadingText(text, headings) {
        const cleanText = text.trim().toLowerCase();
        for (let heading of headings) {
            const headingText = (heading.textContent || heading.innerText || '').trim().toLowerCase();
            if (headingText && cleanText.includes(headingText)) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Split text into sentences for better readability analysis
     */
    splitIntoSentences(text) {
        // Simple sentence splitting - can be enhanced for better accuracy
        return text.split(/[.!?]+/).filter(sentence => sentence.trim().length > 0);
    }

    /**
     * Gets content from Gutenberg editor blocks optimized for readability analysis.
     * 
     * @returns {string} Content from Swrice blocks in the editor with proper structure.
     */
    getGutenbergEditorContentForReadability() {
        try {
            const blocks = wp.data.select('core/block-editor').getBlocks();
            let content = "";
            
            blocks.forEach(block => {
                if (block.name && block.name.startsWith('swrice/')) {
                    const blockContent = this.extractBlockAttributesForReadability(block);
                    if (blockContent.trim()) {
                        content += "\n\n" + blockContent.trim() + "\n\n";
                    }
                }
            });
            
            return content.trim();
        } catch (error) {
            console.warn('Swrice SEO Integration: Failed to get Gutenberg editor content for readability', error);
            return "";
        }
    }

    /**
     * Extracts text content from block attributes optimized for readability analysis.
     * 
     * @param {Object} block The Gutenberg block object.
     * @returns {string} Extracted text content with proper structure for readability.
     */
    extractBlockAttributesForReadability(block) {
        let content = "";
        
        if (block.attributes) {
            // Extract headings first with proper spacing
            const headingAttributes = [
                'pluginName', 'heroSubtitle', 'problemHeading', 'solutionHeading',
                'featuresHeading', 'faqHeading', 'howItWorksHeading', 'testimonialsHeading',
                'bonusesHeading', 'guaranteeHeading', 'whyChooseHeading', 'aboutHeading',
                'finalCtaHeading', 'screenshotsHeading', 'videoTutorialHeading',
                'versionChangelogHeading'
            ];
            
            headingAttributes.forEach(attr => {
                if (block.attributes[attr] && typeof block.attributes[attr] === 'string') {
                    const headingText = block.attributes[attr].trim();
                    if (headingText) {
                        content += headingText + '\n\n';
                    }
                }
            });
            
            // Extract descriptions and content with paragraph structure
            const contentAttributes = [
                'content', 'description', 'text', 'ctaTitle',
                'aboutDescription', 'guaranteeDescription', 'solutionDescription'
            ];
            
            contentAttributes.forEach(attr => {
                if (block.attributes[attr] && typeof block.attributes[attr] === 'string') {
                    const contentText = block.attributes[attr].trim();
                    if (contentText) {
                        // Split into sentences for better readability analysis
                        const sentences = this.splitIntoSentences(contentText);
                        sentences.forEach(sentence => {
                            if (sentence.trim()) {
                                content += sentence.trim() + '. ';
                            }
                        });
                        content += '\n\n';
                    }
                }
            });
            
            // Extract array attributes with proper structure
            const arrayAttributes = [
                'problemItems', 'features', 'faqItems', 'howItWorksSteps',
                'testimonials', 'bonuses', 'whyChooseItems', 'screenshots'
            ];
            
            arrayAttributes.forEach(attr => {
                if (block.attributes[attr] && Array.isArray(block.attributes[attr])) {
                    block.attributes[attr].forEach(item => {
                        if (typeof item === 'string') {
                            const itemText = item.trim();
                            if (itemText) {
                                content += itemText + '. ';
                            }
                        } else if (typeof item === 'object') {
                            // Extract text from object properties
                            Object.values(item).forEach(value => {
                                if (typeof value === 'string') {
                                    const valueText = value.trim();
                                    if (valueText) {
                                        content += valueText + '. ';
                                    }
                                }
                            });
                        }
                    });
                    content += '\n\n';
                }
            });
        }
        
        return content.trim();
    }

    /**
     * Extracts text content from all Swrice Gutenberg blocks on the page.
     * 
     * @param {string} analysisType The type of analysis ('seo', 'readability', or 'inclusive')
     * @returns {string} Combined text content from all Swrice blocks.
     */
    extractSwriceBlocksContent(analysisType = 'seo') {
        let content = "";
        
        // Get all Swrice block elements from the page
        const swriceBlocks = document.querySelectorAll('[class*="sppm-"]');
        
        swriceBlocks.forEach(block => {
            // Extract text content, excluding script and style elements
            const textContent = this.getTextContent(block, analysisType);
            if (textContent.trim()) {
                content += " " + textContent.trim();
            }
        });

        // Also try to get content from Gutenberg editor if we're in the editor
        if (typeof wp !== "undefined" && wp.data && wp.data.select) {
            const editorContent = this.getGutenbergEditorContent(analysisType);
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
     * @param {string} analysisType The type of analysis ('seo', 'readability', or 'inclusive')
     * @returns {string} The extracted text content.
     */
    getTextContent(element, analysisType = 'seo') {
        // Clone the element to avoid modifying the original
        const clone = element.cloneNode(true);
        
        // Remove script and style elements
        const scriptsAndStyles = clone.querySelectorAll('script, style');
        scriptsAndStyles.forEach(el => el.remove());
        
        // Get text content based on analysis type
        let text = '';
        
        if (analysisType === 'readability') {
            // For readability analysis, preserve paragraph structure
            text = this.extractStructuredText(clone);
        } else {
            // For SEO and inclusive language analysis, get all text
            text = clone.textContent || clone.innerText || '';
        }
        
        // Clean up whitespace but preserve structure for readability
        if (analysisType === 'readability') {
            text = text.replace(/\n\s*\n/g, '\n\n').replace(/[ \t]+/g, ' ').trim();
        } else {
            text = text.replace(/\s+/g, ' ').trim();
        }
        
        return text;
    }
    
    /**
     * Extract text while preserving paragraph and heading structure for readability analysis
     * 
     * @param {Element} element The DOM element to extract structured text from
     * @returns {string} The extracted structured text
     */
    extractStructuredText(element) {
        let text = '';
        
        // Process headings and paragraphs separately to maintain structure
        const headings = element.querySelectorAll('h1, h2, h3, h4, h5, h6');
        const paragraphs = element.querySelectorAll('p, div.sppm-description, div.sppm-content');
        const lists = element.querySelectorAll('ul, ol');
        
        // Add headings with proper spacing
        headings.forEach(heading => {
            const headingText = heading.textContent || heading.innerText || '';
            if (headingText.trim()) {
                text += headingText.trim() + '\n\n';
            }
        });
        
        // Add paragraphs with proper spacing
        paragraphs.forEach(paragraph => {
            const paragraphText = paragraph.textContent || paragraph.innerText || '';
            if (paragraphText.trim()) {
                text += paragraphText.trim() + '\n\n';
            }
        });
        
        // Add list items
        lists.forEach(list => {
            const listItems = list.querySelectorAll('li');
            listItems.forEach(item => {
                const itemText = item.textContent || item.innerText || '';
                if (itemText.trim()) {
                    text += itemText.trim() + '\n';
                }
            });
            text += '\n';
        });
        
        return text;
    }

    /**
     * Gets content from Gutenberg editor blocks (when in editor mode).
     * 
     * @param {string} analysisType The type of analysis ('seo', 'readability', or 'inclusive')
     * @returns {string} Content from Swrice blocks in the editor.
     */
    getGutenbergEditorContent(analysisType = 'seo') {
        try {
            const blocks = wp.data.select('core/block-editor').getBlocks();
            let content = "";
            
            blocks.forEach(block => {
                if (block.name && block.name.startsWith('swrice/')) {
                    content += " " + this.extractBlockAttributes(block, analysisType);
                }
            });
            
            return content.trim();
        } catch (error) {
            // Silently fail if we can't access Gutenberg data
            console.warn('Swrice SEO Integration: Failed to get Gutenberg editor content', error);
            return "";
        }
    }

    /**
     * Extracts text content from block attributes.
     * 
     * @param {Object} block The Gutenberg block object.
     * @param {string} analysisType The type of analysis ('seo', 'readability', or 'inclusive')
     * @returns {string} Extracted text content from block attributes.
     */
    extractBlockAttributes(block, analysisType = 'seo') {
        let content = "";
        
        if (block.attributes) {
            // Extract text from common text attributes
            const textAttributes = [
                'pluginName', 'heroSubtitle', 'problemHeading', 'solutionHeading',
                'featuresHeading', 'faqHeading', 'howItWorksHeading', 'testimonialsHeading',
                'bonusesHeading', 'guaranteeHeading', 'whyChooseHeading', 'aboutHeading',
                'finalCtaHeading', 'screenshotsHeading', 'videoTutorialHeading',
                'versionChangelogHeading', 'content', 'description', 'text', 'ctaTitle',
                'aboutDescription', 'guaranteeDescription', 'solutionDescription'
            ];
            
            textAttributes.forEach(attr => {
                if (block.attributes[attr] && typeof block.attributes[attr] === 'string') {
                    if (analysisType === 'readability') {
                        // For readability, add proper spacing for headings and descriptions
                        if (attr.includes('Heading') || attr.includes('Description')) {
                            content += "\n\n" + block.attributes[attr] + "\n\n";
                        } else {
                            content += " " + block.attributes[attr];
                        }
                    } else {
                        content += " " + block.attributes[attr];
                    }
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
                            if (analysisType === 'readability') {
                                content += "\n" + item;
                            } else {
                                content += " " + item;
                            }
                        } else if (typeof item === 'object') {
                            // Extract text from object properties
                            Object.values(item).forEach(value => {
                                if (typeof value === 'string') {
                                    if (analysisType === 'readability') {
                                        content += "\n" + value;
                                    } else {
                                        content += " " + value;
                                    }
                                }
                            });
                        }
                    });
                    
                    if (analysisType === 'readability') {
                        content += "\n";
                    }
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
