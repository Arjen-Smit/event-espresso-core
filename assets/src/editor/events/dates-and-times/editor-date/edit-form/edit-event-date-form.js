/**
 * External imports
 */
import { isEmpty } from 'lodash';
import { Component } from 'react';
// import { __ } from '@eventespresso/i18n';
import {
	withFormHandler,
	twoColumnAdminFormLayout,
	// validations,
} from '@eventespresso/components';
import { __ } from '@eventespresso/i18n';

/**
 * Internal dependencies
 */
import { eventDateEntityFormInputs } from './event-date-entity-form-inputs';

/**
 * @function
 * @param {Object} eventDate    JSON object defining the Event Date
 */
class EditEventDateForm extends Component {
	render() {
		const {
			eventDate,
			submitButton,
			cancelButton,
			currentValues = {},
			initialValues = {},
			newObject = false,
		} = this.props;
		// edit forms for existing objects must have initial values
		if (
			( ! newObject && isEmpty( initialValues ) ) ||
			isEmpty( currentValues )
		) {
			return null;
		}
		// console.log( '' );
		// console.log( 'EditEventDateForm.render()', this.props );
		// console.log( 'EditEventDateForm.render() initialValues', initialValues );
		// console.log( 'EditEventDateForm.render() currentValues', currentValues );
		const {
			// InputLabel,
			FormInput,
			// FormColumn,
			// FormRow,
			FormSection,
			FormWrapper,
			FormSaveCancelButtons,
			FormInfo,
		} = twoColumnAdminFormLayout;

		// entity properties we don't want to be editable
		const exclude = [
			'DTT_ID',
			'EVT_ID',
			'sold',
			'reserved',
			'order',
			'parent',
			'deleted',
		];
		const formRows = eventDateEntityFormInputs(
			eventDate,
			exclude,
			currentValues,
			FormInput
		);
		formRows.unshift(
			<FormInfo
				key="formInfo"
				formInfo={
					__(
						'all fields marked with an asterisk are required',
						'event_espresso'
					)
				}
				dismissable={ false }
			/>
		);

		return eventDate && eventDate.id ? (
			<FormWrapper>
				<FormSection
					htmlId={
						`ee-event-date-editor-${ eventDate.id }-form-section`
					}
					children={ formRows }
				/>
				<FormSaveCancelButtons
					htmlClass={ `ee-event-date-editor-${ eventDate.id }` }
					submitButton={ submitButton }
					cancelButton={ cancelButton }
				/>
			</FormWrapper>
		) : null;
	}
}

/**
 * Enhanced EditEventDateForm with FormHandler
 */
export default withFormHandler( EditEventDateForm );
