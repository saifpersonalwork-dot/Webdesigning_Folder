<?php
declare(strict_types=1);

final class AuthController {
    public function __construct(private PDO $db, private array $config) {}

    public function register(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $name = trim($_POST['name'] ?? '');
            $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
            $password = (string)($_POST['password'] ?? '');
            if ($name === '' || !$email || strlen($password) < 8) {
                $error = 'Enter a name, valid email, and password of at least 8 characters.';
            } else {
                $token = bin2hex(random_bytes(32));
                try {
                    $s = $this->db->prepare('INSERT INTO users(name,email,password,api_token_hash) VALUES(?,?,?,?)');
                    $s->execute([$name,$email,password_hash($password,PASSWORD_DEFAULT),password_hash($token,PASSWORD_DEFAULT)]);
                    $_SESSION['new_api_token'] = $token;
                    redirect('login');
                } catch (PDOException $e) { $error = 'That email may already be registered.'; }
            }
        }
        require __DIR__ . '/../views/auth/register.php';
    }

    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
            $password = (string)($_POST['password'] ?? '');
            $s = $this->db->prepare('SELECT * FROM users WHERE email=?');
            $s->execute([$email ?: '']);
            $user = $s->fetch();
            if ($user && $user['locked_until'] && strtotime($user['locked_until']) > time()) {
                $error = 'Account temporarily locked. Try again later.';
            } elseif ($user && password_verify($password, $user['password'])) {
                $this->db->prepare('UPDATE users SET failed_attempts=0, locked_until=NULL WHERE id=?')->execute([$user['id']]);
                session_regenerate_id(true);
                $_SESSION['user'] = ['id'=>$user['id'],'name'=>$user['name'],'email'=>$user['email']];
                if (!empty($_POST['remember'])) {
                    $payload = $user['id'] . '|' . (time()+604800);
                    $sig = hash_hmac('sha256', $payload, $this->config['app_key']);
                    setcookie('remember_me', base64_encode($payload . '|' . $sig), [
                        'expires'=>time()+604800,'path'=>'/','httponly'=>true,
                        'secure'=>$this->config['session_secure'],'samesite'=>'Lax'
                    ]);
                }
                redirect('dashboard');
            } else {
                if ($user) {
                    $attempts = (int)$user['failed_attempts'] + 1;
                    $lock = $attempts >= 5 ? date('Y-m-d H:i:s', time()+900) : null;
                    $this->db->prepare('UPDATE users SET failed_attempts=?, locked_until=? WHERE id=?')->execute([$attempts,$lock,$user['id']]);
                }
                $error = 'Invalid email or password.';
            }
        }
        require __DIR__ . '/../views/auth/login.php';
    }

    public function logout(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') verify_csrf();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', ['expires'=>time()-42000,'path'=>$p['path'],'domain'=>$p['domain'],
                'secure'=>$p['secure'],'httponly'=>$p['httponly'],'samesite'=>'Lax']);
        }
        setcookie('remember_me','',['expires'=>time()-3600,'path'=>'/']);
        session_destroy();
        redirect('login');
    }

    public function forgot(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
            if ($email) {
                $s=$this->db->prepare('SELECT id FROM users WHERE email=?'); $s->execute([$email]);
                if ($u=$s->fetch()) {
                    $raw=bin2hex(random_bytes(32));
                    $this->db->prepare('UPDATE users SET reset_token_hash=?, reset_expires=? WHERE id=?')
                        ->execute([hash('sha256',$raw),date('Y-m-d H:i:s',time()+1800),$u['id']]);
                    $url='http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . dirname($_SERVER['PHP_SELF']) . '/index.php?route=reset&token=' . urlencode($raw);
                    $this->sendMail($email,'Password reset',"Open this link within 30 minutes: $url");
                    error_log('Local password reset link: ' . $url);
                }
            }
            $message='If the account exists, reset instructions have been sent.';
        }
        require __DIR__ . '/../views/auth/forgot.php';
    }

    public function reset(): void {
        $token=(string)($_GET['token'] ?? $_POST['token'] ?? '');
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            verify_csrf();
            $pw=(string)($_POST['password'] ?? '');
            $s=$this->db->prepare('SELECT id FROM users WHERE reset_token_hash=? AND reset_expires>NOW()');
            $s->execute([hash('sha256',$token)]);
            if (($u=$s->fetch()) && strlen($pw)>=8) {
                $this->db->prepare('UPDATE users SET password=?,reset_token_hash=NULL,reset_expires=NULL WHERE id=?')
                    ->execute([password_hash($pw,PASSWORD_DEFAULT),$u['id']]);
                $message='Password updated. You can log in now.';
            } else $error='Invalid/expired token or password is too short.';
        }
        require __DIR__ . '/../views/auth/reset.php';
    }

    private function sendMail(string $to,string $subject,string $body): void {
        if (class_exists(\PHPMailer\PHPMailer\PHPMailer::class) && $this->config['smtp']['host']) {
            try {
                $m=new \PHPMailer\PHPMailer\PHPMailer(true);
                $m->isSMTP(); $m->Host=$this->config['smtp']['host']; $m->SMTPAuth=true;
                $m->Username=$this->config['smtp']['username']; $m->Password=$this->config['smtp']['password'];
                $m->SMTPSecure=\PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS; $m->Port=$this->config['smtp']['port'];
                $m->setFrom($this->config['smtp']['from_email'],$this->config['smtp']['from_name']);
                $m->addAddress($to); $m->Subject=$subject; $m->Body=$body; $m->send();
            } catch (Throwable $e) { error_log('Mail error: '.$e->getMessage()); }
        } else error_log("Email to $to: $subject — $body");
    }
}
