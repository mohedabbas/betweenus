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

    /**
     * Formulaire d’inscription (GET "/register")
     */
    public function register(): void
    {
        $form = new Form('/register'); // => POST
        $form->addTextField('first_name', 'Prénom', '', [
            'required' => 'required'
        ])
        ->addTextField('last_name', 'Nom', '', [
            'required' => 'required'
        ])
        ->addTextField('username', 'Nom d’utilisateur', '', [
            'required' => 'required'
        ])
        ->addTextField('email', 'Email', '', [
            'required' => 'required'
        ])
        ->addPasswordField('password', 'Mot de passe', [
            'required' => 'required'
        ])
        ->addSubmitButton('Register', ['name' => 'submit']);

        $data = [
            'title' => 'Register',
            'form'  => $form
        ];
        $this->loadView('auth/register', $data);
    }

    /**
     * Traite l’inscription (POST "/register")
     */
    public function store(): void
    {
        $isSubmitted = isset($_POST['submit']) || Form::isSubmitted();
        if (!$isSubmitted) {
            header('Location: /register');
            exit;
        }

        // Récupérer les champs
        $postData = filter_input_array(INPUT_POST, $_POST);
        unset($postData['submit']);

        // Hacher le mot de passe
        if (isset($postData['password'])) {
            $postData['password'] = password_hash($postData['password'], PASSWORD_BCRYPT);
        }

        // Générer un code aléatoire
        $verificationCode = bin2hex(random_bytes(16));
        $postData['verification_code'] = $verificationCode;
        $postData['is_verified']       = 0;

        // Insertion en base
        $authModel = $this->loadModel('AuthModel');
        $authModel->createUser($postData);

        // Envoi du mail
        $this->sendVerificationEmail($postData['email'], $verificationCode);

        // Stocker un message + rediriger
        $_SESSION['register_info'] = "Un e-mail de vérification vous a été envoyé.";
        header('Location: /verify');
        exit;
    }

    /**
     * Envoi d’un e-mail de vérification avec PHPMailer
     */
    private function sendVerificationEmail(string $destMail, string $code): void
    {
        $mail = new PHPMailer(true);

        try {
            // Désactive le debug direct
            $mail->SMTPDebug  = 0;
            $mail->Debugoutput = 'html';

            // Configuration SMTP
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true;
            // Mettez ici votre Gmail + mot de passe d’application (ou less secure apps)
            $mail->Username   = 'projetphpscratch@gmail.com';
            $mail->Password   = 'cxvp lzrf icwq wiss';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // Expéditeur
            $mail->setFrom('votrecompte@gmail.com', 'BetweenUs');

            // Destinataire
            $mail->addAddress($destMail);

            // Contenu
            $mail->isHTML(false);
            $mail->Subject = "Vérification de votre compte BetweenUs";
            $mail->Body    = "Bonjour,\n\n".
                             "Voici votre code de vérification : $code\n\n".
                             "Rendez-vous sur /verify pour valider.\n\n".
                             "Cordialement,\nL’équipe BetweenUs";

            $mail->send();

        } catch (Exception $e) {
            // En cas d’erreur
            error_log("Erreur d’envoi du mail: " . $e->getMessage());
        }
    }

    /**
     * Formulaire pour saisir le code de vérification (GET "/verify")
     */
    public function verifyForm(): void
    {
        $form = new Form('/verify');
        $form->addTextField('verification_code', 'Code reçu', '', [
            'required' => 'required'
        ])
        ->addSubmitButton('Valider', ['name' => 'submit']);

        $data = [
            'title' => 'Vérification',
            'form'  => $form
        ];
        $this->loadView('auth/verify', $data);
    }

    /**
     * Traite la soumission du code (POST "/verify")
     */
    public function verifySubmit(): void
    {
        $isSubmitted = isset($_POST['submit']) || Form::isSubmitted();
        if (!$isSubmitted) {
            header('Location: /verify');
            exit;
        }

        $inputCode = filter_input(INPUT_POST, 'verification_code', FILTER_SANITIZE_STRING);

        $authModel = $this->loadModel('AuthModel');
        $user      = $authModel->findByVerificationCode($inputCode);

        if (!$user) {
            $_SESSION['verify_error'] = "Code invalide ou déjà utilisé.";
            header('Location: /verify');
            exit;
        }

        // Vérification => is_verified=1
        $authModel->verifyUser($user->id);

        $_SESSION['verify_success'] = "Votre compte est validé ! Vous pouvez vous connecter.";
        header('Location: /login');
        exit;
    }

    // -------------------- LOGIN / LOGOUT / CONNECTED --------------------

    /**
     * Formulaire de connexion (GET "/login")
     */
    public function login(): void
    {
        $form = new Form('/login');
        $form->addTextField('identifier', 'Nom d’utilisateur / Email', '', [
            'required' => 'required',
            'placeholder' => 'Entrez votre email ou username'
        ])
        ->addPasswordField('password', 'Mot de passe', [
            'required' => 'required',
            'placeholder' => 'Entrez votre mot de passe'
        ])
        ->addSubmitButton('Connexion', ['name' => 'submit']);

        $data = [
            'title' => 'Connexion',
            'form'  => $form
        ];
        $this->loadView('auth/login', $data);
    }

    /**
     * Traitement connexion (POST "/login")
     */
    public function attemptLogin(): void
    {
        $isSubmitted = isset($_POST['submit']) || Form::isSubmitted();
        if (!$isSubmitted) {
            header('Location: /login');
            exit;
        }

        // Récupérer identifiants
        $identifier = filter_input(INPUT_POST, 'identifier', FILTER_SANITIZE_STRING);
        $password   = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        $authModel = $this->loadModel('AuthModel');
        $user      = $authModel->findUserByUsernameOrEmail($identifier);

        if (!$user) {
            $_SESSION['login_error'] = "Identifiant inconnu.";
            header('Location: /login');
            exit;
        }

        // Vérifier le mot de passe haché
        if (!password_verify($password, $user->password)) {
            $_SESSION['login_error'] = "Mot de passe incorrect.";
            header('Location: /login');
            exit;
        }

        // Vérifier si le compte est vérifié
        if (isset($user->is_verified) && !$user->is_verified) {
            $_SESSION['login_error'] = "Votre compte n’est pas encore validé.";
            header('Location: /login');
            exit;
        }

        // OK => on stocke l’utilisateur en session
        $_SESSION['user'] = [
            'id'         => $user->id,
            'username'   => $user->username,
            'email'      => $user->email,
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
            'role'       => $user->role ?? 'user'
        ];

        // Rediriger vers page connectée
        header('Location: /connected');
        exit;
    }

    /**
     * Déconnexion (GET "/logout")
     */
    public function logout(): void
    {
        session_unset();
        session_destroy();
        header('Location: /');
        exit;
    }

    /**
     * Page connectée (GET "/connected")
     */
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
