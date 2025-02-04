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

    /**
     * Ajoute un champ texte (par ex. pour "Nom d’utilisateur / Email").
     * $attributes permet d’ajouter ou de surcharger les attributs HTML
     * (required, placeholder, class, etc.)
     */
    public function addTextField(
        string $name,
        string $label,
        string $value = '',
        array $attributes = []
    ): static {
        $this->fields[] = [
            'type'       => 'text',
            'name'       => $name,
            'label'      => $label,
            'value'      => $value,
            'attributes' => $attributes
        ];
        return $this;
    }

    /**
     * Ajoute un champ caché (hidden).
     */
    public function addHiddenField(string $name, string $value): static
    {
        $this->fields[] = [
            'type'  => 'hidden',
            'name'  => $name,
            'value' => $value
        ];
        return $this;
    }

    /**
     * Ajoute un champ mot de passe (par ex. "Mot de passe").
     */
    public function addPasswordField(
        string $name,
        string $label,
        array $attributes = []
    ): static {
        $this->fields[] = [
            'type'       => 'password',
            'name'       => $name,
            'label'      => $label,
            'attributes' => $attributes
        ];
        return $this;
    }

    /**
     * Ajoute un bouton de soumission, avec la classe CSS "button" par défaut.
     * Si $attributes['class'] est déjà défini, on ajoute " button" à la fin.
     * On ajoute aussi name="submit" par défaut si non défini.
     */
    public function addSubmitButton(string $label, array $attributes = []): static
    {
        // Ajoute ou concatène la classe "button"
        if (isset($attributes['class'])) {
            $attributes['class'] .= ' ';
        } else {
            $attributes['class'] = '';
        }

        // Ajoute un name="submit" par défaut si non présent
        if (!isset($attributes['name'])) {
            $attributes['name'] = 'submit';
        }

        $this->fields[] = [
            'type'       => 'submit',
            'label'      => $label,
            'attributes' => $attributes
        ];
        return $this;
    }

    /**
     * Vérifie si le formulaire est soumis en POST.
     */
    public static function isSubmitted(): bool
    {
        return ($_SERVER['REQUEST_METHOD'] === 'POST');
    }

    /**
     * Génère le code HTML complet du formulaire.
     */
    public function renderForm(): string
    {
        // Balise d'ouverture du formulaire
        $html = "<form action='{$this->action}' method='{$this->method}'>";

        // Rendu de tous les champs
        foreach ($this->fields as $field) {
            $html .= $this->renderField($field);
        }

        // Fermeture du formulaire
        $html .= "</form>";
        return $html;
    }

    /**
     * Méthode privée pour générer chaque champ (input, button, etc.).
     */
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

        } elseif ($type === 'hidden') {
            $html .= "<input type='hidden' name='{$field['name']}' value='{$field['value']}'>";

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
