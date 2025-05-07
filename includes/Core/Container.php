<?php
namespace DokanKits\Core;

/**
 * Container class
 * 
 * A simple dependency injection container
 *
 * @package DokanKits\Core
 */
class Container {
    /**
     * Container items
     *
     * @var array
     */
    private $items = [];

    /**
     * Container singletons
     *
     * @var array
     */
    private $singletons = [];

    /**
     * Get an item from the container
     *
     * @param string $id Item ID
     *
     * @return mixed
     */
    public function get( $id ) {
        if ( isset( $this->singletons[ $id ] ) ) {
            return $this->singletons[ $id ];
        }

        if ( isset( $this->items[ $id ] ) ) {
            $item = $this->items[ $id ];

            if ( is_callable( $item ) ) {
                // If the item is a factory, call it
                $singleton = $item( $this );
                $this->singletons[ $id ] = $singleton;
                return $singleton;
            }

            return $item;
        }

        // If the class exists, try to instantiate it
        $class_name = '\\DokanKits\\' . $id;
        if ( class_exists( $class_name ) ) {
            $reflection = new \ReflectionClass( $class_name );
            
            if ( ! $reflection->isInstantiable() ) {
                throw new \Exception( "Class {$class_name} is not instantiable" );
            }

            $constructor = $reflection->getConstructor();
            
            if ( ! $constructor || $constructor->getNumberOfRequiredParameters() === 0 ) {
                // If no constructor or no required parameters, just instantiate
                $instance = new $class_name();
            } else {
                // Prepare parameters for the constructor
                $parameters = [];
                foreach ( $constructor->getParameters() as $param ) {
                    if ( $param->getClass() ) {
                        // If parameter is a class, try to resolve it
                        $parameter_class = $param->getClass()->getName();
                        
                        // Get the class shortname to resolve from container
                        $parts = explode( '\\', $parameter_class );
                        $shortname = end( $parts );
                        
                        if ( $this->has( $shortname ) ) {
                            $parameters[] = $this->get( $shortname );
                        } else {
                            // If parameter is optional, use default value
                            if ( $param->isOptional() ) {
                                $parameters[] = $param->getDefaultValue();
                            } else {
                                throw new \Exception( "Cannot resolve parameter {$param->getName()} for {$class_name}" );
                            }
                        }
                    } else {
                        // If parameter is not a class, use default value if available
                        if ( $param->isOptional() ) {
                            $parameters[] = $param->getDefaultValue();
                        } else {
                            throw new \Exception( "Cannot resolve parameter {$param->getName()} for {$class_name}" );
                        }
                    }
                }

                // Instantiate with parameters
                $instance = $reflection->newInstanceArgs( $parameters );
            }

            $this->singletons[ $id ] = $instance;
            return $instance;
        }

        throw new \Exception( "Container item '{$id}' not found" );
    }

    /**
     * Check if an item exists in the container
     *
     * @param string $id Item ID
     *
     * @return bool
     */
    public function has( $id ) {
        return isset( $this->items[ $id ] ) || isset( $this->singletons[ $id ] );
    }

    /**
     * Set an item in the container
     *
     * @param string $id   Item ID
     * @param mixed  $item Item value
     *
     * @return self
     */
    public function set( $id, $item ) {
        if ( isset( $this->singletons[ $id ] ) ) {
            unset( $this->singletons[ $id ] );
        }

        $this->items[ $id ] = $item;

        return $this;
    }

    /**
     * Set a singleton in the container
     *
     * @param string $id        Item ID
     * @param mixed  $singleton Singleton instance
     *
     * @return self
     */
    public function singleton( $id, $singleton ) {
        if ( isset( $this->items[ $id ] ) ) {
            unset( $this->items[ $id ] );
        }

        $this->singletons[ $id ] = $singleton;

        return $this;
    }

    /**
     * Register a factory for an item
     *
     * @param string   $id      Item ID
     * @param callable $factory Factory function
     *
     * @return self
     */
    public function factory( $id, callable $factory ) {
        if ( isset( $this->singletons[ $id ] ) ) {
            unset( $this->singletons[ $id ] );
        }

        $this->items[ $id ] = $factory;

        return $this;
    }

    /**
     * Remove an item from the container
     *
     * @param string $id Item ID
     *
     * @return self
     */
    public function remove( $id ) {
        unset( $this->items[ $id ] );
        unset( $this->singletons[ $id ] );

        return $this;
    }
}