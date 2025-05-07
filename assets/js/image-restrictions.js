jQuery(document).ready(function($) {
        console.log('Vendor image validation script loaded');

        function validateImage(file) {
            return new Promise((resolve, reject) => {
                <?php if ($has_size_restrictions): ?>
                if (file.size > <?php echo $max_size; ?>) {
                    reject('File size must be less than <?php echo size_format($max_size); ?>');
                    return;
                }
                <?php endif; ?>

                <?php if ($has_dimension_restrictions): ?>
                const img = new Image();
                img.src = URL.createObjectURL(file);
                
                img.onload = function() {
                    URL.revokeObjectURL(this.src);
                    if (this.width !== <?php echo $required_width; ?> || this.height !== <?php echo $required_height; ?>) {
                        reject(`Image must be exactly <?php echo $required_width; ?>x<?php echo $required_height; ?> pixels. Your image is ${this.width}x${this.height} pixels.`);
                        return;
                    }
                    resolve(true);
                };

                img.onerror = function() {
                    URL.revokeObjectURL(this.src);
                    reject('Invalid image file');
                };
                <?php else: ?>
                resolve(true);
                <?php endif; ?>
            });
        }

        // Handle file input change for product gallery
        $('.dokan-product-gallery').on('change', 'input[type="file"]', function(e) {
            console.log('File input changed');
            const file = this.files[0];
            if (!file) return;

            validateImage(file).catch(error => {
                alert(error);
                this.value = '';
            });
        });

        // Handle file input change for featured image
        $('#_product_image').on('change', function(e) {
            console.log('Featured image input changed');
            const file = this.files[0];
            if (!file) return;

            validateImage(file).catch(error => {
                alert(error);
                this.value = '';
            });
        });
    });