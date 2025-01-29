<?php
namespace App\Core;

class Form
{
    private string $action;
    private string $method;
    private array $fields = [];

    public function __construct(string $action, string $method = 'POST')
    {
        $this->action = $action;
        $this->method = $method;
    }

    public function addTextField(string $name, string $label, string $value = '', array $attributes = []): static
    {
        $this->fields[] = [
            'type'       => 'text',
            'name'       => $name,
            'label'      => $label,
            'value'      => $value,
            'attributes' => $attributes
        ];
        return $this;
    }

    public function addPasswordField(string $name, string $label, array $attributes = []): static
    {
        $this->fields[] = [
            'type'       => 'password',
            'name'       => $name,
            'label'      => $label,
            'attributes' => $attributes
        ];
        return $this;
    }

    public function addSubmitButton(string $label, array $attributes = []): static
    {
        $this->fields[] = [
            'type'       => 'submit',
            'label'      => $label,
            'attributes' => $attributes
        ];
        return $this;
    }

    public static function isSubmitted(): bool
    {
        return ($_SERVER['REQUEST_METHOD'] === 'POST');
    }

    public function renderForm(): string
    {
        $html = "<form action='{$this->action}' method='{$this->method}'>";
        foreach ($this->fields as $field) {
            $html .= $this->renderField($field);
        }
        $html .= "</form>";
        return $html;
    }

    private function renderField(array $field): string
    {
        $type = $field['type'];
        $html = '';

        if ($type === 'text') {
            $html .= "<label>{$field['label']}</label>";
            $html .= "<input type='text' name='{$field['name']}' value='{$field['value']}'";
            foreach ($field['attributes'] as $attr => $val) {
                $html .= " $attr='$val'";
            }
            $html .= "><br>";
        } elseif ($type === 'password') {
            $html .= "<label>{$field['label']}</label>";
            $html .= "<input type='password' name='{$field['name']}'";
            foreach ($field['attributes'] as $attr => $val) {
                $html .= " $attr='$val'";
            }
            $html .= "><br>";
        } elseif ($type === 'submit') {
            $html .= "<button type='submit'";
            foreach ($field['attributes'] as $attr => $val) {
                $html .= " $attr='$val'";
            }
            $html .= ">{$field['label']}</button><br>";
        }
        return $html;
    }
}
