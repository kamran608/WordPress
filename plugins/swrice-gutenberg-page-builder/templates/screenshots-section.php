<?php
/**
 * Screenshots Section Template - Themed Design
 */

if (!defined('ABSPATH')) exit;

$screenshots_heading = isset($attributes['screenshotsHeading']) ? $attributes['screenshotsHeading'] : 'Screenshots';
$screenshots_icon = isset($attributes['screenshotsIcon']) ? $attributes['screenshotsIcon'] : '📸';
$screenshots_description = isset($attributes['screenshotsDescription']) ? $attributes['screenshotsDescription'] : '';
$screenshot_items = isset($attributes['screenshotItems']) ? $attributes['screenshotItems'] : array();

if (empty($screenshot_items) || !is_array($screenshot_items)) return;
?>

<section class="sppm-section sppm-screenshots-section">
    <div class="sppm-section-header">
        <h2 class="sppm-section-title">
            <?php if ($screenshots_icon): ?><span class="sppm-section-icon"><?php echo $screenshots_icon; ?></span><?php endif; ?>
            <?php echo esc_html($screenshots_heading); ?>
        </h2>
        <?php if ($screenshots_description): ?>
        <p class="sppm-section-description"><?php echo esc_html($screenshots_description); ?></p>
        <?php endif; ?>
    </div>
    
    <div class="sppm-screenshots-container">
        <!-- Simple Slider -->
        <div class="sppm-screenshots-slider">
            <div class="sppm-slides-wrapper">
                <?php if (is_array($screenshot_items) && !empty($screenshot_items)): ?>
                    <?php foreach ($screenshot_items as $index => $screenshot): ?>
                    <div class="sppm-screenshot-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                        <?php if (!empty($screenshot['imageUrl'])): ?>
                        <img src="<?php echo esc_url($screenshot['imageUrl']); ?>" 
                             alt="<?php echo esc_attr($screenshot['title']); ?>"
                             class="sppm-screenshot-image">
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <!-- Simple Arrows -->
            <button class="sppm-arrow sppm-arrow-left" onclick="screenshotsSlider.prev()">‹</button>
            <button class="sppm-arrow sppm-arrow-right" onclick="screenshotsSlider.next()">›</button>
        </div>
        
        <!-- Simple Dots -->
        <div class="sppm-dots">
            <?php if (is_array($screenshot_items) && !empty($screenshot_items)): ?>
                <?php foreach ($screenshot_items as $index => $screenshot): ?>
                <button class="sppm-dot <?php echo $index === 0 ? 'active' : ''; ?>" 
                        onclick="screenshotsSlider.goTo(<?php echo $index; ?>)"></button>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
// Simple Screenshots Slider
const screenshotsSlider = {
    currentSlide: 0,
    totalSlides: <?php echo count($screenshot_items); ?>,
    
    init() {
        if (this.totalSlides <= 1) return;
        this.updateSlider();
    },
    
    next() {
        this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
        this.updateSlider();
    },
    
    prev() {
        this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
        this.updateSlider();
    },
    
    goTo(index) {
        this.currentSlide = index;
        this.updateSlider();
    },
    
    updateSlider() {
        // Update slides
        document.querySelectorAll('.sppm-screenshot-slide').forEach((slide, index) => {
            slide.classList.toggle('active', index === this.currentSlide);
        });
        
        // Update dots
        document.querySelectorAll('.sppm-dot').forEach((dot, index) => {
            dot.classList.toggle('active', index === this.currentSlide);
        });
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    screenshotsSlider.init();
});
</script>

<style>
/* Clean Modern Screenshots Slider */
.sppm-screenshots-container {
    max-width: 900px;
    margin: 0 auto;
}

.sppm-screenshots-slider {
    position: relative;
    background: transparent;
    border-radius: 0;
    overflow: visible;
    margin-bottom: 20px;
}

.sppm-slides-wrapper {
    position: relative;
    min-height: 400px;
    background: transparent;
    display: flex;
    align-items: center;
    justify-content: center;
}

.sppm-screenshot-slide {
    display: none;
    text-align: center;
    padding: 0;
    width: 100%;
}

.sppm-screenshot-slide.active {
    display: block;
}

.sppm-screenshot-image {
    width: 100%;
    height: auto;
    max-height: 400px;
    object-fit: contain;
    border-radius: 12px;
    background: transparent;
    padding: 0;
}

/* Large Centered Arrows */
.sppm-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.9);
    color: #666;
    border: none;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    font-size: 24px;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.sppm-arrow-left {
    left: -30px;
}

.sppm-arrow-right {
    right: -30px;
}

.sppm-arrow:hover {
    background: rgba(255, 255, 255, 1);
    color: #333;
    transform: translateY(-50%) scale(1.05);
}

/* Small Clean Dots */
.sppm-dots {
    display: flex;
    gap: 6px;
    justify-content: center;
    align-items: center;
    padding: 15px 0;
    background: transparent;
}

.sppm-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    border: none;
    background: rgba(0, 0, 0, 0.2);
    cursor: pointer;
    transition: all 0.3s ease;
}

.sppm-dot.active {
    background: #5fa0d8;
    transform: scale(1.2);
}

.sppm-dot:hover {
    background: #4a8bbd;
}

/* Responsive */
@media (max-width: 768px) {
    .sppm-screenshots-container {
        max-width: 100%;
        padding: 0 20px;
    }
    
    .sppm-slides-wrapper {
        min-height: 300px;
    }
    
    .sppm-screenshot-image {
        max-height: 300px;
    }
    
    .sppm-arrow {
        width: 50px;
        height: 50px;
        font-size: 20px;
    }
    
    .sppm-arrow-left {
        left: -25px;
    }
    
    .sppm-arrow-right {
        right: -25px;
    }
}

@media (max-width: 480px) {
    .sppm-slides-wrapper {
        min-height: 250px;
    }
    
    .sppm-screenshot-image {
        max-height: 250px;
    }
    
    .sppm-arrow {
        width: 45px;
        height: 45px;
        font-size: 18px;
    }
    
    .sppm-arrow-left {
        left: -22px;
    }
    
    .sppm-arrow-right {
        right: -22px;
    }
    
    .sppm-dot {
        width: 7px;
        height: 7px;
    }
}
</style>
