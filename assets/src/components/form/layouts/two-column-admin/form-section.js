/**
 * External imports
 */
import PropTypes from 'prop-types';
import { Children } from 'react';

/**
 * Internal imports
 */
import { default as AutoFormRow } from '../../base/auto-form-row';
import { default as AutoColumnRow } from './auto-column-row';

/**
 * FormSection
 * "two-column-admin" form layout strategy component for
 * separating parts of the form into identifiable sections
 *
 * @component
 * @param {Object} children
 * @param {string} htmlId
 * @param {string} htmlClass
 * @return {Object} rendered form section
 */
const FormSection = ( { children, htmlId = '', htmlClass = '' } ) => {
	// console.log( '' );
	// console.log( 'FormSection', htmlId, htmlClass );
	htmlClass = htmlClass ?
		`${ htmlClass } ee-form-section px-0 pt-3 border rounded` :
		'ee-form-section px-0 pt-3 border rounded';
	return (
		<div id={ htmlId } className={ htmlClass }>
			<div className="px-3">
				{
					Children.map( children, ( child, index ) => {
						return (
							<AutoFormRow
								key={ index }
								FormElement={ child }
								AutoColumnRow={ AutoColumnRow }
							/>
						);
					} )
				}
			</div>
		</div>
	);
};

FormSection.propTypes = {
	children: PropTypes.oneOfType( [
		PropTypes.object,
		PropTypes.arrayOf( PropTypes.object ),
	] ),
	htmlId: PropTypes.string,
	htmlClass: PropTypes.string,
};

export default FormSection;
