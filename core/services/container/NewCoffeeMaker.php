<?php
namespace EventEspresso\core\services\container;

if ( ! defined( 'EVENT_ESPRESSO_VERSION' ) ) {
	exit( 'No direct script access allowed' );
}



/**
 * Class NewCoffeeMaker
 * This is the default CoffeeMaker and will return a
 * newly instantiated object every time one is requested.
 * After performing all of the logic for determining how
 * to build an object and resolve it's dependencies,
 * the results are placed in a Closure and stored in the
 * "reservoir" Collection so that the next time the class
 * is required, we simply call the Closure.
 *
 * @package       Event Espresso
 * @author        Brent Christensen
 * @since         4.9.1
 */
class NewCoffeeMaker extends CoffeeMaker
{


	/**
	 * @return string
	 */
	public function type() {
		return CoffeeMaker::BREW_NEW;
	}



	/**
	 * @param RecipeInterface    $recipe
	 * @param array     $arguments
	 * @return mixed
	 */
	public function brew( RecipeInterface $recipe, $arguments = array() )
	{
		$this->resolveClassAndFilepath( $recipe );
		$reflector = $this->injector()->getReflectionClass( $recipe->fqcn() );
		if ( $reflector->getConstructor() === null ) {
			$closure = function() use ( $reflector ) {
				return $reflector->newInstance();
			};
		} else if ( $reflector->isInstantiable() ) {
			$injector = $this->injector();
			$closure = function ( $arguments ) use ( $reflector, $injector ) {
				return $reflector->newInstanceArgs(
					$injector->resolveDependencies( $reflector, $arguments )
				);
			};
		} else if ( method_exists( $reflector->getName(), 'instance' ) ) {
			$injector = $this->injector();
			$closure = function ( $arguments ) use ( $reflector, $injector ) {
				return call_user_func_array(
					array( $reflector->getName(), 'instance' ),
					$injector->resolveDependencies( $reflector, $arguments )
				);
			};
		} else if ( method_exists( $reflector->getName(), 'new_instance' ) ) {
			$injector = $this->injector();
			$closure = function ( $arguments ) use ( $reflector, $injector ) {
				return call_user_func_array(
					array( $reflector->getName(), 'new_instance' ),
					$injector->resolveDependencies( $reflector, $arguments )
				);
			};
		} else {
			$injector = $this->injector();
			$closure = function ( $arguments ) use ( $reflector, $injector ) {
				return $reflector->newInstanceArgs(
					$injector->resolveDependencies( $reflector, $arguments )
				);
			};
		}
		return $this->coffeePot()->addClosure( $recipe->identifier(), $closure );
	}


}
// End of file NewCoffeeMaker.php
// Location: /NewCoffeeMaker.php