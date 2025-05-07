<?php
namespace DokanKits\Core;

use DokanKits\Controllers\ImageRestrictionsController;

/**
 * Image Restriction Service Provider
 *
 * @package DokanKits\Core
 */
class ImageRestrictionServiceProvider implements ServiceProvider {
    /**
     * Register services
     *
     * @param Container $container
     *
     * @return void
     */
    public function register(Container $container) {
        // Register the controller
        $container->set('image_restrictions.controller', function($container) {
            return new ImageRestrictionsController();
        });
    }

    /**
     * Boot services
     *
     * @param Container $container
     *
     * @return void
     */
    public function boot(Container $container) {
        // Initialize the controller
        $container->get('image_restrictions.controller');
    }
}