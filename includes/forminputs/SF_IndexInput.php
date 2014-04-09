<?php
/**
 * File holding the SFIndexInput class
 *
 * @file
 * @ingroup SF
 */

/**
 * The SFIndexInput class.
 *
 * @author James Montalvo
 * @ingroup SFFormInput
 */
class SFIndexInput extends SFFormInput {

	public static function getName() {
		return 'index';
	}
	
	/**
	 * Returns the HTML code to be included in the output page for this input.
	 */
	public function getHtmlText() {
		global $sfgFieldNum, $sfgTabIndex;
		
		// return Html::hidden(
			// "input_$sfgFieldNum", //$this->mInputName,
			// $this->mCurrentValue,
			// array(
				// 'id' => $this->mInputNumber,
				// 'class' => 'multipleTemplateIndex',
				// 'tabindex' => $sfgTabIndex,
			// )
		// );
	
		// $this->mInputNumber = $input_number;
		// $this->mCurrentValue = $cur_value;
		// $this->mInputName = $input_name;
		// $this->mOtherArgs = $other_args;
		// $this->mIsDisabled = $disabled;
		// $this->mIsMandatory = array_key_exists( 'mandatory', $other_args );


		
		return Html::input( $this->mInputName, $this->mCurrentValue, 'hidden', array(
				'id' => "input_" . $this->mInputNumber,
				'class' => 'multipleTemplateIndex',
				'tabindex' => $sfgTabIndex,
			) );

	}

	/**
	 * Returns the set of parameters for this form input.
	 */
	public static function getParameters() {
		$params = array();
		$params['property'] = array(
			'name' => 'property',
			'type' => 'string',
			'description' => wfMessage( 'sf_forminputs_property' )->text()
		);
		return $params;
	}
}
