<?php

namespace App\Core;

/**
 * Form class
 * This class is used to create forms and validate them. In this class, we have methods to add text fields, password fields, text area fields, submit buttons, and hidden fields.
 * We also have methods to render the form, check if the form is submitted, get the form data, and get the form errors.
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
	 *
	 * @param $action
	 * @param string $method
	 */
	public function __construct($action, string $method = 'POST')
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
	 * @param $name
	 * @param $label
	 * @param string $value
	 * @param array $attributes
	 * @return $this
	 */
	public function addTextField($name, $label, $value = '', $attributes = []): static
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

	/**
	 * Render the form
	 * @return string
	 */
	public function renderForm(): string
	{
		$form = "<form action='{$this->action}' method='{$this->method}'>";
		foreach ($this->fields as $field) {
			if ($field['type'] === 'text') {
				$form .= "<label for='{$field['name']}'>{$field['label']}</label>";
				$form .= "<input type='text' name='{$field['name']}' value='{$field['value']}'";
				foreach ($field['attributes'] as $key => $value) {
					$form .= " $key='$value'";
				}
				$form .= ">";
			} elseif ($field['type'] === 'password') {
				$form .= "<label for='{$field['name']}'>{$field['label']}</label>";
				$form .= "<input type='password' name='{$field['name']}'";
				foreach ($field['attributes'] as $key => $value) {
					$form .= " $key='$value'";
				}
				$form .= ">";
			} elseif ($field['type'] === 'textarea') {
				$form .= "<label for='{$field['name']}'>{$field['label']}</label>";
				$form .= "<textarea name='{$field['name']}'";
				foreach ($field['attributes'] as $key => $value) {
					$form .= " $key='$value'";
				}
				$form .= ">{$field['value']}</textarea>";
			} elseif ($field['type'] === 'submit') {
				$form .= "<button type='submit'";
				foreach ($field['attributes'] as $key => $value) {
					$form .= " $key='$value'";
				}
				$form .= ">{$field['label']}</button>";
			}
		}
		$form .= "</form>";
		return $form;
	}

	/**
	 * Check if the form is submitted
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
	 * Get the form data
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
	 * Get the form errors
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
