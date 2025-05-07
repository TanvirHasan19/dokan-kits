jQuery(document).ready(function ($) {
    console.log('Vendor image validation script loaded');

    const settings = dokanKitsImageRestrictions ?? {
        "hasSizeRestrictions": false,
        "enableDimensions": false,
        "requiredWidth": 0,
        "requiredHeight": 0,
        "enableSize": false,
        "maxSize": 0,
        "messages": {
            "dimensionError": "Image dimensions must be exactly 0×0 pixels.",
            "sizeError": "File size exceeds the maximum limit of 0 MB."
        }
    };

    console.log('settings', settings)

    function validateImage(file) {
        return new Promise((resolve, reject) => {
            if (Boolean(settings.hasSizeRestrictions)) {
                if (file.size > settings.maxSize) {
                    reject(`File size must be less than ${settings.maxSize / 1024} KB.`);
                    return;
                }
            }

            if (Boolean(settings.enableDimensions)) {
                const img = new Image();
                img.src = URL.createObjectURL(file);

                img.onload = function () {
                    URL.revokeObjectURL(this.src);
                    if (this.width !== settings.requiredWidth || this.height !== settings.requiredHeight) {
                        reject(`Image must be exactly ${settings.requiredWidth}x${settings.requiredHeight} pixels. Your image is ${this.width}x${this.height} pixels.`);
                        return;
                    }
                    resolve(true);
                };

                img.onerror = function () {
                    URL.revokeObjectURL(this.src);
                    reject('Invalid image file');
                };
            } else {
                resolve(true);
            }
        });
    }

    // Handle file input change for product gallery
    $('.dokan-product-gallery').on('change', 'input[type="file"]', function (e) {
        console.log('File input changed');
        const file = this.files[0];
        if (!file) return;

        validateImage(file).catch(error => {
            alert(error);
            this.value = '';
        });
    });

    // Handle file input change for featured image
    $('#_product_image').on('change', function (e) {
        console.log('Featured image input changed');
        const file = this.files[0];
        if (!file) return;

        validateImage(file).catch(error => {
            alert(error);
            this.value = '';
        });
    });
});