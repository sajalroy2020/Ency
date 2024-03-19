<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordChangeRequest;
use App\Http\Services\TeamMemberService;
use App\Http\Services\UserService;
use App\Models\FileManager;
use App\Models\User;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FAQRCode\Google2FA;
use App\Http\Requests\ProfileRequest;


class ProfileController extends Controller
{
    use ResponseTrait;

    public $userService, $teamMemberService;

    public function __construct()
    {
        $this->teamMemberService = new TeamMemberService;
        $this->userService = new UserService();
    }

    public function index()
    {
        $data['pageTitle'] = __('Profile');
        $data['activeSetting'] = 'active';
        $data['activeProfile'] = 'active';
        $data['user'] = $this->userService->userData();
        $data['designations'] = $this->teamMemberService->getAllDesignation(auth()->id());
        return view('user.profile.index', $data);
    }

    public function password()
    {
        $data['pageTitleParent'] = __('Profile');
        $data['pageTitle'] = __('Change Password');
        $data['activeProfile'] = 'active';
        $data['activeSetting'] = 'active';
        return view('user.profile.password', $data);
    }

    public function update(Request $request)
    {
        try {
            $user = User::find(auth()->id());
            if ($request->image) {
                $existFile = FileManager::where('id', $user->image)->first();
                if ($existFile) {
                    $existFile->removeFile();
                    $uploadedFile = $existFile->upload('User', $request->image, '', $existFile->id);
                    $user->image = $uploadedFile->id;
                } else {
                    $newFile = new FileManager();
                    $uploadedFile = $newFile->upload('User', $request->image);
                    $user->image = $uploadedFile->id;
                }
            }
            $user->name = $request->name;
            $user->mobile = $request->mobile;
            $user->address = $request->address;
            $user->save();
            return $this->success([], __(UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function passwordUpdate(PasswordChangeRequest $request)
    {
        try {
            $user = User::find(auth()->id());
            if (Hash::check($request->current_password, $user->password) == false) {
                throw new Exception(__('Current Password Not Match'));
            }
            $user->password = Hash::make($request->password);
            $user->save();
            return $this->success([], __(UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function profileUpdate(ProfileRequest $request)
    {
        return $this->userService->profileUpdate($request);
    }

    public function addInstitution(Request $request)
    {

        return $this->userService->addInstitution($request);
    }

    public function changePasswordUpdate(Request $request)
    {
        return $this->userService->changePasswordUpdate($request);
    }

    public function security()
    {
        $user = User::where('id', auth()->user()->id)->first();
        $google2fa = new Google2FA();
        $data['qr_code'] = $google2fa->getQRCodeInline(
            getOption('app_name'),
            $user->email,
            $user->google2fa_secret
        );
        return view('profile.security', $data);
    }

    public function smsSend(Request $request)
    {
        return $this->userService->smsSend($request);
    }
    public function smsReSend()
    {
        return $this->userService->smsReSend();
    }
    public function smsVerify(Request $request)
    {
        $request->validate([
            'opt-field.*' => 'required|numeric|',
        ]);
        return $this->userService->smsVerify($request);
    }
}
