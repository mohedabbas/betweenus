<?php

namespace App\Core;

/**
 * Form class
 * This class is used to create forms and validate them. In this class, we have methods to add text fields, password fields, text area fields, submit buttons, and hidden fields.
 * We also have methods to render the form, check if the form is submitted, get the form data, and get the form errors.
 * There are also methods to render the form fields and check if the required fields are empty.
 */
class Form
{
	private string $action;
	private string $method;
	private array $fields = [];
	private array $data = [];
	private array $errors = [];

	/**
	 * Form constructor.
	 * @param string $action
	 * @param string $method
	 */
	public function __construct(string $action, string $method = 'POST')
	{
		// Start the session if it's not already started
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
		$this->action = $action;
		$this->method = $method;
	}

	/**
	 * Add a text field to the form with all the attributes necessary.
	 * @param string $name
	 * @param string $label
	 * @param string $value
	 * @param array $attributes
	 * @return $this
	 */
	public function addTextField(string $name, string $label, string $value = '', array $attributes = []): static
	{
		$this->fields[] = [
			'type' => 'text',
			'name' => $name,
			'label' => $label,
			'value' => $value,
			'attributes' => $attributes
		];
		return $this;
	}

	/**
	 * Add a password field to the form with all the attributes necessary.
	 * @param string $name
	 * @param string $label
	 * @param array $attributes
	 * @return $this
	 */

	public function addPasswordField(string $name, string $label, array $attributes = []): static
	{
		$this->fields[] = [
			'type' => 'password',
			'name' => $name,
			'label' => $label,
			'attributes' => $attributes
		];
		return $this;
	}

	/**
	 * Add a text area field to the form with all the attributes necessary.
	 * @param string $name
	 * @param string $label
	 * @param array $attributes
	 * @param string $value
	 * @return $this
	 */
	public function addTextAreaField(string $name, string $label, array $attributes = [], string $value = ''): static
	{
		$this->fields[] = [
			'type' => 'textarea',
			'name' => $name,
			'label' => $label,
			'attributes' => $attributes,
			'value' => $value
		];
		return $this;
	}

	/**
	 * Add a submit button to the form with all the attributes necessary.
	 * @param string $label
	 * @param array $attributes
	 * @return $this
	 */
	public function addSubmitButton(string $label = "Submit", array $attributes = []): static
	{
		$this->fields[] = [
			'type' => 'submit',
			'label' => $label,
			'attributes' => $attributes
		];
		return $this;
	}

	/**
	 * Add a hidden field to the form with all the attributes necessary.
	 * Usually used for the form to store session keys and other hidden data.
	 * @param string $name
	 * @param string $value
	 * @return $this
	 */
	public function addHiddenField(string $name, string $value): static
	{
		$this->fields[] = [
			'type' => 'hidden',
			'name' => $name,
			'value' => $value
		];
		return $this;
	}


	public function addSelectField(string $name, string $label, array $options, array $attributes = [], string $value = ''): static
	{
		$this->fields[] = [
			'type' => 'select',
			'name' => $name,
			'label' => $label,
			'options' => $options,
			'attributes' => $attributes,
			'value' => $value
		];
		return $this;
	}

	/**
	 * Add a radio button to the form with all the attributes necessary.
	 * @param string $name
	 * @param string $label
	 * @param array $options
	 * @param array $attributes
	 * @return $this
	 */
	public function addRadioField(string $name, string $label, array $options, array $attributes = []): static
	{
		$this->fields[] = [
			'type' => 'radio',
			'name' => $name,
			'label' => $label,
			'options' => $options,
			'attributes' => $attributes,
		];
		return $this;
	}

	/**
	 * Add a checkbox to the form with all the attributes necessary.
	 * @param string $name
	 * @param string $label
	 * @param array $attributes
	 * @param string $value
	 * @return $this
	 */
	public function addCheckboxField(string $name, string $label, array $attributes = [], string $value = ''): static
	{
		$this->fields[] = [
			'type' => 'checkbox',
			'name' => $name,
			'label' => $label,
			'attributes' => $attributes,
			'value' => $value
		];
		return $this;
	}

	/**
	 * Add a file input to the form with all the attributes necessary.
	 * @param string $name
	 * @param string $label
	 * @param array $attributes
	 * @return $this
	 */
	public function addFileField(string $name, string $label, array $attributes = []): static
	{
		$this->fields[] = [
			'type' => 'file',
			'name' => $name,
			'label' => $label,
			'attributes' => $attributes
		];
		return $this;
	}


	/**
	 * Add a hidden input field to the form with all the attributes necessary.
	 * @param string $name
	 * @param string $value
	 * @return $this
	 */
	public function addHiddenInputField(string $name, string $value): static
	{
		$this->fields[] = [
			'type' => 'hidden',
			'name' => $name,
			'value' => $value
		];
		return $this;
	}

	/**
	 * This method renders the text field. All the field rendering function will follow the same pattern and
	 * will only be used in the renderFields method.
	 * @param array $field
	 * @return string
	 */
	private function renderTextField(array $field): string
	{
		$html = "<label for='{$field['name']}'>{$field['label']}</label>";
		$html .= "<input type='text' name='{$field['name']}' value='{$field['value']}'";
		foreach ($field['attributes'] as $key => $value) {
			$html .= " $key='$value'";
		}
		$html .= ">";
		return $html;
	}

	/**
	 * This method will render the password field. All the field rendering function will follow the same pattern and
	 * will only be used in the renderFields method.
	 * @param array $field
	 * @return string
	 */
	private function renderPasswordField(array $field): string
	{
		$html = "<label for='{$field['name']}'>{$field['label']}</label>";
		$html .= "<input type='password' name='{$field['name']}'";
		foreach ($field['attributes'] as $key => $value) {
			$html .= " $key='$value'";
		}
		$html .= ">";

		return $html;
	}

	/**
	 * This method will render the text area field. All the field rendering function will follow the same pattern and
	 * will only be used in the renderFields method.
	 * @param array $field
	 * @return string
	 */
	private function renderTextAreaField(array $field): string
	{
		$html = "<label for='{$field['name']}'>{$field['label']}</label>";
		$html .= "<textarea name='{$field['name']}'";
		foreach ($field['attributes'] as $key => $value) {
			$html .= " $key='$value'";
		}
		$html .= ">{$field['value']}</textarea>";
		return $html;
	}

	/**
	 * This method will render the checkbox field. All the field rendering function will follow the same pattern and
	 * will only be used in the renderFields method.
	 * @param array $field
	 * @return string
	 */
	private function renderCheckboxField(array $field): string
	{
		$html = '';
		if ($field['type'] === 'checkbox') {
			$html .= "<label>{$field['label']}</label>";
			$html .= "<input type='checkbox' name='{$field['name']}' value='{$field['value']}'";
			foreach ($field['attributes'] as $key => $value) {
				$html .= " $key='$value'";
			}
			$html .= ">";
		}
		return $html;
	}

	/**
	 * This method will render the submit button. All the field rendering function will follow the same pattern and
	 * will only be used in the renderFields method.
	 * @param array $field
	 * @return string
	 */
	private function renderSubmitButton(array $field): string
	{
		$html = "<button type='submit'";
		foreach ($field['attributes'] as $key => $value) {
			$html .= " $key='$value'";
		}
		$html .= ">{$field['label']}</button>";
		return $html;
	}

	/**
	 * This method will render the file field. All the field rendering function will follow the same pattern and
	 * will only be used in the renderFields method.
	 * @param array $field
	 * @return string
	 */
	private function renderFileField(array $field): string
	{
		$html = "<label for='{$field['name']}'>{$field['label']}</label>";
		$html .= "<input type='file' name='{$field['name']}'";
		foreach ($field['attributes'] as $key => $value) {
			$html .= " $key='$value'";
		}
		$html .= ">";
		return $html;
	}

	/**
	 * This method will render the select field. All the field rendering function will follow the same pattern and
	 * will only be used in the renderFields method.
	 * @param array $field
	 * @return string
	 */
	private function renderSelectField(array $field): string
	{
		$html = '';
		$attributes = '';
		if ($field['type'] === "select") {
			foreach ($field['attributes'] as $key => $value) {
				$attributes .= $key . "='" . $value . " ' ";
			}
			$html .= "<label for='{$field['name']}'>{$field['label']}</label>";
			$html .= "<select name='{$field['name']}' $attributes";
			$html .= ">";
			foreach ($field['options'] as $key => $value) {
				$html .= "<option value='$value'";
				if ($field['value'] === $value) {
					$html .= " selected";
				}
				$html .= ">$key</option>";
			}
			$html .= "</select>";
		}
		return $html;
	}

	/**
	 * This method will render the radio field. All the field rendering function will follow the same pattern and
	 * will only be used in the renderFields method.
	 * @param array $field
	 * @return string
	 */
	private function renderRadioField(array $field): string
	{
		$html = "<label>{$field['label']}</label>";
		foreach ($field['options'] as $key => $value) {
			$html .= "<input type='radio' name='{$field['name']}' value='$value'";
			foreach ($field['attributes'] as $attrKey => $attrValue) {
				$html .= " $attrKey='$attrValue'";
			}
			$html .= ">$key";
		}
		return $html;
	}

	/**
	 * This method will render the hidden field. All the field rendering function will follow the same pattern and
	 * will only be used in the renderFields method.
	 * @param array $field
	 * @return string
	 */
	private function renderHiddenField(array $field): string
	{
		return "<input type='hidden' name='{$field['name']}' value='{$field['value']}'>";
	}

	/**
	 * This method will render the form fields. It will call the appropriate method based on the field type.
	 * This method uses the approach of a type and method map to render the form fields.
	 * That means we have a map of field types and the method that should be called to render that field.
	 * This way, we can easily add new field types and methods to render them.
	 * @param array $field
	 * @return string
	 */
	private function renderFields(array $field): string
	{
		// This is a type and method map for rendering the form fields.
		$typeMethodMap = [
			'text' => 'renderTextField',
			'password' => 'renderPasswordField',
			'textarea' => 'renderTextAreaField',
			'submit' => 'renderSubmitButton',
			'file' => 'renderFileField',
			'radio' => 'renderRadioField',
			'select' => 'renderSelectField',
			'checkbox' => 'renderCheckboxField',
			'hidden' => 'renderHiddenField'
		];
		$type = $field['type'];
		$method = $typeMethodMap[$type];

		if (!isset($method)) {
			return '';
		}

		return $this->$method($field);
	}

	/**
	 * This method is used to render the form. It will loop through all the fields and call the renderFields method to render each field.
	 * @return string
	 */
	public function renderForm(): string
	{
		$form = "<form action='{$this->action}' method='{$this->method}'>";
		foreach ($this->fields as $field) {
			$form .= $this->renderFields($field);
		}
		$form .= "</form>";
		return $form;
	}

	/**
	 * This method is used to check if the form is submitted. It is a static method and can be called without creating an instance of the class.
	 * @return bool
	 */
	public static function isSubmitted(): bool
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			return true;
		}
		return false;
	}

	/**
	 * This method is used to get the form data. It will loop through all the fields and get the data from the POST request.
	 * @return array
	 */
	public function getData(): array
	{
		if ($this->isSubmitted()) {
			foreach ($this->fields as $field) {
				if ($field['type'] === 'text' || $field['type'] === 'password') {
					$this->data[$field['name']] = filter_input(INPUT_POST, $field['name']);
				} elseif ($field['type'] === 'textarea') {
					$this->data[$field['name']] = filter_input(INPUT_POST, $field['name']);
				}
			}
		}
		return $this->data;
	}

	/**
	 * This method is used to get the form errors. It will loop through all the fields and check if the required fields are empty.
	 * @return array
	 */
	public function getErrors(): array
	{
		if ($this->isSubmitted()) {
			foreach ($this->fields as $field) {
				if ($field['attributes']['required'] && empty($this->data[$field['name']])) {
					$this->errors[$field['name']] = "{$field['label']} is required";
				}
			}
		}
		return $this->errors;
	}


}
