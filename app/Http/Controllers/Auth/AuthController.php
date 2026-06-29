<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use App\Models\Patient;
use App\Models\MedicalInfo;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cartalyst\Sentinel\Laravel\Facades\Reminder;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Sentinel::guest() == false) {
            return redirect('/');
        }

        return view('auth.login');
    }
    
    public function showPatientLoginForm()
	{
		return view('patient.login');
	}

    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        try {
            $remember = $request->remember == 'On' ? true : false;
            $user = Sentinel::authenticate($validatedData, $remember);
            if (! $user) {
                return redirect()
                    ->back()
                    ->withError('Email ou senha não coincidem com nossos registros!!!');  
            }

            $patient = Patient::where('user_id', $user->id)->count();
            $medicalInfo = MedicalInfo::where('user_id', $user->id)->count();
            if ($user->roles[0]->slug == 'patient' && ($patient == 0 || $medicalInfo == 0)) {
                return view('profile-details');
            }
                
            return redirect('/');

        } catch (NotActivatedException $e) {
            return redirect()
                ->back()
                ->withError('Sua conta está desativada. Por favor, entre em contato conosco para mais detalhes!');

        } catch (ThrottlingException $e) {
            $second = $e->getDelay();
            $delay = gmdate('i', $second);

            return redirect('login')->withError('Você só pode entrar na sua conta em alguns minutos ' . $delay . ' Minutos.');

        } catch (Exception $error) {
            return redirect()
                ->back()
                ->withError('Algo deu errado! ' . $error->getMessage());
        }
    }
    
    public function patientLogin(Request $request)
    {	
        $request->validate([
            'loginType' => 'required',
            'loginName' => 'required|numeric',
            'password' => 'required'
        ]);
        
        try {
            $patient = null;
            if ($request->loginType == "patient_cpf") {
                $patient = Patient::firstWhere('patient_cpf', $request->loginName);
            } else if ($request->loginType == "cns") {
                $patient = Patient::firstWhere('cns', $request->loginName);
            } else if ($request->loginType == "sus") {
                $patient = Patient::firstWhere('sus', $request->loginName);
            }
            
            if (! $patient) {
                return redirect()->back()->withError('Credenciais de login incorretas.!');
            }

            $user = User::firstWhere('id', $patient->user_id);
            if (Hash::check($request->password, $user->password)) {
                $loginUser = Sentinel::findById($user->id);
                Sentinel::login($loginUser);

                return redirect('/');
            }
            
            return redirect()->back()->withError('Senha incorreta!');
            
        } catch (Exception $error) {
            return redirect()->back()->withError('Algo deu errado! ' . $error->getMessage());
        }
    }

    public function logout()
    {
        Sentinel::logout(null, true);
        session()->flush();

        return redirect('login');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.passwords.forgotPassword');
    }

    public function forgotPassword(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email'
        ]);

        try {
            $user = User::whereEmail($validatedData)->first();
            if (! $user) {
                return redirect()->back()->withError('E-mail não encontrado!!!');
            }

            $user = Sentinel::findById($user->id);
            $reminder = Reminder::exists($user) ? null : Reminder::create($user);
            if ($reminder == null) {
                return redirect()->back()->withError('A redefinição de senha já foi enviado para seu e-mail cadastrado...');
            } 

            if ($this->sendMail($user, $reminder->code)) {
                return redirect()->back()->withSuccess('A redefinição de Senha já foi enviado para seu email cadastrado...');
            }
                
            return redirect()->back()->withError('Algo deu errado!!! Por favor, tente novamente...');
            
        } catch (Exception $error) {
            return redirect()->back()->withError('Algo deu errado! ' . $error->getMessage());
        }
    }

    public function sendMail($user, $token)
    {
        Mail::send(
            'auth.passwords.email',
            ['user' => $user, 'token' => $token],
            function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Solicitação de redefinição de senha');
            }
        );

        return true;
    }

    public function showResetPasswordForm($user_id, $token)
    {
        $user = User::find($user_id);
        if (! $user) {
            return redirect('login')->withError('Usuário não encontrado em nosso banco de dados! Por favor, tente novamente!');
        } 

        $reminder = Reminder::exists($user, $token);
        if (! $reminder) {
            return redirect('forgot-password')->withError('O link de redefinição de senha expirou!!! Por favor, tente novamente!'); 
        }

        return view('auth.passwords.reset')->with([
            'user' => $user, 
            'token' => $token
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed'
        ]);

        try {
            $user = Sentinel::findById($request->user_id);
            $reminder = Reminder::exists($user, $request->token);

            if (! $reminder) {
                return redirect('forgot-password')->withError('O link de redefinição de senha expirou! Por favor, tente novamente!');
            }

            Reminder::complete($user, $request->token, $request->password);
            Sentinel::logout(null, true);

            return redirect('login')->withSuccess('A senha foi redefinida com sucesso. Por favor, faça o login com a nova senha.');

        } catch (Exception $error) {
            return redirect()->back()->withError('Algo deu errado!' . $error->getMessage());
        }
    }

    public function showChangePasswordForm()
    {
        $user = Sentinel::getUser();

        if (! $user) {
            return redirect('login')->withError('Por favor entre'); 
        }

        return view('auth.passwords.changePassword');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'oldpassword' => 'required',
            'password' => 'required|confirmed'
        ],
        [
            'oldpassword.required'=>'O campo de senha atual é obrigatório',
        ]);

        try {
            $hasher = Sentinel::getHasher();
            $user = Sentinel::getUser();

            if (! $hasher->check($request->oldpassword, $user->password)) {
                return redirect()->back()->withError('A senha antiga não corresponde!');
            }

            Sentinel::update($user, ['password' => $request->password]);
            Sentinel::logout(null, true);

            return redirect('/')->withSuccess('Senha alterada com sucesso.');
            
        } catch (Exception $error) {
            return redirect()->back()->withError('Algo deu errado! ' . $error->getMessage());
        }
    }
    
}
