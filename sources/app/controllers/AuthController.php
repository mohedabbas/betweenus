<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Form;
use App\Models\AuthModel;

// Inclusion manuelle de PHPMailer
require_once __DIR__ . '/../Libs/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../Libs/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../Libs/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * AuthController gère :
 * - Inscription + code 6 chiffres (verify)
 * - Connexion (login)
 * - Mot de passe oublié (forgot-password) => lien par email
 * - Réinitialisation (reset-password) => nouveau mot de passe
 */
class AuthController extends Controller
{
    /**
     * Page d'accueil (GET "/")
     */
    public function index()
    {
        echo "<h1>Accueil</h1>";
        echo "<p><a href='/register'>Register</a> | <a href='/login'>Login</a> | <a href='/logout'>Logout</a></p>";
    }

    // ----------------  INSCRIPTION + CODE 6 CHIFFRES ---------------- //

    /**
     * Formulaire d’inscription (GET "/register")
     */
    public function register(): void
    {
        // Récupérer les erreurs & anciennes données (stockées en session par store())
        $errors = $_SESSION['register_errors'] ?? [];
        $old    = $_SESSION['old_data'] ?? [];

        // Nettoyer la session pour éviter que ces données ne persistent
        unset($_SESSION['register_errors'], $_SESSION['old_data']);

        // Construction du formulaire
        $form = new Form('/register'); // => method=POST
        $form->addTextField('first_name', 'Prénom', $old['first_name'] ?? '', [
            'required' => 'required',
            'class'    => 'form-group'
        ])
        ->addTextField('last_name', 'Nom', $old['last_name'] ?? '', [
            'required' => 'required',
            'class'    => 'form-group'
        ])
        ->addTextField('username', 'Nom d’utilisateur', $old['username'] ?? '', [
            'required' => 'required',
            'class'    => 'form-group'
        ])
        ->addTextField('email', 'Email', $old['email'] ?? '', [
            'required' => 'required',
            'class'    => 'form-group'
        ])
        ->addPasswordField('password', 'Mot de passe', [
            'required' => 'required',
            'class'    => 'form-group'
        ])
        ->addSubmitButton('Register', ['name' => 'submit', 'class' => 'button']);

        $data = [
            'title'  => 'Register',
            'form'   => $form,
            'errors' => $errors,
            'old'    => $old
        ];

        $this->loadView('auth/register', $data);
    }

    /**
     * Traite l’inscription (POST "/register")
     */
    public function store(): void
    {
        // Vérifier la soumission du formulaire
        $isSubmitted = isset($_POST['submit']) || Form::isSubmitted();
        if (!$isSubmitted) {
            header('Location: /register');
            exit;
        }

        // Tableau pour stocker les erreurs de validation
        $errors = [];

        // Récupération sécurisée des champs
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName  = trim($_POST['last_name'] ?? '');
        $username  = trim($_POST['username'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $password  = trim($_POST['password'] ?? '');

        // --- VALIDATIONS SIMPLES ---

        // Prénom
        if (empty($firstName)) {
            $errors['first_name'] = "Le prénom est obligatoire.";
        }
        // Nom
        if (empty($lastName)) {
            $errors['last_name'] = "Le nom est obligatoire.";
        }
        // Nom d’utilisateur
        if (empty($username)) {
            $errors['username'] = "Le nom d’utilisateur est obligatoire.";
        }
        // Email
        if (empty($email)) {
            $errors['email'] = "L'email est obligatoire.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Le format de l'email est invalide.";
        }

        // --- VALIDATION DU MOT DE PASSE : 12+ caractères, majuscules, minuscules, chiffres, spéciaux ---
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{12,}$/';
        if (!preg_match($pattern, $password)) {
            $errors['password'] = "Le mot de passe doit contenir au moins 12 caractères, 
                                   dont 1 minuscule, 1 majuscule, 1 chiffre et 1 caractère spécial.";
        }

        // Si des erreurs existent, on les stocke dans la session + on stocke les anciennes valeurs
        if (!empty($errors)) {
            $_SESSION['register_errors'] = $errors;
            $_SESSION['old_data']        = $_POST;
            header('Location: /register');
            exit;
        }

        // === AUCUNE ERREUR => On peut créer l’utilisateur ===

        // On crée un tableau $postData pour correspondre aux colonnes
        $postData = [
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'username'   => $username,
            'email'      => $email,
            // Hachage du mot de passe
            'password'   => password_hash($password, PASSWORD_BCRYPT),
        ];

        // Générer un code à 6 chiffres pour la vérification
        $verificationCode = strval(random_int(100000, 999999));
        $postData['verification_code'] = $verificationCode;
        $postData['is_verified']       = 0;

        // Insertion en base
        $authModel = $this->loadModel('AuthModel');
        $authModel->createUser($postData);

        // Envoyer un mail de vérification
        $this->sendVerificationEmail($postData['email'], $verificationCode);

        // Message de confirmation + redirection
        $_SESSION['register_info'] = "Un e-mail de vérification (6 chiffres) vous a été envoyé.";
        header('Location: /verify');
        exit;
    }

    /**
     * Envoi d’un e-mail de vérification (6 chiffres)
     */
    private function sendVerificationEmail(string $destMail, string $code): void
    {
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug  = 0;   // Pas de logs à l’écran
            $mail->Debugoutput = 'html';

            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;

            // === RENSEIGNEZ VOS IDENTIFIANTS GMAIL OU APP PASSWORD ===
            $mail->Username   = 'projetphpscratch@gmail.com';
            $mail->Password   = 'cxvp lzrf icwq wiss';

            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // L'adresse "From" => pareil que l'adresse d'envoi
            $mail->setFrom('projetphpscratch@gmail.com', 'BetweenUs');
            $mail->addAddress($destMail);

            $mail->isHTML(false);
            $mail->Subject = "Vérification de votre compte BetweenUs";
            $mail->Body    = "Bonjour,\n\n".
                             "Votre code de vérification (6 chiffres) : $code\n\n".
                             "Rendez-vous sur /verify pour valider.\n\n".
                             "Cordialement,\nBetweenUs";

            $mail->send();
        } catch (Exception $e) {
            error_log("Erreur d’envoi du mail (verify code): " . $e->getMessage());
        }
    }

    /**
     * Formulaire /verify (GET) pour saisir code 6 chiffres
     */
    public function verifyForm(): void
    {
        $form = new Form('/verify');
        $form->addTextField('verification_code', 'Code reçu (6 chiffres)', '', [
            'required'    => 'required',
            'placeholder' => 'Ex: 123456',
            'class'       => 'form-group'
        ])
        ->addSubmitButton('Valider', ['name' => 'submit','class' => 'button ']);

        $data = [
            'title' => 'Vérification',
            'form'  => $form
        ];
        $this->loadView('auth/verify', $data);
    }

    /**
     * POST /verify => vérifie le code
     */
    public function verifySubmit(): void
    {
        $isSubmitted = isset($_POST['submit']) || Form::isSubmitted();
        if (!$isSubmitted) {
            header('Location: /verify');
            exit;
        }

        $inputCode = filter_input(INPUT_POST, 'verification_code', FILTER_SANITIZE_STRING);

        // Vérif: 6 chiffres
        if (!ctype_digit($inputCode) || strlen($inputCode) !== 6) {
            $_SESSION['verify_error'] = "Le code doit être composé de 6 chiffres.";
            header('Location: /verify');
            exit;
        }

        $authModel = $this->loadModel('AuthModel');
        $user      = $authModel->findByVerificationCode($inputCode);

        if (!$user) {
            $_SESSION['verify_error'] = "Code invalide ou déjà utilisé.";
            header('Location: /verify');
            exit;
        }

        // Marquer is_verified=1
        $authModel->verifyUser($user->id);

        $_SESSION['verify_success'] = "Votre compte est validé ! Vous pouvez vous connecter.";
        header('Location: /login');
        exit;
    }

    // -------------------- MOT DE PASSE OUBLIÉ (avec lien) -------------------- //

    /**
     * Formulaire "Mot de passe oublié" (GET "/forgot-password")
     */
    public function forgotPasswordForm(): void
    {
        $form = new Form('/forgot-password');
        $form->addTextField('email', 'Votre email', '', [
            'required'    => 'required',
            'placeholder' => 'Entrez votre email',
            'class'       => 'form-group'
        ])
        ->addSubmitButton('Envoyer', ['name' => 'submit', 'class' => 'button']);

        $data = [
            'title' => 'Mot de passe oublié',
            'form'  => $form
        ];
        $this->loadView('auth/forgot_password', $data);
    }

    /**
     * Traitement "Mot de passe oublié" (POST "/forgot-password")
     */
    public function forgotPasswordSubmit(): void
    {
        $isSubmitted = isset($_POST['submit']) || Form::isSubmitted();
        if (!$isSubmitted) {
            header('Location: /forgot-password');
            exit;
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        if (!$email) {
            $_SESSION['forgot_error'] = "Veuillez saisir un email valide.";
            header('Location: /forgot-password');
            exit;
        }

        $authModel = $this->loadModel('AuthModel');
        $user = $authModel->findUserByEmail($email);

        if (!$user) {
            $_SESSION['forgot_error'] = "Cet email n’existe pas dans nos registres.";
            header('Location: /forgot-password');
            exit;
        }

        // Générer un reset_token
        $resetToken = bin2hex(random_bytes(16));
        $authModel->setResetToken($user->id, $resetToken);

        // Envoyer un e-mail avec un lien
        $this->sendResetEmail($email, $resetToken);

        $_SESSION['forgot_info'] = "Un e-mail de réinitialisation a été envoyé à $email.";
        header('Location: /forgot-password');
        exit;
    }

    /**
     * Envoi d’un mail de reset (lien)
     */
    private function sendResetEmail(string $destMail, string $token): void
    {
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug  = 0;
            $mail->Debugoutput = 'html';

            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;

            // Mettez vos identifiants Gmail ou App Password
            $mail->Username   = 'projetphpscratch@gmail.com';
            $mail->Password   = 'cxvp lzrf icwq wiss';

            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('projetphpscratch@gmail.com', 'BetweenUs');
            $mail->addAddress($destMail);

            $mail->isHTML(false);
            $mail->Subject = "Réinitialisation de votre mot de passe BetweenUs";

            // URL pour /reset-password?token=...
            $resetUrl = "http://localhost:8000/reset-password?token=$token";

            $mail->Body = "Bonjour,\n\n".
                          "Pour réinitialiser votre mot de passe, cliquez sur le lien suivant :\n".
                          "$resetUrl\n\n".
                          "Si ce n’est pas vous, ignorez ce mail.\n\n".
                          "Cordialement,\nBetweenUs";

            $mail->send();
        } catch (Exception $e) {
            error_log("Erreur d’envoi de mail (reset): " . $e->getMessage());
        }
    }

    /**
     * Formulaire "Nouveau mot de passe" (GET "/reset-password?token=xxx")
     */
    public function resetPasswordForm(): void
    {
        // Récup token en GET
        $token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING);

        $form = new Form('/reset-password'); // => POST
        $form->addPasswordField('new_password', 'Nouveau mot de passe', [
            'required' => 'required',
            'class'    => 'form-group '
        ])
        ->addHiddenField('token', $token) // Champ caché = token
        ->addSubmitButton('Valider', ['name' => 'submit','class' => 'button ']);

        $data = [
            'title' => 'Réinitialisation',
            'form'  => $form
        ];
        $this->loadView('auth/reset_password', $data);
    }

    /**
     * Traitement du nouveau mot de passe (POST "/reset-password")
     */
    public function resetPasswordSubmit(): void
    {
        $isSubmitted = isset($_POST['submit']) || Form::isSubmitted();
        if (!$isSubmitted) {
            header('Location: /reset-password');
            exit;
        }

        $newPass = filter_input(INPUT_POST, 'new_password', FILTER_SANITIZE_STRING);
        $token   = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);

        if (!$newPass || !$token) {
            $_SESSION['reset_error'] = "Paramètres invalides.";
            header('Location: /reset-password?token=' . urlencode($token));
            exit;
        }

        $authModel = $this->loadModel('AuthModel');
        $user = $authModel->findByResetToken($token);
        if (!$user) {
            $_SESSION['reset_error'] = "Token invalide ou expiré.";
            header('Location: /reset-password');
            exit;
        }

        // Mettre à jour le password
        $hashed = password_hash($newPass, PASSWORD_BCRYPT);
        $authModel->updatePasswordAndClearToken($user->id, $hashed);

        $_SESSION['reset_success'] = "Mot de passe réinitialisé avec succès ! Connectez-vous.";
        header('Location: /login');
        exit;
    }

    // -------------------- LOGIN / LOGOUT / CONNECTED --------------------

    /**
     * Formulaire de connexion (GET "/login")
     */
    public function login(): void
    {
        $form = new Form('/login', 'POST');

        $form->addTextField('identifier', 'Email', '', [
            'required'    => 'required',
            'placeholder' => 'Enter your email',
            'type'        => 'email',
            'class'       => 'form-group'
        ])
        ->addPasswordField('password', 'Password', [
            'required'    => 'required',
            'placeholder' => 'Enter your password',
            'class'       => 'form-group'
        ])
        ->addSubmitButton('Login', [
            'name'  => 'submit',
            'class' => 'button'
        ]);

        $data = [
            'title' => 'Login',
            'form'  => $form
        ];

        $this->loadView('auth/login', $data);
    }

    /**
     * Traitement du login (POST "/login")
     */
    public function attemptLogin(): void
    {
        $isSubmitted = isset($_POST['submit']) || Form::isSubmitted();
        if (!$isSubmitted) {
            header('Location: /login');
            exit;
        }

        $identifier = filter_input(INPUT_POST, 'identifier', FILTER_SANITIZE_STRING);
        $password   = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        $authModel = $this->loadModel('AuthModel');
        $user      = $authModel->findUserByUsernameOrEmail($identifier);

        if (!$user) {
            $_SESSION['login_error'] = "Identifiant inconnu.";
            header('Location: /login');
            exit;
        }

        if (!password_verify($password, $user->password)) {
            $_SESSION['login_error'] = "Mot de passe incorrect.";
            header('Location: /login');
            exit;
        }

        if (isset($user->is_verified) && !$user->is_verified) {
            $_SESSION['login_error'] = "Votre compte n’est pas encore validé.";
            header('Location: /login');
            exit;
        }

        // OK => connecter
        $_SESSION['user'] = [
            'id'         => $user->id,
            'username'   => $user->username,
            'email'      => $user->email,
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
            'role'       => $user->role ?? 'user'
        ];

        header('Location: /gallery');
        exit;
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        header('Location: /');
        exit;
    }

    public function connected(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
        echo "<h1>Bienvenue, vous êtes connecté(e) !</h1>";
        echo "<p>Bonjour " . htmlspecialchars($_SESSION['user']['first_name'] ?? '') . "</p>";
    }
}
