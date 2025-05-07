<?php
namespace DokanKits\Core;

/**
 * Service Provider interface
 *
 * @package DokanKits\Core
 */
interface ServiceProvider {
    /**
     * Register services to the container
     *
     * @param Container $container Container instance
     *
     * @return void
     */
    public function register( Container $container );
}